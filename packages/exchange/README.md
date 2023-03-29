# Exchange

This package converts EUR into another currency with the provided endpoint or service.

## Installation

This package is not an actual published package and is used for a technical exam.

Regardless, once this package is installed, Laravel will auto-detect this package and will register the provided Service Provider.

The Service Provider will bind `EcbExchangeService` as the default implementation and will register a route accessible via `GET` request.

If more control is wanted, disable auto-discovery for this package and register everything manually.

## Exchange Service

If you need to get the exchange rate manually, you can resolve `Lemuel\Exchange\ExchangeService::class` from the container.

Currently, the available implementation `EcbExchangeService` checks the European Central Bank for the exchange rates.

## Configuration

Set `exchange.routes` (Default: `true`) to `false` to disable the route registration.

Set `exchange.endpoint` (Default: `exchange`) to change the registered endpoint.

## Error handling

`UnreachableExchangeException` is going to be thrown if the exchange service is unreachable or returned an invalid response.

`UnsupportedCurrencyException` is going to be thrown if the exchange service does not have the requested currency.

## Testing

```bash
./vendor/bin/testbench package:test tests
```
