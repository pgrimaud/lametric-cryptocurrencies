<?php

namespace Crypto;

use Crypto\Exception\NotUpdatedException;

class Validator
{
    /**
     * @var array
     */
    private $parameters = [];

    /**
     * @var array
     */
    private $data = [
        'codes'  => [],
        'change' => false,
        'names'  => true,
    ];

    /**
     * Validation constructor.
     * @param $parameters
     */
    public function __construct($parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @throws NotUpdatedException
     */
    public function check()
    {
        // compatibility
        if (isset($this->parameters['currency'])) {
            throw new NotUpdatedException();
        }

        for ($i = 1; $i <= 10; $i++) {
            $key = 'currency' . $i;
            if (isset($this->parameters[$key]) && $this->parameters[$key] !== '') {
                $this->data['codes'][] = strtoupper($this->parameters[$key]);
            }
        }

        if (!count($this->data['codes'])) {
            $this->data['codes'][] = Response::DEFAULT_CRYPTOCURRENCY;
        }

        $this->data['change'] = isset($this->parameters['change']) && strtolower($this->parameters['change']) === 'yes';
        $this->data['name']   = !isset($this->parameters['show_label']) || strtolower($this->parameters['show_label']) === 'yes';
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
