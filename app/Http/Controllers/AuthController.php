<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Support\Audit;
use Inertia\Inertia;
class AuthController extends Controller {
 public function create(){ return Inertia::render('auth/login'); }
 public function store(Request $request){
  $credentials=$request->validate(['email'=>['required','email'],'password'=>['required']]);
  if(!Auth::attempt($credentials,$request->boolean('remember'))) return back()->withErrors(['email'=>'Email atau password salah.'])->onlyInput('email');
  $request->session()->regenerate();
  Audit::record('login', 'session', 'Pengguna berhasil login', Auth::user(), $request);
  return redirect()->intended(Auth::user()->role === 'cashier' ? '/pos' : '/dashboard');
 }
 public function destroy(Request $request){ $user=Auth::user(); Audit::record('logout', 'session', 'Pengguna logout', $user, $request); Auth::logout(); $request->session()->invalidate(); $request->session()->regenerateToken(); return redirect('/login'); }
}
