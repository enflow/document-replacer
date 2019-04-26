<?php

namespace Enflow\DocumentReplacer\Converters;

use Symfony\Component\Process\Process;

class UnoconvConverter extends AbstractConverter
{
    public $binary = '/usr/bin/unoconv';

    public function convert(string $input, string $output): void
    {
        $command =
            $this->binary . ' ' .
            '--format pdf ' .
            '--output "' . $output . '" ' . $input;

        $process = Process::fromShellCommandline($command);
        $process->mustRun();

        // https://github.com/unoconv/unoconv/issues/307
        if (!pathinfo($output, PATHINFO_EXTENSION)) {
            rename($output . '.pdf', $output);
        }
    }
}
