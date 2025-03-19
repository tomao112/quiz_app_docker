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

    /**
     * クイズ出題画面
     */
    public function quizzes(Request $request, int $categoryId)
    {
        $category = Category::with('quizzes.options')->findOrFail($categoryId);
        $quizzes = $category->quizzes->toArray();
        shuffle($quizzes);
        $quiz = $quizzes[0];
        return view('play.quizzes', [
            'categoryId' => $categoryId,
            'quiz' => $quiz,
        ]);
    }

    /**
     * クイズ回答画面
     */
    public function answer(Request $request, int $categoryId)
    {
        $quizId = $request->quizId;
        $selectedOptions = $request->optionId;
        $category = Category::with('quizzes.options')->findOrFail($categoryId);
        // dd($category);
        $quiz = $category->quizzes->firstWhere('id', $quizId);
        $quizOptions = $quiz->options->toArray();

        $result = $this->isCorrectAnswer($selectedOptions, $quizOptions);
        return view('play.answer', []);
    }

    // クイズ結果画面
    private function isCorrectAnswer(array $selectedOptions, array $quizOptions)
    {
        // dd('isCorrectAnswer', $selectedOptions, $quizOptions);
        $correctOptions = array_filter($quizOptions, function ($option) {
            return $option['is_correct'] === 1;

        });

        $correctOptionIds = array_map(function($option) {
            return $option['id'];
        }, $correctOptions);

        if(count($selectedOptions) !== count($correctOptionIds)) {
            return false;

        }

        foreach($selectedOptions as $selectedOption) {
            if(!in_array((int)$selectedOption, $correctOptionIds)) {
                return false;

            }
        }
        return true;

    }
}
