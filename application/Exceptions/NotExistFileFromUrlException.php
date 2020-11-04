<?php

declare(strict_types=1);

namespace Exceptions;

class NotExistFileFromUrlException extends \Exception
{
    /**
     * NotExistFileFromUrlException constructor.
     * @param string $file
     */
    public function __construct(string $file)
    {
        $this->message = 'File not exist ' . $file;
        error_log("\n" . date("Y-m-d H:i:s") . " : File not exist:" . $this->getFile() . ' / line:' . $this->getLine() . "/ Failed to open " . $file ,
            3,
            "errors.log");
    }
}