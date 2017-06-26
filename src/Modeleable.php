<?php

namespace Gigigo\Orchextra\Generation;
use Illuminate\Support\Collection as Collection;

trait Modeleable{
  public function __call($name, $arguments)
  {
    if(substr($name, 0, 3) === 'get'){
      $name = strtolower(str_replace('get', '', $name));
      if(!isset($this->attributes[$name])){
        return null;
      }
      if(is_array($this->attributes[$name])){
        return new Collection($this->attributes[$name]);
      }
      return $this->attributes[$name];
    }
    $name = strtolower(str_replace('set', '', $name));
    return $this->attributes[$name] = $arguments[0];
  }
  public function getOriginalAttributes()
  {
    return $this->attributes;
  }
}