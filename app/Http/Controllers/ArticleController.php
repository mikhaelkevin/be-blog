<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleTag;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    public function getArticles()
    {
        $articles = Article::with(['category:id,category_name', 'user:id,name', 'articleTags:article_id,tag_id'])->orderBy('id', 'desc')->get();

        foreach ($articles as $articleObj) {
            foreach ($articleObj->articleTags as $tagObj) {
                $tagObj->tagName = Tag::find($tagObj->tag_id, 'tag_name')->tag_name;
            }
        }

        return response()->json($articles);
    }

    public function getArticle($articleId)
    {
        $article = Article::with(['category:id,category_name', 'user:id,name', 'articleTags:article_id,tag_id'])->find($articleId);
        if (!$article) return response()->json(['message' => 'Article not found!'], 404);

        foreach ($article->articleTags as $tagObj) {
            $tagObj->tagName = Tag::find($tagObj->tag_id, 'tag_name')->tag_name;
        }

        return response()->json($article);
    }

    public function createArticle(Request $req)
    {
        $userId = Auth::user()->id;
        $validated = $this->validate($req, [
            'title' => 'required|min:6',
            'content' => 'required',
            'categoryId' => 'exists:categories,id'
        ]);

        $tags = json_decode($req->tags);
        if ($tags && count($tags)) {
            foreach ($tags as $tagId) {
                $tag = Tag::find($tagId);
                if (!$tag) return response()->json(['message' => "Couldn't found specified tag!"]);
            }
        }

        $article = new Article();
        $article->title = $validated['title'];
        $article->content = $validated['content'];
        $article->user_id = $userId;
        $article->category_id = $validated['categoryId'] ? $validated['categoryId'] : null;
        $article->save();

        foreach ($tags as $tagId) {
            DB::insert('insert into article_tag (article_id, tag_id) values (:articleId, :tagId)', ['articleId' => $article->id, 'tagId' => $tagId]);
        }

        return response()->json(['message' => 'Article has been created!']);
    }

    public function editArticle(Request $req, $articleId)
    {
        $userId = Auth::user()->id;
        $oldArticle = Article::where('id', $articleId)->where('user_id', $userId)->first();
        if (!$oldArticle) return response()->json(['message' => "Couldn't found the article!"]);

        $validated = $this->validate($req, [
            'categoryId' => 'exists:categories,id'
        ]);

        $tags = json_decode($req->tags);
        if ($tags && count($tags)) {
            foreach ($tags as $tagId) {
                $tag = Tag::find($tagId);
                if (!$tag) return response()->json(['message' => "Couldn't found specified tag!"]);
            }
        }

        $title = $req->title ? $req->title : $oldArticle->title;
        $content = $req->content ? $req->content : $oldArticle->content;
        $categoryId = $validated['categoryId'] ? $validated['categoryId'] : $oldArticle->category_id;

        Article::where('id', $articleId)->where('user_id', $userId)->update([
            'title' => $title,
            'content' => $content,
            'category_id' => $categoryId
        ]);

        ArticleTag::where('article_id', $articleId)->delete();
        foreach ($tags as $tagId) {
            DB::insert('insert into article_tag (article_id, tag_id) values (:articleId, :tagId)', ['articleId' => $articleId, 'tagId' => $tagId]);
        }
        return response()->json(['message' => 'Edit article success!']);
    }

    public function deleteArticle($articleId)
    {
        $userId = Auth::user()->id;
        $articleFounded = Article::where('id', $articleId)->where('user_id', $userId)->first();
        if (!$articleFounded) return response()->json(['message' => "Couldn't found your article!"]);

        $articleFounded->delete();
        return response()->json(['message' => 'Article deleted!']);
    }
}
