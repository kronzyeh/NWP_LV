<!-- resources/views/tasks/create.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Dodaj novi zadatak</h2>

    <form method="POST" action="{{ route('tasks.store') }}">
        @csrf

        <div class="mb-3">
            <label for="title_hr" class="form-label">Naslov (HR)</label>
            <input type="text" name="title_hr" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="title_en" class="form-label">Naslov (EN)</label>
            <input type="text" name="title_en" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Opis</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label for="study_type" class="form-label">Tip studija</label>
            <select name="study_type" class="form-select" required>
                <option value="strucni">Stručni</option>
                <option value="preddiplomski">Preddiplomski</option>
                <option value="diplomski">Diplomski</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Spremi</button>
    </form>
</div>
@endsection
