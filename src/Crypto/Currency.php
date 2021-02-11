<?php

declare(strict_types=1);

namespace Crypto;

class Currency
{
    /**
     * @var string
     */
    private string $code;

    /**
     * @var bool
     */
    private bool $showName = false;

    /**
     * @var float
     */
    private float $price;

    /**
     * @var float
     */
    private float $change;

    /**
     * @var bool
     */
    private bool $showChange;

    /**
     * @return bool
     */
    public function hasShowChange(): bool
    {
        return $this->showChange;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * @return float
     */
    public function getChange(): float
    {
        return round($this->change, 3);
    }

    /**
     * @param float $change
     */
    public function setChange(float $change): void
    {
        $this->change = $change;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return bool
     */
    public function isShowChange(): bool
    {
        return $this->showChange;
    }

    /**
     * @param bool $showChange
     */
    public function setShowChange(bool $showChange): void
    {
        $this->showChange = $showChange;
    }

    /**
     * @param bool $showName
     */
    public function setName(bool $showName)
    {
        $this->showName = $showName;
    }

    /**
     * @return bool
     */
    public function showName(): bool
    {
        return $this->showName;
    }
}
