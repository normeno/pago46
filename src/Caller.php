<?php
/**
 * Created by PhpStorm.
 * User: normeno
 * Date: 23-12-17
 * Time: 23:28
 */

namespace Normeno\Pago46;


class Caller
{
    /**
     * HMAC algorithm
     *
     * @var string
     */
    protected static $hmacAlgo = 'sha256';

    /**
     * API Enpoint
     *
     * @var string
     */
    protected static $enpoint = '';

    /**
     * Caller Instance
     */
    public function __construct()
    {
        // constructor body
    }

    /**
     * Environment Getter
     *
     * @return string
     */
    public static function getEnv()
    {
        return static::$env;
    }

    /**
     * Environment Setter
     *
     * @param string $env Environment
     *
     * @return void
     */
    public static function setEnv($env)
    {
        $permitted = ['production', 'sandbox'];

        if (!in_array($env, $permitted)) {
            static::$env = false;
            return false;
        }

        if ($env == 'production') {
            static::$enpoint = '';
        } elseif ($env == 'sandbox') {
            static::$endpoint = 'https://sandboxapi.pago46.com/';
        }

        static::$env = $env;
    }

    /**
     * Generate header to call 46 API
     *
     * @param bool|string $concatenateParams added params
     *
     * @return array
     */
    public static function setHeader($merchant, $request, $concatenateParams = false)
    {
        $unixTimestamp = date_timestamp_get(date_create());

        $encryptBase = "{$merchant['key']}&{$unixTimestamp}&{$request['method']}&{$request['path']}";

        if ($concatenateParams) {
            $encryptBase = "{$encryptBase}{$concatenateParams}";
        }

        $hmac = hash_hmac(static::$hmacAlgo, $encryptBase, $merchant['secret']);

        return [
            'Content-Type: application/json',
            "merchant-key: {$merchant['key']}",
            "message-hash: {$hmac}",
            "message-date: {$unixTimestamp}"
        ];
    }

    /**
     * Call Pago46 API
     *
     * @param string $url
     * @param string $method
     * @param bool $data
     *
     * @return array
     */
    public static function call($url, $method = 'GET', $data = false)
    {
        $url = (substr(static::$endpoint, -1) == '/') ? static::$endpoint . $url : static::$enpoint . '/' . $url;
        $data = json_encode($data);

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);

        switch (strtolower($method)) {
            case 'post':
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // Hack Non-SSL
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // Hack Non-SSL
        curl_setopt($curl, CURLOPT_HTTPHEADER, static::$header);

        $jsonResult = curl_exec($curl);

        curl_close($curl);

        $result = json_decode($jsonResult);

        return $result;
    }
}