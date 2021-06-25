<?php

namespace Enflow\DocumentReplacer\Converters;

use Enflow\DocumentReplacer\Exceptions\ConversionFailed;
use Symfony\Component\Process\Process;

class UnoconvConverter extends AbstractConverter
{
    public string $binary = '/usr/bin/unoconv';

    public function convert(string $input, string $output): void
    {
        $command =
            $this->binary . ' ' .
            '--format pdf ' .
            '--output "' . $output . '" ' . $input;

        $process = Process::fromShellCommandline($command);
        $process->setTimeout(20);
        $process->mustRun();

        // unoconv 0.7 always appends extension to --output filename https://github.com/unoconv/unoconv/issues/307
        if (! pathinfo($output, PATHINFO_EXTENSION) && file_exists($output . '.pdf')) {
            rename($output . '.pdf', $output);
        }

        if (! file_exists($output)) {
            throw new ConversionFailed("Unable to convert document to PDF through unoconv");
        }
    }
}
