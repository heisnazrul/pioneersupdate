"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { useAuth } from "@/context/AuthContext";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
    faHome,
    faFileLines,
    faUser,
    faRightFromBracket,
    faUniversity,
    faHeart,
    faXmark
} from "@fortawesome/free-solid-svg-icons";

interface StudentSidebarProps {
    isOpen?: boolean;
    onClose?: () => void;
}

export default function StudentSidebar({ isOpen, onClose }: StudentSidebarProps) {
    const pathname = usePathname();

    const links = [
        { name: "Overview", path: "/student/dashboard", icon: faHome },
        { name: "Applications", path: "/student/dashboard/applications", icon: faFileLines },
        { name: "Wishlist", path: "/student/dashboard/wishlist", icon: faHeart },
        { name: "Scholarship", path: "/student/dashboard/scholarship-applications", icon: faFileLines },
        { name: "My Profile", path: "/student/dashboard/profile", icon: faUser },
    ];

    const { logout } = useAuth();
    const handleLogout = async () => {
        await logout();
    };

    return (
        <>
            <aside
                className={`
                    fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-100 flex-col transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:flex
                    ${isOpen ? "translate-x-0" : "-translate-x-full"}
                `}
            >
                <div className="p-4 border-b border-slate-50 flex items-center justify-between">
                    <Link href="/" className="flex items-center justify-center">
                        <img src="/logo.png" alt="Pioneers Application" className="h-12 w-auto" />
                    </Link>
                    {/* Mobile Close Button */}
                    <button
                        onClick={onClose}
                        className="md:hidden p-2 text-slate-400 hover:text-slate-600 focus:outline-none"
                    >
                        <FontAwesomeIcon icon={faXmark} className="w-6 h-6" />
                    </button>
                </div>

                <nav className="flex-1 p-4 space-y-1 overflow-y-auto">
                    {links.map((link) => {
                        const isActive = pathname === link.path;
                        return (
                            <Link
                                key={link.path}
                                href={link.path}
                                onClick={onClose} // Close sidebar on mobile when link clicked
                                className={`flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 font-medium ${isActive
                                    ? "bg-blue-50 text-[#1F63AE]"
                                    : "text-slate-600 hover:bg-slate-50 hover:text-slate-900"
                                    }`}
                            >
                                <FontAwesomeIcon icon={link.icon} className={`w-5 h-5 ${isActive ? "text-[#1F63AE]" : "text-slate-400"}`} />
                                {link.name}
                            </Link>
                        );
                    })}
                </nav>

                <div className="p-4 border-t border-slate-50">
                    <button
                        onClick={handleLogout}
                        className="flex w-full items-center gap-3 px-4 py-3 rounded-xl text-red-600 hover:bg-red-50 transition-all duration-200 font-medium"
                    >
                        <FontAwesomeIcon icon={faRightFromBracket} className="w-5 h-5" />
                        Sign Out
                    </button>
                </div>
            </aside>
        </>
    );
}
