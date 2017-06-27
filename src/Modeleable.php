<?php

namespace Gigigo\Orchextra\Generation;
use Illuminate\Support\Collection as Collection;

/**
 * Trait Modeleable
 * @package Gigigo\Orchextra\Generation
 */
trait Modeleable{
  /**
   * @param $model
   * @param array $attributes
   * @return mixed
   */
  public function createInstance($model, array $attributes = [])
  {
    if(!class_exists($model)){
      throw new \RuntimeException("The model {$model} if no exists");
    }

    $instance = new $model($this->url, $this->version, $this->token);
    $instance->fill($attributes);

    return $instance;
  }

  /**
   * @param $name
   * @param $arguments
   * @return Collection|null
   */
  public function __call($name, $arguments)
  {
    if(substr($name, 0, 3) === 'get'){
      $action = strtolower(substr($name, 0, 3));
      $model = trim(str_replace($action, '', $name), 's');
      $name = strtolower(str_replace($action, '', $name));
      $modelNamespaced = __NAMESPACE__."\\".$model;

      if(!isset($this->attributes[$name])){
        return null;
      }
      if(is_array($this->attributes[$name]) && !in_array ($name, array_keys ($this->models))){
        echo "entre en coleccion simple";
        return new Collection($this->attributes[$name]);
      }
      if(is_array ($this->attributes[$name]) && $this->models[$name] === 'many') {
        echo "entre en coleccion de instancias";
        $collection = Collection::make();
        foreach($this->attributes[$name] as $item){
            $collection->push ($this->createInstance ($modelNamespaced, $item));
        }

        $this->attributes[$name] = $collection;

        return $this->attributes[$name];
      }
      echo "solo retornare una instancia simple";
      return $this->createInstance ($modelNamespaced, $this->attributes[$name]);
    }

    $name = strtolower(str_replace('set', '', $name));
    return $this->attributes[$name] = $arguments[0];
  }

  /**
   * @return mixed
   */
  public function getOriginalAttributes()
  {
    return $this->attributes;
  }
}