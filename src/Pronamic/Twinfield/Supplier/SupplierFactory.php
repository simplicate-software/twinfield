<?php
namespace Pronamic\Twinfield\Supplier;

use \Pronamic\Twinfield\Factory\ParentFactory;
use \Pronamic\Twinfield\Supplier\Mapper\SupplierMapper;
use \Pronamic\Twinfield\Request as Request;

/**
 * SupplierFactory
 * 
 * A facade factory to make interaction with the the twinfield service easier
 * when trying to retrieve or send information about Suppliers.
 * 
 * Each function has detailed explanation over what is required, and what
 * happens.
 * 
 * If you require more complex interactions or a heavier amount of control
 * over the requests to/from then look inside the methods or see
 * the advanced guide detailing the required usages.
 * 
 * @package Pronamic\Twinfield
 * @subpackage Supplier
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Pronamic
 * @version 0.0.1
 */
class SupplierFactory extends ParentFactory
{
    /**
     * Requests a specific supplier based off the passed in code
     * and office.
     * 
     * Office is an optional parameter.
     * 
     * First it attempts to login with the passed configuration into
     * this instances constructor.  If successful it will get the Service
     * class to handle further interactions.
     * 
     * If no office has been passed it will instead take the default office
     * from the passed in config class.
     * 
     * It makes a new instance of the Request\Read\Supplier() and sets the
     * office and code parameters.
     * 
     * Using the Service class it will attempt to send the DOM document from
     * Read\Supplier()
     * 
     * It sets the response to this instances method setResponse() (which you
     * can access with getResponse())
     * 
     * If the response was successful it will return a 
     * \Pronamic\Twinfield\Supplier\Supplier instance, made by the
     * \Pronamic\Twinfield\Supplier\Mapper\SupplierMapper class.
     * 
     * @access public
     * @param int $code
     * @param int $office
     * @return \Pronamic\Twinfield\Supplier\Supplier | false
     */
    public function get($code, $office = null)
    {
        // Attempts to process the login
        if ($this->getLogin()->process()) {
            
            // Get the secure service class
            $service = $this->getService();

            // No office passed, get the office from the Config
            if (! $office) {
                $office = $this->getConfig()->getOffice();
            }

            // Make a request to read a single supplier. Set the required values
            $request_supplier = new Request\Read\Supplier();
            $request_supplier
                ->setOffice($office)
                ->setCode($code);
            
            // Send the Request document and set the response to this instance.
            $response = $service->send($request_supplier);
            $this->setResponse($response);

            // Return a mapped Supplier if successful or false if not.
            if ($response->isSuccessful()) {
                return SupplierMapper::map($response);
            } else {
                return false;
            }
        }
    }
    
    /**
     * Requests all suppliers from the List Dimension Type.
     * 
     * First attempts to login with the passed configuration into this
     * instances constructor. If successful will get the Service class
     * to handle further interactions.
     * 
     * Makes a new instance of Catalog\Dimension and sets the office and
     * dimtype values.
     * 
     * Using the service class it will attempt to send the DOM document
     * from Catalog\Dimension.
     * 
     * It sets the response to this instances method setResponse() (which you
     * can access with getResponse())
     * 
     * If the response was successful it will loop through all the results
     * in the response and make an array of the supplier ID as the array key
     * and the value being an array of 'name' and 'shortname'
     * 
     * If the response wasn't succesful it will return false.
     * 
     * @access public
     * @return array | false
     */
    public function listAll($office = null, $dimType = 'CRD')
    {

        // Attempts to process the login
        if ($this->getLogin()->process()) {

            // Gets the secure service class
            $service = $this->getService();

            // If no office present, use the config set value
            if (! $office) {
                $office = $this->getConfig()->getOffice();
            }

            // Make a request to a list of all suppliers
            $request_suppliers = new Request\Catalog\Dimension(
                $office,
                $dimType
            );

            // Send the Request document and set the response to this instance.
            $response = $service->send($request_suppliers);
            $this->setResponse($response);

            // Loop through the results if successful
            if ($response->isSuccessful()) {

                // Get the raw response document
                $responseDOM = $response->getResponseDocument();

                // Prepared empty supplier array
                $suppliers = array();

                // Store in an array by supplier id
                foreach ($responseDOM->getElementsByTagName('dimension') as $supplier) {
                    $supplier_id = $supplier->textContent;

                    if (! is_numeric($supplier_id)) {
                        continue;
                    }

                    $suppliers[$supplier->textContent] = array(
                        'name'      => $supplier->getAttribute('name'),
                        'shortName' => $supplier->getAttribute('shortname')
                    );
                }

                return $suppliers;
            }
        }
    }

    /**
     * Sends a \Pronamic\Twinfield\Supplier\Supplier instance to Twinfield
     * to update or add.
     * 
     * First attempts to login with the passed configuration in the constructor.
     * If successful will get the secure Service class.
     * 
     * It will then make an instance of 
     * \Pronamic\Twinfield\Supplier\DOM\SuppliersDocument where it will
     * pass in the Supplier class in this methods parameter.
     * 
     * It will then attempt to send the DOM document from SuppliersDocument
     * and set the response to this instances method setResponse() (which you
     * can get with getResponse())
     * 
     * If successful will return true, else will return false.
     * 
     * If you want to map the response back into a supplier use getResponse()->
     * getResponseDocument()->asXML() into the SupplierMapper::map method.
     * 
     * @access public
     * @param \Pronamic\Twinfield\Supplier\Supplier $supplier
     * @return boolean
     */
    public function send(Supplier $supplier)
    {
        // Attempts the process login
        if ($this->getLogin()->process()) {
            // Gets the secure service
            $service = $this->getService();

            // Gets a new instance of SuppliersDocument and sets the $supplier
            $suppliersDocument = new DOM\SuppliersDocument();
            $suppliersDocument->addSupplier($supplier);

            // Send the DOM document request and set the response
            $response = $service->send($suppliersDocument);
            $this->setResponse($response);

            // Return a bool on status of response.
            if ($response->isSuccessful()) {
                return true;
            } else {
                return false;
            }
        }
    }
    
    /**
     * Returns the very next free code available for the Supplier Code.  As adding a new supplier still requires
     * a code.  This method first retrieves all suppliers, sorts by key, gets the last element, and increments the code
     * by one.
     * 
     * @access public
     * @return int
     */
    public function getFirstFreeCode()
    {
        // Get all suppliers
        $suppliers = $this->listAll();
        
        // Check some suppliers exist
        if (empty($suppliers)) {
            return 0;
        }
        
        // Get the keys
        $suppliersKeys = array_keys($suppliers);
        
        // Sort the keys and reverse to get the last first
        asort($suppliersKeys);
        $suppliersKeys = array_reverse($suppliersKeys);
        
        // Get the first of the reversed keys
        $latestSupplierCode = $suppliersKeys[0];
        
        // Increment by one and return.
        return (int) ++$latestSupplierCode;
    }
}
