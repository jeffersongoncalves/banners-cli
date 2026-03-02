# Changelog

All notable changes to `banners-cli` will be documented in this file.

## v1.1.2 - 2026-03-02

### What's Changed

- **Build workflow fix**: Fixed race condition between build and changelog workflows by stashing build output before pulling latest changes
- **CHANGELOG.md**: Added initial changelog file for automated release updates

## v1.1.1 - 2026-03-02

### What's Changed

- **Fix flaky tests**: Use `uniqid` with entropy and suppress `mkdir` errors to prevent race conditions in parallel test execution

## Unreleased
