<?php
namespace Gigigo\Orchextra\Generation;
use GuzzleHttp\Client;

class Project extends BaseCRUD
{
  use Modeleable;
  /**
   * @var array
   */
  protected $models = [
    'user' => 'many',
    'clients' => 'many'
  ];

  /**
   * Project constructor.
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
    $this->entity = "projects";
  }
}