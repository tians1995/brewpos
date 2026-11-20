<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Support\Audit;
use Inertia\Inertia;
class PosController extends Controller {
 public function index(){ return Inertia::render('brewpos/pos',['products'=>Product::where('is_available',true)->where('stock','>',0)->with('category')->orderBy('name')->get()]); }
 public function checkout(Request $r){
  $data=$r->validate(['items'=>'required|array|min:1','items.*.product_id'=>'required|integer|exists:products,id','items.*.quantity'=>'required|integer|min:1','paid_amount'=>'required|integer|min:0']);
  DB::transaction(function() use($data,$r){
   $lines=[];$total=0;
   foreach($data['items'] as $item){$p=Product::whereKey($item['product_id'])->lockForUpdate()->firstOrFail(); if(!$p->is_available||$p->stock<$item['quantity']) throw \Illuminate\Validation\ValidationException::withMessages(['items'=>'Stok '.$p->name.' tidak mencukupi.']); $sub=$p->price*$item['quantity'];$total+=$sub;$lines[]=[$p,$item['quantity'],$sub];}
   if($data['paid_amount']<$total) throw \Illuminate\Validation\ValidationException::withMessages(['paid_amount'=>'Uang pembayaran kurang.']);
   $tx=Transaction::create(['invoice_number'=>'BRW-'.now()->format('Ymd').'-'.strtoupper(Str::random(5)),'user_id'=>$r->user()->id,'subtotal'=>$total,'total'=>$total,'paid_amount'=>$data['paid_amount'],'change_amount'=>$data['paid_amount']-$total,'payment_method'=>'cash']);
   foreach($lines as [$p,$qty,$sub]){$tx->details()->create(['product_id'=>$p->id,'product_name'=>$p->name,'price'=>$p->price,'quantity'=>$qty,'subtotal'=>$sub]);$p->decrement('stock',$qty);}
   Audit::record('create', 'transaction', 'Transaksi '.$tx->invoice_number.' berhasil dibuat senilai Rp '.number_format($tx->total,0,',','.'), $r->user(), $r, $tx->id, ['invoice_number'=>$tx->invoice_number,'total'=>$tx->total,'items_count'=>count($lines)]);
  });
  return redirect('/transactions')->with('success','Transaksi berhasil disimpan.');
 }
}
