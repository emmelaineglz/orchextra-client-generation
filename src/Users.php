<?php

namespace Gigigo\Orchextra\Generation;
use Illuminate\Support\Collection as Collection;
use GuzzleHttp\Client;

class Users extends Generation
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
  public function getUsers ($with) {
    if (!empty($with)) {
      $this->setWith($with);
    }
    $response = $this->client->request('GET', $this->url.'/'.$this->version.'/users?with='.$this->with, ['headers' =>
      [
        'Authorization' => "Bearer {$this->token}"
      ]
    ])
      ->getBody()
      ->getContents();
    return Collection::make(json_decode($response));
  }

  public function getUser ($id) {
    $response = $this->client->request('GET', $this->url.'/'.$this->version.'/users/'.$id, ['headers' =>
      [
        'Authorization' => "Bearer {$this->token}"
      ]
    ])
      ->getBody()
      ->getContents();
    return Collection::make(json_decode($response));
  }

  public function createUser ($body) {
    $body = json_decode ($body);
    $response = $this->client->request('POST', $this->url.'/'.$this->version.'/users', ['headers' =>
      [
        'Authorization' => "Bearer {$this->token}"
      ],
      'json' => $body
    ])
      ->getBody()
      ->getContents();
    return Collection::make(json_decode($response));
  }
  public function deleteUser ($id) {
    $response = $this->client->request('DELETE', $this->url.'/'.$this->version.'/users/'.$id.'?envelopment=true', ['headers' =>
      [
        'Authorization' => "Bearer {$this->token}"
      ]
    ])
      ->getBody()
      ->getContents();
    return Collection::make(json_decode($response));
  }

  public function updateUser ($id) {
    $response = $this->client->request('PATCH', $this->url.'/'.$this->version.'/users/'.$id.'?envelopment=true', ['headers' =>
      [
        'Authorization' => "Bearer {$this->token}"
      ]
    ])
      ->getBody()
      ->getContents();
    return Collection::make(json_decode($response));
  }

  public function replaceUser ($id, $body) {
    $body = json_decode ($body);
    $response = $this->client->request('PUT', $this->url.'/'.$this->version.'/users/'.$id.'?envelopment=true', ['headers' =>
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