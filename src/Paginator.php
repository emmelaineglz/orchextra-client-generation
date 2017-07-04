<?php

namespace Gigigo\Orchextra\Generation;

class Paginator extends BaseCRUD
{
  protected $headers;
  protected $response;
  protected $newArray;
public function __construct ($url, $version, $token, array $headers)
{
  $this->token = $token;
  $this->url = $url;
  $this->version = $version;
  if(!isset($this->headers)) {
    foreach ($headers as $item) {
      $newString = $this->clean ($item);
      $newValor = explode (';', $newString);
      $newArray[$newValor[1]] = $newValor[0];
    }
    $this->headers = $newArray;
  }
  $this->firstPage ();
}
public function clean ($string) {
  $string = str_replace ('"','',$string);
  $string = str_replace (' ', '', $string);
  $string = str_replace ('rel=', '', $string);
  $string = str_replace ('<', '', $string);
  $string = str_replace ('>', '', $string);
  return $string;
}
public function firstPage() {
  if(array_key_exists ('first', $this->headers)){
    $this->page = $this->headers['first'];
  }
  return $this->all();
}

public function nextPage () {

}

public function lastPage() {

}

public function prev () {

}
}
