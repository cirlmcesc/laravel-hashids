<?php

namespace Cirlmcesc\LaravelHashids\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Cirlmcesc\LaravelHashids\LaravelHashids;
use Cirlmcesc\LaravelHashids\Exceptions\AttributeNotProperlySetException;

trait Hashidsable
{
    /**
     * id string variable
     *
     * @property string string _ID_STRING
     */
    const _ID_STRING = "_id";

    /**
     * bootHashidsable function
     *
     * @return void
     */
    public static function bootHashidsable()
    {
        static::saving(function (Model $model) {
            if ($model->hasProperlySetNeedEncodeFields() === true) {
                foreach ($model->_need_encode_fields as $field) {
                    if (key_exists($field, $model->attributes)) {
                        $model->decodeAttribute($field);
                    }
                }
            } else {
                $doesnt_need_encode_fields = $model->hasProperlySetDoesntNeedEncodeFields()
                    ? $model->_doesnt_need_encode_fields
                    : [];

                foreach ($model->attributes as $field => $value) {
                    if (Str::endsWith($field, self::_ID_STRING) === true
                        && key_exists($field, $doesnt_need_encode_fields) === false) {
                        $model->decodeAttribute($field);
                    }
                }
            }
        });
    }

    /**
     * decodeAttribute function
     *
     * @param string $field
     * @return self
     */
    private function decodeAttribute(string $field): self
    {
        $this->attributes[$field] = $this->attributes[$field] == null
            ? null
            : $this->hashids()->decode((string) $this->attributes[$field]);

        return $this;
    }

    /**
     * hasProperlySetOnlyEncodeId function
     *
     * @return boolean
     */
    private function hasProperlySetOnlyEncodeId(): bool
    {
        return empty($this->_only_encode_id) === false
            && gettype($this->_only_encode_id) === 'boolean';
    }

    /**
     * hasProperlySetNeedEncodeFields function
     *
     * @return bool
     */
    private function hasProperlySetNeedEncodeFields(): bool
    {
        return empty($this->_need_encode_fields) === false
            && gettype($this->_need_encode_fields) === 'array';
    }

    /**
     * hasProperlySetDoesntNeedEncodeFields function
     *
     * @return bool
     */
    private function hasProperlySetDoesntNeedEncodeFields(): bool
    {
        return empty($this->_doesnt_need_encode_fields) === false
            && gettype($this->_doesnt_need_encode_fields) === 'array';
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
            && in_array($field, $this->_doesnt_need_encode_fields);
    }

    /**
     * checkProperlyReasonable function
     *
     * @return self
     * @throws LarvelHashidsException
     */
    private function checkProperlyReasonable(): self
    {
        if ($this->hasProperlySetNeedEncodeFields() == true
            && $this->hasProperlySetDoesntNeedEncodeFields() == true) {
            throw new AttributeNotProperlySetException();
        }

        return $this;
    }

    /**
     * attributesToArray function
     *
     * @return array
     */
    public function attributesToArray(): array
    {
        $hashids = $this->checkProperlyReasonable()->hashids();

        // First, use the built-in method of model to get arrays,
        // which can avoid some unknown problems.
        $data = parent::attributesToArray();

        // Hash the ID field. Because by default it is assumed that
        // using this trait requires hash on the ID.
        if (key_exists('id', $data) == true) {
            $data['id'] = $hashids->encode($data['id']);

            if ($this->hasProperlySetOnlyEncodeId() == true) {
                return $data;
            }
        }

        // Determine whether there are other fields that need hash.
        // Determine if there are other fields that need hash. If so, hash them in turn.
        if ($this->hasProperlySetNeedEncodeFields() == true) {
            foreach ($this->_need_encode_fields as $field) {
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
