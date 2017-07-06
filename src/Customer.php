<?php
namespace Gigigo\Orchextra\Generation;

use GuzzleHttp\Client;

class Customer extends BaseCRUD
{
    use Modeleable;
  /**
   * @var array
   */
  protected $models = [
    'project' => 'one',
    'client' => 'one'
  ];

  /**
   * Customer constructor.
   * @param string $url
   * @param string $version
   * @param $token
   */
  public function __construct($url = '', $version = '', $token)
  {
      if (empty($token)) {
          throw new \InvalidArgumentException('the token is required');
      } else {
          $this->token = $token;
      }
      $this->setUrl($url);
      $this->setVersion($version);
      $this->client = new Client();
      $this->entity = "customers";
  }
}
