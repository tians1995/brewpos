import React, { useState } from 'react';
import { Head, router } from '@inertiajs/react';
import Layout from '../../layouts/brewpos-layout';

export default function Audit({ logs, filters, actions }: any) {
  const [search, setSearch] = useState(filters.search || '');
  const [action, setAction] = useState(filters.action || '');

  function applyFilter(event: React.FormEvent) {
    event.preventDefault();
    router.get('/audit', { search, action }, { preserveState: true });
  }

  function clearFilter() {
    setSearch('');
    setAction('');
    router.get('/audit');
  }

  const actionLabel: Record<string, string> = {
    login: 'Login',
    logout: 'Logout',
    create: 'Tambah',
    update: 'Ubah',
    delete: 'Hapus',
  };

  return <Layout title="Audit Trail">
    <Head title="Audit Trail" />

    <form onSubmit={applyFilter} className="mb-5 flex flex-col gap-3 rounded-2xl border border-[#eadfd3] bg-white p-4 sm:flex-row">
      <input
        value={search}
        onChange={(event) => setSearch(event.target.value)}
        placeholder="Cari pengguna atau aktivitas"
        className="min-w-0 flex-1 rounded-lg border border-stone-200 px-3 py-2 text-sm"
      />
      <select value={action} onChange={(event) => setAction(event.target.value)} className="rounded-lg border border-stone-200 px-3 py-2 text-sm">
        <option value="">Semua aksi</option>
        {actions.map((item: string) => <option key={item} value={item}>{actionLabel[item] || item}</option>)}
      </select>
      <button className="rounded-lg bg-[#38251c] px-4 py-2 text-sm font-semibold text-white">Filter</button>
      {(search || action) && <button type="button" onClick={clearFilter} className="rounded-lg border px-4 py-2 text-sm">Reset</button>}
    </form>

    <section className="overflow-hidden rounded-2xl border border-[#eadfd3] bg-white">
      <div className="overflow-x-auto">
        <table className="w-full text-left text-sm">
          <thead className="border-b text-stone-500">
            <tr>
              <th className="px-5 py-3">Waktu</th>
              <th>Pengguna</th>
              <th>Aksi</th>
              <th>Entity</th>
              <th>Aktivitas</th>
              <th>IP</th>
            </tr>
          </thead>
          <tbody>
            {logs.data.map((log: any) => <tr key={log.id} className="border-b last:border-0">
              <td className="whitespace-nowrap px-5 py-4 text-xs text-stone-500">
                {new Date(log.created_at).toLocaleString('id-ID')}
              </td>
              <td className="py-4">
                <p className="font-semibold">{log.user_name || 'Sistem'}</p>
                <p className="text-xs capitalize text-stone-400">{log.user_role || '-'}</p>
              </td>
              <td className="py-4"><span className="rounded-full bg-[#f2e8df] px-2 py-1 text-xs font-semibold text-[#704a32]">{actionLabel[log.action] || log.action}</span></td>
              <td className="py-4 capitalize">{log.entity}</td>
              <td className="max-w-md py-4">{log.description}</td>
              <td className="py-4 pr-5 text-xs text-stone-500">{log.ip_address || '-'}</td>
            </tr>)}
          </tbody>
        </table>
      </div>

      {!logs.data.length && <p className="py-10 text-center text-sm text-stone-400">Belum ada aktivitas audit.</p>}

      <div className="flex gap-2 border-t p-4">
        {logs.links.map((link: any, index: number) => <button
          key={index}
          disabled={!link.url}
          onClick={() => link.url && router.get(link.url)}
          className={`rounded-lg px-3 py-2 text-sm ${link.active ? 'bg-[#38251c] text-white' : 'bg-stone-100 disabled:opacity-40'}`}
          dangerouslySetInnerHTML={{ __html: link.label }}
        />)}
      </div>
    </section>
  </Layout>;
}
