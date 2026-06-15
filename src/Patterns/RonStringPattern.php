<?php

declare(strict_types=1);

namespace Mbolli\TempestHighlightRon\Patterns;

use Mbolli\Ron\Value\RonTokenKind;
use Mbolli\TempestHighlightRon\Tokenization\RonTokenization;
use Tempest\Highlight\Pattern;
use Tempest\Highlight\Tokens\TokenTypeEnum;

/**
 * String values: bare, quoted, apostrophe, comma-prefixed, and repeated-quote forms.
 * The span covers the verbatim source (quoted strings include their quote runs).
 */
final class RonStringPattern implements Pattern {
    /**
     * @return array{match: list<array{0: string, 1: int}>}
     */
    public function match(string $content): array {
        return RonTokenization::spansFor($content, RonTokenKind::String);
    }

    public function getTokenType(): TokenTypeEnum {
        return TokenTypeEnum::VALUE;
    }
}
