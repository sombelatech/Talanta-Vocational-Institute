Hosting and local preview
=========================

Options to run the site locally or deploy:

1) Quick static preview (no PHP required)

   - Serve the site from the `live-site` folder with Python:

```powershell
cd "d:\Personal\MASHULLUE\NewVersion\talanta-site-full-update\live-site"
python -m http.server 8001
# Open http://localhost:8001
```

   - This serves all `.html`, CSS and JS. Dynamic admin pages under `admin/` will not work.

2) Run full site (PHP + admin) using Docker Compose (recommended)

   - Requires Docker Desktop. From the `live-site` folder run:

```powershell
cd "d:\Personal\MASHULLUE\NewVersion\talanta-site-full-update\live-site"
docker compose up -d
# Open http://localhost:8000
```

   - The `docker-compose.yml` uses the `php:8.1-apache` image and mounts the current folder into the container so PHP pages (e.g. `index.php`, `news.php`, admin) are executed.

3) Native Windows PHP (alternative)

   - Install PHP or XAMPP and point the web root to this folder. For simple built-in PHP server (if PHP is in PATH):

```powershell
cd "d:\Personal\MASHULLUE\NewVersion\talanta-site-full-update\live-site"
php -S 0.0.0.0:8000
# Open http://localhost:8000
```

Notes
- Public pages have static `.html` copies ready for static hosting (`index.html`, `news.html`, `contacts.html`).
- Admin area (`admin/`) still requires PHP for editing content.
- If you want, I can:
  - Prepare a small deployment guide for Netlify/Vercel (static) or DigitalOcean/AWS (PHP), or
  - Create a GitHub Actions workflow to deploy the static site automatically.

NetPOA hosting (static upload)
--------------------------------
If you're using NetPOA for hosting, they typically accept a static site upload (ZIP or file manager) or Git deployment. General steps:

1. Create a ZIP of the `live-site` folder (include all files and subfolders).
2. Log into your NetPOA control panel and find the site/file manager or deployment area.
3. Upload and extract the ZIP into the site's web root (or use their file manager to upload files directly).
4. Ensure the web root has `index.html` and set the default document to `index.html` if required.
5. Set the 404 page to `/404.html` if NetPOA supports a custom error page.
6. If NetPOA supports redirects or clean URLs, configure accordingly. No build step is required for this static site.

If NetPOA requires SFTP/FTP upload, use an FTP client (FileZilla) with credentials from their dashboard and upload the contents of the `live-site` folder to the remote web root.

Want me to package the site into a ZIP here and provide it? I can create the ZIP in the workspace so you can download and upload to NetPOA.

Quick preview & packaging scripts (Windows)
-----------------------------------------
I added two helper scripts in the `live-site` folder:

- `start_preview.bat` — starts a local static server on port 8001 (requires Python 3 in PATH) and opens your browser.
- `create_zip.ps1` — packages the entire `live-site` folder into `live-site.zip` for upload.

Usage examples:

```powershell
cd "d:\Personal\MASHULLUE\NewVersion\talanta-site-full-update\live-site"
.\start_preview.bat

# When ready to upload to NetPOA:
powershell -ExecutionPolicy Bypass -File .\create_zip.ps1
# then upload live-site.zip via NetPOA control panel or FTP
```
