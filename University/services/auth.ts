import { AuthResponse, LoginPayload, RegisterPayload, User } from '@/lib/data/types';

const API_URL = process.env.NEXT_PUBLIC_BACKEND_URL || 'https://backend.pioneersedu.com';
const AUTH_BASE = `${API_URL}/api/university/auth`;
const AUTH_TOKEN_KEY = 'auth_token';
const AUTH_TOKEN_TYPE_KEY = 'auth_token_type';
const AUTH_USER_KEY = 'auth_user';
const LEGACY_TOKEN_KEY = 'token';
const LEGACY_USER_KEY = 'user';

export const UNIVERSITY_ALLOWED_ROLES = ['uni_student', 'uni_agent'] as const;

export function isUniversityRole(role?: string | null): boolean {
    return !!role && UNIVERSITY_ALLOWED_ROLES.includes(role as (typeof UNIVERSITY_ALLOWED_ROLES)[number]);
}

export function getStoredToken(): string | null {
    if (typeof window === 'undefined') return null;
    return localStorage.getItem(AUTH_TOKEN_KEY) || localStorage.getItem(LEGACY_TOKEN_KEY);
}

export function getStoredTokenType(): string {
    if (typeof window === 'undefined') return 'Bearer';
    return localStorage.getItem(AUTH_TOKEN_TYPE_KEY) || 'Bearer';
}

export function getStoredUser(): User | null {
    if (typeof window === 'undefined') return null;

    const raw = localStorage.getItem(AUTH_USER_KEY) || localStorage.getItem(LEGACY_USER_KEY);

    if (!raw) return null;

    try {
        return JSON.parse(raw) as User;
    } catch {
        return null;
    }
}

export function storeAuthSession(payload: AuthResponse): void {
    if (typeof window === 'undefined' || !payload?.token || !payload?.user) return;

    const user = {
        ...payload.user,
        roles: payload.roles ?? payload.user.roles,
        access: payload.access ?? payload.user.access,
    };

    localStorage.setItem(AUTH_TOKEN_KEY, payload.token);
    localStorage.setItem(AUTH_TOKEN_TYPE_KEY, payload.tokenType || 'Bearer');
    localStorage.setItem(AUTH_USER_KEY, JSON.stringify(user));

    localStorage.setItem(LEGACY_TOKEN_KEY, payload.token);
    localStorage.setItem(LEGACY_USER_KEY, JSON.stringify(user));

    window.dispatchEvent(new Event('auth-update'));
}

export function clearAuthSession(): void {
    if (typeof window === 'undefined') return;

    localStorage.removeItem(AUTH_TOKEN_KEY);
    localStorage.removeItem(AUTH_TOKEN_TYPE_KEY);
    localStorage.removeItem(AUTH_USER_KEY);
    localStorage.removeItem(LEGACY_TOKEN_KEY);
    localStorage.removeItem(LEGACY_USER_KEY);

    window.dispatchEvent(new Event('auth-update'));
}

export const authService = {
    async login(credentials: LoginPayload): Promise<AuthResponse> {
        const res = await fetch(`${AUTH_BASE}/login`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ ...credentials, app: 'university' }),
        });
        const data = await res.json();
        if (!res.ok) throw new Error(data.message || 'Login failed');
        return {
            token: data.access_token,
            tokenType: data.token_type || 'Bearer',
            user: data.user,
            message: data.message,
            roles: data.roles,
            access: data.access,
            app: data.app,
        };
    },

    async register(payload: RegisterPayload): Promise<AuthResponse> {
        const res = await fetch(`${AUTH_BASE}/register`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ ...payload, app: 'university' }),
        });
        const data = await res.json();
        if (!res.ok) throw new Error(data.message || 'Registration failed');
        return {
            token: data.access_token,
            tokenType: data.token_type || 'Bearer',
            user: data.user,
            message: data.message,
            roles: data.roles,
            access: data.access,
            app: data.app,
        };
    },

    async logout(token: string): Promise<void> {
        await fetch(`${AUTH_BASE}/logout`, {
            method: 'POST',
            headers: {
                Authorization: `Bearer ${token}`,
                Accept: 'application/json',
            },
        });
    },

    async getUser(token: string): Promise<User> {
        const res = await fetch(`${AUTH_BASE}/me`, {
            headers: {
                Authorization: `Bearer ${token}`,
                Accept: 'application/json',
            },
            cache: 'no-store',
        });
        if (!res.ok) throw new Error('Failed to fetch user');
        const data = await res.json();
        return {
            ...data.user,
            roles: data.roles,
            access: data.access,
        };
    },

    async sendWhatsappOtp(phone: string): Promise<{ message: string }> {
        void phone;
        throw new Error('WhatsApp OTP login is not enabled yet.');
    },

    async verifyWhatsappOtp(phone: string, otp: string): Promise<AuthResponse> {
        void phone;
        void otp;
        throw new Error('WhatsApp OTP login is not enabled yet.');
    },
};
