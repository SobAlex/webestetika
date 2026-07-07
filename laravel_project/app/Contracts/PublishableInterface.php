<?php

namespace App\Contracts;

interface PublishableInterface
{
    /**
     * Check if the item is published.
     */
    public function isPublished(): bool;

    /**
     * Check if the item is active.
     */
    public function isActive(): bool;
}

