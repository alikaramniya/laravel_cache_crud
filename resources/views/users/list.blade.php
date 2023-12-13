@extends('layout.main')

@section('title', 'list users')

@section('content')
    @if ($listDeletedUsers)
        <div class="list-soft-deleted">
            <a href="{{ route('users.list.soft-deleted') }}">list soft deleted users</a>
        </div>
    @endif
    <table>
        <thead>
            <tr>
                <th>name</th>
                <th>email</th>
                <th>edit</th>
                <th>delete</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td><a href="{{ route('users.edit', $user) }}">edit</a></td>
                    <td>
                        <form action="{{ route('users.delete', $user->id) }}" method="post" accept-charset="utf-8">
                            @csrf
                            @method('DELETE')
                            <button type="submit">delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <div style="color: red;width: 200px; font-size: 25px; margin:20px auto">
                    list empty
                </div>
            @endforelse
        </tbody>
    </table>
@endsection
