<?php

namespace Enflow\DocumentReplacer\Converters;

use Enflow\DocumentReplacer\DocumentReplacer;

abstract class AbstractConverter
{
    private function __construct(
        protected DocumentReplacer $documentReplacer,
        protected array $options
    ) {
    }

    public static function make(DocumentReplacer $documentReplacer, array $options = []): self
    {
        return new static($documentReplacer, $options);
    }

    abstract public function convert(string $input, string $output): void;
}
