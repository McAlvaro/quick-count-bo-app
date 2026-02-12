# AGENTS.md for quick-count-bo-app

Build/Lint/Test
- Local install: `composer install --no-interaction --prefer-dist` and `npm install`
- Frontend build: `npm run build` (use `npm run dev` for local dev)
- PHP lint/format: `vendor/bin/pint`
- Full PHP tests: `./vendor/bin/phpunit`
- Single class: `./vendor/bin/phpunit --filter 'SomeTest'`
- Single test: `./vendor/bin/phpunit --filter 'SomeTest::test_name'`
- Laravel tests (alternative): `php artisan test --filter 'SomeTest'`

Code Style
- PSR-12; `declare(strict_types=1);` in files
- Imports: std lib, framework, app; groups sorted alphabetically
- Formatting: 4 spaces, max 120 chars
- Types/Naming: explicit types; ClassName PascalCase; methods/cvars camelCase
- Errors: throw specific exceptions; avoid leaking data; use try/catch for IO
- Docs/Tests: PHPDoc on public methods; data providers when useful
- Consistency: no trailing whitespace; run Pint before commit
- Cursor/Copilot: no rules found; will incorporate if added later