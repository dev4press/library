# AI Instructions for Dev4Press Library

## Project Scope
- This repository is a shared code library, not a standalone end-user plugin.
- The code is consumed by other WordPress plugins through Composer.
- Treat this repository as a dependency that must remain stable and backward-compatible.

## Versioned Namespace Convention
- The library uses versioned namespaces tied to the library major/minor version.
- Each release line uses a namespace like `Dev4Press\v56`, `Dev4Press\v55`, `Dev4Press\v54`, and so on.
- The library uses namespacing for most functions and all classes.
- Keep namespaced code consistent with the existing library structure.
- Do not introduce new global functions or global classes unless there is a very specific compatibility reason.
- Prefer namespaced functions, classes, interfaces, and utility objects for new code.
- When extending existing code, preserve the current namespace layout and avoid mixing versioned and non-versioned naming styles.

## PHP Guidance
- Target PHP 8.0 or newer.
- Avoid PHP 8.1+ only features unless explicitly approved.
- Use modern PHP 8.0-compatible syntax.
- Prefer type declarations where safe.
- Avoid deprecated WordPress/PHP patterns.
- Keep compatibility with the WordPress runtime and plugin ecosystem.
- Respect the library's versioned namespace structure when writing or updating code.

## WordPress Guidance
- Target WordPress 6.2 or newer, including the latest stable release.
- Use WordPress APIs whenever possible.
- Prefer hooks and filters over direct edits to core behavior.
- Do not edit WordPress core files unless explicitly requested.
- Keep plugin/theme interoperability in mind.
- Follow WordPress conventions and coding practices.
- Be careful with globals, database queries, and output escaping.

## Development Rules
- Preserve public APIs, class names, namespaces, and file layout unless a change is explicitly required.
- Prefer backward-compatible changes over refactors that may break dependent plugins.
- Do not introduce assumptions that the library is activated as a standalone plugin.
- Keep changes minimal and focused.
- Maintain Composer/autoload compatibility.

## Library Design
- This library provides shared interfaces, core objects, and styling utilities.
- Shared code should be reusable across multiple plugins.
- Avoid plugin-specific behavior unless it is clearly intended for the library itself.

## Code Quality
- Prefer clear, maintainable code.
- Follow existing library conventions.
- Avoid unnecessary abstraction.