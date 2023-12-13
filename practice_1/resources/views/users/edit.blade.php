@extends('layout.main')

@section('title', 'edit user')

@section('content')
    <form class="form" action="{{ route('users.update', $user->id) }}" method="post" accept-charset="utf-8">
        @csrf
        @method('PATCH')
        <input type="text" autofocus name="name" value="{{ $user->name }}" placeholder="name ..." id="" /><br />
        <input type="email" name="email" value="{{ $user->email }}" placeholder="email ..." id="" /><br />
        <button type="submit">update</button>
    </form>
@endsection
