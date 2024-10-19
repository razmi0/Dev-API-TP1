<?php


namespace Model;

class Constant
{
    public const NAME_TYPE = "string";
    public const DESCRIPTION_TYPE = "string";
    public const PRICE_TYPE = "double";
    public const NAME_LENGTH = [1, 65];
    public const DESCRIPTION_LENGTH = [1, 65000];
    public const PRICE_RANGE = [0, null];
    public const NAME_REGEX = "/^[a-zA-Z0-9 ]+$/";
    public const DESCRIPTION_REGEX = "/^[a-zA-Z0-9 ]+$/";
    public const PRICE_REGEX = "/^[0-9.]+$/";
}
