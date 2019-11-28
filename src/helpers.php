<?php

use Hashids\Hashids;
use Cirlmcesc\LaravelHashids\LaravelHashids;

if (! function_exists('hashids')) {
    /**
     * hashids function
     *
     * @return Hashids
     */
    function hashids() : Hashids
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
    function hashidsencode(Int $id) : String
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
    function hashidsdecode(String $id) : Int
    {
        return resolve(LaravelHashids::class)->decode($id);
    }
}


