# api-datatype-uuid

[![Packagist Version](https://img.shields.io/packagist/v/elavora/api-datatype-uuid.svg?style=flat-square)](https://packagist.org/packages/elavora/api-datatype-uuid)
[![PHP Version](https://img.shields.io/packagist/php-v/elavora/api-datatype-uuid.svg?style=flat-square)](https://packagist.org/packages/elavora/api-datatype-uuid)
[![Composer Quality](https://github.com/Elavora/api-datatype-uuid/actions/workflows/quality.yml/badge.svg?branch=main)](https://github.com/Elavora/api-datatype-uuid/actions/workflows/quality.yml)
[![CodeQL](https://github.com/Elavora/api-datatype-uuid/actions/workflows/codeql.yml/badge.svg?branch=main)](https://github.com/Elavora/api-datatype-uuid/actions/workflows/codeql.yml)
[![License](https://img.shields.io/packagist/l/elavora/api-datatype-uuid.svg?style=flat-square)](https://packagist.org/packages/elavora/api-datatype-uuid)

DataType imutavel para validar UUIDs canonicos conforme o RFC 9562 e gerar UUID v4.

## Requisitos

- PHP 8.3 ou superior.
- Demais requisitos declarados em [`composer.json`](composer.json).

## Instalacao

```bash
composer require elavora/api-datatype-uuid
```

## Inicio rapido

```php
use Elavora\Api\DataTypes\Uuid;

$valor = Uuid::generate();
$normalizado = $valor->value();
```

`generate()` retorna um UUID v4 com variante RFC. UUIDs recebidos em maiusculas sao normalizados para minusculas.

## Documentacao

Consulte o [guia de uso](docs/USO.md) para as versoes e os formatos aceitos.
