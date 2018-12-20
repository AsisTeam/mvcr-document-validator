# AsisTeam > MVCR document validator

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

## Overview

This package communicates with MVČR API and check if given document is found amomg registered invalid documents.
Create `Validator` client instance and call it's `validate` method passing the given `Document` entity.

Document to be validated can be:
  - Czech personal id cards
  - Czech passports
  - Czech gun licenses
  
## Usage

```php

$document = new Document('123456AB', DocumentType::PERSONAL_ID_CARD);
$validator = new Validator('AsisTeam checker');
$response = $validator->validate($document);

// true if given document was found in registry of invalid documents
$response->isInvalid();
``` 

