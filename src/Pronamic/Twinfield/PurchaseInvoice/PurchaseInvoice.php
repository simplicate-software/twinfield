<?php

namespace Pronamic\Twinfield\PurchaseInvoice;

use Pronamic\Twinfield\Currency;
use Pronamic\Twinfield\User\User;

/**
 * Class PurchaseInvoice
 *
 * @author Emile Bons <emile@emilebons.nl>
 * @package Pronamic\Twinfield\PurchaseInvoice
 */
class PurchaseInvoice extends BasePurchaseInvoice
{
    /**
     * @var Currency the currency of the purchase invoice
     */
    private $currency;
    /**
     * @var string the due-date of the purchase invoice
     */
    private $dueDate;
    /**
     * @var string the date and time of modification
     */
    private $modificationDate;
    /**
     * @var string the origin
     */
    private $origin;
    /**
     * @var string
     */
    private $originReference;
    /**
     * @var string freetext1
     */
    private $freetext1;
    /**
     * @var string freetext2
     */
    private $freetext2;
    /**
     * @var string freetext3
     */
    private $freetext3;
    /**
     * @var User
     */
    private $user;

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
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * @return string
     */
    public function getModificationDate()
    {
        return $this->modificationDate;
    }

    /**
     * @return string
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * @return string
     */
    public function getOriginReference()
    {
        return $this->originReference;
    }

    /**
     * @return string
     */
    public function getFreetext1()
    {
        return $this->freetext1;
    }

    /**
     * @return string
     */
    public function getFreetext2()
    {
        return $this->freetext2;
    }

    /**
     * @return string
     */
    public function getFreetext3()
    {
        return $this->freetext3;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
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
    public function setDueDate($value)
    {
        $this->dueDate = $value;
    }

    /**
     * @param string $value
     */
    public function setModificationDate($value)
    {
        $this->modificationDate = $value;
    }

    /**
     * @param string $value
     */
    public function setOrigin($value)
    {
        $this->origin = $value;
    }

    /**
     * @param string $value
     */
    public function setOriginReference($value)
    {
        $this->originReference = $value;
    }

    /**
     * @param string $value
     */
    public function setFreetext1($value)
    {
        $this->freetext1 = $value;
    }

    /**
     * @param string $value
     */
    public function setFreetext2($value)
    {
        $this->freetext2 = $value;
    }

    /**
     * @param string $value
     */
    public function setFreetext3($value)
    {
        $this->freetext3 = $value;
    }

    /**
     * @param string $value
     */
    public function setUser($value)
    {
        $this->user = $value;
    }
}