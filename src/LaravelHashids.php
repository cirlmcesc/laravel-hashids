<?php

namespace Cirlmcesc\LaravelHashids;

use Hashids\Hashids;

class LaravelHashids
{
    /**
     * normal method
     * 
     * @var string NORMAL_METHOD
     */
    const NORMAL_METHOD = 'normal';

    /**
     * hex method
     * 
     * @var string HEX_METHOD
     */
    const HEX_METHOD = 'hexadecimal';

    /**
     * Hashids variable
     *
     * @var Hashids $hashids
     */
    private $hashids;

    /**
     * whether use hexadecimal
     *
     * @var boolean $use_hexadecimal
     */
    private $use_hexadecimal = false;

    /**
     * __construct function
     *
     */
    public function __construct()
    {
        $this->hashids = new Hashids(
            salt: config("hashids.salt"),
            minHashLength: config("hashids.length"),
            alphabet: config("hashids.alphabet"));

        $this->use_hexadecimal = config('hashids.method') == self::HEX_METHOD;
    }

    /**
     * getHashidInstance function
     *
     * @return Hashids
     */
    public function getHashidInstance(): Hashids
    {
        return $this->hashids;
    }

    /**
     * getHashMethod function
     *
     * @return boolean
     */
    public function getHashMethod(): string
    {
        return $this->use_hexadecimal ? self::NORMAL_METHOD : self::HEX_METHOD;
    }

    /**
     * encode function
     *
     * @param int $id
     * @return string
     */
    public function encode(int $id): string
    {
        return $this->use_hexadecimal
            ? $this->hashids->encodeHex($id)
            : $this->hashids->encode($id);
    }

    /**
     * decode function
     *
     * @param string $id
     * @param int $remedy
     * @return int
     */
    public function decode(string $id, int $remedy = 0): int
    {
        $resault = $this->use_hexadecimal
            ? $this->hashids->decodeHex($id)
            : $this->hashids->decode($id);

        return (int) count($resault) > 0
            ? $resault[0]
            : ($remedy ?? $id);
    }
}
