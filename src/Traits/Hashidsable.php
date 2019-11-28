<?php

namespace Cirlmcesc\LaravelHashids\Traits;

use Closure;
use Illuminate\Support\Str;

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
                foreach ($model->needHashIdFields as $field) {
                    if (key_exists($field, $model->attributes)) {
                        $model->decodeAttribute($field);
                    }
                }
            } else {
                foreach ($model->attributes as $field => $value) {
                    if (Str::contains($field, self::$_ID_STRING) == true
                        && $model->doesntneedHashidField($field) == false) {
                        $model->decodeAttribute($field);
                    }
                }
            }
        });
    }

    /**
     * encodeAttribute function
     *
     * @param String $field
     * @return void
     */
    private function encodeAttribute(String $field)
    {
        $this->attributes[$field] = $this->attributes[$field] == NULL
            ? NULL
            : $this->hashids()->encode($this->$attributes[$field]);
    }

    /**
     * decodeAttribute function
     *
     * @param String $field
     * @return mixed
     */
    private function decodeAttribute(String $field)
    {
        $this->attributes[$field] = $this->hashids()->decode($this->attributes[$field])
            ?? $this->attributes[$field];

        return $this;
    }

    /**
     * hasProperlySetNeedHasdidFields function
     *
     * @return Bool
     */
    private function hasProperlySetNeedHasdidFields() : Bool
    {
        return empty($this->needHashidFields) === false
            && gettype($this->needHashidFields) === 'array';
    }

    /**
     * hasProperlySetDoesntneedHasdidFields function
     *
     * @return Bool
     */
    private function hasProperlySetDoesntneedHasdidFields() : Bool
    {
        return empty($this->doesntneedHashidFields) === false
            && gettype($this->doesntneedHashidFields) === 'array';
    }

    /**
     * doesntneedHashidField function
     *
     * @param String $field
     * @return Bool
     */
    private function doesntneedHashidField(String $field) : Bool
    {
        return $this->hasProperlySetDoesntneedHasdIdFields()
            && in_array($field, $this->doesntneedHashidFields);
    }

    /**
     * attributesToArray function
     *
     * @return Array
     */
    public function attributesToArray() : Array
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
            foreach ($this->needHashIdFields as $field) {
                // To prevent field name errors
                // First, determine whether the field exists or not.
                if (key_exists($field, $data)) {
                    $data[$field] = $data[$field] == null
                        ? null
                        : $hashids->encode((int)$data[$field]);
                }
            }
        // If no field is set.
        // Automatically hash all fields with ID fields.
        } else {
            foreach ($data as $field => $value) {
                // Determine whether the field contains an ID field
                if (Str::contains($field, self::$_ID_STRING) == true
                    && $this->doesntneedHashidField($field) == false) {
                    $data[$field] = $value == null
                        ? null
                        : $hashids->encode((int)$value);
                }
            }
        }

        return $data;
    }
    
    /**
     * resolveRouteBinding function
     *
     * @param String $value
     * @return mixed
     */
    public function resolveRouteBinding($value)
    {
        return $this->find($this->hashids()->decode($value) ?? $value) ?? abort(404);
    }

    /**
     * hashids function
     *
     * @return Hashids
     */
    public function hashids() : Hashids
    {
        if (empty(self::$_HASHIDS == true)) {
            self::$_HASHIDS = resolve(LaravelHashids::class);
        }

        return self::$_HASHIDS;
    }
}