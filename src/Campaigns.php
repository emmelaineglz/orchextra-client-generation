<?php
namespace Gigigo\Orchextra\Generation;
use Illuminate\Support\Collection as Collection;

use GuzzleHttp\Client;

class Campaigns extends BaseCRUD
{
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
  }
  public function setUrl($url){
    if (empty($url)) {
      throw new \InvalidArgumentException('the url is required');
    }
    return $this->url = $url;

  }

  public function setVersion($version)
  {
    if (empty($version)) {
      throw new \InvalidArgumentException('the version is required');
    }
    return $this->version = $version;
  }
  /**
   * @param $with
   * @method GET
   * @return collection
   */
  public function getCampaigns ($with) {
    if (!empty($with)) {
      $this->setWith($with);
    }
    $response = $this->client->request('GET', $this->url.'/'.$this->version.'/campaigns?with='.$this->with, ['headers' =>
      [
        'Authorization' => "Bearer {$this->token}"
      ]
    ])
      ->getBody()
      ->getContents();
    return Collection::make(json_decode($response));
  }

  /**
   * @param $body
   * @method POST
   * @return collection
   *
   */
  public function createCampaign ($body) {
    $body = $this->setBody ($body);
    $response = $this->client->request ('POST', $this->url.'/'.$this->version.'/campaigns', [
      'headers' => [
        'Authorization' => "Bearer {$this->token}"
      ],
      'multipart' => $body
    ])
      ->getBody()
      ->getContents();
    return Collection::make ([json_decode ($response)]);
  }

  /**
   * @param $id
   * @method GET
   * @return collection
   */
  public function getCampaign ($id) {
    $response = $this->client->request('GET', $this->url.'/'.$this->version.'/campaigns/'.$id, ['headers' =>
      [
        'Authorization' => "Bearer {$this->token}"
      ]
    ])
      ->getBody()
      ->getContents();
    return Collection::make(json_decode($response));
  }

  /**
   * @param $id
   * @param $body
   * @return collection
   */
  public function replaceCampaign ($id, $body) { /** PUT **/
  $body = json_decode($body);
    $response = $this->client->request('PUT', $this->url.'/'.$this->version.'/campaigns/'.$id, ['headers' =>
      [
        'Authorization' => "Bearer {$this->token}"
      ],
      'json' =>  $body
    ])
      ->getBody()
      ->getContents();
    return Collection::make(json_decode($response));

  }

  /**
   * @param $id
   * @return static
   */
  public function deleteCampaign ($id) {
    $response = $this->client->request('DELETE', $this->url.'/'.$this->version.'/campaigns/'.$id, ['headers' =>
      [
        'Authorization' => "Bearer {$this->token}"
      ]
    ])
      ->getBody()
      ->getContents();
    return Collection::make(json_decode($response));
  }
}