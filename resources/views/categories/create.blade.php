@extends('adminlte::page')

@section('title', 'カテゴリ登録')

@section('content_header')
    <h1>カテゴリ登録</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">カテゴリ名</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">登録</button>
    </form>
@stop