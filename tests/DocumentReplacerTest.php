<?php

namespace Enflow\DocumentReplacer\Test;

use Enflow\DocumentReplacer\Converters\UnoserverConverter;
use Enflow\DocumentReplacer\DocumentReplacer;
use Enflow\DocumentReplacer\Exceptions\InvalidReplacement;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

class DocumentReplacerTest extends TestCase
{
    private static $process = null;

    public static function setUpBeforeClass(): void
    {
        $unoserverPath = (new ExecutableFinder())->find('unoserver');

        static::$process = Process::fromShellCommandline($unoserverPath);
        static::$process->start();
    }

    public static function tearDownAfterClass(): void
    {
        static::$process->stop();
    }

    public function test_basic_replace()
    {
        $output = '/tmp/replaced-document.docx';
        file_exists($output) && unlink($output);

        DocumentReplacer::template(__DIR__ . '/fixtures/template.docx')
            ->replace([
                '${user}' => 'Michel',
                '${company}' => 'Enflow',
                '${address.city}' => 'Alphen aan den Rijn',
                '${address.country}' => null,
            ])
            ->save($output);

        $this->assertFileExists($output);
    }

    public function test_pdf_saver()
    {
        $output = '/tmp/replaced-document.pdf';
        file_exists($output) && unlink($output);

        DocumentReplacer::template(__DIR__ . '/fixtures/template.docx')
            ->converter(UnoserverConverter::class)
            ->replace([
                '${user}' => 'Michel',
                '${address.city}' => 'Alphen aan den Rijn',
                '${company}' => 'Enflow',
            ])
            ->save($output);

        $this->assertFileExists($output);
    }

    public function test_unoconv_without_extension()
    {
        $output = '/tmp/replaced-document-without-extension';
        file_exists($output) && unlink($output);

        DocumentReplacer::template(__DIR__ . '/fixtures/template.docx')
            ->converter(UnoserverConverter::class)
            ->save($output);

        $this->assertFileExists($output);
    }

    public function test_replace_with_ampersand_and_convert()
    {
        // https://github.com/PHPOffice/PHPWord/issues/1467

        $output = '/tmp/replaced-document.pdf';
        file_exists($output) && unlink($output);

        DocumentReplacer::template(__DIR__ . '/fixtures/template.docx')
            ->converter(UnoserverConverter::class)
            ->replace([
                '${address.city}' => 'Alphen & de Rijn',
            ])
            ->save($output);

        $this->assertFileExists($output);
    }

    public function test_replacement_tag_must_be_scalar()
    {
        $this->expectException(InvalidReplacement::class);
        $this->expectExceptionMessage('Could not replace \'${address.city}\' in template. Value must be non-scalar or null. Type is: array');

        DocumentReplacer::template(__DIR__ . '/fixtures/template.docx')
            ->converter(UnoserverConverter::class)
            ->replace([
                '${address.city}' => ['long' => 'Alphen aan den Rijn', 'short' => 'Alphen'],
            ]);
    }

    public function test_replace_with_options_passed_to_unoserver()
    {
        $output = '/tmp/replaced-document.docx';
        file_exists($output) && unlink($output);

        DocumentReplacer::template(__DIR__ . '/fixtures/template.docx')
            ->converter(UnoserverConverter::class, [
                'interface' => 'localhost',
                'port' => 2002,
            ])
            ->replace([
                '${user}' => 'Michel',
            ])
            ->save($output);

        $this->assertFileExists($output);
    }
}
