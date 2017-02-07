<?php

namespace Pronamic\Twinfield\Project;

/**
 * Class ProjectQuantity
 */
class ProjectQuantity {

    /** @var string */
    private $id;

    /**
     * @var string (maxlength: 36) The label of the quantity.
     */
    private $label;

    /**
     * @var string  The rate.
     */
    private $rate;

    /**
     * @var bool   Is the quantity line billable or not.
     *              If "billable" = true and "change is not allowed" then locked = true
     *              If "billable" = true and "change is allowed" then locked = false
     */
    private $billable;
    /** @var bool */
    private $billableLocked;

    /**
     * @var bool Is the quantity line mandatory or not.
     */
    private $mandatory;

    /**
     * ProjectQuantity constructor.
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
     * @return string
     */
    public function getLabel() {
        return $this->label;
    }

    /**
     * @param string $label
     *
     * @return $this
     */
    public function setLabel($label) {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string
     */
    public function getRate() {
        return $this->rate;
    }

    /**
     * @param string $rate
     *
     * @return $this
     */
    public function setRate($rate) {
        $this->rate = $rate;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isBillable() {
        return $this->billable;
    }

    /**
     * @param boolean $billable
     *
     * @return $this
     */
    public function setBillable($billable) {
        if(null !== ($value = filter_var($billable, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))) {
            $this->billable = $value;
        }

        return $this;
    }

    /**
     * @return boolean
     */
    public function isBillableLocked() {
        return $this->billableLocked;
    }

    /**
     * @param boolean $locked
     *
     * @return $this
     */
    public function setBillableLocked($locked) {
        if(null !== ($value = filter_var($locked, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))) {
            $this->billableLocked = $value;
        }

        return $this;
    }

    /**
     * @return boolean
     */
    public function isMandatory() {
        return $this->mandatory;
    }

    /**
     * @param boolean $mandatory
     *
     * @return $this
     */
    public function setMandatory($mandatory) {
        if(null !== ($value = filter_var($mandatory, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))) {
            $this->mandatory = $value;
        }

        return $this;
    }
    
}