<?php
namespace WebServCo\ICarsoft\Exceptions;

class ICarsoftException extends \WebServCo\Framework\Exceptions\ApplicationException
{
    const CODE = 0;

    public function __construct($message, \Exception $previous = null)
    {
        parent::__construct($message, self::CODE, $previous);
    }
}
