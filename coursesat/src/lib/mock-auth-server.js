import authUsers from "@/mocdata/auth-users.json";

export function getBearerToken(request) {
  const auth = request.headers.get("authorization") || "";
  if (!auth.toLowerCase().startsWith("bearer ")) return null;
  return auth.slice(7).trim();
}

export function sanitizeUser(user) {
  if (!user) return null;
  const { password, token, ...rest } = user;
  return rest;
}

export function authenticateUser(email, password) {
  const normalized = String(email || "").trim().toLowerCase();
  const user = authUsers.find(
    (entry) => entry.email.toLowerCase() === normalized && entry.password === password
  );
  return user || null;
}

export function findUserByToken(token) {
  if (!token) return null;
  return authUsers.find((entry) => entry.token === token) || null;
}

export function requireAuthUser(request) {
  const token = getBearerToken(request);
  const user = findUserByToken(token);
  if (!user) return null;
  return user;
}

export function authSuccessPayload(user) {
  const safeUser = sanitizeUser(user);
  return {
    success: true,
    access_token: user.token,
    token_type: "Bearer",
    user: safeUser,
    roles: [user.role],
    app: "courseenglish",
    access: {
      courseenglish: true,
      university: false,
    },
  };
}

export function mePayload(user) {
  return {
    success: true,
    token_type: "Bearer",
    user: sanitizeUser(user),
    roles: [user.role],
    app: "courseenglish",
    access: {
      courseenglish: true,
      university: false,
    },
  };
}

export function studentProfilePayload(user) {
  return {
    success: true,
    data: sanitizeUser(user),
  };
}
