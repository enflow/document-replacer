<?php

namespace Enflow\DocumentReplacer\Converters;

use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

class UnoserverConverter extends AbstractConverter
{
    private const BINARY = '/usr/local/bin/unoconvert';

    public function convert(string $input, string $output): void
    {
        $binary = $this->options['binary'] ?? (new ExecutableFinder())->find('unoconvert') ?? static::BINARY;

        // We're using pipes directly to stdin/stdout, as the file reading in unoconvert has issue with Ceph based filesystems.
        // Error on file input: "type detection failed"
        // Error on file output: "failed: 0x507(Error Area:Io Class:Access Code:7)"

        $process = Process::fromShellCommandline(implode(' ', [
            $binary,
            '--convert-to pdf',
            '--interface ' . escapeshellarg($this->options['interface'] ?? '127.0.0.1'),
            '--port ' . escapeshellarg($this->options['port'] ?? '2002'),
            '-', // stdin
            '-', // stdout
            '< ' . escapeshellarg($input),
            '> ' . escapeshellarg($output),
        ]));
        $process->setInput($input);
        $process->setTimeout(20);
        $process->mustRun();
    }
}
