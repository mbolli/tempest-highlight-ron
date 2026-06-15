<?php

declare(strict_types=1);

namespace Mbolli\TempestHighlightRon\Tests;

use Mbolli\TempestHighlightRon\RonLanguage;
use PHPUnit\Framework\TestCase;
use Tempest\Highlight\Highlighter;
use Tempest\Highlight\Tokens\ParseTokens;
use Tempest\Highlight\Tokens\Token;

final class RonLanguageTest extends TestCase {
    public function testRegistersAsRon(): void {
        self::assertSame('ron', (new RonLanguage())->getName());
    }

    public function testRolesAreClassifiedExactly(): void {
        // Keys (id, name, active) vs values (100, Ada, true) are distinguished even
        // though RON has no key/value separator: the php-ron tokenizer reports position.
        self::assertSame(
            [
                [0, 'property', '{'],
                [1, 'keyword', 'id'],
                [4, 'number', '100'],
                [8, 'keyword', 'name'],
                [13, 'value', 'Ada'],
                [17, 'keyword', 'active'],
                [24, 'literal', 'true'],
                [28, 'property', '}'],
            ],
            $this->tokens('{id 100 name Ada active true}'),
        );
    }

    public function testBraceElidedRootObjectKeysAndValues(): void {
        self::assertSame(
            [
                [0, 'keyword', 'count'],
                [6, 'number', '2'],
                [8, 'keyword', 'status'],
                [15, 'value', 'ok'],
            ],
            $this->tokens("count 2\nstatus ok"),
        );
    }

    public function testArrayElementsAreValuesNotKeys(): void {
        self::assertSame(
            [
                [0, 'property', '['],
                [1, 'value', 'admin'],
                [7, 'value', 'writer'],
                [13, 'property', ']'],
            ],
            $this->tokens('[admin writer]'),
        );
    }

    public function testRepeatedQuoteStringIsASingleValueSpan(): void {
        // Five apostrophes are one string token spanning all five source bytes.
        self::assertSame(
            [
                [0, 'property', '['],
                [2, 'value', "'''''"],
                [8, 'property', ']'],
            ],
            $this->tokens("[ ''''' ]"),
        );
    }

    public function testQuotedTrueIsAStringNotALiteral(): void {
        self::assertSame(
            [
                [0, 'property', '['],
                [2, 'value', "'true'"],
                [9, 'property', ']'],
            ],
            $this->tokens("[ 'true' ]"),
        );
    }

    public function testMultibyteValueSpansBytesNotCharacters(): void {
        // php-ron reports byte offsets; 'å' is two bytes, so a char-based slice would
        // drift and swallow the closing brace. The span must stay byte-exact.
        self::assertSame(
            [
                [0, 'property', '{'],
                [1, 'keyword', 'k'],
                [3, 'value', 'åbc'],
                [7, 'property', '}'],
            ],
            $this->tokens('{k åbc}'),
        );

        $html = (new Highlighter())
            ->addLanguage(new RonLanguage())
            ->parse('{k åbc}', 'ron')
        ;

        self::assertStringContainsString('<span class="hl-value">åbc</span>', $html);
    }

    public function testHighlighterWrapsTokensWithCssClasses(): void {
        $html = (new Highlighter())
            ->addLanguage(new RonLanguage())
            ->parse('active true', 'ron')
        ;

        self::assertStringContainsString('<span class="hl-keyword">active</span>', $html);
        self::assertStringContainsString('<span class="hl-literal">true</span>', $html);
    }

    public function testInvalidInputDoesNotThrow(): void {
        $html = (new Highlighter())
            ->addLanguage(new RonLanguage())
            ->parse('{a 1 ', 'ron')
        ;

        self::assertStringContainsString('a', $html);
    }

    /**
     * Run the language's patterns through tempest's token parser and return
     * [offset, typeValue, value] triples sorted by offset.
     *
     * @return list<array{0: int, 1: string, 2: string}>
     */
    private function tokens(string $ron): array {
        $tokens = (new ParseTokens())->parse($ron, (new RonLanguage())->getPatterns());

        $rows = array_map(
            static fn (Token $t): array => [$t->offset, $t->typeValue, $t->value],
            $tokens,
        );
        usort($rows, static fn (array $a, array $b): int => $a[0] <=> $b[0]);

        return $rows;
    }
}
