<?php

namespace Pronamic\Twinfield\ProfitLoss;

/**
 * Class Project
 *
 * @author Emile Bons <emile@emilebons.nl>
 * @package Pronamic\Twinfield\Project
 */
class ProfitLoss {

    private $office;
    private $code;
    private $UID;
    private $status;
    private $name;
    private $shortname;
    private $type = 'PNL';
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

}
