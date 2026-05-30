# Deploy Backendupdate → app.pioneersedu.com (Namecheap shared hosting)

Laravel API + admin/counsellor/team panels.

## Requirements

- PHP 8.3+ with extensions: `mbstring`, `openssl`, `pdo_mysql`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`, `gd` or `imagick`
- Composer 2.x
- MySQL database (already created in cPanel)

## 1. Upload code

Upload the **Backendupdate** folder contents to the hosting account (e.g. `~/app.pioneersedu.com/` or subdomain root).

**Document root must point to `public/`** (not the Laravel root).

If you cannot change docroot, use the standard cPanel trick: move `public/*` to web root and edit `index.php` paths to point to the Laravel folder above.

## 2. Create `.env` on the server

Copy `.env.example` to `.env` on the server and set:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://app.pioneersedu.com
FRONTEND_URL=https://courseenglish.com
APP_KEY=base64:...   # from php artisan key:generate

DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=pionueoc_backend
DB_USERNAME=pionueoc_backend
DB_PASSWORD=YOUR_DB_PASSWORD   # set in cPanel only — never commit

CACHE_STORE=database
API_RESPONSE_CACHE_ENABLED=true
API_RESPONSE_CACHE_STORE=api_responses
```

Generate `APP_KEY`:

```bash
php artisan key:generate
```

## 3. Install & migrate (SSH or cPanel Terminal)

```bash
cd /path/to/Backendupdate
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**Namecheap / older MySQL:** If migrate fails with `Specified key was too long; max key length is 1000 bytes`, ensure `app/Providers/AppServiceProvider.php` includes `Schema::defaultStringLength(191);` in `boot()` (already in this repo), then run migrate again.

Use `php artisan migrate:fresh --force` only on a **new empty** database (it deletes all tables).

## 4. Permissions

Ensure these are writable by the web server user:

```bash
chmod -R 775 storage bootstrap/cache
```

Create API cache directory (auto-created on first request, or pre-create):

```bash
mkdir -p storage/framework/cache/api_responses
chmod -R 775 storage/framework/cache/api_responses
```

## 5. API response cache

Catalog GET endpoints (schools, courses, institutes, articles, home pages, etc.) are cached **on disk** for **30 days** under `storage/framework/cache/api_responses/`. The `api_responses` store is file-based — it does not use the database cache table.

Set in `.env`:

```env
CACHE_STORE=file
API_RESPONSE_CACHE_ENABLED=true
API_RESPONSE_CACHE_STORE=api_responses
API_RESPONSE_CACHE_TTL=2592000
```

**Not cached:** auth, booking/OTP, student/agent account, wishlist, referrals track-click, interactions.

After updating schools/courses/branding in admin, flush so the next request hits the DB once:

```bash
php artisan cache:clear-api
```

Or use **Flush Cache** in the admin header.

Response headers include `X-Cache: HIT` or `MISS` and `X-Cache-TTL` (seconds) for debugging.

## 6. CORS

Frontend at **https://courseenglish.com** calls this API. Origins are configured in `config/cors.php` via `CORS_ALLOWED_ORIGINS`.

If using Vercel rewrites (browser hits `courseenglish.com/api/*`), CORS is not required for those paths.

## 7. Cron (optional)

```cron
* * * * * cd /path/to/Backendupdate && php artisan schedule:run >> /dev/null 2>&1
```

## 8. Verify

- `https://app.pioneersedu.com/up` → health OK
- `https://app.pioneersedu.com/api/coursesat/home` → JSON + `X-Cache` header
- `https://app.pioneersedu.com/storage/blog_images/learning-tips.jpg` → image (not 403/404)
- Admin: `https://app.pioneersedu.com/login`

### Blog / storage images return 403

The seeder references files under `storage/app/public/blog_images/`. If those files are missing, `/storage/...` returns **403 Forbidden**.

**Fix (SSH or cPanel Terminal):**

```bash
cd /path/to/Backendupdate
php artisan storage:link
php artisan db:seed --class=BlogSeeder
php artisan cache:clear-api
```

**Or upload manually:** unzip `database/seeders/assets/blog_images.zip` into `storage/app/public/blog_images/` (create the folder if needed), then run `php artisan storage:link` and `php artisan cache:clear-api`.

On shared hosting, if `storage:link` fails (symlinks disabled), copy contents of `storage/app/public/` into `public/storage/` instead.

## 9. Security checklist

- `APP_DEBUG=false` in production
- Do not commit `.env` or database passwords to Git
- Use HTTPS only
- Restrict `storage/` and `.env` from direct web access (default Laravel `public/` layout handles this)
