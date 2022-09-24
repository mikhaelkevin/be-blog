<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function getTags()
    {
        $tags = Tag::all();
        return response()->json($tags);
    }

    public function getTag($tagId)
    {
        $tag = Tag::find($tagId);
        return response()->json($tag);
    }

    public function createTag(Request $req)
    {
        $validated = $this->validate($req, [
            'tagName' => 'required|unique:tags,tag_name|min:3'
        ]);

        $tag = new Tag();
        $tag->tag_name = $validated['tagName'];
        $tag->save();

        return response()->json(['message' => 'Tag has been added!']);
    }
    public function editTag(Request $req, $tagId)
    {
        $validated = $this->validate($req, [
            'tagName' => 'unique:tags,tag_name'
        ]);

        $tag = Tag::find($tagId);
        if (!$tag) return response()->json(['message' => 'Cannot found spesific tag!'], 404);

        $tempTagName = $validated['tagName'] ? $validated['tagName'] : $tag->tag_name;

        $tag->update([
            'tag_name' => $tempTagName
        ]);

        return response()->json(['message' => 'Tag has been updated!']);
    }
    public function deleteTag($tagId)
    {
        $tag = Tag::find($tagId);
        if (!$tag) return response()->json(['message' => 'Cannot found spesific tag!'], 404);

        $tag->delete();
        return response()->json(['message' => 'Tag has been deleted!']);
    }
}
