<?php

namespace Gigigo\Orchextra\Generation;

class Campaign extends BaseCRUD
{
    use Modeleable;

    /**
     * @var bool
     */
    protected $requiredFiles = true;

    /**
     * @var string
     */
    protected $entity = 'campaigns';

    /**
     * @var array
     */
    protected $models = [
        'user' => 'one',
        'project' => 'one',
        'channels' => 'many'
    ];

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
