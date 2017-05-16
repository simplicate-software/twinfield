<?php

namespace Pronamic\Twinfield\CostCenter;

/**
 * Class CostCenter
 *
 * @author  Johannes Smit <johannes.smit@simplicate.nl>
 * @package Pronamic\Twinfield\CostCenter
 */
class CostCenter {

    const STATUS_ACTUVE = 'active';
    const STATUS_DELETED = 'deleted';
    const STATUS_HIDE = 'hide';

    const BEHAVIOUR_NORMAL = 'normal';
    const BEHAVIOUR_SYSTEM = 'system';
    const BEHAVIOUR_TEMPLATE = 'template';

    /**
     * @var string Status('active', 'deleted', 'hide') of the cost center
     */
    private $status;

    /**
     * @var string The code of the cost center
     */
    private $code;

    /**
     * @var string The name of the cost center
     */
    private $name;

    /**
     * @var string Indicates whether the cost center is used in a financial transaction or not
     */
    private $inUse;

    /**
     * @var string Behaviour('normal', 'system', 'template') of the cost center
     */
    private $behaviour;

    /**
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getInUse() {
        return $this->inUse;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getBehaviour() {
        return $this->behaviour;
    }

    /**
     * @param string $status
     */
    public function setStatus($status) {
        $this->status = $status;
    }

    /**
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @param string $code
     */
    public function setCode($code) {
        $this->code = $code;
    }

    /**
     * @param string $inUse
     */
    public function setInUse($inUse) {
        $this->inUse = $inUse;
    }

    /**
     * @param string $behaviour
     */
    public function setBehaviour($behaviour) {
        $this->behaviour = $behaviour;
    }

}