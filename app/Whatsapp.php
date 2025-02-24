<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Whatsapp extends Model
{
    private static $token = "zoith7kif283fiy7";
    private static $instance = "instance67481";
    //private static $token = "pynedyds8i00l4iy";
    //private static $instance = "instance56885";
    
    
    public static function send($phone, $body, $filename = null) {
        
        $instance = self::$instance;
        $token = self::$token;        
        $action = "message";
        
        $data = [
            'phone' => $phone, // Receivers phone
            "body"=> $body,
        ];
        
        if ( $filename != null ) {
            $data["filename"] = $filename;
            $action = "sendFile";
        }
        
        $json = json_encode($data); // Encode data to JSON
        // URL for request POST /message
        $url = "https://eu58.chat-api.com/$instance/$action?token=$token";
        // Make a POST request
        $options = stream_context_create(['http' => [
                'method'  => 'POST',
                'header'  => 'Content-type: application/json',
                'content' => $json
            ]
        ]);
        // Send a request
        $result = file_get_contents($url, false, $options);
        
        return $result;
        
//        print_r($result);
//        
//        $data = [
//            'phone' => '529811322994', // Receivers phone
//            "body"=> "http://www.africau.edu/images/default/sample.pdf",
//            "filename"=> "sample.pdf"
//        ];
//        $json = json_encode($data); // Encode data to JSON
//        // URL for request POST /message
//        $url = "https://eu58.chat-api.com/$instance/sendFile?token=$token";
//        // Make a POST request
//        $options = stream_context_create(['http' => [
//                'method'  => 'POST',
//                'header'  => 'Content-type: application/json',
//                'content' => $json
//            ]
//        ]);
//        // Send a request
//        $result = file_get_contents($url, false, $options);
//        print_r($result);
    }
}
