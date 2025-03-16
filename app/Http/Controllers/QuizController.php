<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuizRequest;
use App\Http\Requests\UpdateQuizRequest;
use Illuminate\Http\Request;
use App\Models\Quiz;

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
        // dd($request, $categoryId);
        $quiz = new Quiz();
        $quiz->category_id = $categoryId;
        $quiz->question = $request->question;
        $quiz->explanation = $request->explanation;
        $quiz->save();

        $quizId = $quiz->id;
        $option1 = new Option();
        $option1->content = $request->content1;
        $option1->is_correct = $request->isCorrect;
        $option1->save();




        // return redirect()->route('admin.categories.show', ['categoryId' => $categoryId]);
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
