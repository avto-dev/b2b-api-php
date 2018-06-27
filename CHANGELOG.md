# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [v2.x.x]

### Added

- `is_force` flag to `->user()->report()->make()` method [#6](https://github.com/avto-dev/b2b-api-php/issues/6)

### Changed

- Issues & PR templates updated

### Fixed

- [#7](https://github.com/avto-dev/b2b-api-php/issues/7) Different process_request_uid and uid in ->user()->report()->refresh() method

## [v2.2] - 2018-06-10

### Changed

- CI config updated
- Minimal PHPUnit version up to `5.7.10`
- Source code a little bit refactored
- Unimportant PHPDoc blocks removed

## [v2.1.5] - 2018-05-31

### Fixes

- API backward compatible fix with passing empty data (for example - on calling `_refresh` method)

[v2.x.x]: https://github.com/avto-dev/b2b-api-php/compare/v2.2.0...HEAD
[v2.2]: https://github.com/avto-dev/b2b-api-php/compare/v2.1.5...v2.2.0
[v2.1.5]: https://github.com/avto-dev/b2b-api-php/compare/v2.1.4...v2.1.5
