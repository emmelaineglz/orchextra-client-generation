<?php
abstract class BaseCRUD
{
    protected $entity;
    protected $attributes = [];
    protected $client;
    public function __construct(Client $client, $entity, array $attributes = [])
    {
        $this->client = $client;
        $this->entity = $entity;
        $this->attributes = $attributes;
    }
    public function fill(array $attributes = [])
    {
        $this->attributes = $attributes;
    }
    public function get($id)
    {
        $response = $this->client->get("{$this->entity}/{$id}")
      ->getBody()
      ->getContents();
        return new Campaign(['name' => 'prueba']);
    }
    public function delete()
    {
        $response = $this->client->delete("{$this->entity}/{$this->attributes['id']}")
      ->getBody()
      ->getContents();
        $sa = $response->getStatus();
        if ($sa == 204) {
            return true;
        }
        return false;
    }
    public function all()
    {
    }
    public function update()
    {
    }
    public function replace()
    {
    }
    public function create(array $attributes)
    {
        if (count($this->attributes) === 0 && count($attributes) === 0) {
            throw new Exception("The data is empty");
        }
    }
}

class Campaign extends BaseCRUD
{
    use Modeleable;
    public function __construct(Client $client, $entity, array $attributes = [])
    {
        parent::__construct($client, $entity, $attributes);
    }
}
$campaign = new Campaign(new Client, 'campaigns');
$_campaingn = $campaign->get('ad32e29h293ue239en2938en2398');
