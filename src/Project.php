<?php

namespace Gigigo\Orchextra\Generation;
use Illuminate\Support\Collection as Collection;
use GuzzleHttp\Client;


class Projects extends Generation
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

  public function getProjects ($with) {
    if (!empty($with)) {
      $this->setWith($with);
    }
    $response = $this->client->request('GET', $this->url.'/'.$this->version.'/projects?with='.$this->with, ['headers' =>
      [
        'Authorization' => "Bearer {$this->token}"
      ]
    ])
      ->getBody()
      ->getContents();
    return Collection::make(json_decode($response));
  }

  public function getProject ($id) {
    $response = $this->client->request('GET', $this->url.'/'.$this->version.'/projects/'.$id, ['headers' =>
      [
        'Authorization' => "Bearer {$this->token}"
      ]
    ])
      ->getBody()
      ->getContents();
    return Collection::make(json_decode($response));
  }

  public function createProject ($body) {
    $body = json_decode ($body);
    $response = $this->client->request('POST', $this->url.'/'.$this->version.'/projects', ['headers' =>
      [
        'Authorization' => "Bearer {$this->token}"
      ],
      'json' => $body
    ])
      ->getBody()
      ->getContents();
    return Collection::make(json_decode($response));
  }
  public function deleteProject ($id) {
    $response = $this->client->request('DELETE', $this->url.'/'.$this->version.'/projects/'.$id.'?envelopment=true', ['headers' =>
      [
        'Authorization' => "Bearer {$this->token}"
      ]
    ])
      ->getBody()
      ->getContents();
    return Collection::make(json_decode($response));
  }

  public function updateProject ($id) {
    $response = $this->client->request('PATCH', $this->url.'/'.$this->version.'/projects/'.$id.'?envelopment=true', ['headers' =>
      [
        'Authorization' => "Bearer {$this->token}"
      ]
    ])
      ->getBody()
      ->getContents();
    return Collection::make(json_decode($response));
  }

  public function replaceProject ($id, $body) {
    $body = json_decode ($body);
    $response = $this->client->request('PUT', $this->url.'/'.$this->version.'/projects/'.$id.'?envelopment=true', ['headers' =>
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