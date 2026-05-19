"use client";

import React, { createContext, useContext, useEffect, useState, ReactNode } from 'react';
import { LoginPayload, RegisterPayload, User } from '@/lib/data/types';
import { authService, clearAuthSession, getStoredToken, isUniversityRole, storeAuthSession } from '@/services/auth';
import { useRouter } from 'next/navigation';

interface AuthContextType {
    user: User | null;
    token: string | null;
    isAuthenticated: boolean;
    isLoading: boolean;
    login: (credentials: LoginPayload) => Promise<void>;
    register: (data: RegisterPayload) => Promise<void>;
    logout: () => Promise<void>;
    whatsappLogin: (phone: string, otp: string) => Promise<void>;
    handleRedirect: (user: User) => void;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export function AuthProvider({ children }: { children: ReactNode }) {
    const [user, setUser] = useState<User | null>(null);
    const [token, setToken] = useState<string | null>(null);
    const [isLoading, setIsLoading] = useState(true);
    const router = useRouter();

    useEffect(() => {
        const storedToken = getStoredToken();

        if (!storedToken) {
            setIsLoading(false);
            return;
        }

        setToken(storedToken);

        authService.getUser(storedToken)
            .then((userData) => {
                if (!isUniversityRole(userData.role)) {
                    throw new Error('This account cannot access the University app.');
                }

                setUser(userData);
                storeAuthSession({ token: storedToken, tokenType: 'Bearer', user: userData });
            })
            .catch(() => {
                clearAuthSession();
                setToken(null);
                setUser(null);
            })
            .finally(() => {
                setIsLoading(false);
            });
    }, []);

    const handleRedirect = (authUser: User) => {
        if (authUser.role === 'uni_agent') {
            router.push('/agent/dashboard');
            return;
        }

        if (authUser.role === 'uni_student') {
            router.push('/student/dashboard');
            return;
        }

        clearAuthSession();
        setToken(null);
        setUser(null);
        router.push('/login');
    };

    const login = async (credentials: LoginPayload) => {
        setIsLoading(true);

        try {
            const response = await authService.login(credentials);

            if (response.token && response.user) {
                if (!isUniversityRole(response.user.role)) {
                    throw new Error('This account cannot access the University app.');
                }

                setToken(response.token);
                setUser(response.user);
                storeAuthSession(response);
                handleRedirect(response.user);
            }
        } finally {
            setIsLoading(false);
        }
    };

    const register = async (data: RegisterPayload) => {
        setIsLoading(true);

        try {
            const response = await authService.register(data);

            if (response.token && response.user) {
                if (!isUniversityRole(response.user.role)) {
                    throw new Error('This account cannot access the University app.');
                }

                setToken(response.token);
                setUser(response.user);
                storeAuthSession(response);
                handleRedirect(response.user);
            }
        } finally {
            setIsLoading(false);
        }
    };

    const whatsappLogin = async (phone: string, otp: string) => {
        setIsLoading(true);

        try {
            const response = await authService.verifyWhatsappOtp(phone, otp);

            if (response.token && response.user) {
                setToken(response.token);
                setUser(response.user);
                storeAuthSession(response);
                handleRedirect(response.user);
            }
        } finally {
            setIsLoading(false);
        }
    };

    const logout = async () => {
        try {
            if (token) {
                await authService.logout(token);
            }
        } catch (error) {
            console.error('Logout error', error);
        } finally {
            setToken(null);
            setUser(null);
            clearAuthSession();
            router.push('/login');
        }
    };

    return (
        <AuthContext.Provider value={{
            user,
            token,
            isAuthenticated: !!user,
            isLoading,
            login,
            register,
            logout,
            whatsappLogin,
            handleRedirect,
        }}>
            {children}
        </AuthContext.Provider>
    );
}

export function useAuth() {
    const context = useContext(AuthContext);

    if (context === undefined) {
        throw new Error('useAuth must be used within an AuthProvider');
    }

    return context;
}
