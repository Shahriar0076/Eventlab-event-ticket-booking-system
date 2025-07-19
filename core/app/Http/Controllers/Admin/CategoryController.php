<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class CategoryController extends Controller {
    public function index() {
        $pageTitle  = "Manage Category";
        $categories = Category::orderBy('sort_order')->withCount('events')->paginate(getPaginate());
        return view('admin.category.index', compact('pageTitle', 'categories'));
    }

    public function store(Request $request, $id = 0) {
        $imgRequired = $id ? 'nullable' : 'required';
        $request->validate([
            'name' => 'required|unique:categories,id,' . $id,
            'image' => [$imgRequired, 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])]

        ]);

        if ($id) {
            $category = Category::findOrFail($id);
            $notify[] = ['success', 'Category updated successfully'];
        } else {
            $category = new Category();
            $notify[] = ['success', 'Category added successfully'];
        }

        if ($request->hasFile('image')) {
            try {
                $category->image = fileUploader($request->image, getFilePath('category'), getFileSize('category'), $id ? $category->image : null);
            } catch (\Exception $exp) {
                return returnBack('Couldn\'t upload your image');
            }
        }

        $category->name = $request->name;
        $category->slug = slug($request->name);
        $category->save();

        return back()->withNotify($notify);
    }

    public function status($id) {
        return Category::changeStatus($id);
    }

    public function sortCategory() {
        Category::sortOrder();
    }
}
