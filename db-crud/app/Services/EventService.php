<?php

namespace App\Services;
use App\Models\Event;

class EventService
{
    //method to retrieve all data from the table
    public function getAll(){
        $events=Event::all();
        return $events;
    }
    //method to seletct a specific record
    public function findById($id){
        $event=Event::find($id);
        return $event;
    }
    //method to insert a record
    public function store(array $data){
        try {
            $event = new Event($data);
            return $event->save(); // boolean value is returned
        } catch (\Exception $e) {
            \Log::error('Failed to create event: ' . $e->getMessage());
            return false;
        }
    }
    //method to update a record based on id
    // public function update($id,array $data){
    //     $event=Event::find($id);
    //     $event->name=$data['name'];
    //     $event->description=$data['description'];
    //     $event->priority=$data['priority'];
    //     $event->event_date=$data['event_date'];
    //     return $event->save();
    // }
    public function update($id, array $data)
    {
        try {
            $event = Event::find($id);
            if (!$event) {
                return false; // Event not found
            }

            $event->name = $data['name'];
            $event->description = $data['description'];
            $event->priority = $data['priority'];
            $event->event_date = $data['event_date'];
            
            return $event->save();
        } catch (\Exception $e) {
            \Log::error('Failed to update event: ' . $e->getMessage());
            return false;
        }
    }


    public function delete($id){
        try {
            $event = Event::find($id);
            if (!$event) {
                return false;
            }
            $event->delete();
            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to delete event: ' . $e->getMessage());
            return false;
        }
    }
    
    //method to get events by date and priority for charts
    public function getEventsByDateAndPriority($date, $priority) {
        return Event::whereDate('event_date', $date->format('Y-m-d'))
                   ->where('priority', $priority)
                   ->count();
    }
    
    //method to get monthly event counts for chart
    public function getMonthlyEventCounts($months = 6) {
        $data = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = Event::whereYear('event_date', $date->year)
                         ->whereMonth('event_date', $date->month)
                         ->count();
            $data[] = [
                'month' => $date->format('M'),
                'count' => $count
            ];
        }
        return $data;
    }
    
    //method to get activity counts for chart
    public function getActivityCounts() {
        $now = now();
        
        return [
            'created' => Event::whereDate('created_at', $now->toDateString())->count(),
            'updated' => Event::whereDate('updated_at', $now->toDateString())
                            ->where('created_at', '!=', Event::raw('updated_at'))
                            ->count(),
            'completed' => Event::where('priority', 'Low')->count(), // Assuming Low priority means completed
            'deleted' => Event::onlyTrashed()->whereDate('deleted_at', $now->toDateString())->count() // Count soft deleted events for today
        ];
    }
}
