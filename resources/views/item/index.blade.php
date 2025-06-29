@extends('adminlte::page')

@section('title', '商品一覧')

@section('content_header')
    <h1>商品一覧</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">商品一覧</h3>

                    {{-- 検索フォーム --}}
                    <form method="GET" action="{{ route('items.index') }}" class="form-inline">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" name="keyword" class="form-control float-right" placeholder="商品名で検索"
                                value="{{ request('keyword') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i> 検索
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="card-tools">
                        <div class="input-group input-group-sm">
                            <div class="input-group-append">
                                <a href="{{ url('items/add') }}" class="btn btn-default">商品登録</a>
                                 <a href="{{ url('categories/create') }}" class="btn btn-default ml-2">カテゴリ登録</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>名前</th>
                                <th>詳細</th>
                                <!-- <th>商品URL</th> -->
                                <th>カテゴリ</th>
                                <th>画像</th>
                                <th>操作</th>  
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->detail }}</td>
                                    <!-- <td>{{ $item->image_url }}</td> -->
                                     <td>{{ $item->category->name ?? '未設定' }}</td>
                                    <td>
                                        @if ($item->image_url)
                                            <img src="{{ $item->image_url }}" alt="商品画像" style="max-height: 150px;">
                                        @else
                                            <span class="text-muted">画像なし</span>
                                        @endif
                                    </td>
                                     <td> 
                                        {{-- 編集ボタン --}}
                                        <a href="{{ route('items.edit', $item->id) }}" class="btn btn-warning btn-sm">編集</a>

                                        {{-- 削除ボタン --}}
                                        <form action="{{ route('items.destroy', $item->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('本当に削除しますか？')">削除</button>
                                        </form>
                                      </td>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@stop

@section('js')
@stop
