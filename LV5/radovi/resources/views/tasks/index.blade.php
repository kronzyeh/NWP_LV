@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Svi dostupni zadaci</h2>

    @foreach ($tasks as $task)
        <div class="card my-3">
            <div class="card-body">
                <h5>{{ $task->title_hr }} / {{ $task->title_en }}</h5>
                <p>{{ $task->description }}</p>
                <p><strong>Tip studija:</strong> {{ $task->study_type }}</p>

                @if(auth()->user()->role === 'student')
                    <a href="{{ url('apply/' . $task->id) }}" class="btn btn-primary">Prijavi se</a>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection