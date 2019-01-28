<?php

namespace RvltDigital\SymfonyRevoltaBundle\Interfaces;

interface StateInterface
{
    /**
     * Current state as stored in memory but not yet stored in storage
     *
     * @return string
     */
    public function getCurrentState(): string;

    /**
     * Current state as stored in storage (database etc.)
     *
     * @return string
     */
    public function getStoredState(): string;

    /**
     * Returns array of allowed states for the state specified in parameter
     *
     * @param string $state
     * @return string[]
     */
    public function getAllowedStates(string $state): array;

    /**
     * Whether transition to the new state is allowed. If null is supplied, the current state is assumed
     *
     * @param string|null $newState
     * @return bool
     */
    public function isTransitionAllowed(?string $newState = null): bool;
}
