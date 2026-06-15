<?php

declare(strict_types=1);

namespace Mbolli\TempestHighlightRon\Tokenization;

use Mbolli\Ron\Ron;
use Mbolli\Ron\Value\RonToken;
use Mbolli\Ron\Value\RonTokenKind;
use Tempest\Highlight\Tokens\ParseTokens;

/**
 * Bridges php-ron's role-aware tokenizer into tempest/highlight's Pattern API.
 *
 * tempest runs every Pattern against the same content within one parse pass, so the
 * tokenization is memoized per content string: the source is lexed once by
 * {@see Ron::tokenize()} and each Pattern then filters the cached tokens for the
 * role(s) it represents.
 */
final class RonTokenization {
    private static ?string $content = null;

    /** @var list<RonToken> */
    private static array $tokens = [];

    /**
     * Match payload (PREG_OFFSET_CAPTURE shape) for every token whose kind is one of
     * $kinds, as consumed by {@see ParseTokens}.
     *
     * @return array{match: list<array{0: string, 1: int}>}
     */
    public static function spansFor(string $content, RonTokenKind ...$kinds): array {
        $match = [];
        foreach (self::tokens($content) as $token) {
            if (\in_array($token->kind, $kinds, true)) {
                $match[] = [substr($content, $token->offset, $token->length), $token->offset];
            }
        }

        return ['match' => $match];
    }

    /**
     * @return list<RonToken>
     */
    private static function tokens(string $content): array {
        if (self::$content !== $content) {
            self::$content = $content;
            self::$tokens = Ron::tokenize($content);
        }

        return self::$tokens;
    }
}
