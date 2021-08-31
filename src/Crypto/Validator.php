<?php

declare(strict_types=1);

namespace Crypto;

use Crypto\Exception\NotUpdatedException;

class Validator
{
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
    public function __construct(private array $parameters)
    {
    }

    /**
     * @throws NotUpdatedException
     */
    public function check(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            $key = 'currency' . $i;
            if (isset($this->parameters[$key]) && $this->parameters[$key] !== '') {
                $this->data['codes'][] = strtoupper(trim($this->parameters[$key]));
            }
        }

        if (!count($this->data['codes'])) {
            $this->data['codes'][] = Response::DEFAULT_CRYPTOCURRENCY;
        }

        $this->data['change']   = isset($this->parameters['change']) && strtolower($this->parameters['change']) === 'yes';
        $this->data['names']    = !isset($this->parameters['show_label']) || strtolower($this->parameters['show_label']) === 'yes';
        $this->data['position'] = $this->parameters['position'] ?? Response::POSITION_AFTER; // after is default position
        $this->data['currency'] = (isset($this->parameters['currency']) && $this->parameters['currency'] !== '') ?
            strtoupper((string) $this->parameters['currency']) : 'USD'; // USD is default

        // fix for currencies (BC)
        $this->data['currency'] = $this->data['currency'] !== '<NULL>' ? $this->data['currency'] : 'USD';

        if (str_contains($this->data['currency'], ',')) {
            $this->data['currency'] = explode(',', $this->data['currency'])[0];
        }

    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
