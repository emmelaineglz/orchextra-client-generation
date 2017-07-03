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
$channel = new Generation\Channel('https://generation-api-coupons.s.gigigoapps.com', 'v1', $token);
/*
$createChannel = $channel->create([
  'name' => 'landing Ethel',
  'type' => 'landingPages',
  'slug' => 'landing-ethel',
  'campaignId' => '5953f59a6c368c7358d0c2de',
  'stock' => '100',
  'clientId' => '595416e4f3eae769b1d693c4',
  'skinId' => '59480e333157b629aab3eaeb']);

print_r ($createChannel);
*/
/*
$collection = $project->all ([
  'with' => [
    'users.clients'
  ]
]);
print_r ($collection->first()->getUsers()->first()->getClients());
*/
$campaign->setWith ( [
    'user',
    'user.clients'
  ]);
$collection = $campaign->all ([
    'fields' => [
      'name',
      'description',
      'user.email',
      'user.clients.clientSecret'
    ],
    'filters' => [
      'name' => 'Campaña de Prueba Ethel Replace 2',
    ]
  ]);
print_r ($collection);
/*
$valores = $collection->first()->toArray();
$valores['name'] = 'Campaña de Prueba Ethel Replace';
$valores['projectId'] = '59493e4b3157b629aab3eaf0';
print_r ($collection->first()->replace($valores));
*/
