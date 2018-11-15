# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog][keepachangelog] and this project adheres to [Semantic Versioning][semver].

## v2.4.1 - 2018-11-14

### Changed

- Return full message of error in exception [#11]

## [v2.4.0] - 2018-08-15

### Added

- Options array for `->user()->report()->make()` method
- Options array for `->user()->report()->refresh()` method

## [v2.3.0] - 2018-06-27

### Added

- `is_force` flag to `->user()->report()->make()` method [#6]

### Changed

- Issues & PR templates

### Fixed

- Different `process_request_uid` and uid in `->user()->report()->refresh()` method [#7]

## [v2.2.0] - 2018-06-10

### Changed

- CI config updated
- Minimal PHPUnit version up to `5.7.10`
- Source code a little bit refactored
- Unimportant PHPDoc blocks removed

## [v2.1.5] - 2018-05-31

### Fixes

- API backward compatible fix with passing empty data (for example - on calling `_refresh` method)

[v2.4.0]:https://github.com/avto-dev/b2b-api-php/compare/v2.3.0...v2.4.0
[v2.3.0]:https://github.com/avto-dev/b2b-api-php/compare/v2.2.0...v2.3.0
[v2.2.0]:https://github.com/avto-dev/b2b-api-php/compare/v2.1.5...v2.2.0
[v2.1.5]:https://github.com/avto-dev/b2b-api-php/compare/v2.1.4...v2.1.5

[#11]:https://github.com/avto-dev/b2b-api-php/issues/11
[#7]:https://github.com/avto-dev/b2b-api-php/issues/7
[#6]:https://github.com/avto-dev/b2b-api-php/issues/6

[keepachangelog]:https://keepachangelog.com/en/1.0.0/
[semver]:https://semver.org/spec/v2.0.0.html
