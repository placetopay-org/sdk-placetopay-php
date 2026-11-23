# PlacetoPay PHP SDK

With this code you will be able to quickly connect with the PlacetoPay Checkout service.

In order to see more comprehensive examples of how it works, please refer to the examples and the [documentation](https://docs-gateway.placetopay.com/)

## Migration guide

See [Upgrading to the PHP 8.1 version](#upgrading-to-the-php-81-version) at the end of this document for the breaking changes introduced by this version.

## Installation

Requires PHP 8.1 or higher.

Using composer from your project

```
composer require placetopay/placetopay-sdk
```

Or If you just want to run the examples contained in this project run "composer install" to load the vendor autoload

## Usage

Create an object with the configuration required for that instance

```php 
$placetopay = new PlacetoPay\PlacetoPaySdk\PlacetoPay([
    'login' => 'YOUR_LOGIN', // Provided by PlacetoPay
    'tranKey' => 'YOUR_TRANSACTIONAL_KEY', // Provided by PlacetoPay
    'baseUrl' => 'https://THE_BASE_URL_TO_POINT_AT',
    'timeout' => 10, // (optional) 15 by default
]);
```

### Creating a new Payment Request to obtain a Session Payment URL

Just provide the information of the payment needed and you will get a process url if its successful, for this example we are using the MINIMUM INFORMATION that needs to be provided, to see the full structure refer to the documentation or the example on [examples/basic/payment.php](examples/basic/payment.php)

```
$reference = 'COULD_BE_THE_PAYMENT_ORDER_ID";
$request = [
    'payment' => [
        'reference' => $reference,
        'description' => 'Testing payment',
        'amount' => [
            'currency' => 'USD',
            'total' => 120,
        ],
    ],
    'expiration' => date('c', strtotime('+2 days')),
    'returnUrl' => 'http://example.com/response?reference=' . $reference,
    'ipAddress' => '127.0.0.1',
    'userAgent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36',
];

$response = $placetopay->request($request);
if ($response->isSuccessful()) {
    // STORE THE $response->requestId() and $response->processUrl() on your DB associated with the payment order
    // Redirect the client to the processUrl or display it on the JS extension
    $response->processUrl();
} else {
    // There was some error so check the message and log it
    $response->status()->message();
}
```

### Obtain information about a previously created session

```
$response = $placetopay->query('THE_REQUEST_ID_TO_QUERY');

if ($response->isSuccessful()) {
    // In order to use the functions please refer to the PlacetoPay\PlacetoPaySdk\Message\RedirectInformation class

    if ($response->status()->isApproved()) {
        // The payment has been approved
    }
} else {
    // There was some error with the connection so check the message
    print_r($response->status()->message() . "\n");
}
```

## Upgrading to the PHP 8.1 version

This version requires **PHP 8.1 or higher** and introduces the following breaking changes. Integrations that only use
the `PlacetoPay` class with arrays (`request`, `query`, `collect`, `reverse`, `readNotification`) need to review mostly
the items marked with :warning:.

### Settings

- [ ] Change the settings parameter `url` for `baseUrl`
- [ ] If you have set the `timeout` for rest then change it to the root of the settings
- [ ] :warning: `PlacetoPayServiceException` is thrown instead of returning an `ERROR` status response, wrap the calls in a `try/catch`

### Entities are built with PHP 8 constructors

Entities and messages no longer receive an array in the constructor. They use constructor property promotion with
named arguments, and each one exposes a `fromArray()` factory that maps the service representation.

```php
// Before
$person = new Person(['name' => 'John', 'document' => '1040035000', 'documentType' => 'CC']);
$request = new RedirectRequest($data);

// After
$person = Person::fromArray(['name' => 'John', 'document' => '1040035000', 'documentType' => 'CC']);
$person = new Person(name: 'John', document: '1040035000', documentType: 'CC');
$request = RedirectRequest::fromArray($data);
```

- [ ] Replace every `new Entity($array)` with `Entity::fromArray($array)` or with named arguments
- [ ] `Notification` is created with `Notification::fromArray($data, $tranKey)` (or `$placetopay->readNotification($data)`)
- [ ] `CollectRequest` receives `instrument` and `provider`, any other named argument is forwarded to `RedirectRequest`
- [ ] The `LoaderTrait` and the `Entity::load()` / `Entity::loadEntity()` methods were removed, if you extended an entity map the attributes in your own `fromArray()`

### Statuses and document types are enums

- [ ] Replace the `Status::ST_*` constants with the `StatusType` enum, e.g. `Status::ST_APPROVED` → `StatusType::APPROVED->value`
- [ ] Use `$status->type()` to obtain the `StatusType` of a status, unknown values resolve to `StatusType::UNKNOWN` through `StatusType::fromName()`
- [ ] `Status::quick()` accepts a `StatusType` or a string
- [ ] Replace the `DocumentHelper::TYPE_*` constants with the `DocumentType` enum, e.g. `DocumentHelper::TYPE_CC` → `DocumentType::CC->value`. The validation patterns are available through `DocumentType::pattern()`, `isValid()` and `isBusiness()`

### Identifiers are integers

- [ ] `PlacetoPay::query()` receives an `int` request id
- [ ] `PlacetoPay::reverse()` receives the `int` internal reference of the transaction, it now uses the `api/transaction` endpoint
- [ ] `RedirectResponse::requestId()` and `RedirectInformation::requestId()` return `?int`, `Notification::requestId()` returns `int`
- [ ] `Transaction::internalReference()` returns `?int`

### Stricter signatures

- [ ] Setters and helpers are typed (`setReference(string)`, `setLocale(string)`, `setFactor(float)`, ...), passing a wrong type raises a `TypeError`
- [ ] Fluent setters return `static`
- [ ] Optional nested entities return nullable values, e.g. `Transaction::amount(): ?AmountConversion`, `AmountConversion::from(): ?AmountBase`
- [ ] `CollectRequest::payer()` is inherited from `RedirectRequest` and returns `?Person`, the payer is still validated when the request is created
- [ ] Attributes not provided take a default value (empty string, `0`, `false` or `null`) instead of being left uninitialized

