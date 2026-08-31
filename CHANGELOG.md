# Changelog

All notable changes to this project are documented here. The format is based on
[Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and this project adheres to
[Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.3] - 2026-08-31

### Changed
- Allow `mbolli/php-ron` `^0.4` and `^0.5` alongside `^0.3`. The `Ron::tokenize()` API and the
  `RonToken`/`RonTokenKind` shapes are unchanged across all three, so this is a constraint
  widening rather than a behaviour change — and the range means a future php-ron 0.x no longer
  needs a release here just to unblock consumers.

### Fixed
- Highlighting of php-ron 0.5 sources. Its tokenizer treats a JSON escape as one scanner atom
  and classifies before decoding, so `a\nb` and `,foo` each stay a single string span and
  `tr\u0075e` highlights as a string rather than the boolean literal. Covered by a new test.

## [1.0.2] - 2026-06-15

### Changed
- Require `mbolli/php-ron` `^0.3.0`. The `Ron::tokenize()` API and the
  `RonToken`/`RonTokenKind` shapes are unchanged, so highlighting behaviour is
  identical; this only widens the constraint to the current php-ron release.

## [1.0.1] - 2026-06-15

### Changed
- Require the published `mbolli/php-ron` `^0.1.1` from Packagist and drop the
  local path-repository override used during development.

## [1.0.0] - 2026-06-15

### Added
- Initial release: `RonLanguage` for tempest/highlight, registered as `ron`.
- Parser-backed, role-aware highlighting via `mbolli/php-ron`'s `Ron::tokenize()`:
  punctuation, keys, string values, numbers, and `true`/`false`/`null` literals.
- Memoized tokenization bridge so the source is lexed once per parse pass.
