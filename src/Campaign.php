<?php
namespace Gigigo\Orchextra\Generation;
use GuzzleHttp\Client;

class Campaign extends BaseCRUD
{
  use Modeleable;
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
    $this->entity = "campaigns";
  }



  /*
   * @param $body
   * @method POST
   * @return collection
   *

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


   * @param $id
   * @param $body
   * @return collection

  public function replaceCampaign ($id, $body) {
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


   * @param $id
   * @return static

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
  */
}