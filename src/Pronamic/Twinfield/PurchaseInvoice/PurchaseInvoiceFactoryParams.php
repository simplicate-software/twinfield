<?php

namespace Pronamic\Twinfield\PurchaseInvoice;

class PurchaseInvoiceFactoryParams {

    /** @var null|string */
    protected $startYearPeriod = null;

    /** @var null|string */
    protected $endYearPeriod = null;

    /** @var null|string */
    protected $startDate = null;

    /** @var null|string */
    protected $endDate = null;

    /** @var bool */
    protected $onlyWithProjects = true;

    /** @var array */
    protected $sort = [];

    /** @var null|string */
    protected $filterStatus = null;

    /**
     * @return null
     */
    public function getStartYearPeriod() {
        return $this->startYearPeriod;
    }

    /**
     * @param string $startYearPeriod "YYYY/MM"
     *
     * @return $this
     */
    public function setStartYearPeriod($startYearPeriod) {
        $this->startYearPeriod = $startYearPeriod;

        return $this;
    }

    /**
     * @return null
     */
    public function getEndYearPeriod() {
        return $this->endYearPeriod;
    }

    /**
     * @param null $endYearPeriod "YYYY/MM"
     *
     * @return $this
     */
    public function setEndYearPeriod($endYearPeriod) {
        $this->endYearPeriod = $endYearPeriod;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getStartDate() {
        return $this->startDate;
    }

    /**
     * @param null|string $startDate "YYYYMMDD"
     *
     * @return PurchaseInvoiceFactoryParams
     */
    public function setStartDate($startDate) {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getEndDate() {
        return $this->endDate;
    }

    /**
     * @param null|string $endDate "YYYYMMDD"
     *
     * @return PurchaseInvoiceFactoryParams
     */
    public function setEndDate($endDate) {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isOnlyWithProjects() {
        return $this->onlyWithProjects;
    }

    /**
     * @param boolean $onlyWithProjects
     *
     * @return $this
     */
    public function setOnlyWithProjects($onlyWithProjects) {
        $this->onlyWithProjects = $onlyWithProjects;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getFilterStatus() {
        return $this->filterStatus;
    }

    /**
     * @param string $value    One of @see PurchaseInvoice::STATUS_* constants
     *
     * @return $this
     */
    public function setFilterStatus($value) {
        $this->filterStatus = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getSort() {
        return $this->sort;
    }

    /**
     * @param string $field "fin.trs.line.dim2"
     * @param string $order "descending" || "ascending"
     *
     * @return $this
     * @throws \Exception
     */
    public function addSort($field, $order = 'descending') {
        if(count($this->sort) > 3) {
            throw new \Exception('No more than 3 sorts allowed');
        }
        $this->sort[] = [
            'field' => $field,
            'order' => $order,
        ];

        return $this;
    }

    /**
     * @param int $index
     *
     * @return $this
     */
    public function removeSort($index) {
        if(isset($this->sort[$index])) {
            unset($this->sort[$index]);
        }

        return $this;
    }
    
}
