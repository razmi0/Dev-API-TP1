<?php


namespace Model\Schema;


/**
 * 
 * 
 *  _______________________________________________________________________
 * |                                                                       |
 * |    This class handles the schema validation and processing.           |
 * |                                                                       |
 * |    How it basically works for the consumer :                          |
 * |                                                                       |
 * |       1) INSTANCIATION                                                |
 * |          An instance of Schema is declared and a valid                |
 * |          Template is provided as constructor parameter.               |
 * |                                                                       |
 * |       2) PARSING                                                      |
 * |          The parse() or safeParse() method is called by the consumer  |
 *            with the client json data as parameter                       |
 * |                                                                       |
 * |          2.1) MAPPING RULES                                           |
 * |               The method processShema() is internally called and      |
 * |               an array with all the rules is built                    |
 * |               (property validationMap).                               |
 * |                                                                       |
 * |          2.2) PROCESSING DATA                                         |
 * |               The client data is decoded and tested against the       |
 * |               rules using validate() method from Validator classes    |
 * |               and results are built.                                  |
 * |                                                                       |
 * |       3) RETRIEVING RESULTS                                           |
 * |          The method getResults() is called and the results are        |
 * |          retrieved by the consumer                                    |
 * |_______________________________________________________________________|
 * 
 * 
 **/

require_once '../../Autoloader.php';

use Model\Schema\Core as SchemaCore;
use Schema\Validator\ValidatorError as ValidatorError;
use Schema\Validator\ValidatorResult as ValidatorResult;
use Model\Schema\Template as Template;

/**
 * 
 * An exception can be thrown :
 *     - if the schema is not set before processing.
 *     - if the schema is already parsed and the consumer tries to parse it again.
 *     - if the class consumer use the parse() method and the client data has at least one error.
 *       (note : the class consumer can use safeParse() method to avoid the exception).
 * 
 */

use Exception as Exception;

/**
 * 
 * Class Schema
 * 
 * A schema is a set of rules that the client data must follow.
 * Provided with a valid Template, the Schema class can parse the client data and validate it
 * against the rules explicitly defined in the schema by the consumer.
 * 
 * @package Schema
 * @author Cuesta Thomas
 * @version 1.0
 * 
 * <code>
 * <?php
 * $schema = new Schema($template);
 * $allResults = $schema->safeParse($clientJson)->getResults();
 * ?>
 * </code>
 * 
 */
class Schema extends SchemaCore
{

    /**
     * 
     * Stores all the results of the client data validation against the schema after parsing.
     * It is the final output of the parsing and validation process.
     * ( valid and not valid results )
     * 
     */
    private ValidatorResult | array $results = [];
    /**
     * 
     * Stores only the error results.
     * 
     */
    private ValidatorResult | array $errorResults = [];
    /**
     * 
     * Stores only the success results.
     * 
     */
    private ValidatorResult | array $successResults = [];
    /**
     * 
     * Stores the schema definition.
     * 
     */
    private array $schema = [];
    /**
     * 
     * Stores all the rules for each key in the schema.
     * Internally managed.
     * The client data will be validated against these rules.
     * 
     */
    private array $validationMap = [];
    /**
     * 
     * Indicates whether the schema is defined.
     * 
     */
    private bool $hasSchema = false;
    /**
     * 
     * Indicates whether the schema has been processed and the validation map has been built.
     * Avoid processing the schema multiple times.
     * Internally managed.
     * 
     */
    private bool $isProcessed = false;
    /**
     * 
     * Indicates whether the schema has been parsed.
     * 
     */
    private bool $isParsed = false;
    /**
     * 
     * Indicates whether there are any errors in the client data.
     * 
     */
    private bool $hasError = false;
    /**
     * 
     * The constructor cast the template to a schema.
     * A schema is nothing else than a valid template.
     * 
     */
    public function __construct(array $schema)
    {
        $validTemplate = Template::fromArray($schema);
        $this->schema = $validTemplate->getTemplate();
        $this->hasSchema = true;
    }

    /**
     * 
     * Resets the schema validation and processing but not the schema definition.
     * Use it to reset the validation process and start over with the same schema.
     * Throw an exception if the schema is not set.
     * @throw Exception
     * 
     */
    public function resetParsing(): void
    {
        if (!$this->hasSchema) {
            throw new Exception("Schema not set, use setSchema() method to set schema.");
        }
        $this->validationMap = [];
        $this->hasError = false;
        $this->results = [];
        $this->errorResults = [];
        $this->successResults = [];
        $this->isParsed = false;
        $this->isProcessed = false;
    }

    // Getters
    // --

    public function getSchema(): array
    {
        return $this->schema;
    }

    public function getResults(): array
    {
        return $this->results;
    }

    public function getErrorResults(): array
    {
        return $this->errorResults;
    }

    public function getSuccessResults(): array
    {
        return $this->successResults;
    }

    public function getHasError(): bool
    {
        return $this->hasError;
    }

    public function getIsParsed(): bool
    {
        return $this->isParsed;
    }

    public function getHasSchema(): bool
    {
        return $this->hasSchema;
    }

    /**
     * 
     * Builds the validation map from the schema.
     * Insert corrensponding rules for each key in the schema calling the Core class.
     * 
     */
    private function processSchema(): void
    {
        // Loop through the valid schema provided by the consumer
        // --
        foreach ($this->schema as $key => $value) {
            $type = $value["type"];

            // Loop through the rules provided by the consumer
            // --
            foreach ($value as $constraint => $constraintValue) {

                // Process the rules and store them in the validationMap
                // --

                // Type rules
                // --
                if ($this->isType($constraint)) {
                    $this->validationMap[$key][] = $this->processTypeRules($constraintValue);
                }

                // Complex rules
                // -- 
                else if ($this->isComplex($constraint)) {
                    $this->validationMap[$key][] = $this->processComplexRules($constraint, $constraintValue);
                }

                // Range rules
                // --
                else if ($this->isRange($constraint)) {
                    $this->validationMap[$key][] = $this->processRangeRules($constraintValue, $type);
                }
            }
        }

        // Mark the schema as processed
        // --
        $this->isProcessed = true;
    }


    /**
     * 
     * Parse the client data against the validationMap and throw exceptions stopping the execution.
     * @param string $clientJson
     * @throws Exception
     * @throws ValidatorError
     * @return Schema
     * 
     */
    public function parse(string $clientJson): Schema
    {
        // Check if the schema is set
        // --
        if (!$this->schema) {
            throw new Exception("Schema not set, use setSchema() method to set schema.");
        }

        // Check if the schema has been processed
        // --
        if ($this->isProcessed || $this->isParsed) {
            throw new Exception("The schema has already been parsed, use reset() method to reset all processing.");
        }

        // Process the schema to build the validation map
        // --
        $this->processSchema();



        try {



            // Decode the client data
            // --
            $clientData = json_decode($clientJson, true);




            // Loop through the keys provided in the json data 
            // The validationMap keys and the client data keys match
            // --
            foreach ($this->validationMap as $key => $rules) {

                // Loop through the rules for each key
                // --
                foreach ($rules as $rule) {

                    // Validate the data against the rule and store the result as a ValidatorResult object
                    // --
                    $validated = $rule->validate($clientData[$key], $key);

                    // Call the getter getReadable() from ValidatorResult to get a clean result
                    // --
                    $readable = $validated->getReadable();

                    // The parse method doesn't allow errors from the client data 
                    // If the result is not valid, throw an exception
                    // --
                    if ($readable["code"] !== "valid") {

                        // Set the hasError property to true
                        // --
                        $this->hasError = true;

                        // Store the error result
                        // --
                        $this->errorResults = $readable;

                        // Throw a ValidatorError exception with the last readable result as data property
                        // --
                        throw new ValidatorError($readable);
                    }
                    // If the result is valid, store the success result
                    // -- 
                    else {

                        // Store the success result
                        // --
                        $this->successResults[] = $readable;
                    }

                    // Store the result
                    // --
                    $this->results[] = $readable;
                }
            }
        } catch (Exception $e) {
            // If an exception ValidatorError is thrown, rethrow it
            throw $e;
        }
        // Mark the schema as parsed
        // --
        $this->isParsed = true;

        // Return the instance so the consumer can chain methods
        // --
        return $this;
    }



    /**
     * 
     * 
     * 
     * 
     * Parse the client data against the validationMap but doesn't throw exceptions when an error in client data is found so the consumer can handle the errors.
     * @param array $clientJson
     * @throws Exception
     * @return Schema
     * 
     * 
     * 
     * 
     */
    public function safeParse($clientJson): Schema
    {
        // Check if the schema is set
        // --
        if (!$this->schema) {
            throw new Exception("Schema not set, use setSchema() method to set schema.");
        }


        // Check if the schema has been processed
        // --
        if ($this->isParsed) {
            throw new Exception("The schema has already been parsed, use reset() method to reset parsing.");
        }

        // Process the schema to build the validation map
        // --
        $this->processSchema();

        // There's a particular case where the schema provide a key that is not in the client data
        // if the schema set that this key is not required ("required" => false), we don't need to validate it because the consumer doesn't require
        // this key to be present in the client data.
        // Note : if no "required" key is set in the schema, all keys are required by default.

        $notRequiredKeys = array_filter(array_map(function ($key, $value) {
            if (array_key_exists("required", $value) && $value["required"] === false) {
                return $key;
            }
        }, array_keys($this->schema), $this->schema), fn($value) => $value !== null);

        // Loop through the keys provided in the json data 
        // The validationMap keys and the client data keys match (except for the particular case where the key is not required)
        // --
        foreach ($this->validationMap as $key => $rules) {


            // Here we handle the particular case where the key is not required and the client data doesn't have this key
            // --
            if (!array_key_exists($key, $clientJson) && in_array($key, $notRequiredKeys)) {
                // we only store the required rule in the results so we need to strip the other rules
                // --
                $required_rule = array_filter($rules, fn($rule) => $rule->getRule() === "required");
                $validated = $required_rule[0]->validate($this->schema[$key], $key);
                $readable = $validated->getReadable();
                $this->results[] = $readable;
                $this->successResults[] = $readable;
                continue;
            }


            // Loop through the rules for each key of data
            // --
            foreach ($rules as $rule) {




                // Validate the data against the rule and store the result as a ValidatorResult object
                // --
                $validated = $rule->validate($clientJson[$key], $key);

                // Call the getter getReadable() from ValidatorResult to get a clean result
                // --
                $readable = $validated->getReadable();


                // If the result is not valid,
                // --
                if ($readable["code"] !== "valid") {


                    // Set the hasError property to true
                    // --
                    $this->hasError = true;

                    // Store the error result
                    // --
                    $this->errorResults[] = $readable;
                }
                // If the result is valid,
                // --
                else {


                    // Store the success result
                    // --
                    $this->successResults[] = $readable;
                }

                // Store the result
                // --
                $this->results[] = $readable;
            }
        }

        // Mark the schema as parsed
        // --
        $this->isParsed = true;

        return $this;
    }
}
