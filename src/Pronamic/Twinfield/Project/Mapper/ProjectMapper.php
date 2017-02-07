<?php
namespace Pronamic\Twinfield\Project\Mapper;

use Pronamic\Twinfield\Project\Project;
use Pronamic\Twinfield\Project\ProjectQuantity;
use Pronamic\Twinfield\Response\Response;

/**
 * Maps a response DOMDocument to the corresponding entity.
 *
 * @package       Pronamic\Twinfield
 * @subpackage    Mapper
 * @author        Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Pronamic
 */
class ProjectMapper {
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
        $project = new Project();

        // Gets the raw DOMDocument response.
        $responseDOM = $response->getResponseDocument();

        // Set the status attribute
        $dimensionElement = $responseDOM->getElementsByTagName('dimension')->item(0);
        $project->setStatus($dimensionElement->getAttribute('status'));

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
                $project->$method($_tag->textContent);
            }
        }

        // Financial elements and their methods
        $projectProjectTags = [
            'validfrom'          => 'setValidfrom',
            'validtill'          => 'setValidTill',
            'invoicedescription' => 'setInvoicedescription',
            'authoriser'         => [
                'primary'  => 'setAuthoriser',
                'booleans' => [
                    'locked'  => 'setAuthoriserLocked',
                    'inherit' => 'setAuthoriserInherit',
                ],
            ],
            'customer'           => [
                'primary'  => 'setCustomer',
                'booleans' => [
                    'locked'  => 'setCustomerLocked',
                    'inherit' => 'setCustomerInherit',
                ],
            ],
            'billable'           => [
                'primary'  => 'setBillable',
                'booleans' => [
                    'locked'   => 'setBillableLocked',
                    'inherit'  => 'setBillableInherit',
                    'forratio' => 'setBillableForratio',
                ],
            ],
            'rate'               => [
                'primary'  => 'setRate',
                'booleans' => [
                    'locked'  => 'setRateLocked',
                    'inherit' => 'setRateInherit',
                ],
            ],
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
                                $project->{$method2}($boolVal);
                            }
                        }
                    }
                    $primaryMethod = $method['primary'];
                    $project->{$primaryMethod}($_tag->textContent);
                } else {
                    $project->$method($_tag->textContent);
                }
            }
        }

        $quantitiesDOMTag = $projectProjectElement->getElementsByTagName('quantities');
        if (isset($quantitiesDOMTag) && $quantitiesDOMTag->length > 0) {

            // Element tags and their methods for address
            $quantityTags = [
                'label'     => 'setLabel',
                'rate'      => 'setRate',
                'billable'  => 'setBillable',
                'mandatory' => 'setMandatory',
            ];

            $quantitiesDOM = $quantitiesDOMTag->item(0);

            // Loop through each returned address for the customer\
            /** @var \DOMElement $quantityDOM */
            foreach($quantitiesDOM->getElementsByTagName('quantity') as $quantityDOM) {

                // Make a new tempory ProjectQuantity class
                $tempQuantity = new ProjectQuantity();

                // Loop through the element tags. Determine if it exists and set it if it does
                foreach($quantityTags as $tag => $method) {

                    // Get the dom element
                    $_tag = $quantityDOM->getElementsByTagName($tag)->item(0);

                    // Check if the tag is set, and its content is set, to prevent DOMNode errors
                    if(isset($_tag) && isset($_tag->textContent)) {
                        $tempQuantity->$method($_tag->textContent);
                    }

                    switch($tag) {
                        case 'billable':
                            if(!empty($locked = $_tag->getAttribute('locked')) && null !== ($isLocked = filter_var($locked, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))) {
                                $tempQuantity->setBillableLocked($isLocked);
                            }
                            break;
                    }
                }

                // Add the quantity to the project
                $project->addQuantity($tempQuantity);
                // Clean that memory!
                unset($tempQuantity);
            }
        }

        // Credit management elements
        $remittanceAdviceElement = $responseDOM->getElementsByTagName('remittanceadvice')->item(0);

        // Credit management elements and their methods
        $remittanceAdviceTags = [
            'sendtype' => 'setSendType',
            'sendmail' => 'setSendMail',
        ];

        $project->setRemittanceAdvice(new \Pronamic\Twinfield\Project\ProjectRemittanceAdvice());

        // Go through each financial element and add to the assigned method
        foreach($remittanceAdviceTags as $tag => $method) {

            // Get the dom element
            $_tag = $remittanceAdviceElement->getElementsByTagName($tag)->item(0);

            // If it has a value, set it to the associated method
            if(isset($_tag) && isset($_tag->textContent)) {
                $project->getRemittanceAdvice()->$method($_tag->textContent);
            }
        }

        return $project;
    }
}
