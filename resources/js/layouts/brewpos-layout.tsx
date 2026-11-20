import React from 'react';
import { Link, usePage, router } from '@inertiajs/react';
import { Coffee, LayoutDashboard, ShoppingCart, Package, Receipt, LogOut, Users, ScrollText } from 'lucide-react';

export default function Layout({ children, title }: { children: React.ReactNode, title: string }) {
  const page = usePage<any>();
  const user = page.props.auth?.user;
  const isAdmin = user?.role === 'admin';
  const links = isAdmin
    ? [
        ['/dashboard', 'Dashboard', LayoutDashboard],
        ['/products', 'Produk', Package],
        ['/users', 'Manajemen Kasir', Users],
        ['/transactions', 'Transaksi', Receipt],
        ['/audit', 'Audit Trail', ScrollText],
      ]
    : [
        ['/pos', 'Kasir / POS', ShoppingCart],
        ['/transactions', 'Riwayat Transaksi', Receipt],
      ];

  return <div className="min-h-screen md:flex">
    <aside className="w-full bg-[#38251c] p-5 text-[#f8ead9] md:min-h-screen md:w-64">
      <div className="mb-10 flex items-center gap-3">
        <div className="rounded-xl bg-[#c58b5b] p-2"><Coffee /></div>
        <div>
          <b className="tracking-[.2em]">BREWPOS</b>
          <p className="text-xs text-[#c7ad99]">{isAdmin ? 'Admin workspace' : 'Kasir workspace'}</p>
        </div>
      </div>
      <nav className="space-y-2">
        {links.map(([href, label, Icon]: any) => <Link key={href} href={href} className={`flex items-center gap-3 rounded-xl px-4 py-3 text-sm ${location.pathname === href ? 'bg-[#684833] text-white' : 'text-[#ddcbbb] hover:bg-[#52382a]'}`}>
          <Icon size={18} />{label}
        </Link>)}
      </nav>
      <div className="mt-10 border-t border-[#684833] pt-5">
        <p className="font-semibold">{user?.name}</p>
        <p className="text-xs capitalize text-[#c7ad99]">{isAdmin ? 'Administrator' : 'Kasir'}</p>
        <button onClick={() => router.post('/logout')} className="mt-5 flex items-center gap-2 text-sm text-[#ddcbbb]">
          <LogOut size={16} />Keluar
        </button>
      </div>
    </aside>
    <main className="min-w-0 flex-1 p-5 md:p-9">
      <header className="mb-8">
        <p className="text-xs font-bold tracking-[.2em] text-[#a36f48]">BREWPOS WORKSPACE</p>
        <h1 className="mt-2 font-serif text-3xl">{title}</h1>
      </header>
      {children}
    </main>
  </div>;
}
