# Capsule API

Laravel backend for the time capsule application. Handles authentication, capsule storage, media uploads, and time-based reveal logic.

## Setup

Install dependencies and set up the database:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

The API will be available at `http://localhost:8000` (or `http://capsule-server.test` if you have a pretty url feature).

## Key Features

-   JWT authentication with token versioning
-   Time-based capsule reveals
-   File upload handling (images/audio stored as base64)
-   Tag system for organizing capsules
-   Location detection via IP
-   Surprise mode (hidden until reveal date)

## API Endpoints

### Auth

-   `POST /api/register` - User registration
-   `POST /api/login` - User login
-   `GET /api/me` - Get current user
-   `POST /api/logout` - Logout

### Capsules

-   `POST /api/capsules` - Create capsule
-   `GET /api/my-capsules` - User's capsules
-   `GET /api/upcoming-capsules` - User's unrevealed capsules
-   `GET /api/public/public-capsules` - Public capsules
-   `GET /api/public/revealed-capsules` - All revealed capsules

### Media & Tags

-   `POST /api/capsule-media` - Upload media to capsule
-   `POST /api/tags` - Create tags
-   `GET /api/tags` - List all tags

## Database

Uses MySQL with migrations for:

-   Users (with token versioning)
-   Capsules (with reveal dates and privacy settings)
-   Media (base64 storage - should probably switch to file storage)
-   Tags and capsule_tags pivot table
