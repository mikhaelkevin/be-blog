<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getCategories()
    {
        $categories = Category::all();

        return response()->json($categories);
    }

    public function getCategory($categoryId)
    {
        $category = Category::find($categoryId);
        if (!$category) return response()->json(['message' => 'Cannot found specified category!'], 404);

        return response()->json($category);
    }

    public function createCategory(Request $req)
    {
        $validated = $this->validate($req, [
            'categoryName' => 'required|unique:categories,category_name|min:4'
        ]);

        $category = new Category();
        $category->category_name = $validated['categoryName'];
        $category->save();

        return response()->json(['message' => 'New category has been added!'], 201);
    }

    public function editCategory(Request $req, $categoryId)
    {

        $category = Category::find($categoryId);
        if (!$category) return response()->json(['message' => 'Cannot found specified category!'], 404);

        $tempCategoryName = $req->categoryName ? $req->categoryName : $category->category_name;

        $category->update([
            'category_name' => $tempCategoryName
        ]);

        return response()->json(['message' => 'Category has been updated!']);
    }

    public function deleteCategory($categoryId)
    {
        $foundedCategory = Category::find($categoryId);
        if (!$foundedCategory) return response()->json(['message' => 'Cannot found specified category'], 404);

        $foundedCategory->delete();
        return response()->json(['message' => 'Category has been deleted!']);
    }
}
