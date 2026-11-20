import React, { useState } from 'react';
import { Head, useForm, router } from '@inertiajs/react';
import Layout from '../../layouts/brewpos-layout';

export default function Users({ cashiers }: any) {
  const [editing, setEditing] = useState<any>(null);
  const form = useForm({ name: '', email: '', password: '' });

  function submit(event: React.FormEvent) {
    event.preventDefault();
    if (editing) {
      form.put(`/users/${editing.id}`, {
        onSuccess: () => {
          form.reset();
          setEditing(null);
        },
      });
    } else {
      form.post('/users', { onSuccess: () => form.reset() });
    }
  }

  function edit(cashier: any) {
    setEditing(cashier);
    form.setData({ name: cashier.name, email: cashier.email, password: '' });
  }

  function remove(cashier: any) {
    if (window.confirm(`Hapus kasir ${cashier.name}?`)) {
      router.delete(`/users/${cashier.id}`);
    }
  }

  return <Layout title="Manajemen Kasir">
    <Head title="Manajemen Kasir" />
    <div className="grid gap-6 xl:grid-cols-[350px_1fr]">
      <form onSubmit={submit} className="h-fit rounded-2xl bg-white p-5 shadow-sm">
        <h2 className="mb-4 font-semibold">{editing ? 'Edit Kasir' : 'Tambah Kasir'}</h2>
        <div className="space-y-3">
          <label className="block text-sm">Nama
            <input required className="mt-1 w-full rounded-lg border border-stone-200 p-2.5" value={form.data.name} onChange={e => form.setData('name', e.target.value)} />
            {form.errors.name && <p className="mt-1 text-xs text-red-600">{form.errors.name}</p>}
          </label>
          <label className="block text-sm">Email
            <input required type="email" className="mt-1 w-full rounded-lg border border-stone-200 p-2.5" value={form.data.email} onChange={e => form.setData('email', e.target.value)} />
            {form.errors.email && <p className="mt-1 text-xs text-red-600">{form.errors.email}</p>}
          </label>
          <label className="block text-sm">Password
            <input required={!editing} type="password" minLength={8} className="mt-1 w-full rounded-lg border border-stone-200 p-2.5" value={form.data.password} onChange={e => form.setData('password', e.target.value)} placeholder={editing ? 'Kosongkan jika tidak diubah' : 'Minimal 8 karakter'} />
            {form.errors.password && <p className="mt-1 text-xs text-red-600">{form.errors.password}</p>}
          </label>
        </div>
        <button disabled={form.processing} className="mt-4 w-full rounded-lg bg-[#38251c] p-3 font-semibold text-white disabled:opacity-40">
          {editing ? 'Simpan Perubahan' : 'Tambah Kasir'}
        </button>
        {editing && <button type="button" onClick={() => { setEditing(null); form.reset(); }} className="mt-2 w-full rounded-lg border p-2">Batal</button>}
      </form>

      <section className="rounded-2xl bg-white p-5 shadow-sm">
        <h2 className="mb-4 font-semibold">Daftar Kasir ({cashiers.length})</h2>
        <div className="overflow-x-auto">
          <table className="w-full text-left text-sm">
            <thead className="border-b text-stone-500">
              <tr><th className="py-3">Nama</th><th>Email</th><th>Peran</th><th className="text-right">Aksi</th></tr>
            </thead>
            <tbody>
              {cashiers.map((cashier: any) => <tr key={cashier.id} className="border-b last:border-0">
                <td className="py-3 font-medium">{cashier.name}</td>
                <td>{cashier.email}</td>
                <td><span className="rounded-full bg-[#f2e8df] px-2 py-1 text-xs text-[#704a32]">Kasir</span></td>
                <td className="text-right">
                  <button onClick={() => edit(cashier)} className="mr-3 text-[#a36f48]">Edit</button>
                  <button onClick={() => remove(cashier)} className="text-red-600">Hapus</button>
                </td>
              </tr>)}
              {!cashiers.length && <tr><td colSpan={4} className="py-8 text-center text-stone-400">Belum ada kasir.</td></tr>}
            </tbody>
          </table>
        </div>
      </section>
    </div>
  </Layout>;
}
