<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UrlTitles extends Model
{
  //add data
  public static function add($request) 
  {
    $content['status'] = 200;
    $content['message'] = "";
    try {      
      $date = new \DateTime();
      $temp = new UrlTitles;
      $temp->short_code = $request['short_code'];
      $temp->url_title = $request['url_title'];
      $temp->created_at = $date->getTimestamp();
      $temp->updated_at = null;
      $temp->save();
    } catch(\Exception $ex) {
      $content['status'] = 500;
      $content['message'] = $ex->getMessage();
    }
    return json_encode($content);
  }
}
