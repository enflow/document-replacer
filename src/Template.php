<?php

namespace Enflow\DocumentReplacer;

use InvalidArgumentException;

class Template
{
    private function __construct(private string $path)
    {
        if (! file_exists($path)) {
            throw new InvalidArgumentException("File at '{$path}' cannot be found.");
        }

        if (! is_file($path)) {
            throw new InvalidArgumentException("File at '{$path}' must be a file.");
        }
    }

    public function path(): string
    {
        return $this->path;
    }

    public static function fromFile(string $path): self
    {
        return new static($path);
    }
}
