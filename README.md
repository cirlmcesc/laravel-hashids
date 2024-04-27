# Laravel Hashids

[![Latest Stable Version](http://poser.pugx.org/cirlmcesc/laravel-hashids/v)](https://packagist.org/packages/cirlmcesc/laravel-hashids) [![Total Downloads](http://poser.pugx.org/cirlmcesc/laravel-hashids/downloads)](https://packagist.org/packages/cirlmcesc/laravel-hashids) [![License](http://poser.pugx.org/cirlmcesc/laravel-hashids/license)](https://packagist.org/packages/cirlmcesc/laravel-hashids) [![PHP Version Require](http://poser.pugx.org/cirlmcesc/laravel-hashids/require/php)](https://packagist.org/packages/cirlmcesc/laravel-hashids)

---

## TL;DR

Do not explicitly display the ID of the data in the interface or URI. Use the Trail on the Model to automatically encrypt the ID, and at the same time, it will correctly decrypt the instance of the class you want to inject into the route. It also provides quick methods to encrypt and decrypt the ID when you need. It provides some commands for testing. Inspired by [vinkla/hashids](https://github.com/vinkla/hashids).

---

## Getting started

**Install via composer**
Require this package, with Composer, in the root directory of your project.

```bash
composer require cirlmcesc/laravel-hashids
```

Add Service Provider to `config/app.php` in `providers` section

```php
Cirlmcesc\LaravelMddoc\LaravelHashidsServiceProvider::class,
```

**Configuration file**
Artisan command will be published to `config/`. The mode of operation can be customized by modifying parameters and attributes.

```shell
php artisan hashids:install
```

---

## Usage

**Use on Model**
Use Model trait, you can use it quickly. Using trait on the model, you can quickly use it to automatically encrypt the ID when the model is serialized and the value of the field set by ```$needEncodeFields```. It is also possible to not set this property and all fields ends with ```_id``` will be automatically encrypted.You can choose to set the ```$doesntNeedEncodeFields``` property to prevent these fields from being encrypted. However, ``` $needEncodeFields ``` and ``` $dosntNeedEncodeFields ``` are mutually exclusive. If both are set, an exception will be thrown when the model is serialized into an array. Of course, you can also choose to set the ```$onlyEncodeId``` property to determine whether to encrypt only the ID field.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cirlmcesc\LaravelHashids\Traits\Hashidsable;

class Foo extends Model
{
    use Hashidsable;

    /**
     * Encrypt the list of other fields that 
     * need to be encrypted while encrypting the ID.
     *
     * @var bool
     */
    public $onlyEncodeId = false;

    /**
     * Encrypt the list of other fields that 
     * need to be encrypted while encrypting the ID.
     *
     * @var array<string>
     */
    public $needEncodeFields = [
        'aaa_id',
        'bbb_id',
        'ccc_id',
    ];

    /**
     * While encrypting the ID, you can choose 
     * which fields do not encryption,
     *
     * @var array<string>
     */
    public $doesntNeedEncodeFields = [
        'xxx_id',
        'yyy_id',
        'zzz_id',
    ];
}
```

**Use on Route**
When injecting the model ID into routing or controller operations, it will automatically decode the ID. No additional action is required.

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Models\Foo;

Route::get('/foos/{foo}', fn(Foo $foo) => $foo);

```

**Use on Resource Class**
When you use resource classes, model serialization does not go through Model Trait, so you need to encrypt the ID in the ```toArray``` method of the resource class.The usage ```hashids_encode_in_array``` method allows for batch encryption of ID fields.

```php
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FooResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return hashids_encode_in_array([
            'id' => $this->id,
            'foo' => $this->foo,
            'app_id' => $this->app_id,
            'banana_id' => $this->banana_id,
        ]);
    }
}

```

**Use functions elsewhere**
Quickly encrypt and decrypt. Some functions are provided.

```php
<?php

/**
 * hashids_encode function
 *
 * @param int $id
 * @return string
 */
function hashids_encode(int $id): string;

/**
 * hashids_decode function
 *
 * @param string $id
 * @return int
 */
function hashids_decode(string $id): int;

 /**
 * hashids_encode_in_array function
 *
 * @param array $data
 * @param array $dosent_encode_keys
 * @param string $id_string
 * @return array
 */
function hashids_encode_in_array(array $data, array $dosent_encode_keys = [], $id_string = '_id'): array;

/**
 * hashids_decode_in_array function
 *
 * @param array $data
 * @param array $dosent_decode_keys
 * @param string $id_string
 * @return array
 */
function hashids_decode_in_array(array $data, array $dosent_decode_keys = [], $id_string = '_id'): array;

```

**Test command**
Artisan command can use encryption and decryption on the command line.

```shell
php artisan hashids:test
```
