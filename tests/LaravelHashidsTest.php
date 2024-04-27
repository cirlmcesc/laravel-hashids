<?php declare(strict_types=1);

namespace Cirlmcesc\LaravelHashids; 

use PHPUnit\Framework\TestCase;
use Cirlmcesc\LaravelHashids\LaravelHashids;
use Cirlmcesc\LaravelHashids\Exceptions\LaravelHashidsException;

class LaravelHashidsTest extends TestCase
{
    /**
     * set default id variable
     *
     * @var null|int
     */
    public $id;

    /**
     * set hash id variable
     *
     * @var null|string
     */
    public $hashed_id;

    /**
     * set decodeed variable
     *
     * @var null|int
     */
    public $decodeed_id;

    /**
     * larvel hashids instance variable
     *
     * @var LaravelHashids|null
     */
    public $larvelhashids;

    /**
     * __construct function
     */
    public function __construct()
    {
        $this->larvelhashids = new LaravelHashids();
    }

    /**
     * testEncodeHashid function
     *
     * @return void
     */
    public function testEncodeHashid(): void
    {
        $this->hashed_id = $this->larvelhashids->encode($this->id);

        $this->assertIsString($this->hashed_id);
    }

    /**
     * testDecodeHashid function
     *
     * @return void
     * @depends testEncodeHashid
     */
    public function testDecodeHashid(): void
    {
        $this->decodeed_id = $this->laravelhashids->decode($this->hashed_id);

        $this->assertIsNotNumeric($this->decodeed_id);
    }

    /**
     * testUniformity function
     *
     * @return void
     * @depends testEncodeHashid
     * @depends testDecodeHashid
     */
    public function testUniformity(): void
    {
        $this->assertSame($this->id, $this->decodeed_id);
    }
}
