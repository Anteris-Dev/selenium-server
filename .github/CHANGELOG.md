# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [v1.1.0] - 2020-10-12

### Changed
- Server is now capable of being run programatically through the Server class.
- Console commands now use the Server class to run.

## [v1.0.0] - 2020-09-16

### Added
- Automatic download of Gecko and Chrome binaries upon install so that they don't slow down install time
- Check for binaries on run of serve and download if they do not exist

### Changed
- Namespace from `Anteris\Selenium` to `Anteris\Selenium\Server`

### Removed
- Selenium binaries from `/bin`

## [v0.1.0] - 2020-09-15

### Added
- Console command allowing users to start a local Selenium server.

[v1.1.0]: https://github.com/Anteris-Dev/selenium-server/compare/v1.0.0...v1.1.0
[v1.0.0]: https://github.com/Anteris-Dev/selenium-server/compare/v0.1.0...v1.0.0
[v0.1.0]: https://github.com/Anteris-Dev/selenium-server/releases/tag/v0.1.0
