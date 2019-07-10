# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog][keepachangelog] and this project adheres to [Semantic Versioning][semver].

## v2.6.0

### Deprecated

- Package is abandoned and soon will be completely removed. New package is [available here](https://github.com/avtocod/b2b-api-php)

## v2.5.1

### Changed

- Maximal `phpunit` version now is `7.4.x`. Reason - since `7.5.0` frameworks contains assertions like `assertIsString`, `assertIsArray` and others, already declared in `AbstractUnitTestCase`

## v2.5.0

### Changed

- Maximal PHP version now is undefined
- CI changed to [Travis CI][travis]
- [CodeCov][codecov] integrated

[travis]:https://travis-ci.org/
[codecov]:https://codecov.io/

## v2.4.2

### Fixed

- HTTP exception message now contains request body

## v2.4.1

### Fixed

- Return full message of error in exception [#11]

[#11]:https://github.com/avto-dev/b2b-api-php/issues/11

## v2.4.0

### Added

- Options array for `->user()->report()->make()` method
- Options array for `->user()->report()->refresh()` method

## v2.3.0

### Added

- `is_force` flag to `->user()->report()->make()` method [#6]

### Changed

- Issues & PR templates

### Fixed

- Different `process_request_uid` and uid in `->user()->report()->refresh()` method [#7]

[#6]:https://github.com/avto-dev/b2b-api-php/issues/6
[#7]:https://github.com/avto-dev/b2b-api-php/issues/7

## v2.2.0

### Changed

- CI config updated
- Minimal PHPUnit version up to `5.7.10`
- Source code a little bit refactored
- Unimportant PHPDoc blocks removed

## v2.1.5

### Fixes

- API backward compatible fix with passing empty data (for example - on calling `_refresh` method)

[keepachangelog]:https://keepachangelog.com/en/1.0.0/
[semver]:https://semver.org/spec/v2.0.0.html
