"use client";

import { usePathname } from 'next/navigation';

export default function MainLayout({ children }: { children: React.ReactNode }) {
    const pathname = usePathname();
    const isHome = pathname === '/';

    return (
        <main className={`flex-grow ${isHome ? '' : 'pt-[80px]'}`}>
            {children}
        </main>
    );
}
