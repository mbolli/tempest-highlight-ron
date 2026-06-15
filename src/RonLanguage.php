<?php

declare(strict_types=1);

namespace Mbolli\TempestHighlightRon;

use Mbolli\TempestHighlightRon\Patterns\RonKeyPattern;
use Mbolli\TempestHighlightRon\Patterns\RonLiteralPattern;
use Mbolli\TempestHighlightRon\Patterns\RonNumberPattern;
use Mbolli\TempestHighlightRon\Patterns\RonPunctuationPattern;
use Mbolli\TempestHighlightRon\Patterns\RonStringPattern;
use Tempest\Highlight\Languages\Base\BaseLanguage;

/**
 * RON (Readable Object Notation) language for tempest/highlight.
 *
 * Registered as "ron", so markdown ```ron fences and explicit
 * `$highlighter->parse($ron, 'ron')` calls are highlighted. Tokenizing is delegated
 * to the real RON parser (mbolli/php-ron) via {@see Patterns}, so keys, values,
 * numbers, literals, and structure are classified exactly rather than approximated
 * with regex.
 */
class RonLanguage extends BaseLanguage {
    public function getName(): string {
        return 'ron';
    }

    #[\Override]
    public function getInjections(): array {
        return [
            ...parent::getInjections(),
        ];
    }

    #[\Override]
    public function getPatterns(): array {
        return [
            ...parent::getPatterns(),
            new RonPunctuationPattern(),
            new RonKeyPattern(),
            new RonStringPattern(),
            new RonNumberPattern(),
            new RonLiteralPattern(),
        ];
    }
}
