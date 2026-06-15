# Changelog

All notable changes to this project are documented here. The format is based on
[Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and this project adheres to
[Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2026-06-15

### Added
- Initial release: `RonLanguage` for tempest/highlight, registered as `ron`.
- Parser-backed, role-aware highlighting via `mbolli/php-ron`'s `Ron::tokenize()`:
  punctuation, keys, string values, numbers, and `true`/`false`/`null` literals.
- Memoized tokenization bridge so the source is lexed once per parse pass.
