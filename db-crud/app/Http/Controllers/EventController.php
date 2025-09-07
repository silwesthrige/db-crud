<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\EventService;
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
        $result=$this->Service->store($data);//$request.all()
        if($result){
            return redirect(url('/events'));
        }else{
            return redirect(url('/events/create'));
        }
    }

    public function delete($id){
        $this->Service->delete($id);
        return redirect('/events');
    }
    public function edit($id){
        $event = $this->Service->findById($id);
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
        if($result){
            return redirect(url('/events'));
        }else{
            return redirect(url('/events/update'));
        }

    }
}
