<?php

namespace App\Helpers;

Class RestCurlToken {
  // $arrayName = array('ddd' => 11, );
  // exec('GET','http://google.com?adsaf=dfd&sf=sgsgs',[],$token);
  public static function exec($method, $url, $obj = array(), $token = '') {
    $header = ['Accept: application/json','Content-Type: application/json'];
    if(!empty($token)){
      $authorization = 'Authorization: '.$token;
      array_push($header, $authorization);
    }
    $curl = curl_init();
     
    switch($method) {
      case 'GET':
        if(strrpos($url, "?") === FALSE) {
          $url .= '?' . http_build_query($obj);
        }
        break;

      case 'POST': 
        curl_setopt($curl, CURLOPT_POST, TRUE);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($obj));
        break;

      case 'PUT':
      case 'DELETE':
      default:
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($obj));
    }

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header); 
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, TRUE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, TRUE);
    
     // Exec
    $response = curl_exec($curl);
    $info     = curl_getinfo($curl);
    if(curl_errno($curl)){
        throw new \Exception('Request Error: ' . curl_error($curl), $info['http_code']);   
    }

    curl_close($curl);

    // Data
    $header = trim(substr($response, 0, $info['header_size']));
    $body = substr($response, $info['header_size']);
     
    return array('status' => $info['http_code'], 'header' => $header, 'data' => json_decode($body));
  }

  public static function get($url, $obj = array(), $token = '') {
     return RestCurlToken::exec("GET", $url, $obj, $token);
  }

  public static function post($url, $obj = array(), $token = '') {
     return RestCurlToken::exec("POST", $url, $obj, $token);
  }

  public static function put($url, $obj = array(), $token = '') {
     return RestCurlToken::exec("PUT", $url, $obj, $token);
  }

  public static function delete($url, $obj = array(), $token = '') {
     return RestCurlToken::exec("DELETE", $url, $obj, $token);
  }

}