<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Support\Audit;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        return Inertia::render('brewpos/users', [
            'cashiers' => User::where('role', 'cashier')->latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::create($data + ['role' => 'cashier']);
        Audit::record('create', 'cashier', 'Kasir '.$user->name.' ditambahkan', $request->user(), $request, $user->id, ['email'=>$user->email]);

        return back()->with('success', 'Kasir berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        abort_if($user->role !== 'cashier', 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            ...(filled($data['password']) ? ['password' => $data['password']] : []),
        ]);
        Audit::record('update', 'cashier', 'Kasir '.$user->name.' diperbarui', $request->user(), $request, $user->id, ['email'=>$user->email,'password_changed'=>filled($data['password'])]);

        return back()->with('success', 'Kasir berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user)
    {
        abort_if($user->role !== 'cashier', 403);
        abort_if($request->user()->id === $user->id, 403);

        $name=$user->name;
        $user->delete();
        Audit::record('delete', 'cashier', 'Kasir '.$name.' dihapus', $request->user(), $request);

        return back()->with('success', 'Kasir berhasil dihapus.');
    }
}
