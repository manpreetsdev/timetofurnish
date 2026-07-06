# Laravel Staging Deployment Guide (Plesk)

This guide is for deploying the **staging** branch to:
cd /var/www/vhosts/timetofurnish.com/staging

git pull origin staging

```
git config --global --add safe.directory /var/www/vhosts/timetofurnish.com/staging
/var/www/vhosts/timetofurnish.com/staging
```
COMPOSER_ALLOW_SUPERUSER=1 /opt/plesk/php/8.3/bin/php /usr/lib/plesk-9.0/composer.phar install --ignore-platform-reqs --no-dev --optimize-autoloader

---


# 1. Navigate to the staging directory
cd /var/www/vhosts/timetofurnish.com/staging

mkdir -p storage/app/public
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/testing
mkdir -p storage/logs

mkdir -p bootstrap/cache
chmod -R 775 storage bootstrap/cache

find storage -type d -exec chmod 775 {} \;
find storage -type f -exec chmod 664 {} \;
chmod -R 775 storage bootstrap/cache

/opt/plesk/php/8.3/bin/php artisan optimize:clear



# 1. Go to the staging directory

```bash
cd /var/www/vhosts/timetofurnish.com/staging
```

---

# 2. Check the current branch

```bash
git branch
```

---

# 3. Check Git status

```bash
git status
```

---

# 4. Fetch the latest branches

```bash
git fetch origin
```

---

# 5. Switch to the staging branch

```bash
git checkout staging
```

If the branch does not exist locally:

```bash
git checkout -b staging origin/staging
```

---

# 6. Pull the latest code

```bash
git pull origin staging
```

---

# 7. Verify the latest commit

```bash
git log --oneline -5
```

---

# 8. Install Composer dependencies

```bash
COMPOSER_ALLOW_SUPERUSER=1 \
/opt/plesk/php/8.3/bin/php \
/usr/lib/plesk-9.0/composer.phar install --no-dev --optimize-autoloader
```

---

# 9. Generate Application Key (Run only once)

```bash
/opt/plesk/php/8.3/bin/php artisan key:generate
```

---

# 10. Run Database Migration

```bash
/opt/plesk/php/8.3/bin/php artisan migrate --force
```

---

# 11. Create Storage Link

```bash
/opt/plesk/php/8.3/bin/php artisan storage:link
```

---

# 12. Clear Laravel Cache

```bash
/opt/plesk/php/8.3/bin/php artisan optimize:clear
```

---

# 13. Cache Config, Routes & Views

```bash
/opt/plesk/php/8.3/bin/php artisan optimize
```

---

# 14. Restart Queue (If Used)

```bash
/opt/plesk/php/8.3/bin/php artisan queue:restart
```

---

# 15. Fix Ownership

```bash
chown -R timetofurnish.com_i7vlatvvii:psacln /var/www/vhosts/timetofurnish.com/staging
```

---

# 16. Set Directory Permissions

```bash
find /var/www/vhosts/timetofurnish.com/staging -type d -exec chmod 755 {} \;
```

---

# 17. Set File Permissions

```bash
find /var/www/vhosts/timetofurnish.com/staging -type f -exec chmod 644 {} \;
```

---

# 18. Laravel Writable Folders

```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

---

# 19. Check Laravel Version

```bash
/opt/plesk/php/8.3/bin/php artisan --version
```

---

# 20. Check Installed Packages

```bash
ls -la vendor
```

---

# 21. Check Storage Folder

```bash
ls -la storage
```

---

# 22. Check Current PHP Version

```bash
/opt/plesk/php/8.3/bin/php -v
```

---

# 23. View Laravel Logs

```bash
tail -100 storage/logs/laravel.log
```

---

# 24. View Git Remote

```bash
git remote -v
```

---

# 25. View Current Branch

```bash
git branch
```

---

# Daily Deployment Commands

```bash
cd /var/www/vhosts/timetofurnish.com/staging

git fetch origin

git checkout staging

git pull origin staging

COMPOSER_ALLOW_SUPERUSER=1 \
/opt/plesk/php/8.3/bin/php \
/usr/lib/plesk-9.0/composer.phar install --no-dev --optimize-autoloader

/opt/plesk/php/8.3/bin/php artisan migrate --force

/opt/plesk/php/8.3/bin/php artisan optimize:clear

/opt/plesk/php/8.3/bin/php artisan optimize

chmod -R 775 storage bootstrap/cache
```

---

# Useful Git Commands

## Current Status

```bash
git status
```

## Current Branch

```bash
git branch
```

## Remote Branches

```bash
git branch -r
```

## Last 10 Commits

```bash
git log --oneline -10
```

## Reset Local Changes

```bash
git reset --hard HEAD
```

## Remove Untracked Files

```bash
git clean -fd
```

## Force Match Remote Branch

```bash
git fetch origin

git reset --hard origin/staging
```

---

# Laravel Cache Commands

## Clear Everything

```bash
/opt/plesk/php/8.3/bin/php artisan optimize:clear
```

## Cache Everything

```bash
/opt/plesk/php/8.3/bin/php artisan optimize
```

## Clear Config

```bash
/opt/plesk/php/8.3/bin/php artisan config:clear
```

## Cache Config

```bash
/opt/plesk/php/8.3/bin/php artisan config:cache
```

## Cache Routes

```bash
/opt/plesk/php/8.3/bin/php artisan route:cache
```

## Cache Views

```bash
/opt/plesk/php/8.3/bin/php artisan view:cache
```

---

# Log Files

Laravel Log

```bash
storage/logs/laravel.log
```

PHP Error Log

```bash
tail -100 /var/www/vhosts/timetofurnish.com/logs/error_log
```

---

# Project Path

```
/var/www/vhosts/timetofurnish.com/staging
```

# Branch

```
staging
```

# PHP Binary

```
/opt/plesk/php/8.3/bin/php
```

# Composer

```
/usr/lib/plesk-9.0/composer.phar
```
