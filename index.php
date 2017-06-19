<?php
require "vendor/autoload.php";
use Gigigo\Orchextra\Auth;
use Gigigo\Orchextra\Generation;

$auth = new Auth('https://auth-api-coupons.s.gigigoapps.com');
$client = $auth->authClient('qwerty', 'qwerty');
$token = $auth->getToken();
$json = '{"user": ["clients", "customers"], "project": ["users"]}';
$campaign = new Generation\Campaigns('https://generation-api-coupons.s.gigigoapps.com', 'v1', $token);
/*
$collection = $campaign->getCampaigns ($json);
print_r ($collection->all());
*/
$body = '{
    "expirationDate": "2018-06-19",
    "startDate": "2017-06-19",
    "legals": "legales",
    "description": "Esto es una prueba",
    "type": "digital",
    "name": "campaña 6",
    "projectId": "592ef58012638c2edb5e45a8",
    "userId": "592ee7d0b46c2cbdc2ace58c"}';
$collection2 = $campaign->createCampaign ($body);
print_r ($collection2);
/**
{
"user": ["clients", "customers"],
"project": ["users"]
}
 **/