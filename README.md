# Laravel Hashids

TL;DR
-----
Do not explicitly display the ID of the data in the interface or URI. Use the Trail on the Model to automatically encrypt the ID, and at the same time, it will correctly decrypt the instance of the class you want to inject into the route. It also provides quick methods to encrypt and decrypt the ID when you need. It provides some commands for testing. Inspired by [vinkla/laravel-hashids](https://github.com/vinkla/laravel-hashids).

Install
-------
Install via composer
```composer require cirlmcesc/laravel-hashids```

Add Service Provider to `config/app.php` in `providers` section
```php
Cirlmcesc\LaravelMddoc\LaravelHashidsServiceProvider::class,
```

**Configuration file** will be published to `config/`.
The mode of operation can be customized by modifying parameters and attributes.
```php artisan hashids:install```

Usage
-----
**Use on Model** trait, you can use it quickly. Using trait on the model, you can quickly use it to automatically encrypt the ID when the model is serialized and the value of the field set by ```$needhashidfields```.
```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cirlmcesc\LaravelHashids\Traits\Hashidsable;

class Foo extends Model
{
    use Hashidsable;

    public $needHashIdFields = [
        'xxx_id',
        'yyy_id',
        'zzz_id',
    ];
}
```
**Use functions elsewhere** to quickly encrypt and decrypt. Some functions are provided.
```php
<?php
/**
 * hashids function
 *
 * @return Hashids
 */
function hashids(): Hashids

/**
 * hashidsencode function
 *
 * @param Int $id
 * @return String
 */
function hashidsencode(Int $id): String

/**
 * hashidsdecode function
 *
 * @param String $id
 * @return Int
 */
function hashidsdecode(String $id): Int
```
**Test command** can use encryption and decryption on the command line.
```php artisan hashids:test```

Other
-----