<?php

namespace App\Domain\Rules\Contracts;

interface BusinessRule
{
    public function passes(mixed $subject): bool;
    public function message(): string;
}
