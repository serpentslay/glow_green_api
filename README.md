#  Boiler API (Laravel 12.x + Passport)

A RESTful Laravel API application to manage and serve boiler data, built with Laravel 12 and Passport for secure authentication. This project includes:

*  OAuth2 API authentication via Laravel Passport
*  External API integration to seed boiler data
*  Full test coverage for authentication and CRUD
*  Built with Laravel Sail and Docker

---

##  Getting Started

###  Prerequisites

* Docker & Docker Compose
* Composer

###  Installation

```bash
# Run Sail via Docker:
docker run --rm \
  -u "$(id -u):$(id -g)" \
  -v $(pwd):/var/www/html \
  -w /var/www/html \
  laravelsail/php83-composer:latest \
  composer install
  
# Initialize with Laravel Sail
./vendor/bin/sail up -d
```

###  Setup Environment

Copy `.env.example`:

```bash
cp .env.example .env
./vendor/bin/sail artisan key:generate
```

Add the external API token to `.env`:

```env
GLOW_GREEN_API_TOKEN=your-api-token-here
```

### 📚 Migrate & Seed

```bash
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan passport:install
./vendor/bin/sail artisan db:seed
```

---

## Features

###  Boiler Data

* CRUD for boilers
* Linked relationships:

    * Manufacturers
    * Fuel Types

###  Authentication

* User registration/login with Laravel Passport
* OAuth 2.0 client credentials flow for apps

---

## API Endpoints

| Method | Endpoint            | Description                     | Auth |
| ------ |---------------------|---------------------------------|------|
| POST   | `/api/register`     | Register new user               | No   |
| POST   | `/api/login`        | Login as User                   | No   |
| POST   | `/oauth/token`      | Request token (password/client) | No   |
| GET    | `/api/boilers`      | List all boilers                | Yes  |
| GET    | `/api/boilers/{id}` | Show boiler                     | Yes  |
| POST   | `/api/boilers`      | Create boiler                   | Yes  |
| PUT    | `/api/boilers/{id}` | Update boiler                   | Yes  |
| DELETE | `/api/boilers/{id}` | Soft delete boiler              | Yes  |

---

## Authentication

### Password Grant

```bash
POST /api/register
```

```json
{
  "name": "Your Name",
  "email": "you@example.com",
  "password": "secret123",
  "password_confirmation": "secret123"
}
```

```bash
POST /api/login
```

```json
{
  "email": "you@example.com",
  "password": "secret123"
}
```

### Client Credentials

```bash
POST /oauth/token
```

```json
{
  "grant_type": "client_credentials",
  "client_id": "client-id",
  "client_secret": "client-secret",
  "scope": "*"
}
```

Use the returned `token` in headers:

```http
Authorization: Bearer {token}
```

---

## Running Tests

```bash
./vendor/bin/sail artisan test
```

Includes:

* Authentication test suite
* CRUD tests for boiler endpoints
* Seeder test validation

---

## Artisan Commands

* `sail artisan migrate:fresh --seed` – reset and seed database
* `sail artisan passport:install` – regenerate OAuth clients and keys

---

## Project Structure

* `app/Models/` – Eloquent models with relationships
* `app/Http/Controllers/` – API and auth controllers
* `database/factories/` – Model factories for tests
* `database/seeders/` – Seeders to pull from external sources
* `routes/api.php` – All API routes
* `tests/Feature/` – Full API test coverage

---
