<?php

namespace Enflow\DocumentReplacer\Converters;

use Enflow\DocumentReplacer\Exceptions\ConversionFailed;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

class UnoserverConverter extends AbstractConverter
{
    public function convert(string $input, string $output): void
    {
        $process = new Process([
            $this->options['binary'] ?? (new ExecutableFinder())->find('unoconvert'),
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
