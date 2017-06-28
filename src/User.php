<?php

namespace Gigigo\Orchextra\Generation;
use GuzzleHttp\Client;

class User extends BaseCRUD
{
  use Modeleable;
  /**
   * @var array
   */
  protected $models = [
    'clients' => 'many',
    'projects' => 'many'
  ];

  /**
   * User constructor.
   * @param string $url
   * @param string $version
   * @param $token
   */
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
    $this->entity = "users";
  }
}