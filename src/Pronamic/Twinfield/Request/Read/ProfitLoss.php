<?php
namespace Pronamic\Twinfield\Request\Read;

/**
 * Used to request a specific custom from a certain
 * office and code.
 *
 * @package       Pronamic\Twinfield
 * @subpackage    Request\Read
 * @author        Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Pronamic
 * @version       0.0.1
 */
class ProfitLoss extends Read {

    /**
     * Sets the <type> to dimensions for the request and
     * sets the dimtype, office and code if they are present.
     *
     * @access public
     *
     * @param null|string $code
     * @param null|string $office
     */
    public function __construct($code = null, $office = null) {
        parent::__construct();

        $this->add('type', 'dimensions');

        if(null !== $office) {
            $this->setOffice($office);
        }

        if(null !== $code) {
            $this->setCode($code);
        }

        $this->setDimType('PNL');
    }

    /**
     * Sets the office code for this customer request.
     *
     * @access public
     *
     * @param int $office
     *
     * @return \Pronamic\Twinfield\Request\Read\Customer
     */
    public function setOffice($office) {
        $this->add('office', $office);

        return $this;
    }

    /**
     * Sets the code for this customer request.
     *
     * @access public
     *
     * @param string $code
     *
     * @return \Pronamic\Twinfield\Request\Read\Customer
     */
    public function setCode($code) {
        $this->add('code', $code);

        return $this;
    }

    /**
     * Sets the dimtype for the request.
     *
     * @access public
     *
     * @param string $dimType
     *
     * @return \Pronamic\Twinfield\Request\Read\Customer
     */
    public function setDimType($dimType) {
        $this->add('dimtype', $dimType);

        return $this;
    }
}
