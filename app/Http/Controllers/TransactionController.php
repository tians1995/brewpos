<?php
namespace App\Http\Controllers;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Inertia\Inertia;
class TransactionController extends Controller {
 public function index(){return Inertia::render('brewpos/transactions',['transactions'=>Transaction::with('user')->withCount('details')->latest()->paginate(15)]);}
 public function show(Transaction $transaction){return Inertia::render('brewpos/transaction-show',['transaction'=>$transaction->load(['user','details'])]);}
 public function monthlyReport(Request $request): StreamedResponse{
  $month=$request->validate(['month'=>'required|date_format:Y-m'])['month'];
  $start=\Carbon\Carbon::createFromFormat('Y-m',$month)->startOfMonth();
  $end=$start->copy()->endOfMonth();
  $transactions=Transaction::with(['user','details'])->whereBetween('created_at',[$start,$end])->latest()->get();
  $filename='laporan-brewpos-'.$month.'.csv';
  return response()->streamDownload(function() use($transactions){
   $output=fopen('php://output','w');
   fprintf($output,"\xEF\xBB\xBF");
   fputcsv($output,['Invoice','Kasir','Tanggal','Metode Pembayaran','Subtotal','Total','Uang Diterima','Kembalian','Produk']);
   foreach($transactions as $transaction){
    $items=$transaction->details->map(fn($detail)=>$detail->product_name.' x'.$detail->quantity.' (@'.number_format($detail->price,0,'','.').')')->implode('; ');
    fputcsv($output,[
     $transaction->invoice_number,
     $transaction->user?->name,
     $transaction->created_at->format('d/m/Y H:i'),
     $transaction->payment_method,
     $transaction->subtotal,
     $transaction->total,
     $transaction->paid_amount,
     $transaction->change_amount,
     $items,
    ]);
   }
   fclose($output);
  },$filename,['Content-Type'=>'text/csv; charset=UTF-8']);
 }
}
