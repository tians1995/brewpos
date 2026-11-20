<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Transaction;
use Inertia\Inertia;
class DashboardController extends Controller {
 public function __invoke(){
  return Inertia::render('brewpos/dashboard',[
   'stats'=>['sales'=>Transaction::whereDate('created_at',today())->sum('total'),'transactions'=>Transaction::whereDate('created_at',today())->count(),'products'=>Product::count(),'lowStock'=>Product::where('stock','<=',5)->count()],
   'recent'=>Transaction::with('user')->latest()->take(6)->get(),
   'selectedMonth'=>now()->format('Y-m')
  ]);
 }
}
