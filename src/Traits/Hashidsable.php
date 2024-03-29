<?php

namespace Cirlmcesc\LaravelHashids\Traits;

use Illuminate\Support\Str;
use Cirlmcesc\LaravelHashids\LaravelHashids;

trait Hashidsable
{
    /**
     * id string variable
     *
     * @var string
     */
    public static $_ID_STRING = "_id";

    /**
     * hashids variable
     *
     * @var Hashids
     */
    public static $_HASHIDS;

    /**
     * bootHashIdsable function
     *
     * @return void
     */
    public static function bootHashIdsable()
    {
        static::saving(function ($model) {
            if ($model->hasProperlySetNeedHasdidFields() == true) {
                foreach ($model->needHashidFields as $field) {
                    if (key_exists($field, $model->attributes)) {
                        $model->decodeAttribute($field);
                    }
                }
            } else {
                foreach ($model->attributes as $field => $value) {
                    if (Str::endsWith($field, self::$_ID_STRING) == true
                        && $model->doesntneedHashidField($field) == false
                        && $value) {
                        $model->decodeAttribute($field);
                    }
                }
            }
        });
    }

    /**
     * encodeAttribute function
     *
     * @param string $field
     * @return void
     */
    private function encodeAttribute(string $field)
    {
        $this->attributes[$field] = $this->attributes[$field] == null
            ? null
            : $this->hashids()->encode($this->$attributes[$field]);
    }

    /**
     * decodeAttribute function
     *
     * @param string $field
     * @return mixed
     */
    private function decodeAttribute(string $field)
    {
        $this->attributes[$field] = $this->attributes[$field] == null
            ? null
            : $this->hashids()->decode($this->attributes[$field]);

        return $this;
    }

    /**
     * hasProperlySetNeedHasdidFields function
     *
     * @return bool
     */
    private function hasProperlySetNeedHasdidFields(): bool
    {
        return empty($this->needHashidFields) === false
            && gettype($this->needHashidFields) === 'array';
    }

    /**
     * hasProperlySetDoesntneedHasdidFields function
     *
     * @return bool
     */
    private function hasProperlySetDoesntneedHasdidFields(): bool
    {
        return empty($this->doesntneedHashidFields) === false
            && gettype($this->doesntneedHashidFields) === 'array';
    }

    /**
     * doesntneedHashidField function
     *
     * @param string $field
     * @return bool
     */
    private function doesntneedHashidField(string $field): bool
    {
        return $this->hasProperlySetDoesntneedHasdidFields()
            && in_array($field, $this->doesntneedHashidFields);
    }

    /**
     * attributesToArray function
     *
     * @return array
     */
    public function attributesToArray(): array
    {
        $hashids = $this->hashids();

        // First, use the built-in method of model to get arrays,
        // which can avoid some unknown problems.
        $data = parent::attributesToArray();

        // Hash the ID field. Because by default it is assumed that
        // using this trait requires hash on the ID.
        if (key_exists('id', $data)) {
            $data['id'] = $hashids->encode($data['id']);
        }

        // Determine whether there are other fields that need hash.
        // Determine if there are other fields that need hash. If so, hash them in turn.
        if ($this->hasProperlySetNeedHasdidFields() == true) {
            foreach ($this->needHashidFields as $field) {
                // To prevent field name errors
                // First, determine whether the field exists or not.
                if (key_exists($field, $data)) {
                    $data[$field] = $data[$field] == null
                    ? null
                    : $hashids->encode((int) $data[$field]);
                }
            }
            // If no field is set.
            // Automatically hash all fields with ID fields.
        } else {
            foreach ($data as $field => $value) {
                // Determine whether the field contains an ID field
                if (Str::endsWith($field, self::$_ID_STRING) == true
                    && $this->doesntneedHashidField($field) == false) {
                    $data[$field] = $value == null
                        ? null
                        : $hashids->encode((int) $value);
                }
            }
        }

        return $data;
    }

    /**
     * resolveRouteBinding function
     *
     * @param mixed $value
     * @param mixed $field
     * @return mixed
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->find($this->hashids()->decode($value) ?? $value) ?? abort(404);
    }

    /**
     * hashids function
     *
     * @return LaravelHashids
     */
    public function hashids(): LaravelHashids
    {
        if (empty(self::$_HASHIDS == true)) {
            self::$_HASHIDS = resolve(LaravelHashids::class);
        }

        return self::$_HASHIDS;
    }
}
