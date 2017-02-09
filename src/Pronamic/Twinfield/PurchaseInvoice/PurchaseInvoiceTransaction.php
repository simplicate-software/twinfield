<?php

namespace Pronamic\Twinfield\PurchaseInvoice;

/**
 * Class PurchaseInvoiceTransaction
 *
 * @package Pronamic\Twinfield\PurchaseInvoice
 */
class PurchaseInvoiceTransaction extends BasePurchaseInvoice {

    /** @var string|int */
    private $dim1;

    /** @var string|int */
    private $dim2;

    /** @var string|int */
    private $dim3;

    /** @var string */
    private $curCode;

    /** @var float */
    private $valueSigned;

    /** @var float */
    private $baseValueSigned;

    /** @var string */
    private $description;

    /**
     * @return string
     */
    public function getDim1() {
        return $this->dim1;
    }

    /**
     * @param string $dim1
     *
     * @return $this
     */
    public function setDim1($dim1) {
        $this->dim1 = $dim1;

        return $this;
    }

    /**
     * @return string
     */
    public function getDim2() {
        return $this->dim2;
    }

    /**
     * @param string $dim2
     *
     * @return $this
     */
    public function setDim2($dim2) {
        $this->dim2 = $dim2;

        return $this;
    }

    /**
     * @return string
     */
    public function getDim3() {
        return $this->dim3;
    }

    /**
     * @param string $dim3
     *
     * @return $this
     */
    public function setDim3($dim3) {
        $this->dim3 = $dim3;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurCode() {
        return $this->curCode;
    }

    /**
     * @param string $curCode
     *
     * @return $this
     */
    public function setCurCode($curCode) {
        $this->curCode = $curCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getValueSigned() {
        return $this->valueSigned;
    }

    /**
     * @param string $valueSigned
     *
     * @return $this
     */
    public function setValueSigned($valueSigned) {
        $this->valueSigned = $valueSigned;

        return $this;
    }

    /**
     * @return string
     */
    public function getBaseValueSigned() {
        return $this->baseValueSigned;
    }

    /**
     * @param string $baseValueSigned
     *
     * @return $this
     */
    public function setBaseValueSigned($baseValueSigned) {
        $this->baseValueSigned = $baseValueSigned;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

}