<?php

namespace Enflow\DocumentReplacer\ValueTypes;

class Image
{
    private $path;
    private $ratio;
    private $width;
    private $height;

    private function __construct(string $path)
    {
        $this->path = $path;
    }

    public static function forPath(string $path)
    {
        return new static($path);
    }

    public static function forBase64(string $base64data)
    {
        // strip out data uri scheme information (see RFC 2397)
        if (strpos($base64data, ';base64') !== false) {
            [$_, $base64data] = explode(';', $base64data);
            [$_, $base64data] = explode(',', $base64data);
        }

        $binaryData = base64_decode($base64data);

        // temporarily store the decoded data on the filesystem to be able to pass it trough the template replacer
        $tmpFile = tempnam(sys_get_temp_dir(), 'document-replacer');
        file_put_contents($tmpFile, $binaryData);

        return new static($tmpFile);
    }

    public function width(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function height(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function ratio(bool $ratio): self
    {
        $this->ratio = $ratio;

        return $this;
    }

    public function replacements(): array
    {
        return [
            'path' => $this->path,
            'width' => $this->width,
            'height' => $this->height,
            'ratio' => $this->ratio,
        ];
    }
}
