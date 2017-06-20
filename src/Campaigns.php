<?php
namespace Gigigo\Orchextra\Generation;
use Illuminate\Support\Collection as Collection;

use GuzzleHttp\Client;

class Campaigns {
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
    $filters = '';
    foreach($arrayS as $key => $values) {
      $filters .= $key;
      foreach($values as $item) {
        $filters .= '.'.$item;
      }
      $filters .= ',';
    }
    $this->with = trim($filters,',');
    return $this;
  }

  /**
   * @param $body
   * @return array
   */
  public function setBody($body) {
    $array = json_decode ($body);
    foreach ($array as $key=> $value){
      if($key === 'image'){
        $formData []= ['Content-type' => 'multipart/form-data', 'name' => $key , 'contents' => fopen ($value, 'r')];
      }else{
        $formData []= ['name' => $key , 'contents' => $value];
      }
    }
    return $formData;
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
    return Collection::make (json_decode ($response));
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
    var_dump ($response);
    die();
    return Collection::make(json_decode($response));

  }

  public function deleteCampaign () { /** DEL **/

  }
}