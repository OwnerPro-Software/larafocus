# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Changed
- `Prestador`, `Tomador` and `Intermediario` accept the alphanumeric CNPJ issued by Receita Federal since 2026-07-31: 14 positions, the first 12 digits or uppercase letters, the last 2 digits (`/^[0-9A-Z]{12}\d{2}$/`). They used to throw `InvalidDtoException` for any CNPJ with letters, before the request reached Focus. Numeric CNPJs are unaffected; masked or lowercase values are still rejected.

### Known limitations
- Whether the Focus API accepts an alphanumeric CNPJ has not been confirmed with Focus. No public statement was found, and the `openapi/` copies still describe the field as numeric only. Pending confirmation for `POST /nfse` (prestador, tomador, intermediário), `/empresas` and `/hooks`, and for the form in which Focus returns the CNPJ in responses and webhooks (`cnpj_prestador`: masked or not, letter case).

## [0.2.1] - 2026-10-15

### Changed
- Package renamed from `jonathanpmartins/larafocus` to `ownerpro/larafocus`, and the repository moved to `https://github.com/OwnerPro-Software/larafocus`. Consumers must update the `require` key and the `repositories` URL. The `Larafocus\` namespace and the public API are unchanged.

## [0.2.0] - 2026-09-15

### Removed
- **Breaking:** Laravel 11 support. Every `laravel/framework` 11.x release carries unpatched security advisories, so Composer refuses to install it by default and the CI matrix could no longer exercise it. `illuminate/http` and `illuminate/validation` now require `^12.0|^13.0`.

### Changed
- **Breaking:** `EmpresaRequest::$inscricao_estadual`, `$inscricao_municipal`, `$numero` and `$cep` are now `?string` instead of `?int`. Focus stores these fields verbatim, so the `int` cast dropped leading zeros (CEP `01412000` became `1412000`, IM `00054648` became `54648`), truncated at hyphens (IM `7469103-1` became `7469103`) and rejected non-numeric street numbers (`SN`, `APT 310`). Arrays passed to `fromArray()`, `create()` or `update()` may still carry integers; they are converted to strings. Code that builds the DTO through the constructor must now pass strings, and reading these properties yields a string.

## [0.1.0] - 2026-03-11

### Added
- NFSe creation, cancellation, email sending, and querying
- Companies management (create, update, list, get)
- Webhook registration, listing, and deletion
- City and service search endpoints
- Typed DTOs for all API requests and responses
- `FocusResponse` DTO for structured API responses with error handling
- `Environment` and `ContentType` enums
- Immutable `FocusManager` with fluent configuration (`config()`, `using()`)
- HTTP macros: `Http::focus()`, `Http::focusXml()`, `Http::focusPdf()`
- Sandbox environment as default to prevent accidental production calls
- Basic Auth per FocusNFe API specification
- Publishable configuration file
- OpenAPI specs for NFSe, Companies, Webhooks, and Municipalities
- 100% code coverage, mutation testing, and type coverage
- PHPStan level 9, Psalm taint analysis, Rector, and Pint integration
