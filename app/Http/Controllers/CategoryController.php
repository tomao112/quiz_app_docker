<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //カテゴリー一覧取得
        $categories = Category::get();
        return view('admin.top', [
            'categories' => $categories
        ]);
    }

    /**
     * 管理画面トップページ、カテゴリー一覧表示
     */
    public function create()
    {
        //カテゴリー新規登録画面表示
        return view('admin.categories.create');
    }

    /**
     * カテゴリー新規登録機能
     */
    public function store(StoreCategoryRequest $request)
    {
        //カテゴリー新規登録
        // dd('カテゴリー新規登録処理', $request);
        // dd($request->name, $request->description);
        $category = new Category();
        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();
        return redirect()->route('admin.top');
    }

    /**
     * カテゴリー詳細画面　クイズ一覧画面表示
     */
    public function show(Request $request, string $categoryId)
    {
        //
        $category = Category::with('quizzes')->findOrFail($categoryId);
        // dd($category->quizzes);
        return view('admin.categories.show', [
            'category' => $category,
            'quizzes' => $category->quizzes
        ]);
    }

    /**
     * カテゴリー編集画面
     */
    public function edit(Request $request, string $categoryId)
    {
        //
        $category = Category::findOrFail($categoryId);
        return view('admin.categories.edit', [
            'category' => $category,
        ]);
    }

    /**
     *　カテゴリー編集機能
     */
    public function update(UpdateCategoryRequest $request, int $categoryId)
    {
        //
        $category = Category::findOrFail($categoryId);
        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();
        return redirect()->route('admin.categories.show', ['categoryId' => $categoryId]);

    }

    /**
     * カテゴリー削除処理
     */
    public function destroy(Request $request, string $categoryId)
    {
        //
        $category = Category::findOrFail($categoryId);
        $category->delete();
        return redirect()->route('admin.top');
    }
}
