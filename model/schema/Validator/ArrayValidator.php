<?php

namespace Schema\Validators;

use Schema\Validators\ValidatorInterface;
use Schema\Validators\ValidatorResult;


// string[], double[], integer[], null[]

/**
 * 
 * class ArrayStringValidator
 * 
 * Validates that a value is an array of strings.
 * 
 */
class ArrayStringValidator implements ValidatorInterface
{
    private string $rule = "string[]";

    public function validate(mixed $value, string $key): ValidatorResult
    {
        $hasWrongTypeInside = false;
        $wrongTypeFound = null;
        $currentType = gettype($value);

        if (!is_array($value)) {
            return new ValidatorResult("invalid_type", "string[]", $currentType, [$key], "Expected string[], received " . $currentType);
        }

        foreach ($value as $item) {
            if (!is_string($item)) {
                $hasWrongTypeInside = true;
                $wrongTypeFound = gettype($item);
                break;
            }
        }

        return $hasWrongTypeInside
            ? new ValidatorResult("invalid_type", "string[]", "mixed[]", [$key], "Expected string[], received an item : " . $wrongTypeFound)
            : new ValidatorResult("valid", "string[]", "string[]", [$key], "Expected string[], received string[]");
    }

    public function getRule(): string
    {
        return $this->rule;
    }
}


/**
 * 
 * class ArrayDoubleValidator
 * 
 * Validates that a value is an array of doubles.
 * 
 */
class ArrayDoubleValidator implements ValidatorInterface
{
    private string $rule = "double[]";

    public function validate(mixed $value, string $key): ValidatorResult
    {
        $hasWrongType = false;
        $wrongTypeFound = null;
        $currentType = gettype($value);

        if (!is_array($value)) {
            return new ValidatorResult("invalid_type", "double[]", $currentType, [$key], "Expected double[], received " . $currentType);
        }

        foreach ($value as $item) {
            if (!is_double($item)) {
                $hasWrongType = true;
                $wrongTypeFound = gettype($item);
                break;
            }
        }

        return $hasWrongType
            ? new ValidatorResult("invalid_type", "double[]", "mixed[]", [$key], "Expected double[], received an item :" . $wrongTypeFound)
            : new ValidatorResult("valid", "double[]", "double[]", [$key], "Expected double[], received double[]");
    }

    public function getRule(): string
    {
        return $this->rule;
    }
}

/**
 * 
 * class ArrayIntegerValidator
 * 
 * Validates that a value is an array of integers.
 * 
 */
class ArrayIntegerValidator implements ValidatorInterface
{
    private string $rule = "integer[]";

    public function validate(mixed $value, string $key): ValidatorResult
    {
        $hasWrongType = false;
        $wrongTypeFound = null;
        $currentType = gettype($value);

        if (!is_array($value)) {
            return new ValidatorResult("invalid_type", "integer[]", $currentType, [$key], "Expected integer[], received " . $currentType);
        }

        foreach ($value as $item) {
            if (!is_int($item)) {
                $hasWrongType = true;
                $wrongTypeFound = gettype($item);
                break;
            }
        }

        return $hasWrongType
            ? new ValidatorResult("invalid_type", "integer[]", "mixed[]", [$key], "Expected integer[], received an item :" . $wrongTypeFound)
            : new ValidatorResult("valid", "integer[]", "integer[]", [$key], "Expected integer[], received integer[]");
    }

    public function getRule(): string
    {
        return $this->rule;
    }
}

/**
 * 
 * class ArrayNullValidator
 * 
 * Validates that a value is an array of nulls.
 * 
 */
class ArrayNullValidator implements ValidatorInterface
{
    private string $rule = "null[]";

    public function validate(mixed $value, string $key): ValidatorResult
    {
        $hasWrongType = false;
        $wrongTypeFound = null;
        $currentType = gettype($value);

        if (!is_array($value)) {
            return new ValidatorResult("invalid_type", "null[]", $currentType, [$key], "Expected null[], received " . $currentType);
        }

        foreach ($value as $item) {
            if (!is_null($item)) {
                $hasWrongType = true;
                $wrongTypeFound = gettype($item);
                break;
            }
        }

        return $hasWrongType
            ? new ValidatorResult("invalid_type", "null[]", "mixed[]", [$key], "Expected null[], received an item :" . $wrongTypeFound)
            : new ValidatorResult("valid", "null[]", "null[]", [$key], "Expected null[], received null[]");
    }

    public function getRule(): string
    {
        return $this->rule;
    }
}
