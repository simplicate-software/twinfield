<?php
namespace Pronamic\Twinfield\Match\Mapper;

use \Pronamic\Twinfield\Match\Match;
use \Pronamic\Twinfield\Match\MatchLine;
use \Pronamic\Twinfield\Response\Response;

/**
 * Maps a response DOMDocument to the corresponding entity.
 * 
 * @package Pronamic\Twinfield
 * @subpackage Mapper
 * @author Willem van de Sande <W.vandeSande@MailCoupon.nl>
 */
class MatchMapper
{
    /**
     * Maps a Response object to a clean Match entity.
     * 
     * @access public
     * @param \Pronamic\Twinfield\Response\Response $response
     * @return \Pronamic\Twinfield\Match\Match
     */
    public static function map(Response $response)
    {
        // Generate new Match object
        $Match = new Match();

        // Gets the raw DOMDocument response.
        $responseDOM = $response->getResponseDocument();

        // Set the status attribute
        $dimensionElement = $responseDOM->getElementsByTagName('header')->item(0);
        $Match->setStatus($dimensionElement->getAttribute('status'));

        // Match elements and their methods
        $MatchTags = [
            'code'              => 'setCode',
            'office'            => 'setOffice',
            'type'              => 'setType',
            'name'              => 'setName',
            'shortname'         => 'setShortName',
            'unitnamesingular'  => 'setUnitNameSingular',
            'unitnameplural'    => 'setUnitNamePlural',
            'vatcode'           => 'setVatCode',
            'allowchangevatcode' => 'setAllowChangeVatCode',
            'performancetype'   => 'setPerformanceType',
            'allowchangeperformancetype' => 'setAllowChangePerformanceType',
            'percentage'        => 'setPercentage',
            'allowdiscountorpremium' => 'setAllowDiscountorPremium',
            'allowchangeunitsprice' => 'setAllowChangeUnitsPrice',
            'allowdecimalquantity' => 'setAllowDecimalQuantity',
        ];

        // Loop through all the tags
        foreach ($MatchTags as $tag => $method) {
            // Get the dom element
            $_tag = $responseDOM->getElementsByTagName($tag)->item(0);

            // If it has a value, set it to the associated method
            if (isset($_tag) && isset($_tag->textContent)) {
                $Match->$method($_tag->textContent);
            }
        }

        $linesDOMTag = $responseDOM->getElementsByTagName('lines');

        if (isset($linesDOMTag) && $linesDOMTag->length > 0) {
            // Element tags and their methods for lines
            $lineTags = [
                'unitspriceexcl'  => 'setUnitsPriceExcl',
                'unitspriceinc'   => 'setUnitsPriceInc',
                'units'           => 'setUnits',
                'name'            => 'setName',
                'shortname'       => 'setShortName',
                'subcode'         => 'setSubCode',
                'freetext1'       => 'setFreeText1',
            ];

            $linesDOM = $linesDOMTag->item(0);

            // Loop through each returned line for the Match
            foreach ($linesDOM->getElementsByTagName('line') as $lineDOM) {

                // Make a new tempory MatchLine class
                $MatchLine = new MatchLine();

                // Set the attributes ( id,status,inuse)
                $MatchLine->setID($lineDOM->getAttribute('id'))
                    ->setTransCode($lineDOM->getAttribute('status'))
                    ->setTransNumber($lineDOM->getAttribute('inuse'));

                // Loop through the element tags. Determine if it exists and set it if it does
                foreach ($lineTags as $tag => $method) {

                    // Get the dom element
                    $_tag = $lineDOM->getElementsByTagName($tag)->item(0);

                    // Check if the tag is set, and its content is set, to prevent DOMNode errors
                    if (isset($_tag) && isset($_tag->textContent)) {
                        $MatchLine->$method($_tag->textContent);
                    }
                }

                // Add the bank to the customer
                $Match->addLine($MatchLine);

                // Clean that memory!
                unset ($MatchLine);
            }
        }
        return $Match;
    }
}
