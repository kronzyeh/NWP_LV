<!-- resources/views/applications/my-applicants.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Moje prijave</h2>

    @foreach($applications as $application)
        <div class="card my-3">
            <div class="card-body">
                <p><strong>Zadatak:</strong> {{ $application->task->title_hr }}</p>
                <p><strong>Student:</strong> {{ $application->user->name }} ({{ $application->user->email }})</p>
                <p><strong>Status:</strong> {{ $application->status }}</p>

                @if($application->status !== 'accepted')
                    <form method="POST" action="{{ url('accept/' . $application->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success">Prihvati studenta</button>
                    </form>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection
