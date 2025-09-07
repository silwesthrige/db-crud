<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\EventService;
use App\Services\NotificationService;
use App\Models\Notification;
use PhpParser\Node\Expr\FuncCall;

class EventController extends Controller
{
    private EventService $Service;
    public function __construct(EventService  $Service){
        $this->Service=$Service;
    }

    public function index(){
        $events=$this->Service->getAll();
        return view('events.list',compact('events'));
    }

    public function calendar(){
        return view('events.calendar');
    }

    public function calendarData(){
        $events = $this->Service->getAll();
        
        $calendarEvents = $events->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->name,
                'start' => $event->event_date,
                'backgroundColor' => $this->getPriorityColor($event->priority),
                'borderColor' => $this->getPriorityColor($event->priority),
                'extendedProps' => [
                    'description' => $event->description,
                    'priority' => $event->priority,
                ]
            ];
        });

        return response()->json($calendarEvents);
    }

    private function getPriorityColor($priority){
        return match($priority) {
            'High' => '#ef4444',
            'Medium' => '#f59e0b', 
            'Low' => '#10b981',
            default => '#6366f1'
        };
    }

    public function timelineData(){
        // Get last 7 days
        $dates = collect();
        $highPriority = [];
        $mediumPriority = [];
        $lowPriority = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dates->push($date->format('M d'));
            
            // Get actual event counts for each priority level for this date
            $highCount = $this->Service->getEventsByDateAndPriority($date, 'High');
            $mediumCount = $this->Service->getEventsByDateAndPriority($date, 'Medium');
            $lowCount = $this->Service->getEventsByDateAndPriority($date, 'Low');
            
            $highPriority[] = $highCount;
            $mediumPriority[] = $mediumCount;
            $lowPriority[] = $lowCount;
        }

        return response()->json([
            'labels' => $dates->toArray(),
            'high' => $highPriority,
            'medium' => $mediumPriority,
            'low' => $lowPriority
        ]);
    }

    public function create(){
        return view('events.create');
    }
    
    public function store(Request $request){
        // Enhanced validation rules
        $validatedData = $request->validate([
            'name' => 'required|string|min:3|max:100',
            'description' => 'nullable|string|max:500',
            'priority' => 'required|in:High,Medium,Low',
            'event_date' => 'required|date|after_or_equal:today',
        ], [
            'name.required' => 'Event name is required',
            'name.min' => 'Event name must be at least 3 characters',
            'name.max' => 'Event name cannot exceed 100 characters',
            'description.max' => 'Description cannot exceed 500 characters',
            'priority.required' => 'Priority level is required',
            'priority.in' => 'Priority must be High, Medium, or Low',
            'event_date.required' => 'Event date is required',
            'event_date.date' => 'Please enter a valid date',
            'event_date.after_or_equal' => 'Event date must be today or in the future',
        ]);

        $data=[
            'name'=>$validatedData['name'],
            'description'=>$validatedData['description'],
            'priority'=>$validatedData['priority'],
            'event_date'=>$validatedData['event_date'],
        ];
        $result=$this->Service->store($data);
        
        // Create notification for event creation
        NotificationService::createEventNotification(
            'create',
            $data['name'],
            $data,
            $result
        );
        
        if($result){
            return redirect(url('/events'))->with('success', 'Event created successfully!');
        }else{
            return redirect(url('/events/create'))->with('error', 'Failed to create event!');
        }
    }

    public function delete($id){
        // Get event details before deletion for notification
        $event = $this->Service->findById($id);
        $eventName = $event ? $event->name : 'Unknown Event';
        
        $result = $this->Service->delete($id);
        
        // Create notification for event deletion
        NotificationService::createEventNotification(
            'delete',
            $eventName,
            ['event_id' => $id, 'event_name' => $eventName],
            $result
        );
        
        if($result) {
            return redirect('/events')->with('success', 'Event deleted successfully!');
        } else {
            return redirect('/events')->with('error', 'Failed to delete event!');
        }
    }
    
    public function edit($id){
        $event = $this->Service->findById($id);
        if(!$event) {
            // Create notification for event not found
            NotificationService::createSystemNotification(
                'danger',
                'Event Not Found',
                "The requested event (ID: {$id}) could not be found.",
                ['event_id' => $id, 'action' => 'edit_attempt']
            );
            return redirect('/events')->with('error', 'Event not found!');
        }
        
        return view('events.edit',compact('event'));
    }

    public function update(Request $request){
        // Enhanced validation rules for update
        $validatedData = $request->validate([
            'id' => 'required|integer|exists:event1,id',
            'name' => 'required|string|min:3|max:100',
            'description' => 'nullable|string|max:500',
            'priority' => 'required|in:High,Medium,Low',
            'event_date' => 'required|date|after_or_equal:today',
        ], [
            'id.required' => 'Event ID is required',
            'id.exists' => 'Event not found',
            'name.required' => 'Event name is required',
            'name.min' => 'Event name must be at least 3 characters',
            'name.max' => 'Event name cannot exceed 100 characters',
            'description.max' => 'Description cannot exceed 500 characters',
            'priority.required' => 'Priority level is required',
            'priority.in' => 'Priority must be High, Medium, or Low',
            'event_date.required' => 'Event date is required',
            'event_date.date' => 'Please enter a valid date',
            'event_date.after_or_equal' => 'Event date must be today or in the future',
        ]);

        $id = $validatedData['id'];
        $data=[
            'name'=>$validatedData['name'],
            'description'=>$validatedData['description'],
            'priority'=>$validatedData['priority'],
            'event_date'=>$validatedData['event_date'],
        ];
        $id=$request->id;
        $result = $this->Service->update($id,$data);
        
        // Create notification for event update
        NotificationService::createEventNotification(
            'update',
            $data['name'],
            array_merge($data, ['event_id' => $id]),
            $result
        );
        
        if($result){
            return redirect(url('/events'))->with('success', 'Event updated successfully!');
        }else{
            return redirect(url('/events/update/' . $id))->with('error', 'Failed to update event!');
        }
    }

    /**
     * Export events to CSV
     */
    public function export() {
        try {
            $events = $this->Service->getAll();
            $filename = 'events_export_' . date('Y-m-d_H-i-s') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ];
            
            $callback = function() use ($events) {
                $file = fopen('php://output', 'w');
                
                // Add UTF-8 BOM for Excel compatibility
                fwrite($file, "\xEF\xBB\xBF");
                
                // CSV Headers
                fputcsv($file, [
                    'ID',
                    'Event Name',
                    'Description', 
                    'Priority',
                    'Event Date',
                    'Created At',
                    'Updated At'
                ]);
                
                // CSV Data
                foreach ($events as $event) {
                    fputcsv($file, [
                        $event->id,
                        $event->name,
                        $event->description,
                        $event->priority,
                        $event->event_date,
                        $event->created_at ? $event->created_at->format('Y-m-d H:i:s') : '',
                        $event->updated_at ? $event->updated_at->format('Y-m-d H:i:s') : ''
                    ]);
                }
                
                fclose($file);
            };
            
            // Create notification for export
            NotificationService::createSystemNotification(
                'success',
                'Data Export',
                'Events data exported successfully (' . count($events) . ' records)',
                [
                    'export_type' => 'csv',
                    'record_count' => count($events),
                    'filename' => $filename
                ]
            );
            
            return response()->stream($callback, 200, $headers);
            
        } catch (\Exception $e) {
            \Log::error('Export failed: ' . $e->getMessage());
            
            // Create notification for export failure
            NotificationService::createSystemNotification(
                'danger',
                'Export Failed',
                'Failed to export events data: ' . $e->getMessage(),
                ['error' => $e->getMessage()]
            );
            
            return redirect('/events')->with('error', 'Export failed. Please try again.');
        }
    }

    /**
     * Show import form
     */
    public function showImport() {
        return view('events.import');
    }

    /**
     * Import events from CSV
     */
    public function import(Request $request) {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240', // Max 10MB
        ]);

        try {
            $file = $request->file('csv_file');
            $filename = $file->getClientOriginalName();
            $path = $file->getRealPath();
            
            $csvData = array_map('str_getcsv', file($path));
            
            // Remove header row
            $headers = array_shift($csvData);
            
            $importedCount = 0;
            $errors = [];
            
            foreach ($csvData as $index => $row) {
                try {
                    // Skip empty rows
                    if (empty(array_filter($row))) continue;
                    
                    // Map CSV columns (assuming standard format)
                    $eventData = [
                        'name' => $row[1] ?? 'Imported Event',
                        'description' => $row[2] ?? '',
                        'priority' => in_array($row[3] ?? '', ['High', 'Medium', 'Low']) ? $row[3] : 'Medium',
                        'event_date' => $this->parseDate($row[4] ?? date('Y-m-d'))
                    ];
                    
                    if ($this->Service->store($eventData)) {
                        $importedCount++;
                    }
                } catch (\Exception $e) {
                    $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
                }
            }
            
            // Create notification for import
            $notificationType = empty($errors) ? 'success' : 'warning';
            $message = "Imported {$importedCount} events successfully";
            if (!empty($errors)) {
                $message .= " with " . count($errors) . " errors";
            }
            
            NotificationService::createSystemNotification(
                $notificationType,
                'Data Import',
                $message,
                [
                    'import_type' => 'csv',
                    'filename' => $filename,
                    'imported_count' => $importedCount,
                    'error_count' => count($errors),
                    'errors' => array_slice($errors, 0, 10) // Limit errors in notification
                ]
            );
            
            $redirectMessage = "Successfully imported {$importedCount} events.";
            if (!empty($errors)) {
                $redirectMessage .= " " . count($errors) . " rows had errors.";
            }
            
            return redirect('/events')->with('success', $redirectMessage);
            
        } catch (\Exception $e) {
            \Log::error('Import failed: ' . $e->getMessage());
            
            NotificationService::createSystemNotification(
                'danger',
                'Import Failed',
                'Failed to import events: ' . $e->getMessage(),
                ['error' => $e->getMessage()]
            );
            
            return redirect('/events')->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Parse date from various formats
     */
    private function parseDate($dateString) {
        try {
            // Try common date formats
            $formats = ['Y-m-d', 'd/m/Y', 'm/d/Y', 'd-m-Y', 'm-d-Y'];
            
            foreach ($formats as $format) {
                $date = \DateTime::createFromFormat($format, $dateString);
                if ($date !== false) {
                    return $date->format('Y-m-d');
                }
            }
            
            // Fallback to strtotime
            $timestamp = strtotime($dateString);
            if ($timestamp !== false) {
                return date('Y-m-d', $timestamp);
            }
            
            // Default to today
            return date('Y-m-d');
            
        } catch (\Exception $e) {
            return date('Y-m-d');
        }
    }
    
    // API method for monthly trend chart data
    public function monthlyTrendData() {
        $monthlyData = $this->Service->getMonthlyEventCounts(6);
        
        $labels = [];
        $data = [];
        
        foreach ($monthlyData as $month) {
            $labels[] = $month['month'];
            $data[] = $month['count'];
        }
        
        return response()->json([
            'labels' => $labels,
            'data' => $data
        ]);
    }
    
    // API method for activity chart data
    public function activityData() {
        $activityData = $this->Service->getActivityCounts();
        
        return response()->json([
            'labels' => ['Created', 'Updated', 'Completed', 'Deleted'],
            'data' => [
                $activityData['created'],
                $activityData['updated'],
                $activityData['completed'],
                $activityData['deleted']
            ]
        ]);
    }
}
