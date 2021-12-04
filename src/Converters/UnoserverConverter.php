<?php

namespace Enflow\DocumentReplacer\Converters;

use Enflow\DocumentReplacer\Exceptions\ConversionFailed;
use Symfony\Component\Process\Process;

class UnoserverConverter extends AbstractConverter
{
    private const BINARY = '/usr/local/bin/unoconvert';

    public function convert(string $input, string $output): void
    {
        $process = new Process([
            $this->options['binary'] ?? static::BINARY,
            '--convert-to', 'pdf',
            '--interface', ($this->options['interface'] ?? '127.0.0.1'),
            '--port', ($this->options['port'] ?? '2002'),
            $input,
            $output,
        ]);
        $process->setTimeout(20);
        $process->mustRun();

        if (! file_exists($output)) {
            throw new ConversionFailed("Unable to convert document to PDF through unoserver");
        }
    }
}
