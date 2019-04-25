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
apt-get install unoconv
```

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
