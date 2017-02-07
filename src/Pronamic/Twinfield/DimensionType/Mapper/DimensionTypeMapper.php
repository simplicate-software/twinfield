<?php
namespace Pronamic\Twinfield\DimensionType\Mapper;

use Pronamic\Twinfield\DimensionType\DimensionType;
use Pronamic\Twinfield\Response\Response;

/**
 * Maps a response DOMDocument to the corresponding entity.
 *
 * @package       Pronamic\Twinfield
 * @copyright (c) 2013, Pronamic
 */
class DimensionTypeMapper {

    /**
     * Maps a Response object to a clean DimensionType entity.
     *
     * @access public
     *
     * @param \Pronamic\Twinfield\Response\Response $response
     *
     * @return \Pronamic\Twinfield\DimensionType\DimensionType
     */
    public static function map(Response $response) {
        // Generate new customer object
        $dimType = new DimensionType();

        // Gets the raw DOMDocument response.
        $responseDOM = $response->getResponseDocument();

        // Set the status attribute
        $dimensionElement = $responseDOM->getElementsByTagName('dimensiontype')->item(0);
        $dimType->setStatus($dimensionElement->getAttribute('status'));

        // Customer elements and their methods
        $dimTypeTags = [
            'code'    => 'setCode',
            'name'    => 'setName',
            'touched' => 'setTouched',
            'mask'    => 'setMask',
            'office'  => 'setOffice',
        ];

        // Loop through all the tags
        foreach($dimTypeTags as $tag => $method) {

            // Get the dom element
            $_tag = $responseDOM->getElementsByTagName($tag)->item(0);

            // If it has a value, set it to the associated method
            if(isset($_tag) && isset($_tag->textContent)) {
                $dimType->{$method}($_tag->textContent);
            }
        }

        return $dimType;
    }
}
