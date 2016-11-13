<?php

namespace Marking\Exceptions;

/**
 * Custom class extends Exception class.
 * - used to create custom Exceptions
 *
 * Class BankException
 * @package
 */
class CustomException extends \Exception
{
    private $errorMessage;

    public function __construct($errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }

    public function getErrMsg()
    {
        return $this->errorMessage;
    }

}