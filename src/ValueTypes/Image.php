<?php

namespace Enflow\DocumentReplacer\ValueTypes;

use Closure;

class Image
{
    private $image;
    private $ratio;
    private $width;
    private $height;

    private function __construct($image)
    {
        $this->image = $image;
    }

    public static function forPath(string $path): self
    {
        return new static($path);
    }

    public static function forBase64(string $base64data): self
    {
        // strip out data uri scheme information (see RFC 2397)
        if (strpos($base64data, ';base64') !== false) {
            [$_, $base64data] = explode(';', $base64data);
            [$_, $base64data] = explode(',', $base64data);
        }

        return static::forBinary(base64_decode($base64data));
    }

    public static function forBinary($binary): self
    {
        // temporarily store the decoded data on the filesystem to be able to pass it trough the template replacer
        $tmpFile = tempnam(sys_get_temp_dir(), 'document-replacer');
        file_put_contents($tmpFile, $binary);

        return new static($tmpFile);
    }

    public static function lazy(Closure $closure)
    {
        return new static($closure);
    }

    public function path(): string
    {
        if (is_callable($this->image)) {
            return (($this->image)())->path();
        }

        return $this->image;
    }

    public function signature(): string
    {
        return md5_file($this->path());
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
            'path' => $this->path(),
            'signature' => $this->signature(),
            'width' => $this->width,
            'height' => $this->height,
            'ratio' => $this->ratio,
        ];
    }
}
