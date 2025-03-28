<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function pending()
    {
        $status = 'pending';
        $title = 'Pending Documents';

        return view('backend.pages.articles.index', compact('status', 'title'));
    }
    /**
     * Display a listing of the resource.
     */
    public function reviewed()
    {
        $status = ['accepted', 'declined'];
        $title = 'Reviewed Documents';
        return view('backend.pages.articles.index', compact('status', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($area = "")
    {
        $title = "Create Article";
        return view('backend.pages.articles.create', compact('title', 'area'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        $previousUrl = url()->previous();
        return view('backend.pages.articles.show', compact('article', 'previousUrl'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        return view('backend.pages.articles.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        //
    }
}
