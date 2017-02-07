<?php
namespace Pronamic\Twinfield\DimensionType\DOM;

use Pronamic\Twinfield\DimensionType\DimensionType;

/**
 * The Document Holder for modifying dimensiontypes. Is a child class
 * of DOMDocument and makes the required DOM tree for the interaction in
 * creating a new customer.
 *
 * @package Pronamic\Twinfield
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Pronamic
 */
class DimensionTypesDocument extends \DOMDocument {

    /**
     * Holds the <dimensiontype> element
     * that all additional elements should be a child of
     * @var \DOMElement
     */
    private $dimensionElement;

    /**
     * Creates the <dimension> element and adds it to the property
     * dimensionElement
     *
     * @access public
     */
    public function __construct() {
        parent::__construct('1.0', 'UTF-8');

        $this->dimensionElement = $this->createElement('dimensiontype');
        $this->appendChild($this->dimensionElement);
    }

    /**
     * Turns a passed DimensionType class into the required markup for interacting
     * with Twinfield.
     *
     * This method doesn't return anything, instead just adds the dimtype to
     * this DOMDOcument instance for submission usage.
     *
     * @access public
     * @param \Pronamic\Twinfield\DimensionType\DimensionType $dimType
     * 
     * @return void | [Adds to this instance]
     */
    public function addDimensionType(DimensionType $dimType) {
        // Elements and their associated methods for customer
        $dimTypeTags = [
            'code'      => 'getCode',
            'name'      => 'getName',
            'shortname' => 'getShortName',
            'mask'      => 'getMask',
        ];

        if($dimType->getOffice()) {
            $dimTypeTags['office'] = 'getOffice';
        }

        // Go through each customer element and use the assigned method
        foreach($dimTypeTags as $tag => $method) {

            // Make text node for method value
            $node = $this->createTextNode($dimType->$method());

            // Make the actual element and assign the node
            $element = $this->createElement($tag);
            $element->appendChild($node);

            // Add the full element
            $this->dimensionElement->appendChild($element);
        }
    }

}
