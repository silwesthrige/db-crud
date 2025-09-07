@extends('templates.admin-master')
@section('header_content')
<title>Update Event</title>
@endsection

@section('content')
    <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="card-title">Update Event</h2>
                    <form action="{{ url('/events/update') }}" method="POST">
                        <input type="hidden" name="id" value="{{ $event->id }}">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="eventName">Event Name</label>
                            <input type="text" class="form-control" name="name" value="{{ $event->name }}" id="eventName" placeholder="Updateevent name">
                        </div>
                        <div class="form-group mb-3">
                            <label for="eventDescription">Event Description</label>
                            <textarea class="form-control" name="description" id="eventDescription" rows="3">{{ $event->description }}</textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="eventImportance">Event Importance</label>
                            <select class="form-control" name="priority" id="eventImportance">
                                <option value="High" @if($event->priority=='High')selected @endif>High</option>
                                <option value="Medium" @if($event->priority=='Medium')selected @endif>Medium</option>
                                <option value="Low" @if($event->priority=='Low')selected @endif>Low</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="eventDate">Event Date</label>
                            <input type="date" class="form-control" name="event_date" id="eventDate" value="{{ $event->event_date }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
@endsection