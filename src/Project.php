<?php

namespace Gigigo\Orchextra\Generation;

class Project extends BaseCRUD
{
    use Modeleable;

    /**
     * @var array
     */
    protected $models = [
        'users' => 'many'
    ];

    /**
     * @var string
     */
    protected $entity = 'projects';

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
