# Eloquent State Machine

This package provides a simple Eloquent trait that implements State Machine for your models.

## Installation

This package is not an actual published package and is used for a technical exam.

It should automatically be installed by the main project.

## Usage

Just implement the `HasStateMachine` contract and use the trait.

```php
use Lemuel\StateMachine\Contracts\HasStateMachine as HasStateMachineContract;
use Lemuel\StateMachine\Traits\HasStateMachine;

class Order extends Model implments HasStateMachineContract
{
    use HasStateMachine;
}
```

You can then call `setGraph(array $graph)` method to add the possible transitions.

```php
$order = new Order()

$order->setGraph([
    'open' => [
        'pending payment',
        'cancelled',
    ],

    'pending payment' => [
        'paid',
        'cancelled',
    ],

    'paid' => [
        'shipped',
        'cancelled'
    ],

    'shipped' => [],

    'cancelled' => []
]);
```

By default, the trait will get and set the property `$state` in your model.

If you have something else that should define your state, for example the column `title` from table `order_statuses`, you can do the following:

```php
class Order extends Model implements HasListingContract
{
    use HasListing;

    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class);
    }

    public function getCurrentState(): string
    {
        return $this->orderStatus->title;
    }

    public function setCurrentState(string $state): void
    {
        $this->order_status_id = OrderStatus::where('title', $state)->first()->id;
    }
}
```

To transition to a new state:

```php
$order = new Order();

$order->setGraph([
    // omitted for brevity
]);

// 2nd parameter, the callback, is optional.
$order->transition('shipped', function (Order $order) {
    $order->save();
});
```

This method will throw an `InvalidArgumentException` if an invalid `state` is provided.

---

The following methods are also available:

`getNextTransitions(): array` - get the next possible transitions.

`canTransition(string $state)` - checks the provided graph via `setGraph(array $graph)` if current state can move to given `$state`.

## Testing

```bash
./vendor/bin/testbench package:test tests
```
