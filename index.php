<?php
require "vendor/autoload.php";
use Gigigo\Orchextra\Auth;
use Gigigo\Orchextra\Generation;

$auth = new Auth('https://auth-api-coupons.s.gigigoapps.com');
$client = $auth->authClient('qwerty', 'qwerty');
$token = $auth->getToken();
$jsonCampaign = '{"user": ["clients", "customers"], "project": ["users"]}';
$jsonChannels = '{"client": ["customers", "user"], "campaign": ["project"]}';
$campaign = new Generation\Campaigns('https://generation-api-coupons.s.gigigoapps.com', 'v1', $token);
$channels = new Generation\Channels('https://generation-api-coupons.s.gigigoapps.com', 'v1', $token);
/*
$collection = $campaign->getCampaigns ($jsonCampaign);
print_r ($collection->all());
*/
$body = '{
    "image": "/home/ethelgonzalez/Imágenes/facebook_318-136394.jpg",
    "expirationDate": "2018-06-19",
    "startDate": "2017-06-19",
    "legals": "legales",
    "description": "Esto es una prueba",
    "type": "digital",
    "projectId": "592ef58012638c2edb5e45a8",
    "userId": "592ee7d0b46c2cbdc2ace58c",
    "name": "campaña 7"}';

/*
$collection2 = $campaign->createCampaign ($body);
print_r ($collection2);


$id = '5941b62e3157b629aab3eaba';
$collection3 = $campaign->getCampaign ($id);
print_r ($collection3);

*/
$id = '5949a2a83157b629aab3eb2a';
$collection5 = $campaign->replaceCampaign ($id, $body);
print_r ($collection5);
/*
$collection4 = $channels->getChannels ($jsonChannels);
print_r ($collection4->last());
*/
/**
{
"user": ["clients", "customers"],
"project": ["users"]
}
 **/