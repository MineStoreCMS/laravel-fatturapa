<?php

namespace Condividendo\FatturaPA\Entities;

use Brick\Math\BigDecimal;
use Condividendo\FatturaPA\Tags\Item as ItemTag;
use Condividendo\FatturaPA\Traits\Makeable;
use Condividendo\FatturaPA\Traits\UsesDecimal;
use Condividendo\FatturaPA\Enums\Nature;

class Item extends Entity
{
    use Makeable;
    use UsesDecimal;

    /**
     * @var int
     */
    private $lineNumber;

    /**
     * @var string
     */
    private $description;

    /**
     * @var ?\Brick\Math\BigDecimal
     */
    private $quantity = null;

    /**
     * @var \Brick\Math\BigDecimal
     */
    private $unitPrice;

    /**
     * @var ?\Brick\Math\BigDecimal
     */
    private $totalPrice = null;

    /**
     * @var \Brick\Math\BigDecimal
     */
    private $taxRate;

    /**
     * @var ?\Condividendo\FatturaPA\Enums\Nature
     */
    private $nature;

    public function number(int $lineNumber): self
    {
        $this->lineNumber = $lineNumber;

        return $this;
    }

    public function description(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param string|\Brick\Math\BigDecimal $quantity
     * @return $this
     */
    public function quantity($quantity): self
    {
        if (is_numeric($quantity)) {
            if ($quantity instanceof \Brick\Math\BigDecimal) {
                $this->quantity = $quantity;
            } else {
                $formattedValue = number_format((float)$quantity, 2, '.', '');
                $this->quantity = \Brick\Math\BigDecimal::of($formattedValue);
            }
        } else {
            $this->quantity = \Brick\Math\BigDecimal::of('0.00');
        }

        return $this;
    }

    /**
     * @param string|\Brick\Math\BigDecimal $unitPrice
     * @return $this
     */
    public function price($unitPrice): self
    {
        $this->unitPrice = static::makeDecimal($unitPrice);

        return $this;
    }

    /**
     * @param string|\Brick\Math\BigDecimal $totalPrice
     * @return $this
     */
    public function totalAmount($totalPrice): self
    {
        $this->totalPrice = static::makeDecimal($totalPrice);

        return $this;
    }

    /**
     * @param string|\Brick\Math\BigDecimal $rate
     * @return $this
     */
    public function taxRate($rate): self
    {
        $this->taxRate = static::makeDecimal($rate);

        return $this;
    }

    /**
     * @param \Condividendo\FatturaPA\Enums\Nature $nature
     * @return $this
     */
    public function nature(Nature $nature): self
    {
        $this->nature = $nature;

        return $this;
    }

    public function getTag(): ItemTag
    {
        $tag = ItemTag::make()
            ->setLineNumber($this->lineNumber)
            ->setDescription($this->description)
            ->setTaxRate($this->taxRate)
            ->setUnitPrice($this->unitPrice)
            ->setTotalAmount($this->totalPrice ?: $this->calculateTotalAmount())
            ->setNature($this->nature);

        if ($this->quantity) {
            $tag->setQuantity($this->quantity);
        }

        return $tag;
    }

    private function calculateTotalAmount(): BigDecimal
    {
        return $this->quantity
            ? $this->unitPrice->multipliedBy($this->quantity)
            : $this->unitPrice;
    }
}
