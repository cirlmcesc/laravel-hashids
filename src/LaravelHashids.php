<?php

namespace Cirlmcesc\LaravelHashids;

use Hashids\Hashids;

class LaravelHashids
{
    /**
     * const method
     */
    const NORMAL_METHOD = 'normal';

    /**
     * const method
     */
    const HEX_METHOD = 'hexadecimal';

    /**
     * Hashids variable
     *
     * @var Hashids
     */
    private $hashids;

    /**
     * use_hexadecimal variable
     *
     * @var boolean
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
            alphabet: config("hashids.alphabet")
        );

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
     * @param int $default
     * @return int
     */
    public function decode(string $id, int $default = 0): int
    {
        $resault = $this->use_hexadecimal
            ? $this->hashids->decodeHex($id)
            : $this->hashids->decode($id);

        return (int) count($resault) > 0
            ? $resault[0]
            : ($default ? $default : $id);
    }
}
