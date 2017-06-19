<?php
require "vendor/autoload.php";
use Gigigo\Orchextra\Auth;
use Gigigo\Orchextra\Generation;

$auth = new Auth('https://auth-api-coupons.s.gigigoapps.com');
$client = $auth->authClient('qwerty', 'qwerty');
$token = $auth->getToken();
$json = '{"user": ["clients", "customers"], "project": ["users"]}';
$campaign = new Generation\genCampaign('https://generation-api-coupons.s.gigigoapps.com', '', $json);
$collection = $campaign->getCampaigns ($token);
print_r ($collection->all());

/**
{
"user": ["clients", "customers"],
"project": ["users"]
}
 **/