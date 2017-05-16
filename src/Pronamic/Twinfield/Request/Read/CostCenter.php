<?php

namespace Pronamic\Twinfield\Request\Read;

class CostCenter extends Read {

    /**
     * CostCenter constructor.
     *
     * @param null $office
     */
    public function __construct($office = null) {
        parent::__construct();

        $this->add('type', 'dimensions');

        if(null !== $office) {
            $this->setOffice($office);
        }

        $this->setDimType('KPL');
    }

    /**
     * Sets the office code for this customer request.
     *
     * @access public
     *
     * @param int $office
     *
     * @return CostCenter
     */
    public function setOffice($office) {
        $this->add('office', $office);

        return $this;
    }

    /**
     * Sets the dimtype for the request.
     *
     * @access public
     *
     * @param string $dimType
     *
     * @return CostCenter
     */
    public function setDimType($dimType) {
        $this->add('dimtype', $dimType);

        return $this;
    }
}