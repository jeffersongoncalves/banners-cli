<div class="filament-hidden">

![Banners CLI](https://raw.githubusercontent.com/jeffersongoncalves/banners-cli/main/art/jeffersongoncalves-banners-cli.png)

</div>

# Banners CLI

<p align="center">
  <a href="https://github.com/jeffersongoncalves/banners-cli/actions/workflows/run-tests.yml"><img src="https://github.com/jeffersongoncalves/banners-cli/actions/workflows/run-tests.yml/badge.svg" alt="Tests" /></a>
  <a href="https://github.com/jeffersongoncalves/banners-cli/actions/workflows/build.yml"><img src="https://github.com/jeffersongoncalves/banners-cli/actions/workflows/build.yml/badge.svg" alt="Build" /></a>
  <a href="https://github.com/jeffersongoncalves/banners-cli/releases/latest"><img src="https://img.shields.io/github/v/release/jeffersongoncalves/banners-cli" alt="Latest Release" /></a>
  <img src="https://img.shields.io/badge/php-%3E%3D8.2-8892BF" alt="PHP 8.2+" />
  <a href="LICENSE"><img src="https://img.shields.io/github/license/jeffersongoncalves/banners-cli" alt="License" /></a>
</p>

CLI tool to generate banner images using the [beyondcode/banners](https://banners.beyondco.de/) service. Configure default parameters and generate banners with a single command.

## Requirements

- PHP >= 8.2

## Installation

### Download PHAR (recommended)

Download the latest `banners.phar` from the [Releases](https://github.com/jeffersongoncalves/banners-cli/releases) page:

```bash
# Download and make executable
curl -sL https://github.com/jeffersongoncalves/banners-cli/releases/latest/download/banners.phar -o banners
chmod +x banners
sudo mv banners /usr/local/bin/banners
```

### Via Composer (global)

```bash
composer global require jeffersongoncalves/banners-cli
```

## Usage

### Generate a banner

```bash
banners banner:generate "My Project" ./banner.png
```

### With options

```bash
banners banner:generate "My Project" ./banner.png \
  --theme=dark \
  --style=style_2 \
  --pattern=texture \
  --fontSize=72px \
  --packageManager="composer require" \
  --packageName="vendor/package" \
  --description="A great PHP package" \
  --md \
  --showWatermark \
  --fileType=png
```

### Available options

| Option | Values | Default |
|--------|--------|---------|
| `--theme` | `light`, `dark` | `light` |
| `--style` | `style_1`, `style_2` | `style_1` |
| `--pattern` | 90+ hero-patterns (e.g. `texture`, `topography`, `circuitBoard`) | `circuitBoard` |
| `--fontSize` | CSS size (e.g. `96px`, `72px`) | `96px` |
| `--packageManager` | Any text | _(empty)_ |
| `--packageName` | Any text | _(empty)_ |
| `--description` | Any text | _(empty)_ |
| `--md` | Flag (enable markdown) | `false` |
| `--showWatermark` | Flag | `false` |
| `--images` | Image URL or heroicon name | _(empty)_ |
| `--fileType` | `png`, `jpeg` | `png` |

## Configuration

Save default values so you don't have to pass them every time.

### Interactive wizard

```bash
banners config:init
```

The wizard configures: theme, style, pattern, fontSize, markdown rendering, watermark, and file type.

### Set individual values

```bash
banners config:set theme dark
banners config:set pattern texture
banners config:set fontSize 72px
banners config:set md true
banners config:set showWatermark true
```

### View current config

```bash
banners config:show
```

Configuration is stored in `~/.banners-cli/config.json`. Command-line options always override config values.

## Development

```bash
# Clone
git clone git@github.com:jeffersongoncalves/banners-cli.git
cd banners-cli

# Install dependencies
composer install

# Run tests
composer test

# Run code formatting
composer lint

# Build PHAR
composer build
```

## License

Banners CLI is open-source software licensed under the [MIT license](LICENSE).
