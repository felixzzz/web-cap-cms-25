# CMS Deployment Guide (Laravel)

This is a Laravel-based CMS application.

## 🚀 Quick Start (Local Development)

1.  **Install PHP Dependencies**:
    ```bash
    composer install
    ```
2.  **Install Node Dependencies**:
    ```bash
    npm install
    ```
3.  **Environment Setup**:
    - Copy `.env.example` to `.env`.
    - Generate application key:
      ```bash
      php artisan key:generate
      ```
    - Configure your database settings in `.env`.
4.  **Run Migrations**:
    ```bash
    php artisan migrate
    ```
5.  **Start Server**:
    ```bash
    php artisan serve
    ```
6.  **Compile Assets**:
    ```bash
    npm run watch
    ```

---

## 🔄 Deployment Configuration

### Staging / Production

**Typical Laravel Deployment Steps:**

1.  **Pull Code**:
    ```bash
    git pull origin main
    ```
2.  **Install Dependencies**:
    ```bash
    composer install --optimize-autoloader --no-dev
    npm install
    npm run production
    ```
3.  **Run Migrations**:
    ```bash
    php artisan migrate --force
    ```
4.  **Clear/Cache Config**:
    ```bash
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    ```
5.  **File Permissions**:
    Ensure `storage` and `bootstrap/cache` directories are writable by the web server user (e.g., `www-data`).
    ```bash
    chmod -R 775 storage bootstrap/cache
    ```

**Required Secrets/Variables:**
| Variable | Description |
| :--- | :--- |
| `DB_HOST` | Database Host. |
| `DB_DATABASE` | Database Name. |
| `DB_USERNAME` | Database User. |
| `DB_PASSWORD` | Database Password. |
| `APP_KEY` | Application Key (generated via `php artisan key:generate`). |
| `APP_URL` | Full URL of the application. |

---

## 📝 Next Steps

1.  **Create a new Repository** for this CMS.
2.  **Add Remote Origin**:
    ```bash
    git remote add origin <your-cms-repo-url>
    git push -u origin main
    ```
3.  **Configure Web Server**: Ensure Nginx/Apache points to the `public` directory.
