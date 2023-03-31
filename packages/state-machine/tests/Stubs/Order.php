<?php

namespace Lemuel\StateMachine\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;

use Lemuel\StateMachine\Contracts\HasStateMachine as HasStateMachineContract;
use Lemuel\StateMachine\Traits\HasStateMachine;

class Order extends Model implements HasStateMachineContract
{
    use HasStateMachine;
}
