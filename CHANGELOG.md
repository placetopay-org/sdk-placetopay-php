## Changelog

[Unreleased]
- Update the SDK to PHP 8.1, see the upgrading guide in the README for the breaking changes.
- Entities use PHP 8 constructors with promoted properties and a `fromArray()` factory, `LoaderTrait` was removed.
- Add the `StatusType` and `DocumentType` enums, `StatusType::fromName()` resolves unknown statuses to `UNKNOWN`.
- Reverse uses the `api/transaction` endpoint.

### Added
- `cancelSession()`: `POST /api/session/{requestId}/cancel`.
- `transaction()` with the actions of `POST /api/transaction` (`TransactionAction` enum) and the shortcuts `checkout()`, `reauthorize()`, `confirm()` and `cancelTransaction()`.
- `invalidateToken()`: `POST /api/instrument/invalidate`.
- `instrumentInformation()`: `POST /api/gateway/information`, with the credits and installments as `CreditOption`.
- `tokenInformation()`: `POST /api/gateway/token`.
- `type` on `RedirectRequest` to create preauthorization (`checkin`) sessions.
- `StatusType::PENDING_CONFIRMATION` and `StatusType::PARTIAL_EXPIRED`.
- `Token::bin()` and `Token::owner()`.
- `reverse()` accepts an optional amount for a partial refund.
- `Instrument::pocket()` to charge a card pocket (Uruguay).
- `algorithm` setting (`sha256` or `sha1`) for the tranKey digest.

### Changed
- `ReverseResponse` extends the new `TransactionResponse`.
- `Carrier` declares the new operations, custom carriers must implement them.
- `Status::reason()` returns `''` when the service sends `null` instead of failing.
- `Token::installments()` returns `?int` and the token only sends `installments` when it was requested, as an integer. Before it always sent `"0"`.
- `Credit` uses `null` for the missing `code`, `type` and `groupCode` and omits them, `installment` is 1 when it is not provided. Add `Credit::oneInstallment()`.
- The tranKey is digested with sha256 by default, as the authentication documentation defines it. Before it was always sha1; pass `'algorithm' => 'sha1'` to keep it.
- The `api/gateway/*` requests (`instrumentInformation()`, `tokenInformation()`) always use sha256, the service rejects sha1 there with "Autenticación fallida 102".
- `Notification` validates the `sha256:` prefixed signatures. Before only the deprecated sha1 ones were valid, so every sha256 notification was rejected.

## [1.0.0] - 2026-10-16
- Migrate dnetix/placetopay to placetopay/placetopay-sdk-php.