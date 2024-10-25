<?php


namespace Curl;

use CurlHandle;
use Exception;


/**
 * 
 * class Curl
 * 
 * This class is a wrapper for the curl library
 * 
 * @property string URL : The base URL of the API
 * @property array ENDPOINTS : create, read_all, read_one, update, delete, read_many
 * @property CurlHandle $ch : The curl handle
 * @property string $result : The result of the curl session
 */
class Curl
{
    public const URL = "http://localhost/TP1/api/v1/";
    public const ENDPOINTS = [
        "create" => "creer.php",
        "read_all" => "lire.php",
        "read_one" => "lire_un.php",
        "update" => "modifier.php",
        "delete" => "supprimer.php",
        "read_many" => "lire_des.php"
    ];
    private ?CurlHandle $ch = null;
    private ?string $result = null;

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
            CURLOPT_POSTFIELDS => $options["data"] ?? "",
            CURLOPT_HTTPHEADER => $headers,
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

            // throw an exception if an error occurs
            if ($response === false) {
                throw new Exception("An error occur in the API" . curl_error($this->ch));
            }

            // Store the response as a json string
            $this->result = json_encode($response);

            // Close the curl session
            curl_close($this->ch);
        } catch (Exception $e) {
            throw new Exception("An error occurred while executing : " . $e->getMessage());
        }
    }

    public function getResult(): string
    {
        return $this->result;
    }

    public function print_results(): void
    {
        var_export(json_decode($this->result, true));
    }
}
