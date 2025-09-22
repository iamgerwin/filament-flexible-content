# Changelog

All notable changes to `filament-flexible-content` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.2.3] - 2025-09-23

### Fixed
- Fixed PHPStan static analysis errors in Layout classes
- Fixed test class property type conflicts with parent Layout class
- Fixed TestCase encryption key configuration for Livewire tests
- Updated GitHub Actions release workflow to use maintained action

### Changed
- Test layout classes now set properties in setUp() method instead of declarations
- Improved test structure for better compatibility with Filament forms

## [1.2.2] - 2024-09-22

### Added
- Packagist setup documentation for automatic updates
- GitHub Actions workflow for automated release creation
- Comprehensive guide for webhook configuration

### Improved
- Documentation structure and organization
- Release process automation

## [1.2.1] - 2024-09-22

### Fixed
- Fixed clone button not respecting maxLayouts limit
- Fixed add button not respecting maxLayouts limit when maximum is reached
- Clone and add buttons now properly disable when layout count reaches the maximum

### Added
- Tests for maxLayouts enforcement with cloning and adding

## [1.2.0] - 2024-09-22

### Added
- Laravel 10, 11, and 12 support
- Filament v3.2 support
- Improved reactive behavior for dependsOn functionality
- Automatic live() behavior for dependent fields
- Tests for reactive dependency updates

### Changed
- Broadened Laravel version constraint to ^10.0|^11.0|^12.0
- Broadened Filament version constraint to ^3.2 || ^4.0

### Fixed
- Fixed dependsOn not updating when dependent field values change
- Improved real-time visibility updates for dependent components

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