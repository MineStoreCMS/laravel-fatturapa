<?php

namespace Condividendo\FatturaPA\Tags;

use Brick\Math\BigDecimal;
use Condividendo\FatturaPA\Enums\Type;
use Condividendo\FatturaPA\Traits\Makeable;
use DOMDocument;
use DOMElement;
use Illuminate\Support\Carbon;

class GeneralDocumentData extends Tag
{
    use Makeable;

    /**
     * @var \Condividendo\FatturaPA\Tags\DocumentType
     */
    private $type;

    /**
     * @var \Condividendo\FatturaPA\Tags\Currency
     */
    private $currency;

    /**
     * @var \Condividendo\FatturaPA\Tags\Date
     */
    private $date;

    /**
     * @var \Condividendo\FatturaPA\Tags\DocumentNumber
     */
    private $number;

    /**
     * @var ?\Condividendo\FatturaPA\Tags\DocumentAmount
     */
    private $amount = null;

    /**
     * @var ?\Condividendo\FatturaPA\Tags\DocumentDescription
     */
    private $description = null;

    private ?string $bolloVirtuale = null;
    private ?string $importoBollo = null;

    public function setDatiBollo(string $bolloVirtuale, string $importoBollo): self
    {
        $this->bolloVirtuale = $bolloVirtuale;
        $this->importoBollo = $importoBollo;

        return $this;
    }

    public function setType(Type $type): self
    {
        $this->type = DocumentType::make()->setType($type);

        return $this;
    }

    public function setDate(Carbon $date): self
    {
        $this->date = Date::make()->setDate($date);

        return $this;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = Currency::make()->setCurrency($currency);

        return $this;
    }

    public function setDocumentAmount(BigDecimal $amount): self
    {
        $this->amount = DocumentAmount::make()->setDocumentAmount($amount);

        return $this;
    }

    public function setDocumentDescription(string $description): self
    {
        $this->description = DocumentDescription::make()->setDocumentDescription($description);

        return $this;
    }

    public function setDocumentNumber(string $number): self
    {
        $this->number = DocumentNumber::make()->setDocumentNumber($number);

        return $this;
    }

    /**
     * @noinspection PhpUnhandledExceptionInspection
     */
    public function toDOMElement(DOMDocument $dom): DOMElement
    {
        $e = $dom->createElement('DatiGeneraliDocumento');

        $e->appendChild($this->type->toDOMElement($dom));
        $e->appendChild($this->currency->toDOMElement($dom));
        $e->appendChild($this->date->toDOMElement($dom));
        $e->appendChild($this->number->toDOMElement($dom));

        if ($this->bolloVirtuale !== null && $this->importoBollo !== null && $this->bolloVirtuale !== 'NO') {
            $datiBollo = $dom->createElement('DatiBollo');
            $bolloVirtuale = $dom->createElement('BolloVirtuale', $this->bolloVirtuale);
            $importoBollo = $dom->createElement('ImportoBollo', $this->importoBollo);

            $datiBollo->appendChild($bolloVirtuale);
            $datiBollo->appendChild($importoBollo);

            $e->appendChild($datiBollo);
        }

        if ($this->amount) {
            $e->appendChild($this->amount->toDOMElement($dom));
        }

        if ($this->description) {
            $e->appendChild($this->description->toDOMElement($dom));
        }

        return $e;
    }
}
