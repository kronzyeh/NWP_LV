<!-- resources/views/roles/index.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Dodjela uloga korisnicima</h2>

    <table class="table">
        <thead>
            <tr>
                <th>Ime</th>
                <th>Email</th>
                <th>Uloga</th>
                <th>Akcija</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <form method="POST" action="{{ url('roles/' . $user->id) }}">
                        @csrf
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <select name="role" class="form-select">
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="nastavnik" {{ $user->role === 'nastavnik' ? 'selected' : '' }}>Nastavnik</option>
                                <option value="student" {{ $user->role === 'student' ? 'selected' : '' }}>Student</option>
                            </select>
                        </td>
                        <td>
                            <button type="submit" class="btn btn-primary btn-sm">Spremi</button>
                        </td>
                    </form>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
