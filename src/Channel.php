<?php

namespace Gigigo\Orchextra\Generation;
use Illuminate\Support\Collection as Collection;

use GuzzleHttp\Client;


class Channels extends Generation
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

  public function getChannels ($with) {
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

  public function createChannel ($body) {
    $body = json_decode ($body);
    $response = $this->client->request('POST', $this->url.'/'.$this->version.'/channels', ['headers' =>
      [
        'Authorization' => "Bearer {$this->token}"
      ],
      'json' => $body
    ])
      ->getBody()
      ->getContents();
    return Collection::make(json_decode($response));
  }

  public function getChannel ($id) {
    $response = $this->client->request('GET', $this->url.'/'.$this->version.'/channels/'.$id, ['headers' =>
      [
        'Authorization' => "Bearer {$this->token}"
      ]
    ])
      ->getBody()
      ->getContents();
    return Collection::make(json_decode($response));
  }

  public function deleteChannel ($id) {
    $response = $this->client->request('DELETE', $this->url.'/'.$this->version.'/channels/'.$id.'?envelopment=true', ['headers' =>
      [
        'Authorization' => "Bearer {$this->token}"
      ]
    ])
      ->getBody()
      ->getContents();
    return Collection::make(json_decode($response));
  }

  public function updateChannel ($id) {
    $response = $this->client->request('PATCH', $this->url.'/'.$this->version.'/channels/'.$id.'?envelopment=true', ['headers' =>
      [
        'Authorization' => "Bearer {$this->token}"
      ]
    ])
      ->getBody()
      ->getContents();
    return Collection::make(json_decode($response));
  }

  public function replaceChannel ($id, $body) {
    $body = json_decode ($body);
    $response = $this->client->request('PUT', $this->url.'/'.$this->version.'/channels/'.$id.'?envelopment=true', ['headers' =>
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