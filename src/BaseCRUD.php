<?php

namespace Gigigo\Orchextra\Generation;
use Illuminate\Support\Collection as Collection;

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
  /**
   * @var array
   */
  protected $attributes = [];
  /**
   * @var $client
   */
  protected $client;
  /**
   * @var $entity
   */
  protected $entity;
  /**
   * @var $token
   */
  protected $token;
  /**
   * @var $body
   */
  protected $body;
  /**
   * @param $url
   * @return mixed
   */
  public function setUrl($url){
    if (empty($url)) {
      throw new \InvalidArgumentException('the url is required');
    }
    return $this->url = $url;

  }

  /**
   * @param $version
   * @return mixed
   */
  public function setVersion($version)
  {
    if (empty($version)) {
      throw new \InvalidArgumentException('the version is required');
    }
    return $this->version = $version;
  }

  /**
   * @param $with
   * @return $this
   */
  public function setWith ($with)
  {
    $filters = '?';
    foreach($with as $key => $values) {
      if($key !== "filter") {
        $filters .= $key . "=";
        foreach ($values as $item) {
          $filters .= $item . ',';
        }
        $filters = trim ($filters, ',');
        $filters .= '&';
      }else{
        foreach ($values as $item) {
          $filters .= $item . '&';
        }
        $filters = trim ($filters, '&');
      }
    }
    $this->with = trim($filters,',');

    return $this;
  }

  /**
   * @param $body
   * @return array
   */
  public function setBody($body) {
    foreach ($body as $key=> $value){
      if($key === 'image'){
        $formData []= ['Content-type' => 'multipart/form-data', 'name' => $key , 'contents' => fopen ($value, 'r')];
      }else{
        $formData []= ['name' => $key , 'contents' => $value];
      }
    }
    return [
      'headers' => [
        'Authorization' => "Bearer {$this->token}"
       ],
      'multipart' => $formData];
  }

  /**
   * @param $url
   * @param $version
   * @param $token
   * @param array $attributes
   * @return static
   */
  public static function createFromResponse($url, $version, $token, array $attributes = []){
    $instance = new static($url, $version, $token);
    $instance->fill ($attributes);
    return  $instance;
  }

  /**
   * @param array $attributes
   */
  public function fill(array $attributes = [])
  {
    $this->attributes = $attributes;
  }

  /**
   * @param $id
   * @return BaseCRUD
   */
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

  /**
   * @param $with
   * @return Collection
   */
  public function all(array $parameters = [])
  {
    $this->setWith ($parameters);
    $response = $this->client->get("{$this->url}/{$this->version}/{$this->entity}{$this->with}", ['headers' =>
      [
        'Authorization' => "Bearer {$this->token}"
      ]
    ])
      ->getBody()
      ->getContents();
    $collection = Collection::make();
    foreach(json_decode ($response, true) as $value) {
      $collection->push (static::createFromResponse ($this->url, $this->version, $this->token, $value));
    }
    return $collection;
  }

  /**
   * @param array $body
   * @return BaseCRUD
   */
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

  /**
   * @param $body
   * @return BaseCRUD
   */
  public function create ($body) {
    if($this->entity === "campaigns"){
      $body = $this->setBody ($body);
    }else{
      $body = [
        'headers' => [
          'Authorization' => "Bearer {$this->token}"
         ],
        'json' => $body
      ];
    }
    $response = $this->client->post ("{$this->url}/{$this->version}/{$this->entity}?envelopment=true", $body)
      ->getBody()
      ->getContents();
    return static::createFromResponse ($this->url, $this->version, $this->token, json_decode ($response,true));
  }

  /**
   * @return BaseCRUD
   */
  public function delete () {

    $response = $this->client->delete("{$this->url}/{$this->version}/{$this->entity}/{$this->attributes['id']}?envelopment=true", ['headers' =>
      [
        'Authorization' => "Bearer {$this->token}"
      ]
    ])
      ->getBody()
      ->getContents();

    $data = json_decode ($response);

    return $data->status;
  }

  /**
   * @param $body
   * @return BaseCRUD
   */
  public function update ($body) {
    $response = $this->client->patch("{$this->url}/{$this->version}/{$this->entity}/{$this->attributes['id']}", ['headers' =>
      [
        'Authorization' => "Bearer {$this->token}"
      ],
      'json' => $body
    ])
      ->getBody()
      ->getContents();
    return static::createFromResponse ($this->url, $this->version, $this->token, json_decode ($response, true));
  }
}
