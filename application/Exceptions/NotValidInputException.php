<?php

declare(strict_types=1);

namespace Exceptions;

class NotValidInputException extends \Exception
{
    public function __construct(?string $string)
    {
        $this->message = "Post or Get data not valid ->".$string."\n";
        error_log("\n" . date("Y-m-d H:i:s") . " : Script with problem: " . $this->getFile() . " | Line with problem: " . $this->getLine() . " | " . $this->message,
            3,
            'errors.log');
    }
}