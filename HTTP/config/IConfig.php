<?php

namespace HTTP\Config;

interface IConfig
{
    public function get(string $key);
    public function set(string $key, mixed $value);
}
