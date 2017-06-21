<?php

namespace Gigigo\Orchextra\Generation;


class Generation
{
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
}