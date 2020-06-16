<?php

namespace Cirlmcesc\LaravelHashids;

use Hashids\Hashids;

class LaravelHashids
{
    /**
     * Hashids variable
     *
     * @var Hashids\Hashids
     */
    public $hashids;

    /**
     * __construct function
     *
     * @param Hashids $hashids
     */
    public function __construct(Hashids $hashids)
    {
        $this->hashids = $hashids;
    }

    /**
     * encode function
     *
     * @param Int $id
     * @return String
     */
    public function encode(Int $id): String
    {
        return $this->hashids->encode($id);
    }

    /**
     * decode function
     *
     * @param String $id
     * @param Int $default
     * @return Int
     */
    public function decode(String $id, Int $default = 0): Int
    {
        return (Int) $this->hashids->decode($id)[0] ?? ($default ?? $id);
    }
}
