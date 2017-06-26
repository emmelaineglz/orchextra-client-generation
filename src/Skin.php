<?php

namespace Gigigo\Orchextra\Generation;
use Illuminate\Support\Collection as Collection;
use GuzzleHttp\Client;

class Skins extends Generation
{
  protected $url;
  protected $version;
  protected $token;
  public function __construct ($url = '', $version = '', $token)
  {
    if (empty($token)) {
      throw new \InvalidArgumentException('the token is required');
    } else {
      $this->token = $token;
    }
    $this->setUrl($url);
    $this->setVersion($version);
    $this->client = new Client();
  }

  public function getSkins ($with) {
    if (!empty($with)) {
      $this->setWith($with);
    }
    $response = $this->client->request('GET', $this->url.'/'.$this->version.'/skins?with='.$this->with, ['headers' =>
      [
        'Authorization' => "Bearer {$this->token}"
      ]
    ])
      ->getBody()
      ->getContents();
    return Collection::make(json_decode($response));
  }

  public function createSkin ($body) {
    $body = json_decode ($body);
    $response = $this->client->request('POST', $this->url.'/'.$this->version.'/skins', ['headers' =>
      [
        'Authorization' => "Bearer {$this->token}"
      ],
      'json' => $body
    ])
      ->getBody()
      ->getContents();
    return Collection::make(json_decode($response));
  }
}