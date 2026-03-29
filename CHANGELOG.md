# Changelog

All notable changes to `banners-cli` will be documented in this file.

## v1.2.0 - 2026-03-29

### What's Changed

#### New Features

- **Image resize support**: Added `--width` and `--height` options to resize generated banners locally via PHP GD extension
- Useful for filamentphp.com plugin submissions requiring 2560x1440 JPEG format
- Both options are also available as persistent config keys via `config:set`

#### Example

```bash
banners banner:generate "My Plugin" art/banner.jpeg --fileType=jpeg --width=2560 --height=1440

```
**Full Changelog**: https://github.com/jeffersongoncalves/banners-cli/compare/v1.1.2...v1.2.0

## v1.1.2 - 2026-03-02

### What's Changed

- **Build workflow fix**: Fixed race condition between build and changelog workflows by stashing build output before pulling latest changes
- **CHANGELOG.md**: Added initial changelog file for automated release updates

## v1.1.1 - 2026-03-02

### What's Changed

- **Fix flaky tests**: Use `uniqid` with entropy and suppress `mkdir` errors to prevent race conditions in parallel test execution

## Unreleased
