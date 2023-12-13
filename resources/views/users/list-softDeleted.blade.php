@extends('layout.main')

@section('title', 'list soft deleted users')

@section('content')
    <table>
        <thead>
            <tr>
                <th>name</th>
                <th>email</th>
                <th>restore</th>
                <th>force delete</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td><a href="{{ route('users.restore', $user) }}">restore</a></td>
                    <td><a href="{{ route('users.force.delete', $user) }}">delete</a></td>
                </tr>
            @empty
                not found
            @endforelse
        </tbody>
    </table>
@endsection
