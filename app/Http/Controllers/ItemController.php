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
    $query = Item::query();

    // 複数項目でのキーワード検索（名前・種別・詳細）
    if ($request->filled('keyword')) {
        $keyword = $request->input('keyword');
        $query->where(function ($q) use ($keyword) {
            $q->where('name', 'like', '%' . $keyword . '%')
              ->orWhere('type', 'like', '%' . $keyword . '%')
              ->orWhere('detail', 'like', '%' . $keyword . '%');
        });
    }

    // カテゴリIDが指定されていれば絞り込み
    if ($request->filled('category_id')) {
        $query->where('category_id', $request->input('category_id'));
    }

    // 商品一覧取得
    $items = $query->get();

    //カテゴリ一覧を検索フォームに渡す
    $categories = \App\Models\Category::all();

    return view('item.index', compact('items', 'categories'));
}
    /**
     * 商品登録
     */
    public function add(Request $request)
    {
        $categories = \App\Models\Category::all();

        // POSTリクエストのとき
        if ($request->isMethod('post')) {
            // バリデーション
            $this->validate($request, [
                'name' => 'required|max:100',
                //'category_id' => 'required|exists:categories,id',
            ]);

            // 商品登録
            Item::create([
                'user_id' => Auth::user()->id,
                'name' => $request->name,
                //'type' => $request->type,
                'detail' => $request->detail,
                'image_url'=>$request->image_url,
                'category_id' => $request->category_id,
            ]);

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
        ]);

        $item = Item::findOrFail($id);
        $item->update([
            'name' => $request->name,
            'type' => $request->type,
            'detail' => $request->detail,
            'image_url' => $request->image_url,
            'category_id' => $request->category_id,
        ]);

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