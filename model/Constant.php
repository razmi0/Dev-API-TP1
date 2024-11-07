<?php


namespace Model;

use Model\Schema\Schema;


/**
 * class Constant
 * 
 * All those constants emerged from the database and are use to validate the client json
 * 
 */
class Constant
{
    public const NAME_REGEX = "/^[a-zA-Z0-9-'%,.:\/&()|; ]+$/";
    public const DESCRIPTION_REGEX = "/^[a-zA-Z0-9-'%,.:\/&()|; ]+$/";
    public const PRICE_REGEX = "/^[0-9.]+$/";
    public const ID_REGEX = "/^[0-9]+$/";
    public const COLUMNS = ["id", "name", "description", "prix", "date_creation"];
}
