<?php

namespace Pronamic\Twinfield\Match\DOM;

use \Pronamic\Twinfield\Match\Match;

/**
 * The Document Holder for making new XML Match. Is a child class
 * of DOMDocument and makes the required DOM tree for the interaction in
 * creating a new Match.
 * 
 * @package Pronamic\Twinfield
 * @subpackage Match\DOM
 * @author Willem van de Sande <W.vandeSande@MailCoupon.nl>
 */
class MatchDocument extends \DOMDocument
{
    /**
     * Holds the <Match> element
     * that all additional elements should be a child of
     * @var \DOMElement
     */
    private $matchElement;

    /**
     * Creates the <Match> element and adds it to the property
     * MatchElement
     * 
     * @access public
     */
    public function __construct()
    {
        parent::__construct();

        $this->matchElement = $this->createElement('Match');
        $this->appendChild($this->matchElement);
    }

    /**
     * Turns a passed Match class into the required markup for interacting
     * with Twinfield.
     * 
     * This method doesn't return anything, instead just adds the Match to
     * this DOMDOcument instance for submission usage.
     * 
     * @access public
     * @param \Pronamic\Twinfield\Match\Match $match
     * @return void | [Adds to this instance]
     */
    public function addMatch(Match $match)
    {
        // Match->header elements and their methods
        $matchTags = array(
            'office'            => 'getOffice',
            'matchcode'         => 'getCode',
            'matchdate'         => 'getDate'
        );


        // Make header element
        $setElement = $this->createElement('set');
        $this->matchElement->appendChild($setElement);

        // Go through each Match element and use the assigned method
        foreach ($matchTags as $tag => $method) {
            // Make text node for method value
            $node = $this->createTextNode($match->$method());

            // Make the actual element and assign the node
            $element = $this->createElement($tag);
            $element->appendChild($node);

            // Add the full element
            $setElement->appendChild($element);
        }

        $lines = $match->getLines();
        if (!empty($lines)) {
            $linesElement = $this->createElement('lines');
            $this->matchElement->appendChild($linesElement);

             // Element tags and their methods for lines
            $lineTags = [
                'transcode'     => 'getTransCode',
                'transnumber'   => 'getTransNumber',
                'transline'     => 'getTransLine',
                'matchvalue'    => 'getMatchValue'
            ];

            // Go through each line assigned to the Match
            foreach ($lines as $line) {
                // Makes new MatchLine element
                $lineElement = $this->createElement('line');
                $linesElement->appendChild($lineElement);

                // Go through each line element and use the assigned method
                foreach ($lineTags as $tag => $method) {
                    // Make the text node for the method value
                    $value = $line->$method();
                    if ($value === null) continue;
                    $node = $this->createTextNode($line->$method());

                    // Make the actual element and assign the text node
                    $element = $this->createElement($tag);
                    $element->appendChild($node);

                    // Add the completed element
                    $lineElement->appendChild($element);
                }
            }
        }
    }
}
