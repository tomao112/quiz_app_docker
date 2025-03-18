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
}
