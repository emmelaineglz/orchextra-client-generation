<?php

namespace Gigigo\Orchextra\Generation;
use GuzzleHttp\Exception\ServerException;
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
  protected $parameters;
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
   * @var array
   */
  protected $filters;
  /**
   * @var array
   */
  protected $fields;
  /**
   * @var array
   */
  protected $with;
  /**
   * @var array
   */
  protected $pagination;
  /**
   * @var array
   */
  protected $totalCount;
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
  public function setWith (array $with = [])
  {
    $parameters = 'with=';
      foreach ($with as $item) {
        $parameters .= $item . ',';
      }
    return $this->with = trim($parameters,',');
  }
  public function setFields(array $fields = []){
    $parameters = 'fields=';
      foreach ($fields as $item) {
        $parameters .= $item . ',';
      }
    return $this->fields = trim($parameters,',');
  }

  public function setFilters(array $filters = []){
    $parameters = '';
    foreach($filters as $key => $values) {
      $parameters .= $key . "=" . $values . '&';
    }
    return $this->filters = trim($parameters,'&');
  }

  public function setPagination ( array $pagination = []){
    $parameters = '';
    foreach ($pagination as $key => $values){
      $parameters .= $key . "=" . $values . "&";
    }
    return $this->pagination = trim($parameters,'&');
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
  public function parametersAll(array $parameters){
    $paramAll = '?';
    if(!isset($this->with) && isset($parameters['with'])){$paramAll .= $this->setWith ($parameters['with']) . "&";}elseif(isset($this->with)){$paramAll .= $this->with . "&";}
    if(!isset($this->fields) && isset($parameters['fields'])){$paramAll .= $this->setFields ($parameters['fields']) . "&";}elseif(isset($this->fields)){$paramAll .= $this->fields . "&";}
    if(!isset($this->filters) && isset($parameters['filters'])){$paramAll .= $this->setFilters ($parameters['filters']) . "&";}elseif(isset($this->filters)){$paramAll .= $this->filters . "&";}
    if(!isset($this->pagination) && isset($parameters['pagination'])){$paramAll .= $this->setPagination ($parameters['pagination']) . "&";}elseif(isset($this->pagination)){$paramAll .= $this->pagination . "&";}
    $this->parameters = (trim($paramAll, '&')) === '?' ? '' : trim($paramAll, '&');
  }
  /**
   * @param array
   * @return Collection
   */
  public function all(array $parameters = [])
  {
      $this->parametersAll($parameters);
      $response = $this->client->get("{$this->url}/{$this->version}/{$this->entity}{$this->parameters}", ['headers' =>
        [
          'Authorization' => "Bearer {$this->token}"
        ]
      ]);
      $totalCount = $response->getHeader('X-total-count');
      $this->totalCount = $totalCount[0];
      $body = $response->getBody();
      $content = $body->getContents();

    $collection = Collection::make();
    foreach(json_decode ($content, true) as $value) {
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
    try {
      $response = $this->client->delete ("{$this->url}/{$this->version}/{$this->entity}/{$this->attributes['id']}?envelopment=true", ['headers' =>
        [
          'Authorization' => "Bearer {$this->token}"
        ]
      ])
        ->getBody ()
        ->getContents ();

      $data = json_decode ($response);

      return $data->status;
    }
    catch (ClientException $e){
      var_dump ($e);
    }
    catch (ServerException $e){
      var_dump ($e);
    }
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

