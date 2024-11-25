<?php



namespace Utils\Patterns;


interface ISingleton
{
    public static function getInstance(): self;
}
