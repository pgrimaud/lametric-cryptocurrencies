<?php
namespace Crypto;

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
        'change' => false
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
     * @return void
     */
    public function check()
    {
        if (isset($this->parameters['currency']) && strpos($this->parameters['currency'], ',') !== false) {
            $codes = explode(',', strtoupper($this->parameters['currency']));
            foreach ($codes as $code) {
                if ($code !== '') {
                    $this->data['codes'][] = $code;
                }
            }
        } else if (isset($this->parameters['currency'])) {
            $this->data['codes'][] = strtoupper($this->parameters['currency']);
        } else {
            $this->data['codes'][] = 'BTC';
        }

        $this->data['change'] = isset($this->parameters['change']) && $this->parameters['change'] === 'yes';
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
