<?php

namespace Enflow\DocumentReplacer\Test;

use Enflow\DocumentReplacer\Converters\UnoconvConverter;
use Enflow\DocumentReplacer\DocumentReplacer;
use Enflow\DocumentReplacer\ValueTypes\Image;
use PHPUnit\Framework\TestCase;

class ImageValueTypeTest extends TestCase
{
    public function test_image_create_from_path()
    {
        $this->assertEquals('4e7e0753fd7068d368bbe516f09e321a', Image::forPath(__DIR__ . '/fixtures/test.png')->signature());
    }

    public function test_image_create_from_base64_with_prefix()
    {
        $this->assertEquals('4e7e0753fd7068d368bbe516f09e321a',
            Image::forBase64('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAVCAIAAADJt1n/AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAB8SURBVDhP3YxBDoAgDAR5iEf//zPfgCutCGwp4MXEyZI0W6bh2PaYyMM8Aa/0l05UsjDvGzIoG+fWJQPHfyMDKccy4E9oOLpLeLLQ9OWJRwamz2VXNqPrm1xWMjB/M56coy0hK0PWKdHz5fRABj0f/Efm6I5o5SW+kmM8AS/fakEk7YJkAAAAAElFTkSuQmCC')->signature());
    }

    public function test_image_create_from_base64_without_prefix()
    {
        $this->assertEquals('4e7e0753fd7068d368bbe516f09e321a',
            Image::forBase64('iVBORw0KGgoAAAANSUhEUgAAABQAAAAVCAIAAADJt1n/AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAB8SURBVDhP3YxBDoAgDAR5iEf//zPfgCutCGwp4MXEyZI0W6bh2PaYyMM8Aa/0l05UsjDvGzIoG+fWJQPHfyMDKccy4E9oOLpLeLLQ9OWJRwamz2VXNqPrm1xWMjB/M56coy0hK0PWKdHz5fRABj0f/Efm6I5o5SW+kmM8AS/fakEk7YJkAAAAAElFTkSuQmCC')->signature());
    }

    public function test_image_create_from_binary()
    {
        $this->assertEquals('4e7e0753fd7068d368bbe516f09e321a',
            Image::forBinary(base64_decode('iVBORw0KGgoAAAANSUhEUgAAABQAAAAVCAIAAADJt1n/AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAB8SURBVDhP3YxBDoAgDAR5iEf//zPfgCutCGwp4MXEyZI0W6bh2PaYyMM8Aa/0l05UsjDvGzIoG+fWJQPHfyMDKccy4E9oOLpLeLLQ9OWJRwamz2VXNqPrm1xWMjB/M56coy0hK0PWKdHz5fRABj0f/Efm6I5o5SW+kmM8AS/fakEk7YJkAAAAAElFTkSuQmCC'))->signature());
    }

    public function test_image_replacement_tags()
    {
        $replacments = Image::forPath(__DIR__ . '/fixtures/test.png')->replacements();

        $this->assertNotEmpty($replacments['path']);
        $this->assertEquals('4e7e0753fd7068d368bbe516f09e321a', $replacments['signature']);
        $this->assertEmpty($replacments['width']);
        $this->assertEmpty($replacments['height']);
        $this->assertEmpty($replacments['ratio']);
    }

    public function test_image_setters()
    {
        $replacments = Image::forPath(__DIR__ . '/fixtures/test.png')
            ->width(100)
            ->height(200)
            ->ratio(1.2)
            ->replacements();

        $this->assertEquals(100, $replacments['width']);
        $this->assertEquals(200, $replacments['height']);
        $this->assertEquals(1.2, $replacments['ratio']);
    }
}
