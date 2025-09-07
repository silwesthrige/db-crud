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
}
