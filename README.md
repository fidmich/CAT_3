# Obituary Management Platform

A PHP + MySQL web application for submitting, managing, and displaying obituaries, built for the Internet Programming 2 CAT 3 assignment. Includes SEO and Social Media Optimization features to improve visibility and shareability of each obituary.

## Tech Stack
- PHP (plain, no framework) with PDO
- MySQL (via XAMPP)
- HTML5, CSS3, vanilla JavaScript

## Project Structure
```
Cat 3/
  obituary_form.php        Entry point - form for submitting a new obituary
  includes/
    db.php                  Shared PDO database connection + helper functions
  handlers/
    submit_obituary.php      Handles form POST, validates, inserts into DB
  pages/
    view_obituaries.php      Lists all obituaries in a paginated table
    obituary.php              Single obituary page with full SEO tags
    sitemap.php                Dynamically generated XML sitemap
  assets/
    style.css                 Shared styling
    validate.js                 Client-side form validation
  database/
    schema.sql                  Database + table creation script
  README.md
```

Start here: open `obituary_form.php` in your browser - everything else is reached by navigating from that page.

## Setup Instructions

1. **Install XAMPP** (if not already installed): https://www.apachefriends.org/
2. **Start Apache and MySQL** from the XAMPP Control Panel.
3. **Import the database:**
   - Open `http://localhost/phpmyadmin`
   - Go to the "Import" tab, choose `database/schema.sql`, and click "Go".
   - This creates the `obituary_platform` database and the `obituaries` table.
4. **Deploy the project files:**
   - Copy this entire project folder into your XAMPP `htdocs` directory, e.g. `C:\xampp\htdocs\obituary-platform`.
5. **Open the app in your browser:**
   - `http://localhost/obituary-platform/obituary_form.php`

If your MySQL `root` user has a password, update `$db_user` / `$db_pass` in `includes/db.php` accordingly.

## Using the Application

- **Submit an obituary:** go to `obituary_form.php`, fill in the Name, Date of Birth, Date of Death, Content, and Author fields. Client-side JavaScript validates the form (required fields, date logic, minimum content length) before it is submitted to `handlers/submit_obituary.php`, which re-validates on the server and inserts the record using a prepared statement.
- **View all obituaries:** `pages/view_obituaries.php` lists submissions in a paginated table (10 per page), newest first.
- **View a single obituary:** click any name in the table to go to `pages/obituary.php?slug=...`, which shows the full obituary with SEO/social meta tags.
- **Sitemap:** `pages/sitemap.php` generates a valid XML sitemap listing every obituary page plus the form/listing pages, ready to submit to search engines (e.g. Google Search Console).

## SEO & Social Media Optimization Features
- Dynamic per-page `<title>` and meta description/keywords generated from each obituary's content.
- `<link rel="canonical">` tags on every page to avoid duplicate-content issues.
- Open Graph (`og:title`, `og:description`, `og:url`, `og:type`) and Twitter Card meta tags for rich social previews.
- JSON-LD structured data (`schema.org/Person`) describing each obituary for search engines.
- Dynamically generated XML sitemap (`pages/sitemap.php`).

## Error Handling
- `includes/db.php` catches connection failures and shows a clear error message instead of a raw PHP error.
- `handlers/submit_obituary.php` validates all fields server-side (even though the form also validates client-side) and reports specific validation errors back to the user.
- Duplicate obituary slugs are automatically de-duplicated by appending a numeric suffix.
- `pages/obituary.php` returns a proper 404 page for unknown slugs.

## Testing Notes
- Tested with valid submissions, missing fields, invalid date ranges (death before birth, future dates), and very long content.
- Tested pagination by submitting more than 10 obituaries.
- Verified meta tags/Open Graph output via browser "View Page Source" on `pages/obituary.php`.
- Verified `pages/sitemap.php` produces valid XML.

## GitHub Repository
https://github.com/fidmich/CAT_3
