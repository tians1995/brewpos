<?php
namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
class ProductController extends Controller {
 public function index(){ return Inertia::render('brewpos/products',['products'=>Product::with('category')->latest()->get(),'categories'=>Category::orderBy('name')->get()]); }
 public function store(Request $r){
  $data=$r->validate(['category_id'=>'nullable|exists:categories,id','name'=>'required|string|max:120','sku'=>'nullable|string|max:60|unique:products,sku','price'=>'required|integer|min:0','stock'=>'required|integer|min:0','description'=>'nullable|string']);
  Product::create($data+['is_available'=>true]); return back()->with('success','Produk ditambahkan.');
 }
 public function update(Request $r, Product $product){
  $data=$r->validate(['category_id'=>'nullable|exists:categories,id','name'=>'required|string|max:120','sku'=>'nullable|string|max:60|unique:products,sku,'.$product->id,'price'=>'required|integer|min:0','stock'=>'required|integer|min:0','description'=>'nullable|string','is_available'=>'boolean']);
  $product->update($data); return back()->with('success','Produk diperbarui.');
 }
 public function destroy(Product $product){ $product->delete(); return back()->with('success','Produk dihapus.'); }
}
