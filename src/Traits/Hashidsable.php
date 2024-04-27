<?php

namespace Cirlmcesc\LaravelHashids\Traits;

use Illuminate\Support\Str;
use Cirlmcesc\LaravelHashids\LaravelHashids;
use Cirlmcesc\LaravelHashids\Exceptions\LarvelHashidsException;

trait Hashidsable
{
    /**
     * id string variable
     *
     * @param sring _ID_STRING
     */
    const _ID_STRING = "_id";

    /**
     * bootHashidsable function
     *
     * @return void
     */
    public static function bootHashidsable()
    {
        static::saving(function ($model) {
            if ($model->hasProperlySetNeedEncodeFields() === true) {
                foreach ($model->needEncodeFields as $field) {
                    if (key_exists($field, $model->attributes)) {
                        $model->decodeAttribute($field);
                    }
                }
            } else {
                foreach ($model->attributes as $field => $value) {
                    if (Str::endsWith($field, self::_ID_STRING) === true
                        && $model->doesntNeedEncodeFields($field) === false
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
            : $this->hashids()->encode($this->attributes[$field]);
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
     * hasProperlySetOnlyEncodeId function
     *
     * @return boolean
     */
    private function hasProperlySetOnlyEncodeId(): bool
    {
        return empty($this->onlyEncodeId) === false
            && gettype($this->onlyEncodeId) === 'boolean';
    }

    /**
     * hasProperlySetNeedEncodeFields function
     *
     * @return bool
     */
    private function hasProperlySetNeedEncodeFields(): bool
    {
        return empty($this->needEncodeFields) === false
            && gettype($this->needEncodeFields) === 'array';
    }

    /**
     * hasProperlySetDoesntNeedEncodeFields function
     *
     * @return bool
     */
    private function hasProperlySetDoesntNeedEncodeFields(): bool
    {
        return empty($this->doesntNeedEncodeFields) === false
            && gettype($this->doesntNeedEncodeFields) === 'array';
    }

    /**
     * doesntNeedEncodeField function
     *
     * @param string $field
     * @return bool
     */
    private function doesntNeedEncodeField(string $field): bool
    {
        return $this->hasProperlySetDoesntNeedEncodeFields()
            && in_array($field, $this->doesntNeedEncodeFields);
    }

    /**
     * checkProperlyReasonable function
     *
     * @return void
     * @throws LarvelHashidsException
     */
    private function checkProperlyReasonable(): void
    {
        if ($this->hasProperlySetNeedEncodeFields() == true
            && $this->hasProperlySetDoesntNeedEncodeFields() == true) {
                throw new LarvelHashidsException("\$needEncodeFields and \$dosntNeedEncodeFields are mutually exclusive. Cannot coexist simultaneously.");
        }
    }

    /**
     * attributesToArray function
     *
     * @return array
     */
    public function attributesToArray(): array
    {
        $this->checkProperlyReasonable();

        $hashids = $this->hashids();

        // First, use the built-in method of model to get arrays,
        // which can avoid some unknown problems.
        $data = parent::attributesToArray();

        // Hash the ID field. Because by default it is assumed that
        // using this trait requires hash on the ID.
        if (key_exists('id', $data)) {
            $data['id'] = $hashids->encode($data['id']);
        }

        if ($this->hasProperlySetOnlyEncodeId() == true) {
            return $data;
        }

        // Determine whether there are other fields that need hash.
        // Determine if there are other fields that need hash. If so, hash them in turn.
        if ($this->hasProperlySetNeedEncodeFields() == true) {
            foreach ($this->needEncodeFields as $field) {
                // To prevent field name errors
                // First, determine whether the field exists or not.
                if (key_exists($field, $data)) {
                    $data[$field] = $data[$field] == null
                        ? null
                        : $hashids->encode((int) $data[$field]);
                }
            }
        } else {
            // If no field is set.
            // Automatically hash all fields with ID fields.
            foreach ($data as $field => $value) {
                // Determine whether the field contains an ID field
                if (Str::endsWith($field, self::_ID_STRING) == true
                    && $this->doesntNeedEncodeField($field) == false) {
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
     * @param string|int $value
     * @param string $field
     * @return Illuminate\Database\Eloquent\Model|null
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
        return resolve(LaravelHashids::class);
    }
}
