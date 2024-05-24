<?php

use Cirlmcesc\LaravelHashids\LaravelHashids;

if (! function_exists('hashids_encode')) {
    /**
     * encode id
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
     * decode id
     *
     * @param string $id
     * @param int $remedy 
     * @return int
     */
    function hashids_decode(string $id, int $remedy = 0): int
    {
        return resolve(LaravelHashids::class)->decode($id, $remedy);
    }
}

if (! function_exists('hashids_encode_in_array')) {
    /**
     * encode ids in array
     *
     * @param array $data
     * @param array $dosent_encode_keys
     * @param string $suffix
     * @return array
     */
    function hashids_encode_in_array(array $data, array $dosent_encode_keys = [], string $suffix = '_id'): array
    {
        $hashids = resolve(LaravelHashids::class);

        if (array_key_exists('id', $data) == true) {
            $data['id'] = hashids_encode((int) $data['id']);
        }

        foreach ($data as $key => $value) {
            if (str_ends_with($key, $suffix) 
                && in_array($key, $dosent_encode_keys) == false) {
                $data[$key] = $value == null ? null : $hashids->encode((int) $value);
            }
        }

        return $data;
    }
}

if (! function_exists('hashids_decode_in_array')) {
    /**
     * decode ids in array
     *
     * @param array $data
     * @param array $dosent_decode_keys
     * @param string $suffix
     * @return array
     */
    function hashids_decode_in_array(array $data, array $dosent_decode_keys = [], string $suffix = '_id'): array
    {
        $hashids = resolve(LaravelHashids::class);

        if (array_key_exists('id', $data) == true) {
            $data['id'] = hashids_decode($data['id']);
        }

        foreach ($data as $key => $value) {
            if (str_ends_with($key, $suffix) 
                && in_array($key, $dosent_decode_keys) == false) {
                $data[$key] = $value == null ? null : $hashids->decode((string) $value);
            }
        }

        return $data;
    }
}
