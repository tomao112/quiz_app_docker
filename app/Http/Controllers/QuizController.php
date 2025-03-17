<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuizRequest;
use App\Http\Requests\UpdateQuizRequest;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Option;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * クイズ新規登録画面
     */
    public function create(Request $request, int $categoryId)
    {
        //
        // dd($request, $categoryId);
        return view('admin.quizzes.create', [
            'categoryId' => $categoryId
        ]);
    }

    /**
     * クイズ新規登録処理
     */
    public function store(StoreQuizRequest $request, int $categoryId)
    {
        //
        $quiz = new Quiz();
        $quiz->category_id = $categoryId;
        $quiz->question = $request->question;
        $quiz->explanation = $request->explanation;
        $quiz->save();

        $options = [
            [
                'quiz_id' => $quiz->id,
                'content'=> $request->content1,
                'is_correct' => $request->isCorrect1,
            ],
            [
                'quiz_id' => $quiz->id,
                'content'=> $request->content2,
                'is_correct' => $request->isCorrect2,
            ],
            [
                'quiz_id' => $quiz->id,
                'content'=> $request->content3,
                'is_correct' => $request->isCorrect3,
            ],
            [
                'quiz_id' => $quiz->id,
                'content'=> $request->content4,
                'is_correct' => $request->isCorrect4,
            ],
        ];

        foreach($options as $option) {
            $newOption = new option();
            $newOption->quiz_id = $option['quiz_id'];
            $newOption->content = $option['content'];
            $newOption->is_correct = $option['is_correct'];
            $newOption->save();
        }

        return redirect()->route('admin.categories.show', ['categoryId' => $categoryId]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Quiz $quiz)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quiz $quiz)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQuizRequest $request, Quiz $quiz)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz $quiz)
    {
        //
    }
}
