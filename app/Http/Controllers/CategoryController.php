<?php
namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Http\Request;
class CategoryController extends Controller {
 public function store(Request $r){ Category::create($r->validate(['name'=>'required|string|max:100|unique:categories,name','description'=>'nullable|string'])); return back(); }
 public function destroy(Category $category){ if($category->products()->exists()) return back()->withErrors(['category'=>'Kategori masih digunakan produk.']); $category->delete(); return back(); }
}
