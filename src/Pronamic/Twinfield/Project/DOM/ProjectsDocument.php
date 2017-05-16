<?php
namespace Pronamic\Twinfield\Project\DOM;

use Pronamic\Twinfield\Project\Project;

/**
 * The Document Holder for making new XML customers. Is a child class
 * of DOMDocument and makes the required DOM tree for the interaction in
 * creating a new customer.
 *
 * @package Pronamic\Twinfield
 * @subpackage Invoice\DOM
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Pronamic
 */
class ProjectsDocument extends \DOMDocument
{
    /**
     * Holds the <dimension> element
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

        $this->dimensionElement = $this->createElement('dimension');
        $this->appendChild($this->dimensionElement);
    }

    /**
     * Turns a passed Customer class into the required markup for interacting
     * with Twinfield.
     *
     * This method doesn't return anything, instead just adds the invoice to
     * this DOMDOcument instance for submission usage.
     *
     * @access public
     * @param \Pronamic\Twinfield\Project\Project $project
     * @return void | [Adds to this instance]
     */
    public function addProject(Project $project)
    {
        // Elements and their associated methods for customer
        $projectTags = [
            'code'      => 'getCode',
            'name'      => 'getName',
            'shortname' => 'getShortName',
            'type'      => 'getType',
            'website'   => 'getWebsite',
        ];

        if($project->getOffice()) {
            $projectTags['office'] = 'getOffice';
        }

        $status = $project->getStatus();
        if(!empty($status)) {
            $this->dimensionElement->setAttribute('status', $status);
        }

        // Go through each customer element and use the assigned method
        foreach($projectTags as $tag => $method) {

            // Make text node for method value
            $node = $this->createTextNode($project->$method());

            // Make the actual element and assign the node
            $element = $this->createElement($tag);
            $element->appendChild($node);

            // Add the full element
            $this->dimensionElement->appendChild($element);
        }

        $projectProjectTags = [
            'validfrom'          => 'getValidFrom',
//            'validto'            => 'getValidTo', // Ongeldige XML - Het element 'validto' mag niet worden aangeleverd.
            'validtill'          => 'getValidTill', // Ongeldige XML - Het element 'validto' mag niet worden aangeleverd.
            'invoicedescription' => 'getInvoiceDescription',
            'authoriser'         => 'getAuthoriser',
            'customer'           => 'getCustomer',
            'billable'           => 'isBillable',
            'rate'               => 'getRate',
        ];

        // Make the creditmanagement element
        $projectProjectElement = $this->createElement('projects');
        $this->dimensionElement->appendChild($projectProjectElement);

        // Go through each credit management element and use the assigned method
        foreach($projectProjectTags as $tag => $method) {

            // Make the text node for the method value
            $nodeValue = $project->{$method}();
            if(is_bool($nodeValue)) {
                $nodeValue = ($nodeValue) ? 'true' : 'false';
            }

            // Make the actual element and assign the node
            $element = $this->createElement($tag);
            // For instance, if billable's locked=true, billable's node value must be left empty
            $addNodeValue = true;
            switch($tag) {
                case 'authoriser':
                    $element->setAttribute('locked',  $project->isAuthoriserLocked() ? 'true' : 'false');
                    $element->setAttribute('inherit', $project->isAuthoriserInherit() ? 'true' : 'false');
                    break;
                case 'customer':
                    $element->setAttribute('locked',  $project->isCustomerLocked() ? 'true' : 'false');
                    $element->setAttribute('inherit', $project->isCustomerInherit() ? 'true' : 'false');
                    break;
                case 'billable':
                    $element->setAttribute('locked',   $project->isBillableLocked() ? 'true' : 'false');
                    $element->setAttribute('inherit',  $project->isBillableInherit() ? 'true' : 'false');
                    $element->setAttribute('forratio', $project->isBillableForratio() ? 'true' : 'false');
                    if($project->isBillableLocked()) {
                        $addNodeValue = false;
                    }
                    break;
                case 'rate':
                    $element->setAttribute('locked',  $project->isRateLocked() ? 'true' : 'false');
                    $element->setAttribute('inherit', $project->isRateInherit() ? 'true' : 'false');
                    break;
            }
            $node = $this->createTextNode($addNodeValue ? $nodeValue : '');
            $element->appendChild($node);

            // Add the full element
            $projectProjectElement->appendChild($element);
        }

        if ($project->getCostCenter() !== null) {
            $financialTags = [
                'substitutionlevel' => 'getSubstituteLevel',
                'substitutewith'    => 'getCostCenter'
            ];

            $financialElement = $this->createElement('financials');
            $this->dimensionElement->appendChild($financialElement);

            foreach ($financialTags as $tag => $method) {
                $node = $this->createTextNode($project->$method());

                $element = $this->createElement($tag);
                $element->appendChild($node);

                $financialElement->appendChild($element);
            }
        }

//        // Check if the financial information should be supplied
//        if ($project->getDueDays() > 0) {
//
//            // Financial elements and their methods
//            $financialsTags = array(
//                'duedays'      => 'getDueDays',
//                'payavailable' => 'getPayAvailable',
//                'paycode'      => 'getPayCode',
//                'vatcode'      => 'getVatCode',
//                'ebilling'     => 'getEBilling',
//                'ebillmail'    => 'getEBillMail'
//            );
//
//            // Make the financial element
//            $financialElement = $this->createElement('financials');
//            $this->dimensionElement->appendChild($financialElement);
//
//            // Go through each financial element and use the assigned method
//            foreach ($financialsTags as $tag => $method) {
//
//                // Make the text node for the method value
//                $node = $this->createTextNode($project->$method());
//
//                // Make the actual element and assign the node
//                $element = $this->createElement($tag);
//                $element->appendChild($node);
//
//                // Add the full element
//                $financialElement->appendChild($element);
//            }
//        }

        //check if creditmanagement should be set
        if ($project->getRemittanceAdvice() !== null) {

            // Credit management elements and their methods
            $remittanceAdviceTags = array(
                'sendtype' => 'getSendType',
                'sendmail' => 'getSendMail',
            );

            // Make the creditmanagement element
            $remittanceAdviceElement = $this->createElement('remittanceadvice');
            $this->dimensionElement->appendChild($remittanceAdviceElement);

            // Go through each credit management element and use the assigned method
            foreach($remittanceAdviceTags as $tag => $method) {

                // Make the text node for the method value
                $nodeValue = $project->getRemittanceAdvice()->$method();
                if(is_bool($nodeValue)) {
                    $nodeValue = ($nodeValue) ? 'true' : 'false';
                }
                $node = $this->createTextNode($nodeValue);

                // Make the actual element and assign the node
                $element = $this->createElement($tag);
                $element->appendChild($node);

                // Add the full element
                $remittanceAdviceElement->appendChild($element);
            }
        }
    }
}
