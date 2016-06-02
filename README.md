Laravel Likeable Plugin
============

Important Note: As of version 1.2 I renamed `Fusion52\Likeable\LikeableTrait` to `Fusion52\Likeable\Likeable`

[![Build Status](https://travis-ci.org/fusion52/laravel-likeable.svg?branch=master)](https://travis-ci.org/fusion52/laravel-likeable)
[![Latest Stable Version](https://poser.pugx.org/fusion52/laravel-likeable/v/stable.svg)](https://packagist.org/packages/fusion52/laravel-likeable)
[![License](https://poser.pugx.org/fusion52/laravel-likeable/license.svg)](https://packagist.org/packages/fusion52/laravel-likeable)

Trait for Laravel Eloquent models to allow easy implementation of a "like" or "favorite" or "follower" feature.

[Laravel 5 Documentation](https://github.com/fusion52/laravel-likeable/tree/laravel-5)  
[Laravel 4 Documentation](https://github.com/fusion52/laravel-likeable/tree/laravel-4)

#### Composer Install (for Laravel 5)

	composer require fusion52/laravel-likeable "~1.2"

#### Install and then run the migrations

```php
'providers' => [
	\Fusion52\Likeable\FavoriteableServiceProvider::class,
	\Fusion52\Likeable\FollowerableServiceProvider::class,
	\Fusion52\Likeable\LikeableServiceProvider::class,
],
```

```bash
php artisan vendor:publish --provider="Fusion52\Favoriteable\FavoriteableServiceProvider" --tag=migrations
php artisan vendor:publish --provider="Fusion52\Followerable\FollowerableServiceProvider" --tag=migrations
php artisan vendor:publish --provider="Fusion52\Likeable\LikeableServiceProvider" --tag=migrations
php artisan migrate
```

#### Setup your models

```php
class Article extends \Illuminate\Database\Eloquent\Model {
	use \Fusion52\Favoriteable\Favoriteable;
	use \Fusion52\Followerable\Followerable;
	use \Fusion52\Likeable\Likeable;
}
```

#### Sample Usage

```php
$article->like(); // like the article for current user
$article->like($myUserId); // pass in your own user id
$article->like(0); // just add likes to the count, and don't track by user

$article->unlike(); // remove like from the article
$article->unlike($myUserId); // pass in your own user id
$article->unlike(0); // remove likes from the count -- does not check for user

$article->likeCount; // get count of likes

$article->likes; // Iterable Illuminate\Database\Eloquent\Collection of existing likes

$article->liked(); // check if currently logged in user liked the article
$article->liked($myUserId);

Article::whereLikedBy($myUserId) // find only articles where user liked them
	->with('likeCounter') // highly suggested to allow eager load
	->get();
```

#### Credits

 - Fusion52 Team - http://fusion52.com
 Forked from Robert Conner - http://smartersoftware.net
