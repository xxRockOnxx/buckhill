<?php

namespace Lemuel\StateMachine\Contracts;

interface HasStateMachine
{
    /**
     * @param array<string, array<string>> $graph
     */
    public function setGraph(array $graph): void;

    public function getCurrentState(): string;

    public function setCurrentState(string $state): void;

    /**
     * @return array<string>
     */
    public function getNextTransitions(): array;

    public function canTransition(string $state): bool;

    public function transition(string $state, ?callable $callback = null): void;
}
