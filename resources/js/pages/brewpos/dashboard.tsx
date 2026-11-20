import React, { useState } from 'react';
import { Head } from '@inertiajs/react';
import Layout from '../../layouts/brewpos-layout';
import { ShoppingBag, Receipt, Package, AlertTriangle, Download } from 'lucide-react';

const money = (n: number) => new Intl.NumberFormat('id-ID', {
  style: 'currency',
  currency: 'IDR',
  maximumFractionDigits: 0,
}).format(n);

export default function Dashboard({ stats, recent, selectedMonth }: any) {
  const [month, setMonth] = useState(selectedMonth);
  const cards = [
    ['Penjualan Hari Ini', money(stats.sales), ShoppingBag],
    ['Transaksi Hari Ini', stats.transactions, Receipt],
    ['Total Produk', stats.products, Package],
    ['Stok Menipis', stats.lowStock, AlertTriangle],
  ];

  function downloadReport() {
    window.location.href = `/reports/monthly?month=${month}`;
  }

  return <Layout title="Dashboard">
    <Head title="Dashboard" />

    <div className="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
      <div>
        <p className="text-sm text-stone-500">Unduh laporan transaksi bulanan</p>
        <label className="mt-2 block text-sm font-semibold">
          Bulan
          <input
            type="month"
            value={month}
            onChange={(event) => setMonth(event.target.value)}
            className="ml-3 rounded-lg border border-stone-200 bg-white px-3 py-2"
          />
        </label>
      </div>
      <button
        onClick={downloadReport}
        className="inline-flex items-center justify-center gap-2 rounded-lg bg-[#38251c] px-4 py-3 text-sm font-semibold text-white"
      >
        <Download size={17} />Unduh Laporan CSV
      </button>
    </div>

    <div className="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
      {cards.map(([label, value, Icon]: any) => <div key={label} className="rounded-2xl border border-[#eadfd3] bg-white p-5">
        <div className="flex justify-between text-sm text-stone-500">
          <span>{label}</span>
          <Icon size={19} className="text-[#a36f48]" />
        </div>
        <p className="mt-4 text-2xl font-bold">{value}</p>
      </div>)}
    </div>

    <section className="mt-8 rounded-2xl border border-[#eadfd3] bg-white p-6">
      <h2 className="font-serif text-xl">Transaksi Terbaru</h2>
      <div className="mt-4 overflow-x-auto">
        <table className="w-full text-left text-sm">
          <thead className="text-stone-400">
            <tr><th className="py-3">Invoice</th><th>Kasir</th><th>Tanggal</th><th className="text-right">Total</th></tr>
          </thead>
          <tbody>
            {recent.map((transaction: any) => <tr key={transaction.id} className="border-t border-stone-100">
              <td className="py-3 font-semibold">{transaction.invoice_number}</td>
              <td>{transaction.user?.name}</td>
              <td>{new Date(transaction.created_at).toLocaleString('id-ID')}</td>
              <td className="text-right">{money(transaction.total)}</td>
            </tr>)}
          </tbody>
        </table>
        {recent.length === 0 && <p className="py-6 text-center text-stone-400">Belum ada transaksi.</p>}
      </div>
    </section>
  </Layout>;
}
