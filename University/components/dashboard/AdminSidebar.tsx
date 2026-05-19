"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
    faHome,
    faUsers,
    faFileLines,
    faCogs,
    faRightFromBracket,
    faExternalLinkAlt
} from "@fortawesome/free-solid-svg-icons";

export default function AdminSidebar() {
    const pathname = usePathname();
    const API_BASE = process.env.NEXT_PUBLIC_API_BASE_URL || 'http://127.0.0.1:8000'; // Base URL for backend links

    // Mix of internal Next.js links and external Laravel Admin links
    const links = [
        { name: "Overview", path: "/admin/dashboard", icon: faHome, external: false },
        { name: "Manage Applications", path: `${API_BASE.replace('/api', '')}/admin/applications`, icon: faFileLines, external: true },
        // { name: "Manage Users", path: `${API_BASE.replace('/api', '')}/admin/users`, icon: faUsers, external: true }, // Assuming this route exists or will exist
        { name: "System Settings", path: `${API_BASE.replace('/api', '')}/admin/settings`, icon: faCogs, external: true },
    ];

    const handleLogout = () => {
        localStorage.removeItem("auth_token");
        localStorage.removeItem("auth_user");
        window.dispatchEvent(new Event('auth-update'));
        window.location.href = "/"; // Force refresh to home
    };

    return (
        <aside className="w-64 bg-slate-900 text-white flex-shrink-0 flex flex-col min-h-screen">
            <div className="p-6 border-b border-slate-800">
                <span className="text-xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-400">
                    Admin Portal
                </span>
            </div>

            <nav className="flex-1 p-4 space-y-2">
                {links.map((link) => {
                    const isActive = !link.external && pathname === link.path;
                    return link.external ? (
                        <a
                            key={link.path}
                            href={link.path}
                            target="_blank"
                            rel="noopener noreferrer"
                            className="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 font-medium text-slate-400 hover:bg-slate-800 hover:text-white group"
                        >
                            <FontAwesomeIcon icon={link.icon} className="w-5 h-5 text-slate-500 group-hover:text-blue-400" />
                            <span className="flex-1">{link.name}</span>
                            <FontAwesomeIcon icon={faExternalLinkAlt} className="w-3 h-3 opacity-50" />
                        </a>
                    ) : (
                        <Link
                            key={link.path}
                            href={link.path}
                            className={`flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 font-medium ${isActive
                                ? "bg-blue-600 text-white shadow-lg shadow-blue-500/20"
                                : "text-slate-400 hover:bg-slate-800 hover:text-white"
                                }`}
                        >
                            <FontAwesomeIcon icon={link.icon} className={`w-5 h-5 ${isActive ? "text-white" : "text-slate-500"}`} />
                            {link.name}
                        </Link>
                    );
                })}
            </nav>

            <div className="p-4 border-t border-slate-800">
                <button
                    onClick={handleLogout}
                    className="flex w-full items-center gap-3 px-4 py-3 rounded-xl text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-all duration-200 font-medium"
                >
                    <FontAwesomeIcon icon={faRightFromBracket} className="w-5 h-5" />
                    Sign Out
                </button>
            </div>
        </aside>
    );
}
