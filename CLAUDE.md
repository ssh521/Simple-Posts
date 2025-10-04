# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Package Overview

This is a Laravel package `ssh521/simple-posts` that provides CRUD functionality for simple blog posts with title, content, and date fields. The package is designed to be installed into Laravel applications via Composer.

## Architecture

### Package Structure
- **Namespace**: `Ssh521\SimplePosts`
- **Service Provider**: `SimplePostsServiceProvider` handles package bootstrapping
- **Model**: `Post` (Eloquent model) with fillable fields: title, content, date
- **Controller**: `SimplePostController` with full CRUD operations
- **Request Validation**: `PostRequest` with Korean error messages
- **Views**: Blade templates using Bootstrap 5 with `simple-posts::` namespace
- **Routes**: RESTful routes prefixed with `/posts`

### Key Components

**Service Provider (`src/SimplePostsServiceProvider.php`)**
- Auto-loads routes from `routes/web.php`
- Registers views with `simple-posts` namespace
- Auto-loads migrations conditionally from `database/migrations/` (configurable)
- Publishes views, migrations, config, and routes when running in console

**Database Schema**
- Single `posts` table with: id, title (string), content (text), date (date), timestamps
- Migration file: `2024_01_01_000000_create_posts_table.php`

**Routes Structure**
- All routes prefixed with `/posts` and named with `posts.` prefix
- Standard RESTful resource routes for CRUD operations
- Uses route model binding for Post model

**View Architecture**
- Base layout template (`layout.blade.php`) with Bootstrap 5
- All views extend the base layout
- Uses `simple-posts::` view namespace prefix
- Korean language interface with success/error messaging

## Development Commands

### Package Installation (in host Laravel app)
```bash
composer require ssh521/simple-posts
php artisan migrate
```

### Publishing Assets (optional for customization)
```bash
# Publish views for customization
php artisan vendor:publish --tag=simple-posts-views

# Publish migrations for customization
php artisan vendor:publish --tag=simple-posts-migrations

# Publish config for customization
php artisan vendor:publish --tag=simple-posts-config

# Publish routes for customization
php artisan vendor:publish --tag=simple-posts-routes
```

### Testing Package Integration
Access the package functionality at `/posts` route in the host Laravel application.

## Package Dependencies

- PHP ^8.2
- Laravel Framework ^12.0
- illuminate/support ^12.0

Note: This package is auto-discovered by Laravel through the service provider configuration in composer.json extra section.