# Modify docx templates and convert them to PDF

[![Latest Version on Packagist](https://img.shields.io/packagist/v/enflow/document-replacer.svg?style=flat-square)](https://packagist.org/packages/enflow/document-replacer)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/enflow-nl/document-replacer/master.svg?style=flat-square)](https://travis-ci.org/spatie/document-replacer)
[![Total Downloads](https://img.shields.io/packagist/dt/enflow/document-replacer.svg?style=flat-square)](https://packagist.org/packages/enflow/document-replacer)

The `enflow/document-replacer` package provides a easy way to modify docx templates, replace text and save it. Adds the ability to export it to PDF trough unoconv.

## Installation
You can install the package via composer:

``` bash
composer require enflow/document-replacer
```

## PDF conversion
This package comes with an implementation to convert docx templates to PDF using unoconv. You can install this on your machine globally using:
```
sudo add-apt-repository ppa:libreoffice/ppa
apt-get install unoconv
```

### Issue
- unoconv process hangs: check for hanging openoffice processes: `pgrep -l 'office|writer|calc'`. `kill -9 [PID]` the hanging process. Happened in `libreoffice --version` 5, running on 6.3 now to see if it's still a common issue

## Usage
``` php
use Enflow\DocumentReplacer\DocumentReplacer;

DocumentReplacer::template('filename.docx')
    ->converter(UnoconvConverter::class)
    ->replace([
        '${user}' => 'Michel',
        '${address.city}' => 'Alphen aan den Rijn',
        '${company}' => 'Enflow',
    ])
    ->save('document.pdf');
```

#### Images
If you wish to replace images in your document, you can pass the `Image` class to the replacement array like this:
```
use Enflow\DocumentReplacer\DocumentReplacer;
use Enflow\DocumentReplacer\ValueTypes\Image;

DocumentReplacer::template('filename.docx')
    ->converter(UnoconvConverter::class)
    ->replace([
        '${primary}' => Image::forPath('image.png'),
        '${second}' => Image::forBase64('iVBORw0KGgoA...'),
    ])
    ->save('document.pdf');
```

The search-pattern model for images can be like:
- ``${search-image-pattern}``
- ``${search-image-pattern:[width]:[height]:[ratio]}``
- ``${search-image-pattern:[width]x[height]}``
- ``${search-image-pattern:size=[width]x[height]}``
- ``${search-image-pattern:width=[width]:height=[height]:ratio=false}``
Where:
- [width] and [height] can be just numbers or numbers with measure, which supported by Word (cm|mm|in|pt|pc|px|%|em|ex)
- [ratio] uses only for ``false``, ``-`` or ``f`` to turn off respect aspect ration of image. By default template image size uses as 'container' size.

More info can be found in the [`PHPWord` documentation](https://github.com/PHPOffice/PHPWord/blob/develop/docs/templates-processing.rst#setimagevalue)

## Testing
``` bash
$ composer test
```

## Contributing
Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security
If you discover any security related issues, please email michel@enflow.nl instead of using the issue tracker.

## Credits
- [Michel Bardelmeijer](https://github.com/mbardelmeijer)
- [All Contributors](../../contributors)

## About Enflow
Enflow is a digital creative agency based in Alphen aan den Rijn, Netherlands. We specialize in developing web applications, mobile applications and websites. You can find more info [on our website](https://enflow.nl/en).

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
