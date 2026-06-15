<?php

declare(strict_types=1);

namespace Mbolli\TempestHighlightRon\Patterns;

use Mbolli\Ron\Value\RonTokenKind;
use Mbolli\TempestHighlightRon\Tokenization\RonTokenization;
use Tempest\Highlight\Pattern;
use Tempest\Highlight\Tokens\TokenTypeEnum;

/**
 * Numeric values (JSON number grammar). Bare tokens in key position never reach here:
 * the tokenizer only classifies a token as a number when it sits in value position.
 */
final class RonNumberPattern implements Pattern {
    /**
     * @return array{match: list<array{0: string, 1: int}>}
     */
    public function match(string $content): array {
        return RonTokenization::spansFor($content, RonTokenKind::Number);
    }

    public function getTokenType(): TokenTypeEnum {
        return TokenTypeEnum::NUMBER;
    }
}
