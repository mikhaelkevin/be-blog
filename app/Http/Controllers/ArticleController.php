<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleTag;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use JD\Cloudder\Facades\Cloudder;

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

        return response()->json($articles->makeHidden(['picture_id']));
    }

    public function getArticle($articleId)
    {
        $article = Article::with(['category:id,category_name', 'user:id,name', 'articleTags:article_id,tag_id'])->find($articleId);
        if (!$article) return response()->json(['message' => 'Article not found!'], 404);

        foreach ($article->articleTags as $tagObj) {
            $tagObj->tagName = Tag::find($tagObj->tag_id, 'tag_name')->tag_name;
        }

        return response()->json($article->makeHidden(['picture_id']));
    }

    public function createArticle(Request $req)
    {
        $userId = Auth::user()->id;
        $validated = $this->validate($req, [
            'title' => 'required|min:6',
            'content' => 'required',
            'categoryId' => 'exists:categories,id',
            'picture' => 'mimes:jpg,jpeg,png,svg|max:3056'
        ]);

        $tags = json_decode($req->tags);
        if ($tags && count($tags)) {
            foreach ($tags as $tagId) {
                $tag = Tag::find($tagId);
                if (!$tag) return response()->json(['message' => "Couldn't found specified tag!"]);
            }
        }

        $requestFile = $req->file('picture');
        $uploadedFile = (object) [];
        if ($req->hasFile('picture') && $requestFile->isValid()) {
            $imageUpload = Cloudder::upload($requestFile->getRealPath(), null, ['folder' => 'blog-alba-tech']);
            $uploadResult = $imageUpload->getResult();
            $uploadedFile->fileUrl = $uploadResult['url'];
            $uploadedFile->fileId = $uploadResult['public_id'];
        }

        $article = new Article();
        $article->title = $validated['title'];
        $article->content = $validated['content'];
        $article->user_id = $userId;
        $article->category_id = $validated['categoryId'] ? $validated['categoryId'] : null;
        $article->picture = $uploadedFile->fileUrl ? $uploadedFile->fileUrl : null;
        $article->picture_id = $uploadedFile->fileId ? $uploadedFile->fileId : null;
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

        $requestFile = $req->file('picture');
        $uploadedFile = (object) [];
        if ($req->hasFile('picture') && $requestFile->isValid()) {
            if ($oldArticle->picture_id) {
                Cloudder::destroyImage($oldArticle->picture_id);
            }
            $imageUpload = Cloudder::upload($requestFile->getRealPath(), null, ['folder' => 'blog-alba-tech'])->getResult();
            $uploadedFile->fileUrl = $imageUpload['url'];
            $uploadedFile->fileId = $imageUpload['public_id'];
        }

        $title = $req->title ? $req->title : $oldArticle->title;
        $content = $req->content ? $req->content : $oldArticle->content;
        $categoryId = $validated['categoryId'] ? $validated['categoryId'] : $oldArticle->category_id;
        $picture = property_exists($uploadedFile, 'fileUrl') ? $uploadedFile->fileUrl : $oldArticle->picture;
        $pictureId = property_exists($uploadedFile, 'fileId') ? $uploadedFile->fileId : $oldArticle->picture_id;

        Article::where('id', $articleId)->where('user_id', $userId)->update([
            'title' => $title,
            'content' => $content,
            'category_id' => $categoryId,
            'picture' => $picture,
            'picture_id' => $pictureId,
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
