<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\DomCrawler\Crawler;

class UrlShorteners extends Model
{
  //list top100 visited sites
  public static function top100($request)
  {    
    $data = null;
    $temp = UrlShorteners::orderBy('hits','desc')->take(100)->get();
    foreach($temp as $key=>$values)
      $data[] = ['id'=>$values['id'], 'long_url'=>$values['long_url'],
                'hits'=>$values['hits']];
    echo json_encode($data);
  }

  //crawler to read title of pages and save it on database
  public static function crawler()
  {    
    //clean table
    UrlTitles::query()->delete();
    $temp = UrlShorteners::orderBy('id')->get();
    $message = "";
    \DB::beginTransaction();

    foreach($temp as $key=>$values)
    {
      $urlPai = $values['long_url'];
      $client = new \GuzzleHttp\Client(['verify' => 'C:/wamp64_accelog/www/bluecoding/backend/cacert.pem']);
      $response = $client->get($urlPai);
      $html = $response->getBody()->getContents();
      $crawler = new \Symfony\Component\DomCrawler\Crawler($html);

      //saving titles
      $fields['short_code'] = $values['short_code'];
      $fields['url_title'] = $crawler->filter('title')->text();
      $temp = json_decode(UrlTitles::add($fields));
      $message = $temp->message;
      if ($message != "") 
      {
        \DB::rollback();
        echo $temp->message;
        break;
      }
    }

    if ($message == "") {
      echo "save data successfully";
      \DB::commit();
    }
  }
   
  //return long_url from table
  public static function getLongURL($request) 
  {
    $content['status'] = 200;
    $content['message'] = "";
    $url = $request['url'];
    $short_code = $request['short_code'];
    try {
      if ($short_code)
        $content['message'] = UrlShorteners::whereRaw('short_code = ?', [$short_code])->first();

      if ($url) 
      {
        $temp = UrlShorteners::whereRaw('long_url = ?', [$url])->first();
        if ($temp != null)
          $content['message'] = "The url (".$url.") is already saved on database, please provide another one";
      }        


    } catch(\Exception $ex) {
      $content['status'] = 500;
      $content['message'] = $ex->getMessage();
    }
    return json_encode($content);
  }

  //add data
  public static function add($request) 
  {
    $content['status'] = 200;
    $content['message'] = "";
    try {      
      $date = new \DateTime();
      $temp = new UrlShorteners;
      $temp->short_code = $request['short_code'];
      $temp->long_url = $request['long_url'];
      $temp->hits = $request['hits'];
      $temp->created_at = $date->getTimestamp();
      $temp->updated_at = null;
      $temp->save();
      $content['message'] = "The url (".$request['long_url'].") was saved on database";
    } catch(\Exception $ex) {
      $content['status'] = 500;
      $content['message'] = $ex->getMessage();
    }
    return json_encode($content);
  }

  //update hits from url
  public static function updateHits($request) 
  {
    $content['status'] = 200;
    $content['message'] = "";
    $short_code = $request['short_code'];    
    try {      
      $temp = UrlShorteners::whereRaw('short_code = ?', [$short_code])->first();
      if ($temp == null)
        $message = "Record not found (short_code: ".$short_code.")";
      else {
        $date = new \DateTime();
        $temp->hits = $temp['hits']+1;
        $temp->updated_at = $date->getTimestamp();
        $temp->save();
      }
    } catch(\Exception $ex) {
      $content['status'] = 500;
      $content['message'] = $ex->getMessage();
    }
    return json_encode($content);
  }
}
