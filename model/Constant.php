<?php


namespace Model;

use Model\Schema\Schema;


/**
 * class Constant
 * 
 * All those constants emerged from the database
 * @property Schema $PRODUCT_CREATE_SCHEMA
 */
class Constant
{

    /**
     * This schema will be use in /api/v1/creer.php to validate client json
     */
    public const PRODUCT_CREATE_SCHEMA = [
        "name" => [
            "type" => "string",
            "range" => [1, 65],
            "regex" => "/^[a-zA-Z0-9-'%,.:\/&()|; ]+$/",
            "required" => true
        ],
        "description" => [
            "type" => "string",
            "range" => [1, 65000],
            "regex" => "/^[a-zA-Z0-9-'%,.:\/&()|; ]+$/",
            "required" => true
        ],
        "prix" => [
            "type" => "double",
            "range" => [0, null],
            "regex" => "/^[0-9.]+$/",
            "required" => true
        ]
    ];

    public const READ_ONE_SCHEMA = [
        "id" => [
            "type" => "integer",
            "required" => false,
            "range" => [1, null],
            "regex" => "/^[0-9]+$/"
        ],
    ];

    public const DELETE_SCHEMA = [
        "id" => [
            "type" => "integer",
            "required" => true,
            "range" => [1, null],
            "regex" => "/^[0-9]+$/"
        ]
    ];

    public const UPDATE_SCHEMA = [
        "id" => [
            "type" => "integer",
            "required" => true,
            "range" => [1, null],
            "regex" => "/^[0-9]+$/"
        ],
        "name" => [
            "type" => "string",
            "range" => [1, 65],
            "regex" => "/^[a-zA-Z0-9 ]+$/",
            "required" => false
        ],
        "description" => [
            "type" => "string",
            "range" => [1, 65000],
            "regex" => "/^[a-zA-Z0-9-. ]+$/",
            "required" => false
        ],
        "prix" => [
            "type" => "double",
            "range" => [0, null],
            "regex" => "/^[0-9.]+$/",
            "required" => false
        ]
    ];
}
