# Changelog

All notable changes to this project will be documented in this file.

## [1.2.6] - 2026-09-08

### Bug Fixes

- **deps:** Update guzzlehttp/guzzle to patch security advisories
- **ci:** Publish release as draft until PHAR asset is attached

### CI/CD

- Pin actions to commit SHA, add dependabot cooldown/composer, trim dist archive
- **release:** Generate CHANGELOG.md and release notes with git-cliff

### Dependencies

- **deps:** Bump actions/checkout from 6.1.0 to 7.0.1
- **deps:** Bump shivammathur/setup-php
- **deps:** Bump actions/cache from 5.1.0 to 6.1.0
- **deps:** Bump orhun/git-cliff-action from 4.8.0 to 4.9.0

### Documentation

- Add Buy Me a Coffee sponsor link

### Miscellaneous Tasks

- Bump guzzlehttp/guzzle and guzzlehttp/psr7 for security advisories
- Add GitHub Sponsors to FUNDING.yml

## [1.2.5] - 2026-07-24

### CI/CD

- Replace split build/changelog/publish-phar workflows with a single release job

### Refactor

- Use jeffersongoncalves/laravel-zero-self-update package
- Consume shared laravel-zero-* packages

## [1.2.4] - 2026-06-06

### CI/CD

- **release:** Use version.txt as the single source of truth for the version

### Miscellaneous Tasks

- Refresh portfolio banner
- Bump version to v1.2.4

### Other

- Chain builds after Update Changelog + fix release-tagged rebuild

On the release path, three workflows fan out in parallel: publish-phar,
Update Changelog, and builds. Update Changelog force-pushes CHANGELOG
and version.txt, which raced with builds and caused non-fast-forward
rejections. Worse, the tag created by the release stayed on the commit
that existed before the PHAR was rebuilt, so `composer require` would
pull a PHAR with the previous version baked in.

This rewires build.yml to:

- Run via workflow_run after Update Changelog completes successfully,
  eliminating the race. Regular push on main still triggers.
- Pin ref and commit branch to main on workflow_run invocations
  (github.event.workflow_run.head_branch resolves to the tag name for
  release events and would land the commit on a detached HEAD / fail
  to push).
- Resolve the build version from workflow_run.head_branch when running
  under workflow_run. `git describe --tags --abbrev=0` is unreliable
  once the pre-release tag and current release tag share a commit.
- After the rebuild commit lands, move the release tag to that commit
  so Packagist (and direct git installs) serve the PHAR whose embedded
  version matches the tag.

Validated end-to-end in the git-worktree-cli sibling repo.

Co-Authored-By: Claude Opus 4.6 (1M context) <noreply@anthropic.com>
- Delete .github/workflows/dependabot-auto-merge.yml

## [1.2.3] - 2026-04-12

### Other

- Serialize build workflow runs and rebase before push to avoid race with changelog

Co-Authored-By: Claude Opus 4.6 (1M context) <noreply@anthropic.com>

## [1.2.2] - 2026-04-12

### Other

- Fix styling

Co-Authored-By: Claude Opus 4.6 (1M context) <noreply@anthropic.com>
- Rebuild PHAR on release to reflect latest tag version

Co-Authored-By: Claude Opus 4.6 (1M context) <noreply@anthropic.com>
- Remove phpstan workflow (not installed in banners-cli)

Co-Authored-By: Claude Opus 4.6 (1M context) <noreply@anthropic.com>

## [1.2.1] - 2026-03-30

### Other

- Standardize GitHub workflows: update actions, add missing workflows (phpstan, pint, dependabot)
- Standardize .gitignore: add .claude/settings.local.json, .phpunit.cache, .env

## [1.2.0] - 2026-03-29

### Features

- Add --width and --height options for image resizing via GD

## [1.1.2] - 2026-03-02

### Bug Fixes

- Add git pull --rebase before auto-commit in build workflow
- Stash build output before pulling in build workflow

### Documentation

- Add initial CHANGELOG.md for automated release updates

## [1.1.1] - 2026-03-02

### Bug Fixes

- Prevent flaky tests by using uniqid with entropy and suppressing mkdir errors

## [1.1.0] - 2026-03-02

### Bug Fixes

- Ensure build workflow fetches tags for version detection

### Documentation

- Update README to match current config:init wizard and composer scripts
- Add MIT license

### Features

- Add self-update command for PHAR auto-update via GitHub releases

### Other

- Add project banner and update README with logo

## [1.0.1] - 2026-02-24

### Bug Fixes

- Remove unused imports in GenerateCommand (pint no_unused_imports)

### Miscellaneous Tasks

- Sync composer description and keywords with GitHub repo

### Refactor

- Remove runtime params from config:init wizard
- Remove packageManager, packageName, description from config:init

## [1.0.0] - 2026-02-24

### Documentation

- Update README with project documentation, badges and usage examples

### Features

- Initial Banners CLI project with Laravel Zero

### Miscellaneous Tasks

- Add GitHub Actions workflows and align composer/box config with filakit-cli


