@extends('adminlte::page')

@section('title', '商品編集')

@section('content_header')
    <h1>商品編集</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            {{-- エラーメッセージ --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">商品名</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $item->name) }}">
                </div>

                <!--<div class="form-group">
                    <label for="type">種別</label>
                    <input type="text" name="type" class="form-control" value="{{ old('type', $item->type) }}">
                </div>--->

                <div class="form-group">
                    <label for="detail">詳細</label>
                    <textarea name="detail" class="form-control">{{ old('detail', $item->detail) }}</textarea>
                </div>

                <!--<div class="form-group">
                    <label for="image_url">画像URL</label>
                    <input type="text" name="image_url" class="form-control" value="{{ old('image_url', $item->image_url) }}">
                </div>--->

                <div class="form-group">
                    <label for="cover_image">商品画像（任意）</label>
                    @if (!empty($item->image_url))
                        <div>
                            <p>現在の画像：</p>
                            <img src="data:image/jpeg;base64,{{ $item->cover_image }}" alt="商品画像" width="200">
                        </div>
                    @endif
                    <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/*">
                </div>

                <div class="form-group">
                    <label>カテゴリ</label>
                    <select name="category_id" class="form-control">
                        <option value="">選択してください</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id', $item->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-success">更新する</button>
                <a href="{{ route('items.index') }}" class="btn btn-secondary">戻る</a>
            </form>
        </div>
    </div>
@stop
