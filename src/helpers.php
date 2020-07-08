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
     * @param Int $id
     * @return String
     */
    function hashidsencode(Int $id): String
    {
        return resolve(LaravelHashids::class)->encode($id);
    }
}

if (! function_exists('hashidsdecode')) {
    /**
     * hashidsdecode function
     *
     * @param String $id
     * @return Int
     */
    function hashidsdecode(String $id, Int $default = 0): Int
    {
        return resolve(LaravelHashids::class)->decode($id, $default);
    }
}

if (! function_exists('hashidsencode_array')) {
    /**
     * hashidsencode_array function
     *
     * @param Array $data
     * @param Array $dosent_encode_keys
     * @return Array
     */
    function hashidsencode_array(Array $data, Array $dosent_encode_keys = []): Array
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
     * @param Array $data
     * @param Array $dosent_decode_keys
     * @return Array
     */
    function hashidsdecode_array(Array $data, Array $dosent_decode_keys = []): Array
    {
        foreach ($data as $key => $value) {
            if (Str::endsWith($key, '_id') && in_array($key, $dosent_decode_keys) == false) {
                $data[$key] = hashidsdecode($value);
            }
        }

        return $data;
    }
}
