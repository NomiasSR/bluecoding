<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;


class UrlShortenerController extends BaseController
{
  protected static $chars = "abcdfghjkmnpqrstvwxyz|ABCDFGHJKLMNPQRSTVWXYZ|0123456789";
  protected static $codeLength = 6;  
  
  //generate random string
  function generateRandomString($length = 6)
  {
    $sets = explode('|', self::$chars);
    $all = '';
    $randString = '';
    foreach($sets as $set){
        $randString .= $set[array_rand(str_split($set))];
        $all .= $set;
    }
    $all = str_split($all);
    for($i = 0; $i < $length - count($sets); $i++){
        $randString .= $all[array_rand($all)];
    }
    $randString = str_shuffle($randString);
    return $randString;
  }

  //create shortcode
  function createShortCode($url)
  {
    $shortCode = $this->generateRandomString(self::$codeLength);
    return $shortCode;
  }

  //validate url format
  function validateUrlFormat($url){
    return filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED);
  }

  //verify if url exists
  function verifyUrlExists($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch,  CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return (!empty($response) && $response != 404);
  }

  //validate url
  function validateURL($url="") 
  {
    $message = "";

    if(empty($url))
      $message = "No URL was supplied";

    else if($this->validateUrlFormat($url) == false)
      $message = "URL does not have a valid format";        

    else if (!$this->verifyUrlExists($url))
      $message = "URL does not appear to exist.";

    return $message;
  }

  //create code and save data
  public function createCode(Request $request)
  {
    $url = $request['url'];
    $status = 200;
    $message = $this->validateURL($url);

    if (empty($message))
    {
      //check if code exists in db
      $temp = json_decode(\App\Models\UrlShorteners::getLongURL($request));
      $status = $temp->status;
      $message = $temp->message;
      if (empty($message) && empty($data))
      {
        //saving record
        $shortCode = $this->createShortCode($url, self::$codeLength);
        $request['long_url'] = $url;
        $request['short_code'] = $shortCode;
        $request['hits'] = 0;
        $temp = json_decode(\App\Models\UrlShorteners::add($request)); 
        $message = $temp->message;
        $status = $temp->status;
      }
    }
    return response()->json(['message'=>$message], $status, ['Content-Type' => 
      'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);    
  }

  //check if url exists on database and redirect
  public function checkUrlShortener(Request $request) 
  {
    $param = $request->param;
    $message = "";
    if ($param)
    {
      //check if code exists in db
      $request['short_code'] = $param;
      $temp = json_decode(\App\Models\UrlShorteners::getLongURL($request));
      $message = $temp->message;
      if ($message != null) 
      {
        //update hits from url
        $temp = json_decode(\App\Models\UrlShorteners::updateHits($request));
        if ($temp->status != 200)
          return $temp->message;
        
        return redirect($message->long_url); 
      } 
    }    

    //if url short cant be found, redirect to welcome laravel default page
    return view('welcome');
  }

  //crawler for get title of sites and save it on database
  public function crawler(Request $request) 
  {
    return \App\Models\UrlShorteners::crawler();
  }

  //list top100 visited sites
  public function top100(Request $request) 
  {
    return \App\Models\UrlShorteners::top100($request);
  }
}
