<?php

declare(strict_types=1);

namespace Mbolli\TempestHighlightRon\Patterns;

use Mbolli\Ron\Value\RonTokenKind;
use Mbolli\TempestHighlightRon\Tokenization\RonTokenization;
use Tempest\Highlight\Pattern;
use Tempest\Highlight\Tokens\TokenTypeEnum;

/**
 * The bare keyword literals true, false, and null in value position. A quoted
 * 'true' or a key named true is reported as a string/key by the tokenizer instead.
 */
final class RonLiteralPattern implements Pattern {
    /**
     * @return array{match: list<array{0: string, 1: int}>}
     */
    public function match(string $content): array {
        return RonTokenization::spansFor($content, RonTokenKind::Bool, RonTokenKind::Null);
    }

    public function getTokenType(): TokenTypeEnum {
        return TokenTypeEnum::LITERAL;
    }
}
