<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                ->constrained()         //categoryテーブルのidカラムと紐づけ
                ->onUpdate('cascade')   //親テーブルのレコードが更新されたとき,同時に更新
                ->onDelete('cascade')
                ->comment('カテゴリーID');  //親テーブルのレコードが削除されたとき、同時に更新
            $table->text('question')->comment('問題文');
            $table->text('explanation')->comment('解説');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
