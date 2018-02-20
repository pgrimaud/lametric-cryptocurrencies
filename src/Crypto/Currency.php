<?php
namespace Crypto;

class Currency
{
    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $name;

    /**
     * @var float
     */
    private $price;

    /**
     * @var float
     */
    private $change;

    /**
     * @var bool
     */
    private $showChange;

    /**
     * Currency constructor.
     * @param array $parameters
     */
    public function __construct($parameters = [])
    {
        $this->code       = strtoupper(isset($parameters['currency']) ? $parameters['currency'] : 'BTC');
        $this->showChange = isset($parameters['change']) && $parameters['change'] === 'yes';
    }

    /**
     * @return bool
     */
    public function hasShowChange()
    {
        return $this->showChange;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return float
     */
    public function getChange()
    {
        return $this->change;
    }

    /**
     * @param float $change
     */
    public function setChange($change)
    {
        $this->change = $change;
    }
}
