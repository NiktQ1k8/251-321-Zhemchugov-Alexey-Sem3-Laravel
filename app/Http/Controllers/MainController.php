<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class MainController extends Controller
{
    public function index(): View
    {
        $articles = File::json(public_path('articles.json'));

        return view('welcome', ['articles' => $articles]);
    }

    public function galery(string $index): View
    {
        $articles = File::json(public_path('articles.json'));

        abort_unless(isset($articles[$index]), 404);

        return view('galery', ['article' => $articles[$index]]);
    }
}
