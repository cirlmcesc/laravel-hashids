<?php

use Cirlmcesc\LaravelHashids\LaravelHashids;
use Hashids\Hashids;
use Illuminate\Support\Str;

if (! function_exists('hashids')) {
    /**
     * hashids function
     *
     * @return Hashids
     */
    function hashids(): Hashids
    {
        return resolve(Hashids::class);
    }
}

if (! function_exists('hashidsencode')) {
    /**
     * hashidsencode function
     *
     * @param int $id
     * @return string
     */
    function hashidsencode(int $id): string
    {
        return resolve(LaravelHashids::class)->encode($id);
    }
}

if (! function_exists('hashidsdecode')) {
    /**
     * hashidsdecode function
     *
     * @param string $id
     * @return int
     */
    function hashidsdecode(string $id, int $default = 0): int
    {
        return resolve(LaravelHashids::class)->decode($id, $default);
    }
}

if (! function_exists('hashidsencode_array')) {
    /**
     * hashidsencode_array function
     *
     * @param array $data
     * @param array $dosent_encode_keys
     * @return array
     */
    function hashidsencode_array(array $data, array $dosent_encode_keys = []): array
    {
        foreach ($data as $key => $value) {
            if (Str::endsWith($key, '_id') && in_array($key, $dosent_encode_keys) == false) {
                $data[$key] = hashidsencode($value);
            }
        }

        return $data;
    }
}

if (! function_exists('hashidsdecode_array')) {
    /**
     * hashidsdecode_array function
     *
     * @param array $data
     * @param array $dosent_decode_keys
     * @return array
     */
    function hashidsdecode_array(array $data, array $dosent_decode_keys = []): array
    {
        foreach ($data as $key => $value) {
            if (Str::endsWith($key, '_id') && in_array($key, $dosent_decode_keys) == false) {
                $data[$key] = hashidsdecode($value);
            }
        }

        return $data;
    }
}
