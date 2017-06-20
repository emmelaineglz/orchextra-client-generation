<?php

namespace Gigigo\Orchextra\Generation;
use Illuminate\Support\Collection as Collection;

use GuzzleHttp\Client;


class Channels
{
  protected $url;
  protected $version;
  protected $token;
  public function __construct ($url = '', $version = '', $token)
  {
    if (empty($token)) {
      throw new \InvalidArgumentException('token not provided');
    } else {
      $this->token = $token;
    }
    $this->setUrl($url);
    $this->setVersion($version);
    $this->client = new Client();
  }

  public function setUrl($url){
    if (empty($url)) {
      throw new \InvalidArgumentException('token not provided');
    }
    return $this->url = $url;

  }

  public function setVersion($version){
    if (empty($version)) {
      throw new \InvalidArgumentException('token not provided');
    }
    return $this->version = $version;
  }

  public function setWith ($with)
  {
    $arrayS = json_decode($with);
    $filters = '';
    foreach($arrayS as $key => $values) {
      $filters .= $key;
      foreach($values as $item) {
        $filters .= '.'.$item;
      }
      $filters .= ',';
    }
    $this->with = trim($filters,',');
    return $this;
  }

  public function getChannels($with){
    if (!empty($with)) {
      $this->setWith($with);
    }
    $response = $this->client->request('GET', $this->url.'/'.$this->version.'/channels?with='.$this->with, ['headers' =>
      [
        'Authorization' => "Bearer {$this->token}"
      ]
    ])
      ->getBody()
      ->getContents();
    return Collection::make(json_decode($response));
  }

}