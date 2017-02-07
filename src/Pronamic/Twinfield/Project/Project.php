<?php

namespace Pronamic\Twinfield\Project;

use Pronamic\Twinfield\Exception\ProjectException;

/**
 * Class Project
 *
 * @author Emile Bons <emile@emilebons.nl>
 * @package Pronamic\Twinfield\Project
 */
class Project {

    private $office;
    private $code;
    private $UID;
    private $status;
    private $name;
    private $shortname;
    private $type = 'PRJ';
    /** @var bool */
    private $inUse;
    private $behaviour;
    private $touched;
    private $beginPeriod;
    private $beginYear;
    private $endPeriod;
    private $endYear;
    private $website;
    private $cocNumber;
    private $vatNumber;

    /** @var ProjectRemittanceAdvice */
    private $remittanceAdvice;

    /**
     * @var string  A project can be set to only be valid for certain dates. Users will then only be able to book hours
     *              to the project during these dates. When both validfrom as well validto are filled in, time can be
     *              entered during this period. When only validfrom is filled in, time can be entered starting from
     *              this date. When only validto is filled in, time can be entered until this date. When both validfrom
     *              as well validto are empty, time can be entered without limitations.
     */
    private $validFrom;

    /**
     * NOTE: Twinfield API speaks of 'validto', but the response shows 'validtill'
     * 
     * @var string  A project can be set to only be valid for certain dates. Users will then only be able to book hours
     *              to the project during these dates. When both validfrom as well validto are filled in, time can be
     *              entered during this period. When only validfrom is filled in, time can be entered starting from
     *              this date. When only validto is filled in, time can be entered until this date. When both validfrom
     *              as well validto are empty, time can be entered without limitations.
     */
    private $validTill;

    /**
     * @var string (maxlength: 128) This field can be used to enter a longer project description which will be available on the invoice template.
     */
    private $invoicedescription;

    /**
     * @var string A specific authoriser for a project. 
     *             
     *              If "change" = allow then locked = false and inherit = false
     *              If "change" = disallow then locked = true and inherit = false
     *              If "change" = inherit then locked = true and inherit = true
     */
    private $authoriser;
    private $authoriserLocked;
    private $authoriserInherit;

    /**
     * @var string A project always needs to be linked to a customer. Choose to have the customer ‘inherited’
     *              (from an activity) or you can specify the customer here.
     *             
     *              If "change" = allow then locked = false and inherit = false
     *              If "change" = disallow then locked = true and inherit = false
     *              If "change" = inherit then locked = true and inherit = true
     */
    private $customer;
    private $customerLocked;
    private $customerInherit;

    /**
     * @var bool Choose to make a project billable (true) or not (false) and whether or not it should be included
     *           when calculating the "productivity" ratio (@forratio). You could also decide that these settings should be inherited from activity or user level (@inherit). You can also set whether a change of these settings is allowed or disallowed when a user is entering their timesheet (@locked).
     */
    private $billable;
    private $billableLocked;
    private $billableInherit;
    private $billableForratio;

    /**
     * @var string Choose to define a specific rate code here or you could also decide that these settings should be
     *              inherited from activity or user level (@inherit). You can also set whether a change of the rate
     *              code is allowed or disallowed when a user is entering their timesheet (@locked).
     *             
     *              If "change" = allow then locked = false and inherit = false
     *              If "change" = disallow then locked = true and inherit = false
     *              If "change" = inherit then locked = true and inherit = true
     */
    private $rate;
    private $rateLocked;
    private $rateInherit;

    /**
     * @var ProjectQuantity[]
     */
    private $quantity;










    /**
     * @return mixed
     */
    public function getOffice() {
        return $this->office;
    }

    /**
     * @param mixed $office
     *
     * @return $this
     */
    public function setOffice($office) {
        $this->office = $office;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * @param mixed $code
     *
     * @return $this
     */
    public function setCode($code) {
        $this->code = $code;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUID() {
        return $this->UID;
    }

    /**
     * @param mixed $UID
     *
     * @return $this
     */
    public function setUID($UID) {
        $this->UID = $UID;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return $this
     */
    public function setStatus($status) {
        $this->status = $status;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     *
     * @return $this
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getShortname() {
        return $this->shortname;
    }

    /**
     * @param mixed $shortname
     *
     * @return $this
     */
    public function setShortname($shortname) {
        $this->shortname = $shortname;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param mixed $type
     *
     * @return $this
     */
    public function setType($type) {
        $this->type = $type;

        return $this;
    }

    /**
     * @return bool
     */
    public function isInUse() {
        return $this->inUse;
    }

    /**
     * @param string $inUse
     *
     * @return $this
     */
    public function setInUse($inUse) {
        if(null !== ($value = filter_var($inUse, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))) {
            $this->inUse = $value;
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBehaviour() {
        return $this->behaviour;
    }

    /**
     * @param mixed $behaviour
     *
     * @return $this
     */
    public function setBehaviour($behaviour) {
        $this->behaviour = $behaviour;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTouched() {
        return $this->touched;
    }

    /**
     * @param mixed $touched
     *
     * @return $this
     */
    public function setTouched($touched) {
        $this->touched = $touched;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBeginPeriod() {
        return $this->beginPeriod;
    }

    /**
     * @param mixed $beginPeriod
     *
     * @return $this
     */
    public function setBeginPeriod($beginPeriod) {
        $this->beginPeriod = $beginPeriod;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBeginYear() {
        return $this->beginYear;
    }

    /**
     * @param mixed $beginYear
     *
     * @return $this
     */
    public function setBeginYear($beginYear) {
        $this->beginYear = $beginYear;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEndPeriod() {
        return $this->endPeriod;
    }

    /**
     * @param mixed $endPeriod
     *
     * @return $this
     */
    public function setEndPeriod($endPeriod) {
        $this->endPeriod = $endPeriod;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEndYear() {
        return $this->endYear;
    }

    /**
     * @param mixed $endYear
     *
     * @return $this
     */
    public function setEndYear($endYear) {
        $this->endYear = $endYear;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getWebsite() {
        return $this->website;
    }

    /**
     * @param mixed $website
     *
     * @return $this
     */
    public function setWebsite($website) {
        $this->website = $website;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCocNumber() {
        return $this->cocNumber;
    }

    /**
     * @param mixed $cocNumber
     *
     * @return $this
     */
    public function setCocNumber($cocNumber) {
        $this->cocNumber = $cocNumber;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVatNumber() {
        return $this->vatNumber;
    }

    /**
     * @param mixed $vatNumber
     *
     * @return $this
     */
    public function setVatNumber($vatNumber) {
        $this->vatNumber = $vatNumber;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEditDimensionName() {
        return $this->editDimensionName;
    }

    /**
     * @param mixed $editDimensionName
     *
     * @return $this
     */
    public function setEditDimensionName($editDimensionName) {
        $this->editDimensionName = $editDimensionName;

        return $this;
    }
    private $editDimensionName;
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    /**
     * @return string
     */
    public function getValidFrom() {
        return $this->validFrom;
    }

    /**
     * @param string $validFrom
     *
     * @return $this
     */
    public function setValidFrom($validFrom) {
        $this->validFrom = $validFrom;

        return $this;
    }

    /**
     * @return string
     */
    public function getValidTill() {
        return $this->validTill;
    }

    /**
     * @param string $validTill
     *
     * @return $this
     */
    public function setValidTill($validTill) {
        $this->validTill = $validTill;

        return $this;
    }

    /**
     * @return string
     */
    public function getInvoicedescription() {
        return $this->invoicedescription;
    }

    /**
     * @param string $invoicedescription
     *
     * @return $this
     */
    public function setInvoicedescription($invoicedescription) {
        $this->invoicedescription = $invoicedescription;

        return $this;
    }

    /**
     * @return string
     */
    public function getAuthoriser() {
        return $this->authoriser;
    }

    /**
     * @param string $authoriser
     *
     * @return $this
     */
    public function setAuthoriser($authoriser) {
        $this->authoriser = $authoriser;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAuthoriserLocked() {
        return $this->authoriserLocked;
    }

    /**
     * @param bool $authoriserLocked
     *
     * @return $this
     */
    public function setAuthoriserLocked($authoriserLocked) {
        if(null !== ($value = filter_var($authoriserLocked, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))) {
            $this->authoriserLocked = $value;
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isAuthoriserInherit() {
        return $this->authoriserInherit;
    }

    /**
     * @param bool $authoriserInherit
     *
     * @return $this
     */
    public function setAuthoriserInherit($authoriserInherit) {
        if(null !== ($value = filter_var($authoriserInherit, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))) {
            $this->authoriserInherit = $value;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getCustomer() {
        return $this->customer;
    }

    /**
     * @param string $customer
     *
     * @return $this
     */
    public function setCustomer($customer) {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return bool
     */
    public function isCustomerLocked() {
        return $this->customerLocked;
    }

    /**
     * @param bool $customerLocked
     *
     * @return $this
     */
    public function setCustomerLocked($customerLocked) {
        if(null !== ($value = filter_var($customerLocked, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))) {
            $this->customerLocked = $value;
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isCustomerInherit() {
        return $this->customerInherit;
    }

    /**
     * @param bool $customerInherit
     *
     * @return $this
     */
    public function setCustomerInherit($customerInherit) {
        if(null !== ($value = filter_var($customerInherit, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))) {
            $this->customerInherit = $value;
        }

        return $this;
    }

    /**
     * @return bool
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
     * @return bool
     */
    public function isBillableLocked() {
        return $this->billableLocked;
    }

    /**
     * @param bool $billableLocked
     *
     * @return $this
     */
    public function setBillableLocked($billableLocked) {
        if(null !== ($value = filter_var($billableLocked, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))) {
            $this->billableLocked = $value;
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isBillableInherit() {
        return $this->billableInherit;
    }

    /**
     * @param bool $billableInherit
     *
     * @return $this
     */
    public function setBillableInherit($billableInherit) {
        if(null !== ($value = filter_var($billableInherit, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))) {
            $this->billableInherit = $value;
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isBillableForratio() {
        return $this->billableForratio;
    }

    /**
     * @param bool $billableForratio
     *
     * @return $this
     */
    public function setBillableForratio($billableForratio) {
        if(null !== ($value = filter_var($billableForratio, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))) {
            $this->billableForratio = $value;
        }

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
     * @return bool
     */
    public function isRateLocked() {
        return $this->rateLocked;
    }

    /**
     * @param bool $rateLocked
     *
     * @return $this
     */
    public function setRateLocked($rateLocked) {
        if(null !== ($value = filter_var($rateLocked, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))) {
            $this->rateLocked = $value;
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isRateInherit() {
        return $this->rateInherit;
    }

    /**
     * @param bool $rateInherit
     *
     * @return $this
     */
    public function setRateInherit($rateInherit) {
        if(null !== ($value = filter_var($rateInherit, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))) {
            $this->rateInherit = $value;
        }

        return $this;
    }

    /**
     * @return ProjectRemittanceAdvice
     */
    public function getRemittanceAdvice() {
        return $this->remittanceAdvice;
    }

    /**
     * @param ProjectRemittanceAdvice $remittanceAdvice
     *
     * @return $this
     */
    public function setRemittanceAdvice(ProjectRemittanceAdvice $remittanceAdvice) {
        $this->remittanceAdvice = $remittanceAdvice;

        return $this;
    }

    /**
     * @return ProjectQuantity[]
     */
    public function getQuantity() {
        return $this->quantity;
    }

    /**
     * @param ProjectQuantity $quantity
     *
     * @return $this
     */
    public function addQuantity(ProjectQuantity $quantity) {
        $this->quantity[$quantity->getID()] = $quantity;

        return $this;
    }

    /**
     * @param string $index
     *
     * @return bool
     */
    public function removeQuantity($index) {
        if(array_key_exists($index, $this->quantity)) {
            unset($this->quantity[$index]);
            return true;
        } else {
            return false;
        }
    }

}
