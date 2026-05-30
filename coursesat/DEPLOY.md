# Deploy coursesat → courseenglish.com (Vercel)

Next.js 16 frontend for CourseSat / language institute booking.

## 1. New GitHub repository

From the monorepo, publish **only the `coursesat/` folder** as its own repo (or set Vercel root directory to `coursesat` if deploying from the monorepo).

```bash
cd coursesat
git init
git add .
git commit -m "Initial CourseSat frontend for Vercel"
git remote add origin git@github.com:YOUR_ORG/courseenglish-frontend.git
git push -u origin main
```

## 2. Vercel project

1. Import the GitHub repo in [Vercel](https://vercel.com)
2. **Root Directory:** `coursesat` (if deploying from monorepo) or repo root (if standalone)
3. Framework: **Next.js** (auto-detected)
4. Build command: `npm run build`
5. Install command: `npm install`

## 3. Environment variables (Production)

| Variable | Value |
|----------|--------|
| `BACKEND_ORIGIN` | `https://app.pioneersedu.com` |

Optional (only if **not** using same-origin rewrites):

| Variable | Value |
|----------|--------|
| `NEXT_PUBLIC_API_BASE_URL` | `https://app.pioneersedu.com/api` |
| `NEXT_PUBLIC_BACKEND_URL` | `https://app.pioneersedu.com` |

With rewrites (default in `vercel.json` + `next.config.mjs`), the browser calls `/api/*` on `courseenglish.com` and Vercel proxies to Laravel — **no CORS issues**.

## 4. Custom domain

In Vercel → Domains:

- `courseenglish.com`
- `www.courseenglish.com` (redirect to apex if desired)

## 5. Backend dependency

Ensure **Backendupdate** is live at `https://app.pioneersedu.com` before go-live. See `Backendupdate/DEPLOY.md`.

Required endpoints:

- `/api/coursesat/*`
- `/storage/*` (school logos, gallery images)

## 6. Local development

```bash
cp .env.example .env.local
# BACKEND_ORIGIN=http://127.0.0.1:8000

npm install
npm run dev
```

Runs at `http://localhost:3000` with API proxied to local Laravel on port 8000.

## 7. Verify production

- Home page loads institutes and hero search
- Institute detail pages show courses, accommodation, images
- Booking flow reaches OTP endpoints (not cached on backend)
- Images load from `/storage/...` (rewritten to backend)

## 8. Build locally (optional)

```bash
BACKEND_ORIGIN=https://app.pioneersedu.com npm run build
npm run start
```
