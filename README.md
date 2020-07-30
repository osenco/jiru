# Mpesa Payments Tracker

This is a micoservice (API backend service) for tracking mpesa payments

## Installation

Clone this repo or download an archive of the codebase and extract it in your server<br>
Remember to set the document root as `/public`

### Install Dependencies

Run the following command

```cmd
composer install
```

### Optimize Dependencies

Run the following command

```cmd
composer dump-autoload -o
```

### Configuration

Rename `.env.example` to `.env` and configure accordingly

### Database

Once you have set your database credentilas, run the following command

```cmd
php artisan migrate
```

## Endpoints

The following endpoints are available
POST `validate`
POST `confirm`
POST `results`
POST `register`
POST `timeout`
POST `reconcile`
POST `reverse`
POST `status`

### Confirmation & Validation URLs
POST `validate`
POST `confirm`

### Callback URL
POST `reconcile`