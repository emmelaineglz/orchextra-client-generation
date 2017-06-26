<?php

namespace Gigigo\Orchextra\Generation;
use Illuminate\Support\Collection as Collection;

use GuzzleHttp\Client;

abstract class BaseCRUD
{
  /**
   * @var string
   */
  protected $url;
  /**
   * @var string
   */
  protected $version;
  /**
   * @var string
   */
  protected $with;

  protected $attributes = [];

  protected $client;
  protected $entity;
  protected $token;

  /*

  protected $client;


   * BaseCRUD constructor.
   * @param Client $client
   * @param array $attributes

  public function __construct(Client $client, array $attributes = [])
  {
    $this->client = $client;
    $this->attributes = $attributes;
  }
*/
  public function setUrl($url){
    if (empty($url)) {
      throw new \InvalidArgumentException('the url is required');
    }
    return $this->url = $url;

  }

  public function setVersion($version)
  {
    if (empty($version)) {
      throw new \InvalidArgumentException('the version is required');
    }
    return $this->version = $version;
  }

  public function setWith ($with)
  {
    $filters = '?';
    foreach($with as $key => $values) {
      $filters .= $key."=";
      foreach($values as $item) {
        $filters .= $item.',';
      }
      $filters = trim($filters,',');
      $filters .= '&';
    }
    $this->with = trim($filters,',');
    return $this;
  }
  /**
   * @param $body
   * @return array
   */
  public function setBody($body) {
    $array = json_decode ($body);
    foreach ($array as $key=> $value){
      if($key === 'image'){
        $formData []= ['Content-type' => 'multipart/form-data', 'name' => $key , 'contents' => fopen ($value, 'r')];
      }else{
        $formData []= ['name' => $key , 'contents' => $value];
      }
    }
    return $formData;
  }

  public static function createFromResponse($url, $version, $token, array $attributes = []){
    $instance = new static($url, $version, $token);
    $instance->fill ($attributes);
    return  $instance;
  }
  public function fill(array $attributes = [])
  {
    $this->attributes = $attributes;
  }


  public function get($id)
  {
    $response = $this->client->get("{$this->url}/{$this->version}/{$this->entity}/{$id}", ['headers' =>
      [
        'Authorization' => "Bearer {$this->token}"
      ]
    ])
      ->getBody()
      ->getContents();
    return static::createFromResponse ($this->url, $this->version, $this->token, json_decode ($response,true));
  }

  public function all($with)
  {
    $this->setWith ($with);
    $response = $this->client->get("{$this->url}/{$this->version}/{$this->entity}{$this->with}", ['headers' =>
      [
        'Authorization' => "Bearer {$this->token}"
      ]
    ])
      ->getBody()
      ->getContents();
      foreach(json_decode ($response, true) as $value) {
        $res[] = static::createFromResponse ($this->url, $this->version, $this->token, $value);
      }
      return Collection::make ($res);
  }

  public function replace (array $body = []){
    $response = $this->client->put("{$this->url}/{$this->version}/{$this->entity}/{$this->attributes['id']}", ['headers' =>
      [
        'Authorization' => "Bearer {$this->token}"
      ],
      'json' =>  $body
    ])
      ->getBody()
      ->getContents();
    return static::createFromResponse ($this->url, $this->version, $this->token, json_decode ($response,true));
  }
}