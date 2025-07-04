<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Category;

class ItemController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 商品一覧
     */
    public function index(Request $request)
{
    $query = Item::query()
        ->select('items.*', 'categories.name as category_name')
        ->leftJoin('categories', 'items.category_id', '=', 'categories.id');

    if ($request->filled('keyword')) {
        $keyword = $request->input('keyword');
        $query->where(function ($q) use ($keyword) {
            $q->where('items.name', 'like', '%' . $keyword . '%')
              ->orWhere('categories.name', 'like', '%' . $keyword . '%')
              ->orWhere('items.detail', 'like', '%' . $keyword . '%');
        });
    }

    if ($request->filled('category_id')) {
        $query->where('items.category_id', $request->input('category_id'));
    }

    $items = $query->get();

    $categories = \App\Models\Category::all();

    return view('item.index', compact('items', 'categories'));
}
    /**
     * 商品登録
     */
    public function add(Request $request)
{
    $categories = \App\Models\Category::all();

    if ($request->isMethod('post')) {
        $this->validate($request, [
            'name' => 'required|max:100',
            'detail' => 'nullable|string|max:500',  // 文字列として最大500文字
            'cover_image' => 'nullable|image|max:20480', // 画像バリデーション
        ]);

        $item = new Item();
        $item->user_id = Auth::id();
        $item->name = $request->name;
        $item->detail = $request->detail;
        $item->category_id = $request->category_id;

        // 画像がアップロードされた場合
        if ($request->hasFile('cover_image')) {
            $imageData = file_get_contents($request->file('cover_image')->getRealPath());
            $mimeType = $request->file('cover_image')->getMimeType();
            $base64Image = base64_encode($imageData);
            $item->image_url = 'data:' . $mimeType . ';base64,' . $base64Image;
        }

        $item->save();

        return redirect('/items');
    }

    return view('item.add', compact('categories'));
}

/**
     * 商品編集画面を表示
     */
    public function edit($id)
    {
        $item = Item::findOrFail($id);
         $categories = \App\Models\Category::all();
        return view('item.edit', compact('item', 'categories'));
    }

    /**
     * 商品を更新
     */
    public function update(Request $request, $id)
{
    $this->validate($request, [
        'name' => 'required|max:100',
        'detail' => 'nullable|string|max:500',// 文字列として最大500文字
        'cover_image' => 'nullable|image|max:20480', // 追加OK
    ]);

    $item = Item::findOrFail($id);
    $item->name = $request->name;
    $item->detail = $request->detail;
    $item->category_id = $request->category_id;

    if ($request->hasFile('cover_image')) {
        $imageData = file_get_contents($request->file('cover_image')->getRealPath());
        $mimeType = $request->file('cover_image')->getMimeType();
        $base64Image = base64_encode($imageData);
        $item->image_url = 'data:' . $mimeType . ';base64,' . $base64Image;
    }

    $item->save();

    return redirect('/items')->with('success', '商品を更新しました');
}


    /**
     * 商品を削除
     */
    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return redirect('/items')->with('success', '商品を削除しました');
    }
}