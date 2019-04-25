<?php

namespace Enflow\DocumentReplacer\Converters;

use Enflow\DocumentReplacer\DocumentReplacer;

abstract class AbstractConverter
{
    private $documentReplacer;

    private function __construct(DocumentReplacer $documentReplacer)
    {
        $this->documentReplacer = $documentReplacer;
    }

    public static function make(DocumentReplacer $documentReplacer): self
    {
        return new static($documentReplacer);
    }

    abstract public function convert(string $input, string $output): void;
}
