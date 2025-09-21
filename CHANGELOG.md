# Changelog

All notable changes to `filament-flexible-content` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.1.0] - 2024-09-22

### Added
- Conditional visibility support with `dependsOn()` method for FlexibleContent component
- Conditional visibility support for individual Layout components
- Support for single and multiple field dependencies
- Reactive behavior integration for dynamic form updates
- Comprehensive test coverage for dependency functionality

### Changed
- Updated Layout `isVisible()` method to accept optional `$get` parameter
- Enhanced Block creation to pass form state to visibility checks

## [1.0.0] - 2024-01-01

### Added
- Initial release
- Flexible content field component for Filament v4
- Layout system with customizable fields
- Preset support for bundling layouts
- Eloquent cast for database integration
- Artisan command for generating layouts and presets
- Full PHP 8.3 support with strict typing
- Comprehensive test suite using Pest
- Example layouts (Hero, Content)
- Configuration file for customization
- Auto-discovery of layouts and presets
- Caching support for improved performance