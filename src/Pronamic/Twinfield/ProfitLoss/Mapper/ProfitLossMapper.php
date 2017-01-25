<?php
namespace Pronamic\Twinfield\ProfitLoss\Mapper;

use Pronamic\Twinfield\ProfitLoss\ProfitLoss;
use Pronamic\Twinfield\Response\Response;

/**
 * Maps a response DOMDocument to the corresponding entity.
 *
 * @package       Pronamic\Twinfield
 * @subpackage    Mapper
 * @author        Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Pronamic
 */
class ProfitLossMapper {
    /**
     * Maps a Response object to a clean Customer entity.
     *
     * @access public
     *
     * @param \Pronamic\Twinfield\Response\Response $response
     *
     * @return \Pronamic\Twinfield\Project\Project
     */
    public static function map(Response $response) {
        // Generate new customer object
        $profitLoss = new ProfitLoss();

        // Gets the raw DOMDocument response.
        $responseDOM = $response->getResponseDocument();

        // Set the status attribute
        $dimensionElement = $responseDOM->getElementsByTagName('dimension')->item(0);
        $profitLoss->setStatus($dimensionElement->getAttribute('status'));

        // Customer elements and their methods
        $projectTags = [
            'office'      => 'setOffice',
            'code'        => 'setCode',
            'uid'         => 'setUID',
            'name'        => 'setName',
            'shortname'   => 'setShortname',
            'type'        => 'setType',
            'inuse'       => 'setInUse',
            'behaviour'   => 'setBehaviour',
            'touched'     => 'setTouched',
            'beginperiod' => 'setBeginPeriod',
            'beginyear'   => 'setBeginYear',
            'endperiod'   => 'setEndPeriod',
            'endyear'     => 'setEndYear',
            'website'     => 'setWebsite',
            'cocnumber'   => 'setCocNumber',
            'vatnumber'   => 'setVatNumber',
        ];

        // Loop through all the tags
        foreach($projectTags as $tag => $method) {

            // Get the dom element
            $_tag = $responseDOM->getElementsByTagName($tag)->item(0);

            // If it has a value, set it to the associated method
            if(isset($_tag) && isset($_tag->textContent)) {
                $profitLoss->$method($_tag->textContent);
            }
        }

        // Financial elements and their methods
        $projectProjectTags = [
            'validfrom'          => 'setValidfrom',
            'validtill'          => 'setValidTill',
        ];

        // Financial elements
        $projectProjectElement = $responseDOM->getElementsByTagName('projects')->item(0);

        // Go through each financial element and add to the assigned method
        foreach($projectProjectTags as $tag => $method) {
            // Get the dom element
            $_tag = $projectProjectElement->getElementsByTagName($tag)->item(0);


            // If it has a value, set it to the associated method
            if(isset($_tag) && isset($_tag->textContent)) {
                if(is_array($method) && isset($method['booleans'])) {
                    foreach($method['booleans'] as $tag2 => $method2) {
                        if(!empty($tagValue = $_tag->getAttribute($tag2))) {
                            if(null !== ($boolVal = filter_var($tagValue, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))) {
                                $profitLoss->{$method2}($boolVal);
                            }
                        }
                    }
                    $primaryMethod = $method['primary'];
                    $profitLoss->{$primaryMethod}($_tag->textContent);
                } else {
                    $profitLoss->$method($_tag->textContent);
                }
            }
        }

        return $profitLoss;
    }
}
