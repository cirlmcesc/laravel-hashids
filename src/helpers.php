<?php

use Cirlmcesc\LaravelHashids\LaravelHashids;
use Illuminate\Support\Str;

if (! function_exists('hashids_encode')) {
    /**
     * hashids_encode function
     *
     * @param int $id
     * @return string
     */
    function hashids_encode(int $id): string
    {
        return resolve(LaravelHashids::class)->encode($id);
    }
}

if (! function_exists('hashids_decode')) {
    /**
     * hashids_decode function
     *
     * @param string $id
     * @return int
     */
    function hashids_decode(string $id, int $default = 0): int
    {
        return resolve(LaravelHashids::class)->decode($id, $default);
    }
}

if (! function_exists('hashids_encode_in_array')) {
    /**
     * hashids_encode_in_array function
     *
     * @param array $data
     * @param array $dosent_encode_keys
     * @param string $id_string
     * @return array
     */
    function hashids_encode_in_array(array $data, array $dosent_encode_keys = [], $id_string = '_id'): array
    {
        $hashids = resolve(LaravelHashids::class);

        if (array_key_exists('id', $data) == true) {
            $data['id'] = hashids_encode($data['id']);
        }

        foreach ($data as $key => $value) {
            if (Str::endsWith($key, $id_string) && in_array($key, $dosent_encode_keys) == false) {
                $data[$key] = $hashids->encode($value);
            }
        }

        return $data;
    }
}

if (! function_exists('hashids_decode_in_array')) {
    /**
     * hashids_decode_in_array function
     *
     * @param array $data
     * @param array $dosent_decode_keys
     * @param string $id_string
     * @return array
     */
    function hashids_decode_in_array(array $data, array $dosent_decode_keys = [], $id_string = '_id'): array
    {
        $hashids = resolve(LaravelHashids::class);

        if (array_key_exists('id', $data) == true) {
            $data['id'] = hashids_decode($data['id']);
        }

        foreach ($data as $key => $value) {
            if (Str::endsWith($key, $id_string) && in_array($key, $dosent_decode_keys) == false) {
                $data[$key] = $hashids->decode($value);
            }
        }

        return $data;
    }
}
