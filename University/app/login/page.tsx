"use client";

import Link from "next/link";
import { useEffect, useState } from "react";
import AuthShell from "@/components/AuthShell";
import { useAuth } from "@/context/AuthContext";

export default function LoginPage() {
    const { login, isLoading, isAuthenticated, user, handleRedirect, logout } = useAuth();
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const [localLoading, setLocalLoading] = useState(false);
    const [error, setError] = useState("");

    useEffect(() => {
        setError("");
    }, []);

    const handlePasswordLogin = async (event: React.FormEvent) => {
        event.preventDefault();
        setError("");

        if (!email.trim() || !password) {
            setError("Enter email and password.");
            return;
        }

        try {
            setLocalLoading(true);
            await login({ email, password });
        } catch (err) {
            setError((err as Error).message || "Login failed.");
        } finally {
            setLocalLoading(false);
        }
    };

    const loadingState = localLoading || isLoading;

    if (isAuthenticated && user) {
        return (
            <AuthShell title="Welcome back" subtitle="You are already logged in.">
                <div className="mt-8 flex flex-col gap-4">
                    <div className="bg-blue-50 text-blue-800 p-4 rounded-xl text-center">
                        <p className="font-semibold">Logged in as {user.name || user.email}</p>
                    </div>
                    <button
                        onClick={() => handleRedirect(user)}
                        className="w-full rounded-full bg-[#1F63AE] py-3 text-sm font-semibold text-white shadow-md transition hover:bg-[#175093]"
                    >
                        Go to Dashboard
                    </button>
                    <button
                        onClick={() => {
                            logout();
                        }}
                        className="text-xs text-slate-500 text-center"
                    >
                        Want to switch accounts? Log out here.
                    </button>
                </div>
            </AuthShell>
        );
    }

    return (
        <AuthShell
            title="Welcome back"
            subtitle="Sign in to access your University dashboard."
        >
            <form className="mt-6 space-y-4" onSubmit={handlePasswordLogin}>
                <div>
                    <label className="text-sm font-semibold text-slate-700">Email</label>
                    <input
                        type="email"
                        name="email"
                        placeholder="you@email.com"
                        value={email}
                        onChange={(event) => setEmail(event.target.value)}
                        className="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-[#1F63AE] focus:ring-2 focus:ring-[#1F63AE]/20"
                    />
                </div>
                <div>
                    <label className="text-sm font-semibold text-slate-700">
                        Password
                    </label>
                    <input
                        type="password"
                        name="password"
                        placeholder="••••••••"
                        value={password}
                        onChange={(event) => setPassword(event.target.value)}
                        className="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-[#1F63AE] focus:ring-2 focus:ring-[#1F63AE]/20"
                    />
                </div>
                <div className="rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-500">
                    WhatsApp OTP and Google login will be added in a later auth phase.
                </div>
                {error ? (
                    <div className="rounded-2xl border border-rose-200 bg-rose-50 px-3 py-2 text-sm text-rose-600">
                        {error}
                    </div>
                ) : null}
                <button
                    type="submit"
                    disabled={loadingState}
                    className="mt-2 w-full rounded-full bg-[#1F63AE] py-3 text-sm font-semibold text-white shadow-md transition hover:bg-[#175093] disabled:cursor-not-allowed disabled:opacity-70"
                >
                    {loadingState ? "Signing in..." : "Sign in"}
                </button>
            </form>

            <div className="mt-6 text-center text-sm text-slate-600">
                Don&apos;t have an account?{" "}
                <Link href="/signup" className="font-semibold text-[#1F63AE]">
                    Create one
                </Link>
            </div>
            <div className="mt-2 text-center text-sm text-slate-600">
                <Link href="/forget" className="font-semibold text-[#1F63AE]">
                    Forgot password?
                </Link>
            </div>
        </AuthShell>
    );
}
