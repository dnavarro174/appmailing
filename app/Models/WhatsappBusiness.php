<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/* class WhatsappBusiness extends Model
{
    use HasFactory;
} */

class WhatsappBusiness extends Model
{
    #private static $token = "EAAMYc9Fy48QBO7IFWQf5Fe3N3ueIHwib5LUZAwKzTCjMfJTusgndfewnID1qNEK7iuoQz2f1pWZBpNiMvnhtzi2ZChb8wBxgklJAO2Vkxy5JDYUWE1vBRzSPgZAYiq0rgbVzAzgpRamkwhIK28Ezm21tweO2dSKPROTz0OxBFDbLAGFPavM3JT6AKvyiGibRd73bZClvtWkZBOjRoZD";
    private static $token = "EAAMYc9Fy48QBO9oErUtEz1fyIxuS012XAT4hmJGI0M2Ie52MWcnXkZAisECCYxuPJIFUo8ZCB6sWZAai1dnaVhZCieAFpyZACNCvWDihdtYCJpDD5bZCdBhEsJiT85JqlPrZA8R2TBSLeoN3LC6yan7YavpVu0J04ZCSezoouT3l76xVhezZA6ceOcjJeq5ZA5W2JY48nbAOtyR98hCMZBKiqaG";
    
    #private static $token = "EAAMYc9Fy48QBOwWX7t1AYCpL2ZAp4ImryCWeEBe2nTWxo9Q1aibe3wVYZBX3t6w7jBZA4oxXVQEQ1zLppu2ZCJsCx5ZAugZA7HPErvT834ZAzFNfZCXSon1ZAoOpwsZAZCRtLP6KbKPX33fE9Dhu9wyESr412jW8rUNp2lwlnxbZCQae7mtzy8lxHqeIdcCaxkrjxA3A";
    #private static $phone_id = "143495638852509";
    private static $phone_id = "166219189905456";
    private static $version = "v17.0";

    public static function send($phone_number, $body, $filename = null, $token=null, $phone_id=null, $version=null) {
        if($token == null)$token = self::$token;
        if($phone_id == null)$phone_id = self::$phone_id;
        if($version == null)$version = self::$version;
        $payload = [
            "messaging_product" => "whatsapp",
            "recipient_type"=> "individual",
            "to" => $phone_number,
            "type" => "text",
            "text"=> [
                "preview_url" => false,
                "body"=> $body
            ]
        ];
        if ( $filename != null ) {
            $payload = [
                "messaging_product" => "whatsapp",
                "recipient_type"=> "individual",
                "to" => $phone_number,
                "type" => "document",
                "document"=> [
                    "link" => $body,
                    "filename" => $filename,
                    //"caption"=> $body??''
                ]
            ];
        }
        if(stristr($filename,'.jpg')||stristr($filename,'.png')){//si filename contiene .jpg o .png
            $payload = [
                "messaging_product" => "whatsapp",
                "recipient_type"=> "individual",
                "to" => $phone_number,
                "type" => "image",
                "image"=> [
                    "link" => $body,
                    "caption"=> $filename??''
                ]
            ];
        }
        $url = 'https://graph.facebook.com/'.$version.'/'.$phone_id.'/messages';
        #$url = 'https://developers.facebook.com/tools/debug/accesstoken/?access_token=EAAMYc9Fy48QBOwWX7t1AYCpL2ZAp4ImryCWeEBe2nTWxo9Q1aibe3wVYZBX3t6w7jBZA4oxXVQEQ1zLppu2ZCJsCx5ZAugZA7HPErvT834ZAzFNfZCXSon1ZAoOpwsZAZCRtLP6KbKPX33fE9Dhu9wyESr412jW8rUNp2lwlnxbZCQae7mtzy8lxHqeIdcCaxkrjxA3A';
        $data = json_encode($payload);
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => "Content-type: application/json\r\nAuthorization: Bearer {$token}\r\n",
                'content' => $data,
                'ignore_errors' => true//ADDED
            ]
        ];
        $context = stream_context_create($options);
        // Send a request
        $result = file_get_contents($url, false, $context);
        return $result;
    }
}