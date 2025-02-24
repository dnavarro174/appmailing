<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class VonageService extends Model
{
    private static $apiKey = "7d046d7e";
    private static $apiSecret = "Ex07FxbZcUb6akQ7";
    private static $from = "TKT APIs";
    public static function send($phone_number, $message, $from = '', $apiKey ='', $apiSecret='') {
        if($apiKey == null)$apiKey = self::$apiKey;
        if($apiSecret == null)$apiSecret = self::$apiSecret;
        if($from == null)$from = self::$from;

        $basic  = new \Vonage\Client\Credentials\Basic($apiKey, $apiSecret);
        $client = new \Vonage\Client($basic);
        $response = $client->sms()->send(
            new \Vonage\SMS\Message\SMS($phone_number, $from, $message)
        );

        $message = $response->current();

        if ($message->getStatus() == 0) {
            $msg = "El mensaje ha sido enviado correctamente\n";
        } else {
            $msg = "El mensaje fallo con estado: " . $message->getStatus() . "\n";
        }
        return [
            'status' => $message->getStatus(),
            'message' => $msg,
            'response' => $message,
        ];
    }
}
