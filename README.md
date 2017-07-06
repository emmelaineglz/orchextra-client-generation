# orchextra-coupons-generation

SDK para consumo de servicios de Generación de Cupones
## Instalación por composer

```
composer require emmelaineglz/orchextra-coupons-generation-php
```

Para poder tener acceso a este SDK, es necesario autenticarse.

```
composer require emmelaineglz/orchextra-client-php
```

## Ejemplo de uso con autenticación

```php
require "vendor/autoload.php";
use Gigigo\Orchextra\Auth;
use Gigigo\Orchextra\Generation;
```
## Instanciamos la clase

```php
$auth = new Auth('https://ejemplo.com.mx');
```

## Hacemos referencia al método de Autentucación del Cliente y llamamos al metodo "getToken()" para obtener el token de acceso.

```php
$client = $auth->authClient('cliente1', '12345');
$token = $auth->getToken();
```

## Ahora instanciamos la clase, a la cual vamos a enviar url, version y el token.

```php
$campaign = new Generation\Campaign('https://ejemplo.com.mx', 'v1', $token);
```

## Ya con la intancia, podemos acceder a sus metodos, para settera nuestros parametros, se puede hacer uso de los setters o enviar un arreglo que contenga lo que necesitamos.

```php
$campaign->setWith ( [
    'user',
    'user.clients'
  ]);
$campaign->setFields ( [
    'name',
    'description'
  ]);
$campaign->setFilters ( [
    'name' => 'Campaña 1',
    'description' => 'Campaña de promoción'
  ]);
$campaign->setPagination ( [
  'perPage' => 3,
  'page' => 2
]);
$collection = $campaign->all ([
    'with' => [
      'user',
      'user.clients'
    ],
    'fields' => [
      'name',
      'description',
      'user.email',
      'user.clients.clientSecret'
    ],
    'filters' => [
      'name' => 'Campaña de Prueba Ethel Replace 2',
    ],
    'pagination' => [
        'perPage' => 3
        'page' => 2
        ]
  ]);
```

## Las posibles respuestas obtenidas, serán una colleccion de instancias de objetos, una instancia sencilla, o un Array.
## Accediendo a las colecciones de la siguiente manera:

```php

$collection->first();
```
## Convertir en un array
```php

$collection->first()->toArray();
```

## Aplicar acciones
```php

$collection->first()->replace();
$collection->first()->update();
$collection->first()->delete();
```

## Acceder a las propiedades de manera directa
```php

$collection->first()->name;
$collection->first()->description;
```