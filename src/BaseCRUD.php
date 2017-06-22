<?php

namespace Gigigo\Orchextra\Generation;
use Illuminate\Support\Collection as Collection;

use GuzzleHttp\Client;

abstract class BaseCRUD
{
  protected $entity;
  protected $attributes = [];
  protected $client;

  /**
   * BaseCRUD constructor.
   * @param Client $client
   * @param $entity
   * @param array $attributes
   */
  public function __construct(Client $client, $entity, array $attributes = [])
  {
    $this->client = $client;
    $this->entity = $entity;
    $this->attributes = $attributes;
  }

  public function fill(array $attributes = [])
  {
    $this->attributes = $attributes;
  }

  public function get()
  {
    $response = $this->client->get("{$this->entity}/{$id}")
      ->getBody()
      ->getContents();
    return new Campaign(['name' => 'prueba']);
  }
}