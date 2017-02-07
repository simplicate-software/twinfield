<?php
namespace Pronamic\Twinfield\Request\Read;

/**
 * Used to request a specific custom from a certain
 * office and code.
 *
 * @package       Pronamic\Twinfield
 * @subpackage    Request\Read
 * @copyright (c) 2013, Pronamic
 * @version       0.0.1
 */
class DimensionType extends \DOMDocument {

    /**
     * Holds the <read> element that all
     * additional elements shoudl be a child of.
     *
     * @access private
     * @var \DOMElement
     */
    private $dimensionTypeElement;

    /**
     * Sets the <dimensiontype> to dimensions for the request and
     * sets the dimtype, office and code if they are present.
     *
     * @access public
     *
     * @param null|string $code
     * @param null|string $office
     */
    public function __construct($code = null, $office = null) {
        parent::__construct();

        $this->dimensionTypeElement = $this->createElement('dimensiontype');
        $this->appendChild($this->dimensionTypeElement);

        if(null !== $office) {
            $this->setOffice($office);
        }

        if(null !== $code) {
            $this->setCode($code);
        }
    }

    /**
     * Sets the office code for this customer request.
     *
     * @access public
     *
     * @param int $office
     *
     * @return $this
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
     * @return $this
     */
    public function setCode($code) {
        $this->add('code', $code);

        return $this;
    }

    /**
     * Adds additional elements to the <read> dom element.
     *
     * See the documentation over what <read> requires to know
     * and what additional elements you need.
     *
     * @access protected
     * @param string $element
     * @param mixed $value
     * @return void
     */
    protected function add($element, $value) {
        $_element = $this->createElement($element, $value);
        $this->dimensionTypeElement->appendChild($_element);
    }
}
