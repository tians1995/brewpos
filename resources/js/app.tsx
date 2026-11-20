import '../css/app.css';
import React from 'react';
import { createRoot, hydrateRoot } from 'react-dom/client';
import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
createInertiaApp({
 title: title => `${title} | BREWPOS`,
 resolve: name => resolvePageComponent(`./pages/${name}.tsx`, import.meta.glob('./pages/**/*.tsx')),
 setup({el,App,props}) { if (import.meta.env.SSR) return; createRoot(el).render(<App {...props}/>); },
});
