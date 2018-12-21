# AsisTeam > MVCR document validator

[![Build Status](https://img.shields.io/travis/com/AsisTeam/mvcr-document-validator.svg?style=flat-square)](https://travis-ci.com/AsisTeam/mvcr-document-validator)
[![Licence](https://img.shields.io/packagist/l/AsisTeam/mvcr-document-validator.svg?style=flat-square)](https://packagist.org/packages/AsisTeam/mvcr-document-validator)
[![Downloads this Month](https://img.shields.io/packagist/dm/AsisTeam/mvcr-document-validator.svg?style=flat-square)](https://packagist.org/packages/AsisTeam/mvcr-document-validator)
[![Downloads total](https://img.shields.io/packagist/dt/AsisTeam/mvcr-document-validator.svg?style=flat-square)](https://packagist.org/packages/AsisTeam/mvcr-document-validator)
[![Latest stable](https://img.shields.io/packagist/v/AsisTeam/mvcr-document-validator.svg?style=flat-square)](https://packagist.org/packages/AsisTeam/mvcr-document-validator)
[![PHPStan](https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat)](https://github.com/phpstan/phpstan)

## Credits

The development is under [AsisTeam s.r.o.](https://www.asisteam.cz/).
Feel free to use and contribute.

<img src="https://www.asisteam.cz/img/logo.svg" width="200" alt="Asisteam" title="Asisteam"/>

## Install

```
composer require asisteam/mvcr-document-validator
```

## Versions

| State       | Version | Branch   | PHP      |
|-------------|---------|----------|----------|
| development | `^0.1`  | `master` | `>= 7.1` |
| production  | `^1.0`  | `master` | `>= 7.1` |

## Overview

This package communicates with MVČR API and check if given document is found amomg registered invalid documents.
Create `Validator` client instance and call it's `validate` method passing the given `Document` entity.

Following document types can be validated:
  - Czech personal id cards
  - Czech passports (issued centrally or regionally)
  - Czech gun licenses
  
Original MVČR documentation to be found at: https://www.mvcr.cz/clanek/neplatne-doklady-ve-formatu-xml.aspx
  
## Usage

Juc create `Document` object and `Validator` instance and pass the `Document` to `Validator's` method `validate`, which returns `ValidatorResult` object.
Using `ValidatorResult` you can verify whether the given document is marked as invalid in MVČR registries or not.
Furthermore you may get the information when the document was added to the registries and when the registries themselves were last updated.

Please use `DocumentType` enum for specifying the document type.

```php

$document = new Document('123456AB', DocumentType::PERSONAL_ID_CARD);
$response = (new Validator())->validate($document);

// true if given document was found in registry of invalid documents
$response->isInvalid();
``` 
Or you can configure it as Nette Framework DI service
```neon
extensions:
	mvcr.doc_validator: AsisTeam\MVCR\DocumentValidator\Bridges\Nette\DI\ValidatorExtension
	
mvcr.doc_validator:
	timeout: 5

```

If any problem during doing the Request to API or parsing the response the `ResponseException` in being thrown.

