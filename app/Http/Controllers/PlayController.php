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
        // セッションをクリア
        session()->forget('resultArray');
        $category = Category::withCount('quizzes')->findOrFail($categoryId);
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
        // セッションに保存されているクイズIDを取得
        $reaultArray = session('resultArray');
        if(is_null($reaultArray)) {
            // クイズIDをすべて抽出
            $quizIds = $category->quizzes->pluck('id')->toArray();
            // クイズIDをシャッフル
            shuffle($quizIds);
            // 結果配列を初期化
            $reaultArray = [];
            // クイズIDを順に取り出して配列に格納
            foreach($quizIds as $quizId) {
                $reaultArray[] = [
                    'quizId' => $quizId,
                    'reault' =>null,
                ];
            }

            session(['resultArray' => $reaultArray]);
        };

        $noAnswerResult = collect($reaultArray)->filter(function($item) {
            return $item['result'] === null;
        })->first();

        if(!$noAnswerResult) {
            return redirect()->route('categories.quizzes.result', ['categoryId' => $categoryId]);
        }

        $quiz = $category->quizzes->firstWhere('id', $noAnswerResult['quizId'])->toArray();

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
        $selectedOptions = $request->optionId === null ? [] : $request->optionId;
        $category = Category::with('quizzes.options')->findOrFail($categoryId);
        $quiz = $category->quizzes->firstWhere('id', $quizId);
        $quizOptions = $quiz->options->toArray();
        $isCorrectAnswer = $this->isCorrectAnswer($selectedOptions, $quizOptions);


        $reaultArray = session('resultArray');
        foreach($reaultArray as $index => $result) {
            if($result['quizId'] === (int)$quizId) {
                $resultArray[$index]['result'] = $isCorrectAnswer;
                break;
            }
        }
        session(['resultArray' => $resultArray]);


        return view('play.answer', [
            'isCorrectAnswer' => $isCorrectAnswer,
            'quiz' => $quiz->toArray(),
            'quizOptions' => $quizOptions,
            'selectedOptions' => $selectedOptions,
            'categoryId' => $categoryId,
        ]);
    }

    public function result(Request $request, int $categoryId) {
        $resultArray = session('resultArray');
        $questionCount = count($resultArray);
        $correctCount = collect($reaultArray)->filter(function ($result) {
            return $result['result'] === true;
        })->count();

        return view('play.result', [
            'categoryId' => $categoryId,
            'questionCount' => $questionCount,
            'correctCount' => $correctCount,
        ]);
    }

    // クイズ結果画面
    private function isCorrectAnswer(array $selectedOptions, array $quizOptions)
    {
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
