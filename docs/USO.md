# Guia de uso

DataType UUID para aplicacoes Elavora API.

## Instalacao

```bash
composer require elavora/api-datatype-uuid
```

## Quando usar

- Validar e normalizar valores antes de chegar na regra de negocio.
- Evitar passar strings soltas entre services, DTOs e persistencia.
- Reutilizar a mesma validacao em controllers, comandos e testes.

## Exemplo rapido

```php
use Elavora\Api\DataTypes\Uuid;

$novo = Uuid::generate();
$valor = Uuid::from('00000000-0000-4000-8000-000000000000');
$normalizado = $valor->value();
```

## API publica

A classe `Elavora\Api\DataTypes\Uuid` e `final readonly` e estende `Elavora\Api\DataTypes\AbstractDataType`, do
`elavora/api-datatype-core`. O construtor nao e publico: crie o objeto com `from()` ou `generate()`.

| Metodo | Retorno | O que faz |
| --- | --- | --- |
| `Uuid::from(mixed $value)` | `Uuid` | valida, normaliza e cria o objeto; lanca `InvalidArgumentException` se o valor for invalido |
| `Uuid::generate()` | `Uuid` | gera um UUID v4 aleatorio, ja em minusculas |
| `Uuid::isValid(mixed $value)` | `bool` | diz se o valor e um UUID valido; nunca lanca excecao |
| `value()` | `string` | o UUID normalizado |
| `jsonSerialize()` | `string` | o mesmo valor de `value()` |
| `(string) $uuid` | `string` | o mesmo valor de `value()` |

## Regras de validacao e normalizacao

- Valido: texto de 36 caracteres no formato `8-4-4-4-12` de digitos hexadecimais, com hifens, em maiusculas ou
  minusculas.
- O primeiro digito do terceiro grupo (versao) vai de `1` a `5`.
- O primeiro digito do quarto grupo (variante) e `8`, `9`, `a` ou `b`, em qualquer caixa.
- Qualquer valor que nao seja `string` (inteiro, `null`, array, objeto) e invalido.
- Normalizacao: o valor sai em minusculas. Hifens e demais caracteres ficam como entraram.

| Entrada | `isValid` | `from()` |
| --- | --- | --- |
| `'00000000-0000-4000-8000-000000000000'` | `true` | `value()` e `'00000000-0000-4000-8000-000000000000'` |
| `'550E8400-E29B-41D4-A716-446655440000'` | `true` | `value()` e `'550e8400-e29b-41d4-a716-446655440000'` |
| `'6ba7b810-9dad-11d1-80b4-00c04fd430c8'` | `true` | `value()` e `'6ba7b810-9dad-11d1-80b4-00c04fd430c8'` |
| `'00000000-0000-0000-0000-000000000000'` (UUID nulo, versao `0`) | `false` | `InvalidArgumentException` |
| `'550e8400-e29b-61d4-a716-446655440000'` (versao `6`) | `false` | `InvalidArgumentException` |
| `'550e8400-e29b-41d4-c716-446655440000'` (variante `c`) | `false` | `InvalidArgumentException` |
| `'550e8400e29b41d4a716446655440000'` (sem hifens) | `false` | `InvalidArgumentException` |
| `'{550e8400-e29b-41d4-a716-446655440000}'` (com chaves) | `false` | `InvalidArgumentException` |
| `'550e8400-e29b-41d4-a716-44665544000g'` (digito nao hexadecimal) | `false` | `InvalidArgumentException` |
| `'invalid'`, `''` | `false` | `InvalidArgumentException` |
| `123`, `null`, `[]` | `false` | `InvalidArgumentException` |

A mensagem da excecao e `Valor invalido para Elavora\Api\DataTypes\Uuid.`

`Uuid::generate()` sempre devolve um UUID com versao `4` e variante `8`, `9`, `a` ou `b`, e `isValid()` aceita o
resultado.

## Principais pontos de entrada

- `Elavora\Api\DataTypes\Uuid`

## Dependencias de runtime

- PHP 8.3 ou superior
- `elavora/api-datatype-core` `^2.0` (que exige o `elavora/api-framework` `^2.0`)

## Atualizacao da versao anterior

A versao `1.0.0` exige PHP 8.3 ou superior e o `elavora/api-datatype-core` `^2.0`. Antes eram PHP 8.1 e core `^0.1`,
que nao funciona mais com o framework atual. A API publica de `Uuid` nao muda.

## Validacao no projeto consumidor

Depois de instalar o pacote, rode os testes da aplicacao consumidora.

Para validar o pacote isoladamente (testes, lint e PHPStan, e como reproduzir o CI), veja
[Desenvolvimento e testes](DESENVOLVIMENTO.md). A automacao, as versoes e os arquivos de qualidade do repositorio estao em
[Automacao do repositorio](AUTOMACAO.md).

## Observacoes

- Mantenha regras de produto fora deste pacote.
- Prefira configurar extensoes no bootstrap da aplicacao.
- Instale apenas os modulos que a aplicacao realmente usa.
