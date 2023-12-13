@extends('layout.main')

@section('title', 'insert form')

@section('content')
    <form action="{{ route('users.store') }}" method="post" accept-charset="utf-8">
        @csrf
        <input type="text" autofocus name="name" placeholder="name ..." id="" /><br />
        <input type="email" name="email" placeholder="email ..." id="" /><br />
        <input type="password" name="password" placeholder="password ..." id="" /><br />
        <button type="submit">save</button>
    </form>
@endsection
