<?php


namespace Model;

use Model\Schema\Schema;


/**
 * class Constant
 * 
 * All those constants emerged from the database
 * @property Schema $CREATE_SCHEMA
 */
class Constant
{
    private const NAME_REGEX = "/^[a-zA-Z0-9-'%,.:\/&()|; ]+$/";
    private const DESCRIPTION_REGEX = "/^[a-zA-Z0-9-'%,.:\/&()|; ]+$/";
    private const PRICE_REGEX = "/^[0-9.]+$/";
    private const ID_REGEX = "/^[0-9]+$/";

    /**
     * This schema will be use in /api/v1/creer.php to validate client json
     */
    public const CREATE_SCHEMA = [
        "name" => [
            "type" => "string",
            "range" => [1, 65],
            "regex" => self::NAME_REGEX,
            "required" => true
        ],
        "description" => [
            "type" => "string",
            "range" => [1, 65000],
            "regex" => self::DESCRIPTION_REGEX,
            "required" => true
        ],
        "prix" => [
            "type" => "double",
            "range" => [0, null],
            "regex" => self::PRICE_REGEX,
            "required" => true
        ]
    ];

    public const READ_ONE_SCHEMA = [
        "id" => [
            "type" => "integer",
            "required" => false,
            "range" => [1, null],
            "regex" => self::ID_REGEX
        ],
    ];

    public const DELETE_SCHEMA = [
        "id" => [
            "type" => "integer",
            "required" => true,
            "range" => [1, null],
            "regex" => self::ID_REGEX
        ]
    ];

    public const UPDATE_SCHEMA = [
        "id" => [
            "type" => "integer",
            "required" => true,
            "range" => [1, null],
            "regex" => self::ID_REGEX
        ],
        "name" => [
            "type" => "string",
            "range" => [1, 65],
            "regex" => self::NAME_REGEX,
            "required" => false
        ],
        "description" => [
            "type" => "string",
            "range" => [1, 65000],
            "regex" => self::DESCRIPTION_REGEX,
            "required" => false
        ],
        "prix" => [
            "type" => "double",
            "range" => [0, null],
            "regex" => self::PRICE_REGEX,
            "required" => false
        ]
    ];

    public const READ_MANY_SCHEMA = [
        "id" => [
            "type" => "integer[]",
            "required" => false,
        ]
    ];
}
