<?php

namespace Enflow\DocumentReplacer;

use Enflow\DocumentReplacer\Converters\AbstractConverter;
use Exception;
use PhpOffice\PhpWord\TemplateProcessor;

class DocumentReplacer
{
    private $template;
    private $templateProcessor;
    private $converter;

    private function __construct(Template $template)
    {
        $this->template = $template;
        $this->templateProcessor = new TemplateProcessor($this->template->path());
    }

    public function replace(array $keyValue): self
    {
        foreach ($keyValue as $key => $value) {
            $this->templateProcessor->setValue($key, $value);
        }

        return $this;
    }

    public function converter($converter)
    {
        $this->converter = $converter;

        return $this;
    }

    public function save(string $outputPath): string
    {
            $temporaryFile = tempnam(sys_get_temp_dir(), 'document-replacer');
            $this->templateProcessor->saveAs($temporaryFile);

            if ($this->converter) {
                /** @var AbstractConverter $class */
                $class = $this->converter;

                $class::make($this)->convert($temporaryFile, $outputPath);

                return $outputPath;
            }

            rename($temporaryFile, $outputPath);

            return $outputPath;
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
