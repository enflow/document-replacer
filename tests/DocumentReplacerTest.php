<?php

namespace Enflow\DocumentReplacer\Test;

use Enflow\DocumentReplacer\Converters\UnoconvConverter;
use Enflow\DocumentReplacer\DocumentReplacer;
use PHPUnit\Framework\TestCase;

class DocumentReplacerTest extends TestCase
{
    public function test_basic_replace()
    {
        $output = '/tmp/replaced-document.docx';
        file_exists($output) && unlink($output);

        DocumentReplacer::template(__DIR__ . '/fixtures/template.docx')
            ->replace([
                '${user}' => 'Michel',
                '${address.city}' => 'Alphen aan den Rijn',
                '${company}' => 'Enflow',
            ])
            ->save($output);

        $this->assertFileExists($output);
    }

    public function test_pdf_saver()
    {
        $output = '/tmp/replaced-document.pdf';
        file_exists($output) && unlink($output);

        DocumentReplacer::template(__DIR__ . '/fixtures/template.docx')
            ->converter(UnoconvConverter::class)
            ->replace([
                '${user}' => 'Michel',
                '${address.city}' => 'Alphen aan den Rijn',
                '${company}' => 'Enflow',
            ])
            ->save($output);

        $this->assertFileExists($output);
    }
}
