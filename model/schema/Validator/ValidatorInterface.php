<?php

namespace Schema\Validators;

/**
 * 
 * 
 * All Validator classes must implement this interface.
 * 
 * 
 */
interface ValidatorInterface
{
    /**
     * 
     * The validate method is called by the Schema class to validate the client data using the intrinsic rules defined in the Validator rule object.
     */
    public function validate(mixed $value, string $key): ValidatorResult;
    public function getRule(): string;
}
