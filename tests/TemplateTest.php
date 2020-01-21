<?php

namespace Enflow\DocumentReplacer\Test;

use Enflow\DocumentReplacer\Converters\UnoconvConverter;
use Enflow\DocumentReplacer\DocumentReplacer;
use Enflow\DocumentReplacer\Template;
use PHPUnit\Framework\TestCase;

class TemplateTest extends TestCase
{
    public function test_template_path_equals_input()
    {
        $template = Template::fromFile($input = __DIR__ . '/fixtures/template.docx');

        $this->assertEquals($input, $template->path());
    }

    public function test_unknown_file_as_input()
    {
        $this->expectException(\InvalidArgumentException::class);

        Template::fromFile(__DIR__ . '/not-existing.docx');
    }

    public function test_input_must_be_a_file()
    {
        $this->expectException(\InvalidArgumentException::class);

        Template::fromFile(__DIR__ . '/fixtures');
    }
}
