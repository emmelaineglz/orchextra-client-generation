<?php
namespace Gigigo\Orchextra\Generation;
use GuzzleHttp\Client;

class Channel extends BaseCRUD
{
  use Modeleable;
  /**
   * @var array
   */
  protected $models = [
    'client' => 'one',
    'campaign' => 'one',
    'customers' => 'many',
    'user' => 'one'
  ];

  /**
   * Channel constructor.
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
    $this->entity = "channels";
  }
}