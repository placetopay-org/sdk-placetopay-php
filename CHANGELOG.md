## Changelog

[Unreleased]
- Update the SDK to PHP 8.1, see the upgrading guide in the README for the breaking changes.
- Entities use PHP 8 constructors with promoted properties and a `fromArray()` factory, `LoaderTrait` was removed.
- Add the `StatusType` and `DocumentType` enums, `StatusType::fromName()` resolves unknown statuses to `UNKNOWN`.
- Reverse uses the `api/transaction` endpoint.

## [1.0.0] - 2026-10-16
- Migrate dnetix/placetopay to placetopay/placetopay-sdk-php.