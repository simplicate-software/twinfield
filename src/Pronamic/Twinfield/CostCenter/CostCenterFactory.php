<?php

namespace Pronamic\Twinfield\CostCenter;

use Pronamic\Twinfield\CostCenter\Mapper\CostCenterMapper;
use Pronamic\Twinfield\Factory\ProcessXmlFactory;
use Pronamic\Twinfield\Request\Catalog\Dimension;

/**
 * InvoiceFactory
 *
 * A facade factory to make interaction with the twinfield service easier
 * when trying to retrieve or set information about Purchase Invoices.
 *
 * Each method has detailed explanation over what is required, and what
 * happens.
 *
 * If you require more complex interactions or a heavier amount of control
 * over the requests to/from then look inside the methods or see the
 * advanced guide details the required usages.
 *
 * @package Pronamic\Twinfield
 * @subpackage PurchaseInvoice
 * @author Emile Bons <emile@emilebons.nl>
 */
class CostCenterFactory extends ProcessXmlFactory {
    /**
     * List all VAT codes.
     *
     * @param $office
     *
     * @return array
     */
    public function listAll($office)
    {
        $dimType = 'KPL';

        // Attempts to process the login
        if ($this->getLogin()->process()) {

            // Gets the secure service class
            $service = $this->getService();

            // If no office present, use the config set value
            if (! $office) {
                $office = $this->getConfig()->getOffice();
            }

            // Make a request to a list of all customers
            $request_costcenter = new Dimension(
                $office,
                $dimType
            );

            // Send the Request document and set the response to this instance.
            $response = $service->send($request_costcenter);
            $this->setResponse($response);

            // Loop through the results if successful
            if ($response->isSuccessful()) {

                // Get the raw response document
                $responseDOM = $response->getResponseDocument();

                // Prepared empty customer array
                $costCenters = array();

                // Store in an array by customer id
                foreach ($responseDOM->getElementsByTagName('dimension') as $costCenter) {
                    $costCenterId = $costCenter->textContent;

                    if (! is_numeric($costCenterId)) {
                        continue;
                    }

                    $costCenters[$costCenter->textContent] = $costCenter->getAttribute('name');
                }

                return $costCenters;
            }
        }
    }
}