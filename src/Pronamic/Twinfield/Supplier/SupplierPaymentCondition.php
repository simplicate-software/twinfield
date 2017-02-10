<?php

namespace Pronamic\Twinfield\Supplier;

class SupplierPaymentCondition {

    /** @var string */
    private $id;

    /** @var int */
    private $discountDays;

    /** @var float */
    private $discountPercentage;

    /**
     * SupplierPaymentCondition constructor.
     */
    public function __construct() {
        $this->id = uniqid();
    }

    /**
     * @return string
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param $id
     *
     * @return $this
     */
    public function setId($id) {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getDiscountDays() {
        return $this->discountDays;
    }

    /**
     * @param int $discountDays
     *
     * @return $this
     */
    public function setDiscountDays($discountDays) {
        $this->discountDays = $discountDays;

        return $this;
    }

    /**
     * @return float
     */
    public function getDiscountPercentage() {
        return $this->discountPercentage;
    }

    /**
     * @param float $discountPercentage
     *
     * @return $this
     */
    public function setDiscountPercentage($discountPercentage) {
        $this->discountPercentage = $discountPercentage;

        return $this;
    }
}
