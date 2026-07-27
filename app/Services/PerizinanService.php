<?php

namespace App\Services;

use App\Domain\Rules\Contracts\BusinessRule;
use App\Domain\Rules\Perizinan\TidakAdaTunggakanRule;
use App\Exceptions\RuleViolationException;
use App\Models\Perizinan;
use App\Models\Santri;

class PerizinanService
{
    /** @var BusinessRule[] */
    private array $rules;

    public function __construct(?array $rules = null)
    {
        $this->rules = $rules ?? [new TidakAdaTunggakanRule()];
    }

    public function validateAndCreate(Santri $santri, array $data): Perizinan
    {
        foreach ($this->rules as $rule) {
            if (! $rule->passes($santri)) {
                throw new RuleViolationException($rule->message());
            }
        }

        return $santri->perizinan()->create(array_merge($data, [
            'status' => 'diajukan',
        ]));
    }

    public function checkCanApply(Santri $santri): ?string
    {
        foreach ($this->rules as $rule) {
            if (! $rule->passes($santri)) {
                return $rule->message();
            }
        }

        return null;
    }
}
