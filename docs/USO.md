# Guia de uso

`Uuid` aceita UUID canonico v1 a v8 com variante RFC, alem dos valores especiais Nil e Max definidos pelo RFC 9562.

```php
use Elavora\Api\DataTypes\Uuid;

$uuid = Uuid::from('017F22E2-79B0-7CC3-98C4-DC0C0C07398F');

echo $uuid->value(); // 017f22e2-79b0-7cc3-98c4-dc0c0c07398f
```

Use `Uuid::generate()` para criar um UUID v4 aleatorio. URNs, chaves, valores sem hifens e texto adicional nao sao aceitos.

Para verificar uma entrada sem criar uma instancia:

```php
if (Uuid::isValid($entrada)) {
    $uuid = Uuid::from($entrada);
}
```

## Validacao do pacote

Execute os comandos a partir da raiz do clone:

```bash
docker run --rm -v "${PWD}:/workspace" -w /workspace composer:2 composer update --no-interaction --no-progress --prefer-dist
docker run --rm -v "${PWD}:/workspace" -w /workspace composer:2 composer check
```
