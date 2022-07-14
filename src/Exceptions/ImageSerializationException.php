<?php

namespace Enflow\DocumentReplacer\Exceptions;

use Exception;

class ImageSerializationException extends Exception
{
    public static function noKeyDefined(): self
    {
        return new static('Image JSON serialization failed. A "key" is required to create a unique JSON key.');
    }
}
