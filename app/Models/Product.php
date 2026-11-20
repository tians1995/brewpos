<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Product extends Model {
 protected $fillable=['category_id','name','description','sku','price','stock','is_available'];
 protected $casts=['price'=>'integer','stock'=>'integer','is_available'=>'boolean'];
 public function category(){ return $this->belongsTo(Category::class); }
}
