# tempest-highlight-ron

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mbolli/tempest-highlight-ron.svg?style=flat-square)](https://packagist.org/packages/mbolli/tempest-highlight-ron)
[![PHP Version](https://img.shields.io/packagist/php-v/mbolli/tempest-highlight-ron.svg?style=flat-square)](https://packagist.org/packages/mbolli/tempest-highlight-ron)
[![Total Downloads](https://img.shields.io/packagist/dt/mbolli/tempest-highlight-ron.svg?style=flat-square)](https://packagist.org/packages/mbolli/tempest-highlight-ron)
[![PHPStan Level 10](https://img.shields.io/badge/PHPStan-level%2010-brightgreen.svg?style=flat-square)](https://phpstan.org/)
[![License](https://img.shields.io/packagist/l/mbolli/tempest-highlight-ron.svg?style=flat-square)](LICENSE)

[RON (Readable Object Notation)](https://github.com/starfederation/ron) language support for
[tempest/highlight](https://github.com/tempestphp/highlight).

Unlike a regex grammar, this plugin tokenizes with the **real RON parser**
([mbolli/php-ron](https://github.com/mbolli/php-ron)), so highlighting is byte-exact and
context-aware: keys are distinguished from values even though RON has no `:`/`=` separator,
and repeated-quote strings (`''''' `, `""a "quoted" phrase""`) are spanned correctly.

## Installation

```bash
composer require mbolli/tempest-highlight-ron
```

## Usage

```php
use Tempest\Highlight\Highlighter;
use Mbolli\TempestHighlightRon\RonLanguage;

$highlighter = new Highlighter();
$highlighter->addLanguage(new RonLanguage());

echo $highlighter->parse($ron, 'ron');
```

Registering the language as `ron` also highlights fenced ` ```ron ` blocks in markdown
rendered through tempest/highlight.

## What gets highlighted

For example RON like:

```ron
users [
  {id 100 name Ada roles [admin writer] active true}
]
settings {retry {max 3} tags [llm json]}
```

| RON construct                                   | Role        | tempest token | CSS class      |
|-------------------------------------------------|-------------|---------------|----------------|
| `{` `}` `[` `]`                                 | punctuation | `PROPERTY`    | `hl-property`  |
| object keys (bare/quoted, elided or braced)     | key         | `KEYWORD`     | `hl-keyword`   |
| string values (bare, quoted, repeated-quote)    | string      | `VALUE`       | `hl-value`     |
| numbers (`100`, `-12.5e+2`)                     | number      | `NUMBER`      | `hl-number`    |
| `true` / `false` / `null`                       | literal     | `LITERAL`     | `hl-literal`   |

Because classification comes from the parser, `'true'` (quoted) is a string, a key named
`true` is a key, and a bare `123` in key position stays a key — none are mis-coloured.

## How it works

`RonLanguage` adds five `Pattern`s on top of tempest's base language. Each delegates to
`Mbolli\Ron\Ron::tokenize()`, which returns role-aware source spans; the spans are handed to
tempest in `PREG_OFFSET_CAPTURE` shape. Tokenization is memoized per content string, so the
source is lexed once per parse regardless of how many patterns run. `Ron::tokenize()` is
lenient — malformed RON never throws, it just highlights as much as it can classify.

## License

MIT
