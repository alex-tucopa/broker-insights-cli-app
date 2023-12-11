<?php

namespace App\Validators;

use App\Validators\Constraints;

abstract class Validator
{
    protected array $rules;
    protected array $errors;

    public function validate($input): bool
    {
        $this->errors = [];

        // Assumes all rules required
        $missingRequired = array_diff(array_keys($this->rules), array_keys($input));
        if ($missingRequired) {
            $this->errors[] = 'Missing required: ' . implode(', ', $missingRequired);
        }

        foreach ($input as $key => $value) {
            if (array_key_exists($key, $this->rules)) {
                $constraints = is_array($this->rules[$key]) ? $this->rules[$key] : [$this->rules[$key]];

                foreach ($constraints as $constraint) {
                    $constraintArgs = explode('|', $constraint);
                    $constraintName = array_shift($constraintArgs);
                    
                    $isValid = call_user_func_array([Constraints::class, $constraintName], [$value, ...$constraintArgs]);

                    if (!$isValid) {
                        $this->errors[] = "Invalid input for $key: \"$value\"";
                    }
                }
            }
        }

        return empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
