# UrlShortener

[![UrlShortener](https://preview.dragon-code.pro/LaravelReady/url-shortener.svg?brand=laravel)](https://github.com/laravel-ready/url-shortener)

[![Stable Version][badge_stable]][link_packagist]
[![Unstable Version][badge_unstable]][link_packagist]
[![Total Downloads][badge_downloads]][link_packagist]
[![License][badge_license]][link_license]

## 📂 About
URL shortener for Laravel apps...

## 📦 Installation

Get via composer

```bash
composer require laravel-ready/url-shortener
```

## ⚙️ Configs

```bash
php artisan vendor:publish --tag=url-shortener-config
```
## Migrations

```bash
# publish migrations
php artisan vendor:publish --tag=url-shortener-migrations

# apply migrations
php artisan migrate --path=/database/migrations/laravel-ready/url-shortener
```

## 📝 Usage

```php
use LaravelReady\UrlShortener\Enums\ShortingType;
use LaravelReady\UrlShortener\Supports\UrlShortener;

$shortUrl = UrlShortener::shortUrl(
    'https://github.com/laravel-ready/url-shortener',
    [
        'title' => 'TEST TITLE',
        'description' => 'Lorem ipsum dolar amet',
    ],
    ShortingType::Emoji
);    
```

### CreateShortUrlRequest

To see all validation rules, see the [CreateShortUrlRequest](src/Http/Requests/CreateShortUrlRequest.php#L22) class.

```php
use LaravelReady\UrlShortener\Requests\CreateShortUrlRequest;

class ShortUrlController extends Controller
{
    public function store(CreateShortUrlRequest $request)
    {
        $validateData = $request->validated();

        $shortUrl = UrlShortener::shortUrl(
            $validateData['url'],
            $validateData['meta'] ?? [],
            $validateData['type'] ?? ShortingType::Random
        );
    }
}
```

## ⚓Credits

- This project was generated by the **[packager](https://github.com/laravel-ready/packager)**.

[badge_downloads]: https://img.shields.io/packagist/dt/laravel-ready/url-shortener.svg?style=flat-square

[badge_license]: https://img.shields.io/packagist/l/laravel-ready/url-shortener.svg?style=flat-square

[badge_stable]: https://img.shields.io/github/v/release/laravel-ready/url-shortener?label=stable&style=flat-square

[badge_unstable]: https://img.shields.io/badge/unstable-dev--main-orange?style=flat-square

[link_license]: LICENSE

[link_packagist]: https://packagist.org/packages/laravel-ready/url-shortener
