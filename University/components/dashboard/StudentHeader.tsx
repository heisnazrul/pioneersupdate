"use client";

import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faBell, faSearch, faRightFromBracket, faBars } from "@fortawesome/free-solid-svg-icons";
import { useAuth } from "@/context/AuthContext";

interface StudentHeaderProps {
    onMenuClick?: () => void;
}

export default function StudentHeader({ onMenuClick }: StudentHeaderProps) {
    const { user, logout } = useAuth();

    const handleLogout = async () => {
        await logout();
    };

    return (
        <header className="bg-white border-b border-slate-100 h-16 md:h-20 px-4 md:px-8 flex items-center justify-between sticky top-0 z-10 transition-shadow">

            {/* Mobile Menu Toggle + Search/Title */}
            <div className="flex items-center gap-4">
                <button
                    onClick={onMenuClick}
                    className="md:hidden p-2 -ml-2 text-slate-500 hover:text-slate-700 focus:outline-none"
                >
                    <FontAwesomeIcon icon={faBars} className="w-5 h-5" />
                </button>

                <div className="relative hidden md:block group">
                    <FontAwesomeIcon icon={faSearch} className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-[#1F63AE] transition-colors" />
                    <input
                        type="text"
                        placeholder="Search..."
                        className="bg-slate-50 border border-slate-200 rounded-full pl-10 pr-4 py-2 w-64 text-sm focus:outline-none focus:border-[#1F63AE] focus:ring-2 focus:ring-[#1F63AE]/10 transition-all"
                    />
                </div>
            </div>

            {/* Right Side Actions */}
            <div className="flex items-center gap-4 md:gap-6">
                {/* Notifications */}
                <button className="relative w-8 h-8 md:w-10 md:h-10 rounded-full bg-slate-50 hover:bg-blue-50 text-slate-500 hover:text-[#1F63AE] flex items-center justify-center transition-all">
                    <FontAwesomeIcon icon={faBell} className="w-4 h-4 md:w-5 md:h-5" />
                    <span className="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                </button>

                {/* Divider */}
                <div className="h-6 md:h-8 w-px bg-slate-200 hidden md:block"></div>

                {/* Profile / Logout */}
                <div className="flex items-center gap-3 md:gap-4">
                    <div className="text-right hidden md:block">
                        <div className="text-sm font-bold text-slate-900">{user?.name || "Student"}</div>
                        <div className="text-xs text-slate-500 uppercase font-semibold tracking-wide">
                            {user?.role?.replace('_', ' ') || "Student"}
                        </div>
                    </div>

                    <button
                        onClick={handleLogout}
                        className="flex items-center gap-2 bg-red-50 hover:bg-red-100 text-red-600 px-3 py-1.5 md:px-4 md:py-2 rounded-full text-xs md:text-sm font-bold transition-colors"
                    >
                        <span className="hidden sm:inline">Logout</span>
                        <FontAwesomeIcon icon={faRightFromBracket} />
                    </button>
                </div>
            </div>
        </header>
    );
}
