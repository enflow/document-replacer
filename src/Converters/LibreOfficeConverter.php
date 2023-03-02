<?php

namespace Enflow\DocumentReplacer\Converters;

use Enflow\DocumentReplacer\Exceptions\ConversionFailed;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

class LibreOfficeConverter extends AbstractConverter
{
    private const BINARY = '/usr/bin/libreoffice';

    protected array $libreOfficeOptions = [
        '--headless',
        '--invisible',
        '--nocrashreport',
        '--nodefault',
        '--nofirststartwizard',
        '--nologo',
        '--norestore',
    ];

    public function convert(string $input, string $output): void
    {
        $temporaryOutputDirectory = sys_get_temp_dir() . '/document-replacer-' . uniqid() . '-libreoffice';

        try {
            $binary = $this->options['binary'] ?? (new ExecutableFinder())->find('libreoffice') ?? static::BINARY;

            $process = Process::fromShellCommandline(implode(' ', [
                $binary,
                '--convert-to pdf:writer_pdf_Export',
                ...$this->libreOfficeOptions,
                '--outdir ' . escapeshellarg($temporaryOutputDirectory),
                escapeshellarg($input),
            ]));
            $process->setTimeout(20);
            $process->mustRun();

            if (! file_exists($temporaryOutputDirectory)) {
                throw new ConversionFailed('Failed to convert document: no output directory was created');
            }

            // LibreOffice can not write to a file directly, so we need to move the file to the correct location.
            $files = glob($temporaryOutputDirectory . "/*.pdf");
            if (count($files) !== 1) {
                throw new ConversionFailed('Failed to convert document: more than one file was created');
            }

            // Move the file to the correct location.
            rename($files[0], $output);
        } finally {
            if (file_exists($temporaryOutputDirectory)) {
                rmdir($temporaryOutputDirectory);
            }
        }
    }
}
