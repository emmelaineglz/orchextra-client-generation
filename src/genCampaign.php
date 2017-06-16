<?php
namespace Gigigo\Orchextra\Generation;
use Illuminate\Support\Collection as Collection;

use GuzzleHttp\Client;

class genCampaign {
  /**
   * @var string
   */
  protected $url;
  /**
   * @var string
   */
  protected $version;
  /**
   * @var string
   */
  protected $with;
  /**
   * Campaign constructor.
   * @param string $url
   * @param string $version
   * @param string $with
   */
  public function __construct ($url = '', $version = '', $with = '')
  {
    $this->setUrl($url);
    $this->setVersion($version);
    if (!empty($with)) {
      $this->setWith($with);
    }
    $this->client = new Client();
  }

  /**
   * @param string $url
   * @return $this
   */
  public function setUrl ($url)
  {
    if (empty($url)) {
      throw new \InvalidArgumentException('The url is required');
    }
    $this->url = $url;
    return $this;
  }

  /**
   * @param float $version
   * @return $this
   */
  public function setVersion ($version)
  {
    if (empty($version)) {
      throw new \InvalidArgumentException('The version is required');
    }
    $this->version = $version;
    return $this;
  }

  /**
   * @param $with
   * @return $this
   * @internal param float $version
   */
  public function setWith ($with)
  {
    $arrayS = json_decode($with);
    foreach ($arrayS as $valor => $key){
      echo $valor;
      foreach ($key as $value){
        echo $value;
      }
    }

    die();
    $this->with = $with;
    return $this;
  }

  /**
   * @param $token
   * @return Campaign
   */
  public function getCampaigns ($token) {
    $response = $this->client->request('GET', $this->url.'/'.$this->version.'/campaigns?with='.$this->with, ['headers' =>
      [
        'Authorization' => "Bearer {$token}"
      ]
    ])
      ->getBody()
      ->getContents();
    return Collection::make(json_decode($response));
  }
}