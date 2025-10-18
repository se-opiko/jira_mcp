# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Blog application built with Nuxt.js (frontend) and Laravel (backend). Articles are written in Markdown format.

## Project Structure

- `frontend/` - Nuxt.js 4.x application (Vue 3)
- `backend/` - Laravel 12.x API (PHP 8.2+, SQLite)
- Root contains shared configuration and dependencies for both projects

## Development Commands

### Frontend (Nuxt.js)
```bash
cd frontend
npm install          # Install dependencies
npm run dev          # Start dev server (http://localhost:3000)
npm run build        # Production build
npm run preview      # Preview production build
```

### Backend (Laravel)
```bash
cd backend
composer install     # Install dependencies
php artisan serve    # Start dev server (http://localhost:8000)
php artisan migrate  # Run database migrations
php artisan test     # Run PHPUnit tests
```

### Integrated Development
```bash
cd backend
composer dev         # Concurrently runs: server, queue, logs (pail), and vite
composer test        # Clear config and run tests
```

## Architecture Notes

### Frontend
- **Framework**: Nuxt.js with Vue 3 composition API
- **Markdown**: Uses `marked.js` library for rendering
- **Configuration**: `frontend/nuxt.config.ts` - devtools enabled, compatibility date 2025-10-04
- **Pages**: Single page at `frontend/pages/index.vue`

### Backend
- **Framework**: Laravel 12 with PHP 8.2+
- **Database**: SQLite for development
- **Routes**: Defined in `backend/routes/web.php`
- **Models**: PSR-4 autoloaded from `app/` directory
- **Testing**: PHPUnit 11.5+ configured via composer scripts

### Communication
Frontend communicates with Laravel backend API (frontend @ :3000, backend @ :8000).

## Key Technologies

- **Frontend**: Nuxt 4, Vue 3, Vue Router 4, marked.js
- **Backend**: Laravel 12, Guzzle HTTP client, PHPUnit
- **Dev Tools**: Laravel Pail (logs), Laravel Tinker, Laravel Pint (linting), Collision (error handling)
