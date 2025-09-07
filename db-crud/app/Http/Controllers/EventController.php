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

    public function create(){
        return view('events.create');
    }
    
    public function store(Request $request){
        $data=[
            'name'=>$request->name,
            'description'=>$request->description,
            'priority'=>$request->priority,
            'event_date'=>$request->event_date,
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
        $data=[
            'name'=>$request->name,
            'description'=>$request->description,
            'priority'=>$request->priority,
            'event_date'=>$request->event_date,
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
}
