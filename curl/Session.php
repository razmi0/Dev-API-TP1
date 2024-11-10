<?php


namespace Curl;

use CurlHandle;
use Exception;


/**
 * 
 * class Session
 * 
 * This class is a wrapper for the curl library
 * 
 * @property string URL : The base URL of the API
 * @property int code : The HTTP code of the response
 * @property array ENDPOINTS : create, read_all, read_one, update, delete, read_many
 * @property CurlHandle $ch : The curl handle
 * @property mixed $result : The result of the curl session
 */
class Session
{
    public const URL = "http://localhost/TP1/api/v1.0/produit/";
    public const ENDPOINTS = [
        "create" => "new",
        "read_all" => "list",
        "read_one" => "listone",
        "update" => "update",
        "delete" => "delete",
        "read_many" => "listmany"
    ];
    private ?CurlHandle $ch = null;
    private mixed $result = null;
    private int $code = 0;

    /**
     * Constructor
     * @param array $curl_parameters : An array containing the endpoint and the options
     */
    public function __construct(array $curl_parameters)
    {
        try {
            // Set the whole URI
            $uri = self::URL . self::ENDPOINTS[$curl_parameters["endpoint"]];

            // Initialize the curl session
            $this->ch = curl_init($uri);

            // Set the options for the curl session
            $this->setOptions($curl_parameters["options"]);
        } catch (Exception $e) {
            throw new Exception("An error occurred while initializing", $e->getMessage());
        }
    }

    /**
     * Encode the data as a json string and throw an exception if an error occurs
     * @param array $raw_data : The data to encode
     */
    public static function encodeData(array $raw_data): string
    {
        return json_encode($raw_data, JSON_THROW_ON_ERROR);
    }

    /**
     * Set the options for the curl session
     * @param array $options : The options to set ( method, data )
     */
    private function setOptions(array $options): void
    {
        // Set the headers ( assuming that the data is a json string )
        $headers = [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($options["data"])
        ];

        // Set the options for the curl session
        $allOptSuccess = curl_setopt_array($this->ch, [
            CURLOPT_CUSTOMREQUEST => $options["method"],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => array_key_exists("data", $options) ? $options["data"] ?? "" : "",
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_HEADER => true,
            CURLOPT_NOBODY => false
        ]);

        // If one of the options failed, throw an exception
        if (!$allOptSuccess) {
            throw new Exception("A cURL option was not set : " . curl_error($this->ch));
        }
    }

    /**
     * Execute the curl session and close it
     */
    public function executeAndClose(): void
    {
        try {

            // Execute the curl session
            $response = curl_exec($this->ch);

            // To get the body AND the header at the same time, we need to split the response

            $header_size = curl_getinfo($this->ch, CURLINFO_HEADER_SIZE);

            // First part is the header ( not used )
            // $header = substr($response, 0, $header_size);

            // Second part is the body(int $headerSize to end of the string), we store it in the result property as an array
            $this->result = json_decode(substr($response, $header_size), true);

            // Get the HTTP code
            $this->code = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);

            // throw an exception if an error occurs
            if ($response === false) {
                throw new Exception("An error occur in the API" . curl_error($this->ch));
            }

            // Close the curl session
            curl_close($this->ch);
        } catch (Exception $e) {
            throw new Exception("An error occurred while executing : " . $e->getMessage());
        }
    }

    public function getResult(): mixed
    {

        return $this->result;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function print_results(): void
    {
        var_export($this->result);
    }
}
