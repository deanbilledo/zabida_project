# ZABIDA website

Zamboanga&ndash;Basilan Integrated Development Alliance, Inc. &mdash; consortium
site with a public front end and a lightweight admin panel for the journal.

## Requirements
- PHP 8.0+
- MySQL/MariaDB (optional — the site works without one, see below)

## Setup
1. Point your web server's document root at this folder (or run locally with
   `php -S localhost:8000` from this directory).
2. Drop the NGO/consortium logo files into `assets/images/` — see
   `assets/images/README.txt` for the exact filenames the templates expect.
3. **Database (optional):** import `database/zabida.sql` into MySQL, then set
   these environment variables: `ZABIDA_DB_HOST`, `ZABIDA_DB_NAME`,
   `ZABIDA_DB_USER`, `ZABIDA_DB_PASS`. If no database is reachable, the site
   automatically falls back to the flat-file store at `database/posts.json`
   — nothing breaks, journal posts are just stored as JSON instead.
4. **Admin login:** default is username `admin`, password `changeme`
   (bcrypt hash lives in `config/auth.php` / `database/zabida.sql`). Replace
   `ADMIN_PASS_HASH` with a fresh `password_hash()` output before deploying.
5. **Facebook sync (optional):** set `ZABIDA_FB_PAGE_TOKEN` (a Page Access
   Token for facebook.com/zabida.org) to enable `/admin/sync-facebook.php`
   and the cron job at `scheduler/facebook-sync.php`.

## Structure
- `index.php`, `activities.php`, `contact.php`, `post.php` — public pages
- `admin/` — login-gated dashboard for managing journal posts
- `api/` — JSON endpoints backing the admin panel and Facebook sync
- `includes/` — shared header/navbar/footer/helpers
- `config/` — database, auth, and Facebook API configuration
- `database/` — SQL schema + the flat-file JSON fallback store
- `scheduler/` — cron entry point for the Facebook sync
- `uploads/` — user-uploaded post images and NGO assets
- `assets/` — CSS, JS, and image files

## Content source
Copy and figures (leaders, programs, contact details, core values) were
sourced from the official ZABIDA brochure.
