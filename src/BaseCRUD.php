<?php

namespace Gigigo\Orchextra\Generation;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Support\Collection as Collection;

abstract class BaseCRUD
{
    /**
     * @var bool
     */
    protected $requiredFiles = false;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $version;

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var $entity
     */
    protected $entity;

    /**
     * @var $token
     */
    protected $token;

    /**
     * @var array
     */
    protected $filters = [];

    /**
     * @var array
     */
    protected $fields = [];

    /**
     * @var array
     */
    protected $with = [];

    /**
     * @var array
     */
    protected $pagination = [
        'perPage' => 10,
        'paginate' => true,
    ];

    /**
     * @var
     */
    protected $envelopment = [
        'envelopment' => false
    ];

    /**
     * @var array
     */
    protected $totalCount;

    /**
     * BaseCRUD constructor.
     *
     * @param $url
     * @param $version
     */
    public function __construct($url, $version)
    {
        $this->setUrl($url);
        $this->setVersion($version);
    }

    /**
     * @param ClientInterface $client
     *
     * @return $this
     */
    protected function setClient(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param $url
     * @return mixed
     */
    public function setUrl($url)
    {
        if (empty($url)) {
            throw new \InvalidArgumentException('the url is required');
        }
        return $this->url = $url;
    }

    /**
     * @param $version
     * @return mixed
     */
    public function setVersion($version)
    {
        if (empty($version)) {
            throw new \InvalidArgumentException('the version is required');
        }
        return $this->version = $version;
    }

    /**
     * @param $with
     * @return $this
     */
    public function setWith(array $with = [])
    {
        $this->with = implode(',', $with);

        return $this;
    }

    /**
     * @param array $fields
     *
     * @return $this
     */
    public function setFields(array $fields = [])
    {
        $this->fields = implode(',', $fields);

        return $this;
    }

    /**
     * @param array $filters
     *
     * @return $this
     */
    public function setFilters(array $filters = [])
    {
        $this->filters = http_build_query($filters);

        return $this;
    }

    /**
     * @param bool $paginate
     * @param int $perPage
     *
     * @return $this
     */
    public function setPagination($paginate = true, $perPage = 10)
    {
        $this->pagination['paginate'] = $paginate;
        $this->pagination['perPage'] = $perPage;

        return $this;
    }

    /**
     * @param bool $envelopment
     * @return $this
     */
    public function setEnvelopment($envelopment = false)
    {
        $this->envelopment = $envelopment;

        return $this;
    }

    /**
     * @param $url
     * @param $version
     * @param $token
     * @param array $attributes
     *
     * @return static
     */
    public static function createFromResponse($url, $version, $token, array $attributes = [])
    {
        $instance = new static($url, $version, $token);
        $instance->fill($attributes);

        return $instance;
    }

    /**
     * @param array $attributes
     */
    public function fill(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    /**
     * @param $id
     *
     * @return BaseCRUD
     */
    public function get($id)
    {
        $response = $this->client->get("{$this->url}/{$this->version}/{$this->entity}/{$id}")
            ->getBody()
            ->getContents();

        return static::createFromResponse($this->url, $this->version, $this->token, json_decode($response, true));
    }

    /**
     * @return string
     */
    protected function buildQueryString()
    {
        $params = [];

        if ($this->with) {
            $params['with'] = $this->with;
        }

        if ($this->fields) {
            $params['fields'] = $this->fields;
        }

        if ($this->filters) {
            $params['filters'] = $this->filters;
        }

        $params['paginate'] = $this->pagination['paginate'];
        $params['perPage'] = $this->pagination['perPage'];
        $params['envelopment'] = $this->envelopment;

        return "?".http_build_query($params);
    }

    /**
     * @return Collection
     */
    public function all()
    {
        $response = $this->client->get("{$this->url}/{$this->version}/{$this->entity}{$this->buildQueryString()}")
            ->getBody()
            ->getContents();

        $collection = Collection::make();
        foreach (json_decode($response, true) as $value) {
            $collection->push(static::createFromResponse($this->url, $this->version, $this->token, $value));
        }

        return $collection;
    }

    /**
     * @param array $body
     * @return BaseCRUD
     */
    public function replace(array $body = [])
    {
        $response = $this->client->put("{$this->url}/{$this->version}/{$this->entity}/{$this->attributes['id']}{$this->buildQueryString()}", [
            'json' => $body
        ])
            ->getBody()
            ->getContents();

        return static::createFromResponse($this->url, $this->version, $this->token, json_decode($response, true));
    }

    /**
     * @param array $data
     *
     * @return BaseCRUD
     */
    public function create(array $data = [])
    {
        $parameters = [];

        if ($this->requiredFiles) {
            $fields = [];
            foreach ($data as $key => $value) {
                if (is_file($value)) {
                    $fields[] = [
                        'name' => $key,
                        'contents' => fopen($value, 'r'),
                        'filename' => basename($value)
                    ];
                } else {
                    $fields[] = [
                        'name' => $key,
                        'contents' => $value
                    ];
                }
            }

            $parameters['multipart'] = $fields;
        } else {
            $parameters['json'] = $data;
        }


        $response = $this->client->post("{$this->url}/{$this->version}/{$this->entity}{$this->buildQueryString()}", $parameters)
            ->getBody()
            ->getContents();

        return static::createFromResponse($this->url, $this->version, $this->token, json_decode($response, true));
    }

    /**
     * @return bool
     */
    public function delete()
    {
        try {
            $response = $this->client->delete("{$this->url}/{$this->version}/{$this->entity}/{$this->attributes['id']}{$this->buildQueryString()}")
                ->getBody()
                ->getContents();

            $data = json_decode($response);

            return $data->status;
        } catch (ClientException $e) {
            return false;
        } catch (ServerException $e) {
            return false;
        }
    }

    /**
     * @param array $data
     *
     * @return BaseCRUD
     */
    public function update(array $data = [])
    {
        $response = $this->client->patch("{$this->url}/{$this->version}/{$this->entity}/{$this->attributes['id']}{$this->buildQueryString()}", [
            'json' => $data
        ])
            ->getBody()
            ->getContents();

        return static::createFromResponse($this->url, $this->version, $this->token, json_decode($response, true));
    }
}
