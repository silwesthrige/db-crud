@extends('templates.admin-master')
@section('header_content')
<title>Event List</title>
@endsection
@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="card-title mb-1">
                        Manage Events
                    </h2>
                    <p class="text-muted mb-0">Create, edit, and manage your events</p>
                </div>
                <a href="{{url('/events/create')}}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create New Event
                </a>
            </div>
            
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Event Name</th>
                            <th>Description</th>
                            <th>Priority</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $events as $event )
                        <tr>
                            <td><strong>{{ $event->name }}</strong></td>
                            <td>{{ Str::limit($event->description, 50) }}</td>
                            <td>
                                @if($event->priority == 'High')
                                    <span class="badge badge-priority badge-high">{{ $event->priority }}</span>
                                @elseif($event->priority == 'Medium')
                                    <span class="badge badge-priority badge-medium">{{ $event->priority }}</span>
                                @else
                                    <span class="badge badge-priority badge-low">{{ $event->priority }}</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ url('/events/update/'.$event->id) }}" class="btn btn-sm btn-outline-primary me-2" title="Edit Event">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ url('/events/delete/'.$event->id) }}" class="btn btn-sm btn-outline-danger" title="Delete Event" onclick="return confirm('Are you sure you want to delete this event?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

