const STORAGE_KEY = "coursesat_referral";

export function getStoredReferral() {
  if (typeof window === "undefined") return null;
  try {
    const raw = localStorage.getItem(STORAGE_KEY);
    if (!raw) return null;
    const parsed = JSON.parse(raw);
    if (!parsed?.code) return null;
    if (parsed.expires_at && Date.now() > Number(parsed.expires_at)) {
      localStorage.removeItem(STORAGE_KEY);
      return null;
    }
    return parsed;
  } catch {
    return null;
  }
}

export function storeReferral(payload) {
  if (typeof window === "undefined" || !payload?.code) return;
  const ttlDays = Number(payload.cookie_ttl_days) || 30;
  const entry = {
    code: payload.code || payload.referral_code,
    referrer_type: payload.referrer_type,
    referrer_name: payload.referrer_name,
    discount_percent: payload.discount_percent,
    expires_at: Date.now() + ttlDays * 24 * 60 * 60 * 1000,
  };
  localStorage.setItem(STORAGE_KEY, JSON.stringify(entry));
}

export function getReferralCodeForRequest() {
  return getStoredReferral()?.code || null;
}

export async function resolveReferralCode(code) {
  const trimmed = String(code || "").trim();
  if (!trimmed) {
    throw new Error("Referral code is required.");
  }

  const response = await fetch(
    `/api/courseenglish/referrals/resolve?code=${encodeURIComponent(trimmed)}`,
    { headers: { Accept: "application/json" } },
  );
  const json = await response.json().catch(() => ({}));

  if (!response.ok || json?.success === false) {
    throw new Error(json?.message || "Invalid referral code.");
  }

  return json.data;
}

export async function applyReferralCode(code) {
  const data = await resolveReferralCode(code);
  storeReferral({
    code: data.referral_code,
    referrer_type: data.referrer_type,
    referrer_name: data.referrer_name,
    discount_percent: data.discount_percent,
    cookie_ttl_days: 30,
  });
  return data;
}

export function clearStoredReferral() {
  if (typeof window === "undefined") return;
  localStorage.removeItem(STORAGE_KEY);
}

export async function captureReferralFromUrl(searchParams) {
  const ref = searchParams?.get?.("ref") || searchParams?.get?.("referral");
  if (!ref || typeof window === "undefined") return null;

  try {
    const response = await fetch(`/api/courseenglish/referrals/track-click`, {
      method: "POST",
      headers: { Accept: "application/json", "Content-Type": "application/json" },
      body: JSON.stringify({ code: ref, landing_path: window.location.pathname }),
    });
    if (!response.ok) return null;
    const json = await response.json();
    const data = json?.data;
    if (data) {
      storeReferral({
        code: data.referral_code || ref,
        cookie_ttl_days: data.cookie_ttl_days,
        referrer_type: data.referrer_type,
        referrer_name: data.referrer_name,
        discount_percent: data.discount_percent,
      });
    }
    return data;
  } catch {
    storeReferral({ code: ref.toUpperCase(), cookie_ttl_days: 30 });
    return { referral_code: ref };
  }
}
