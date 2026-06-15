<?php

declare(strict_types=1);

namespace Mbolli\TempestHighlightRon\Patterns;

use Mbolli\Ron\Value\RonTokenKind;
use Mbolli\TempestHighlightRon\Tokenization\RonTokenization;
use Tempest\Highlight\Pattern;
use Tempest\Highlight\Tokens\TokenTypeEnum;

/**
 * Object keys (bare, quoted, or comma-prefixed), in both brace-elided and braced
 * objects. The tokenizer reports key position, so these never collide with values.
 */
final class RonKeyPattern implements Pattern {
    /**
     * @return array{match: list<array{0: string, 1: int}>}
     */
    public function match(string $content): array {
        return RonTokenization::spansFor($content, RonTokenKind::Key);
    }

    public function getTokenType(): TokenTypeEnum {
        return TokenTypeEnum::KEYWORD;
    }
}
