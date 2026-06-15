<?php

declare(strict_types=1);

namespace Mbolli\TempestHighlightRon\Patterns;

use Mbolli\Ron\Value\RonTokenKind;
use Mbolli\TempestHighlightRon\Tokenization\RonTokenization;
use Tempest\Highlight\Pattern;
use Tempest\Highlight\Tokens\TokenTypeEnum;

/**
 * Structural delimiters: { } [ ]. Coloured like JSON braces/brackets.
 */
final class RonPunctuationPattern implements Pattern {
    /**
     * @return array{match: list<array{0: string, 1: int}>}
     */
    public function match(string $content): array {
        return RonTokenization::spansFor($content, RonTokenKind::Punctuation);
    }

    public function getTokenType(): TokenTypeEnum {
        return TokenTypeEnum::PROPERTY;
    }
}
