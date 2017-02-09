<?php

namespace Pronamic\Twinfield\PurchaseInvoice;

use Pronamic\Twinfield\Currency;

/**
 * Class PurchaseInvoice
 *
 * @author Emile Bons <emile@emilebons.nl>
 * @package Pronamic\Twinfield\PurchaseInvoice
 */
class BasePurchaseInvoice {

    /**
     * @var Currency the currency of the purchase invoice
     */
    private $currency;
    /**
     * @var string the date of the purchase invoice
     */
    private $date;
    /**
     * @var string the date of input
     */
    private $inputDate;
    /**
     * @var string the invoice number of the purchase invoice (suppliers' number)
     */
    private $invoiceNumber;
    /**
     * @var string the number of the purchase invoice, e.g. '20150001'
     */
    private $number;
    /**
     * @var string the period in which the purchase invoice was booked
     */
    private $period;
    /**
     * @var string the regime
     */
    private $regime;

    /**
     * @return Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getInputDate()
    {
        return $this->inputDate;
    }

    /**
     * @return string
     */
    public function getInvoiceNumber()
    {
        return $this->invoiceNumber;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * @return string
     */
    public function getRegime()
    {
        return $this->regime;
    }

    /**
     * @param Currency $value
     */
    public function setCurrency(Currency $value)
    {
        $this->currency = $value;
    }

    /**
     * @param string $value
     */
    public function setDate($value)
    {
        $this->date = $value;
    }

    /**
     * @param string $value
     */
    public function setInputDate($value)
    {
        $this->inputDate = $value;
    }

    /**
     * @param string $value
     */
    public function setInvoiceNumber($value)
    {
        $this->invoiceNumber = $value;
    }

    /**
     * @param string $value
     */
    public function setNumber($value)
    {
        $this->number = $value;
    }

    /**
     * @param string $value
     */
    public function setPeriod($value)
    {
        $this->period = $value;
    }

    /**
     * @param string $value
     */
    public function setRegime($value)
    {
        $this->regime = $value;
    }
}