<?php

namespace Enflow\DocumentReplacer;

use Enflow\DocumentReplacer\Converters\AbstractConverter;
use Enflow\DocumentReplacer\Exceptions\InvalidReplacement;
use Exception;
use PhpOffice\PhpWord\TemplateProcessor;

class DocumentReplacer
{
    private TemplateProcessor $templateProcessor;
    private ?string $converter = null;
    private array $converterOptions = [];

    private function __construct(private Template $template)
    {
        $this->templateProcessor = new TemplateProcessor($template->path());
    }

    public function variables(): array
    {
        return $this->templateProcessor->getVariables();
    }

    public function replace(array $keyValue): self
    {
        foreach ($keyValue as $key => $value) {
            if ($value instanceof ValueTypes\Image) {
                $this->templateProcessor->setImageValue($key, $value->replacements());
            } else {
                if (! is_scalar($value) && $value !== null) {
                    $type = gettype($value);

                    throw new InvalidReplacement("Could not replace '{$key}' in template. Value must be non-scalar or null. Type is: {$type}");
                }

                // Use htmlentities due to this PHPWord bug: https://github.com/PHPOffice/PHPWord/issues/1467
                $this->templateProcessor->setValue($key, htmlentities($value, ENT_XML1));
            }
        }

        return $this;
    }

    public function converter($converter, array $options = []): self
    {
        $this->converter = $converter;
        $this->converterOptions = $options;

        return $this;
    }

    public function save(string $outputPath): string
    {
        $temporaryFile = $this->templateProcessor->save();

        try {
            if (! file_exists($temporaryFile) || ! filesize($temporaryFile)) {
                throw new Exception("Template processor failed to output valid file to {$temporaryFile}");
            }

            if ($this->converter) {
                /** @var AbstractConverter $class */
                $class = $this->converter;

                $class::make($this, $this->converterOptions)->convert($temporaryFile, $outputPath);

                if (! file_exists($outputPath) || ! filesize($outputPath)) {
                    throw new Exception("Converter failed to output valid file to {$outputPath}");
                }

                return $outputPath;
            }

            rename($temporaryFile, $outputPath);

            return $outputPath;
        } finally {
            if (file_exists($temporaryFile)) {
                unlink($temporaryFile);
            }
        }
    }

    public function output(): string
    {
        $temporaryFile = tempnam(sys_get_temp_dir(), 'document-replacer-output');

        try {
            return file_get_contents($this->save($temporaryFile));
        } finally {
            if (file_exists($temporaryFile)) {
                unlink($temporaryFile);
            }
        }
    }

    public function templateProcessor(): TemplateProcessor
    {
        return $this->templateProcessor;
    }

    public static function template($template): self
    {
        return new static($template instanceof Template ? $template : Template::fromFile($template));
    }
}
