<?php

namespace Gigigo\Orchextra\Generation;
use Illuminate\Support\Collection as Collection;

use GuzzleHttp\Client;


class Clients extends Generation
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

  public function getClients ($with) {
    if (!empty($with)) {
      $this->setWith($with);
    }
    $response = $this->client->request('GET', $this->url.'/'.$this->version.'/clients?with='.$this->with, ['headers' =>
      [
        'Authorization' => "Bearer {$this->token}"
      ]
    ])
      ->getBody()
      ->getContents();
    return Collection::make(json_decode($response));
  }

  public function getClient ($id) {
    $response = $this->client->request('GET', $this->url.'/'.$this->version.'/clients/'.$id, ['headers' =>
      [
        'Authorization' => "Bearer {$this->token}"
      ]
    ])
      ->getBody()
      ->getContents();
    return Collection::make(json_decode($response));
  }

  public function createClient ($body) {
    $body = json_decode ($body);
    $response = $this->client->request('POST', $this->url.'/'.$this->version.'/clients', ['headers' =>
      [
        'Authorization' => "Bearer {$this->token}"
      ],
      'json' => $body
    ])
      ->getBody()
      ->getContents();
    return Collection::make(json_decode($response));
  }

  public function updateClient ($id, $body) {
    $body = json_decode ($body);
    $response = $this->client->request('PATCH', $this->url.'/'.$this->version.'/clients/'.$id, ['headers' =>
      [
        'Authorization' => "Bearer {$this->token}"
      ],
      'json' => $body
    ])
      ->getBody()
      ->getContents();
    return Collection::make(json_decode($id, $response));
  }

  public function replaceClient ($id, $body) {
    $body = json_decode ($body);
    $response = $this->client->request('PUT', $this->url.'/'.$this->version.'/clients/'.$id, ['headers' =>
      [
        'Authorization' => "Bearer {$this->token}"
      ],
      'json' => $body
    ])
      ->getBody()
      ->getContents();
    return Collection::make(json_decode($response));
  }

  public function deleteClient ($id) {
    $response = $this->client->request('DELETE', $this->url.'/'.$this->version.'/clients/'.$id.'?envelopment=true', ['headers' =>
      [
        'Authorization' => "Bearer {$this->token}"
      ]
    ])
      ->getBody()
      ->getContents();
    return Collection::make(json_decode($response));
  }
}