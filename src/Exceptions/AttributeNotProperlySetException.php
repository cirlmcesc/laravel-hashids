<?php

namespace Cirlmcesc\LaravelHashids\Exceptions;

use Exception;

class AttributeNotProperlySetException extends Exception
{
    /**
     * id string variable
     *
     * @property string sring ERROR_MESSAGE
     */
    const ERROR_MESSAGE = "\$needEncodeFields and \$dosntNeedEncodeFields are mutually exclusive. Cannot coexist simultaneously.";

    /**
     * __construct function
     */
    function __construct()
    {
        $this->message = self::ERROR_MESSAGE;
    }
}