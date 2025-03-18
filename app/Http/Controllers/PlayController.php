<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class PlayController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('play.top', ['categories' => $categories]);
    }

    /**
     * カテゴリー選択画面
     */
    public function categories(Request $request, int $categoryId)
    {
        // dd($categoryId, $request);
        $category = Category::withCount('quizzes')->findOrFail($categoryId);
        // dd($category->quizzes_count);
        return view('play.start', [
            'category' => $category,
            'quizzesCount' => $category->quizzes_count,
        ]);
    }
}
