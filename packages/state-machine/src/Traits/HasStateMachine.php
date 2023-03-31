<?php

namespace Lemuel\StateMachine\Traits;

trait HasStateMachine
{
    protected $graph = [];

    /**
     * @param array<string, array<string>> $graph
     */
    public function setGraph(array $graph): void
    {
        $this->graph = $graph;
    }

    public function getCurrentState(): string
    {
        return $this->state;
    }

    public function setCurrentState(string $state): void
    {
        $this->state = $state;
    }

    public function getNextTransitions(): array
    {
        return $this->graph[$this->getCurrentState()];
    }

    public function canTransition(string $state): bool
    {
        return in_array($state, $this->getNextTransitions());
    }

    public function transition(string $state, ?callable $callback = null): void
    {
        if (! $this->canTransition($state)) {
            throw new \InvalidArgumentException('Invalid transition');
        }

        $this->setCurrentState($state);

        if ($callback) {
            $callback($this);
        }
    }
}
