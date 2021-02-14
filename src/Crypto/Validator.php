<?php

declare(strict_types=1);

namespace Crypto;

use Crypto\Exception\NotUpdatedException;

class Validator
{
    /**
     * @var array
     */
    private array $parameters = [];

    /**
     * @var array
     */
    private array $data = [
        'codes'    => [],
        'change'   => false,
        'names'    => true,
        'currency' => 'USD',
    ];

    /**
     * Validation constructor.
     * @param $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @throws NotUpdatedException
     */
    public function check(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $key = 'currency' . $i;
            if (isset($this->parameters[$key]) && $this->parameters[$key] !== '') {
                $this->data['codes'][] = strtoupper($this->parameters[$key]);
            }
        }

        if (!count($this->data['codes'])) {
            $this->data['codes'][] = Response::DEFAULT_CRYPTOCURRENCY;
        }

        $this->data['change']   = isset($this->parameters['change']) && strtolower($this->parameters['change']) === 'yes';
        $this->data['names']    = !isset($this->parameters['show_label']) || strtolower($this->parameters['show_label']) === 'yes';
        $this->data['position'] = $this->parameters['position'] ?? Response::POSITION_AFTER; // after is default position
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
