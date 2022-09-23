<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function getArticles()
    {
        $articles = Article::all();
        return response()->json($articles);
    }

    public function getArticle($articleId)
    {
        $article = Article::find($articleId);

        if (!$article) return response()->json(['message' => 'Article not found!'], 404);

        return response()->json($article);
    }

    public function createArticle(Request $req)
    {
        $userId = Auth::user()->id;
        $validated = $this->validate($req, [
            'title' => 'required|min:6',
            'content' => 'required'
        ]);

        $article = new Article();
        $article->title = $validated['title'];
        $article->content = $validated['content'];
        $article->user_id = $userId;
        $article->save();

        return response()->json(['message' => 'Succesful create a new article!']);
    }

    public function editArticle(Request $req, $articleId)
    {
        $userId = Auth::user()->id;
        $oldArticle = Article::where('id', $articleId)->where('user_id', $userId)->first();
        if (!$oldArticle) return response()->json(['message' => "Couldn't found the article!"]);

        $title = $req->title ? $req->title : $oldArticle->title;
        $content = $req->content ? $req->content : $oldArticle->content;

        Article::where('id', $articleId)->where('user_id', $userId)->update([
            'title' => $title,
            'content' => $content
        ]);

        return response()->json(['message' => 'Edit article success!']);
    }

    public function deleteArticle($articleId)
    {
        $userId = Auth::user()->id;
        $articleFounded = Article::where('id', $articleId)->where('user_id', $userId)->first();
        if (!$articleFounded) return response()->json(['message' => "Couldn't found the article!"]);

        Article::where('id', $articleId)->where('user_id', $userId)->first()->delete();
        return response()->json(['message' => 'Article deleted!']);
    }
}
