<?php
namespace Gigigo\Orchextra\Generation;
use GuzzleHttp\Client;

class Campaign extends BaseCRUD
{
  use Modeleable;

  protected $models = [
    'user' => 'one',
    'project' => 'one',
    'channels' => 'many'
  ];

  /**
   * Campaign constructor.
   * @param string $url
   * @param string $version
   * @param $token
   */
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
    $this->entity = "campaigns";
  }
}