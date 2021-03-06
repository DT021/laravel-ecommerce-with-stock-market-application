<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;
use App\Admin;
use Session;

class CategoryController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth:admin');
    }

    public function category()
    {
    	$categories = Category::all();
    	return view('admin.category.category')->with('categories', $categories);
    }

    public function jsoncategory()
    {
        $categories = Category::all();
        return $categories;
    }

    public function store(Request $request)
    {
    	$this->validate($request, [
            'category_name' => 'required|max:255'
        ]);

        $new_cat_slug = str_slug($request->category_name);

        $check_cat = Category::where('category_slug', $new_cat_slug)->first();
        
        if ($check_cat) {
        	Session::flash('noty-error', 'Category already exists. Try different one.');
	        return redirect()->route('category');
        } else {
	        $category = new Category;
	        $category->category_name = $request->category_name;
	        $category->category_slug = str_slug($request->category_name);
	        $category->save();

	        Session::flash('noty-success', 'Category Added Successfully !');
	        return redirect()->route('category');
	    }
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'category_name' => 'required|max:255'
        ]);

        $new_cat_slug = str_slug($request->category_name);
        $check_cat = Category::where('category_slug', $new_cat_slug)->first();
        
        if ($check_cat) {
            Session::flash('noty-warning', 'Category already exists. Update denied.');
            return redirect()->route('category');
        } else {
            $category = Category::find($id);
            $category->category_name = $request->category_name;
            $category->category_slug = str_slug($request->category_name);
            $category->save();

            Session::flash('noty-info', 'Category Updated Successfully !');
            return redirect()->route('category');
        }
    }

}
