<?php
require "vendor/autoload.php";
use Gigigo\Orchextra\Auth;
use Gigigo\Orchextra\Generation;

$auth = new Auth('https://auth-api-coupons.s.gigigoapps.com');
$client = $auth->authClient('qwerty', 'qwerty');
$token = $auth->getToken();
//$token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJwdWJsaWNJZCI6IjU5MmVlODQxYjQ2YzJjYmRjMmFjZTU4ZCIsIm5hbWUiOiJEYXNoYm9hcmQiLCJ0eXBlIjoiZ2VuZXJhdGlvbiIsImxhc3RSZXF1ZXN0IjoiMjAxNy0wNi0wN1QxNTozNjowOC45NTFaIiwiaWF0IjoxNDk2ODQ5NzY4fQ.oJZzkIyvDGrycnc1e91SDUzDdY-VZwdcHdIot6m7L7Y';
$skin = new Generation\Skin('https://generation-api-coupons.s.gigigoapps.com', 'v1', $token);
$campaign = new Generation\Campaign('https://generation-api-coupons.s.gigigoapps.com', 'v1', $token);
$project = new Generation\Project('https://generation-api-coupons.s.gigigoapps.com', 'v1', $token);
/*
$collection = $project->all ([
  'with' => [
    'users.clients'
  ]
]);
print_r ($collection->first()->getUsers()->first()->getClients());
*/

$collection = $campaign->all (
  [
    'with' => [
      'user',
      'user.clients'
    ]
  ]);
$valores = $collection->first()->toArray();
$valores['name'] = 'Campaña de Prueba Ethel Replace';
$valores['projectId'] = '59493e4b3157b629aab3eaf0';
//print_r ($valores);
print_r ($collection->first()->replace($valores));

/*
$collection = $campaign->create ([
  'projectId' => '5949934b3157b629aab3eb24',
  'userId' => '592ee7d0b46c2cbdc2ace58c',
  'name' => 'campaña Emme4',
  'description' => 'campaña prueba',
  'legals' => 'legales',
  'type' => 'digital',
  'startDate' => '2017-06-19',
  'expirationDate' => '2018-06-19',
  'image' => '/home/ethelgonzalez/Imágenes/facebook_318-136394.jpg']);
print_r($collection->getStatus());
*/
/*
$collection = $campaign->get ('5953f38b6c368c7358d0c2d7');
$update = $collection->update(['name' => 'eeeeeee uuuuuuu']);
print_r ($update);*/
/*$collection->replace ([
  'projectId' => '5949957e3157b629aab3eb28',
  'name' => 'Emmelaine Campaign',
  'description' => 'Esto es una prueba',
  'legals' => 'legales',
  'type' => 'digital',
  'startDate' => '2017-06-19T00:00:00.000Z',
  'expirationDate' => '2018-06-19T00:00:00.000Z',
  'image' => ['hola.jpg']
  ]);*/
//print_r ($collection->delete ());
/*
$body = '{
    "expirationDate": "2018-06-19",
    "startDate": "2017-06-19",
    "legals": "legales",
    "description": "Esto es una prueba",
    "type": "digital",
    "projectId": "592ef58012638c2edb5e45a8",
    "userId": "592ee7d0b46c2cbdc2ace58c",
    "name": "campaña Ethel",
    "image": "/home/ethelgonzalez/Imágenes/facebook_318-136394.jpg"}';


 ['with' => [
  'user.clients',
  'user.clients.customers'
],
  'field' => [
    'name',
    'description'
  ],
  'search' => [
    'name' => 'Ethel'
  ]
]
$collection2 = $campaign->createCampaign ($body);
print_r ($collection2->image);

$id = '5941b62e3157b629aab3eaba';
$collection3 = $campaign->getCampaign ($id);
print_r ($collection3);

$id = '5941b62e3157b629aab3eaba';
$collection5 = $campaign->replaceCampaign ($id, $body);
print_r ($collection5);
*/
/*
$collection4 = $channels->getChannels ($jsonChannels);
print_r ($collection4->last()->name);

$bodyChannel = '{
  "name" : "landing Ethel 1",
  "type" : "landingPages",
  "slug": "landing-ethel",
  "campaignId" : "5941b6163157b629aab3eab9",
  "stock": 100,
  "clientId": "5942b9b63157b629aab3eabc",
  "skinId": "59480e333157b629aab3eaeb"
}';
$collection6 = $channels->createChannel ($bodyChannel);
print_r ($collection6);

$collection7 = $channels->getChannel ('594aa98f3157b629aab3eb2e');
print_r ($collection7);

$bodyChannel = '{
    "campaignId": "5941b6163157b629aab3eab9",
    "clientId": "5942b9b63157b629aab3eabc",
    "type": "landingPages",
    "name": "Prueba 5 Ethel",
    "stock": 2500,
    "couponValidity": 12,
    "slug": "dsfasdf",
    "skinId": "59494b013157b629aab3eaf1"
  }';
$collection8 = $channels->replaceChannel ('594bf08c3157b629aab3eb5c', $bodyChannel);
print_r ($collection8);

$collection9 = $clients->getClients ($jsonClients);
print_r ($collection9);

$collection9 = $clients->getClient ('592ee841b46c2cbdc2ace58d');
print_r ($collection9);

$bodyClient = '{
  "type": "emission",
  "name": "Emission Ethel",
  "clientId": "ethelglz3",
  "clientSecret": "ethelglz3",
  "userId": "594ae5eb3157b629aab3eb41"
}';

$collection10 = $clients->createClient ($bodyClient);
print_r ($collection10);

$bodyClient = '{
    "type": "generation",
    "name": "Generation Emission Ethel",
    "clientId": "ethelglz5"}';
$collection11 = $clients->updateClient ('594bfa063157b629aab3eb67', $bodyClient);

$bodyUser = '{
  "name": "Ethel E Gonzalez",     
  "email": "eth.gonzalez@gigigo.com.mx",
  "username": "eth.gonzalez",
  "role": "superadmin",
  "password": "gigigo10",
  "passwordConfirmation": "gigigo10",
  "projectsIds": ["5936cb98d318c404f94951e2"],
  "languageCode": "es"
  
}';*/