<?php

namespace Gigigo\Orchextra\Generation;

class Skin extends BaseCRUD
{
    use Modeleable;

    /**
     * @var array
     */
    protected $models = [
        'user' => 'one'
    ];

    /**
     * @var string
     */
    protected $entity = 'skins';

    /**
     * Campaign constructor.
     * @param $url
     * @param $version
     * @param $token
     */
    public function __construct($url, $version, $token)
    {
        parent::__construct($url, $version);
        $this->setup($token);
    }

    /**
     * @param $token
     */
    private function setup($token)
    {
        parent::setClient(new \GuzzleHttp\Client([
            'headers' => [
                'Authorization' => "Bearer {$token}"
            ]
        ]));
    }
}
