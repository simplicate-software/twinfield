<?php

namespace Pronamic\Twinfield\ProfitLoss;

use Pronamic\Twinfield\Factory\ParentFactory;
use Pronamic\Twinfield\ProfitLoss\Mapper\ProfitLossMapper;
use Pronamic\Twinfield\Request;
use Pronamic\Twinfield\Response\Response;

/**
 * ProjectFactory
 *
 * A facade factory to make interaction with the twinfield service easier
 * when trying to retrieve or set information about Projects.
 *
 * Each method has detailed explanation over what is required, and what
 * happens.
 *
 * If you require more complex interactions or a heavier amount of control
 * over the requests to/from then look inside the methods or see the
 * advanced guide details the required usages.
 *
 * @package    Pronamic\Twinfield
 * @subpackage Project
 */
class ProfitLossFactory extends ParentFactory {
    /**
     * Requests a specific project based off the passed in code
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
     * It makes a new instance of the Request\Read\Project() and sets the
     * office and code parameters.
     *
     * Using the Service class it will attempt to send the DOM document from
     * Read\Project()
     *
     * It sets the response to this instances method setResponse() (which you
     * can access with getResponse())
     *
     * If the response was successful it will return a
     * \Pronamic\Twinfield\Project\Project instance, made by the
     * \Pronamic\Twinfield\Project\Mapper\ProjectMapper class.
     *
     * @access public
     *
     * @param int $code
     * @param int $office
     *
     * @return \Pronamic\Twinfield\Project\Project | false
     */
    public function get($code, $office = null) {
        // Attempts to process the login
        if($this->getLogin()->process()) {

            // Get the secure service class
            $service = $this->getService();

            // No office passed, get the office from the Config
            if(!$office) {
                $office = $this->getConfig()->getOffice();
            }

            // Make a request to read a single customer. Set the required values
            $requestProject = new Request\Read\ProfitLoss();
            $requestProject
                ->setOffice($office)
                ->setCode($code);

            // Send the Request document and set the response to this instance.
            /** @var Response $response */
            $response = $service->send($requestProject);
            $this->setResponse($response);

            // Return a mapped Customer if successful or false if not.
            if($response->isSuccessful()) {
                return ProfitLossMapper::map($response);
            } else {
                return false;
            }
        }
    }

    /**
     * Requests all projects from the List Dimension Type.
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
     * in the response and make an array of the project ID as the array key
     * and the value being an array of 'name' and 'shortname'
     *
     * If the response wasn't succesful it will return false.
     *
     * @access public
     *
     * @param null   $office
     *
     * @return array|false
     */
    public function listAll($office = null) {
        $dimType = 'PNL';

        // Attempts to process the login
        if($this->getLogin()->process()) {

            // Gets the secure service class
            $service = $this->getService();

            // If no office present, use the config set value
            if(!$office) {
                $office = $this->getConfig()->getOffice();
            }

            // Make a request to a list of all customers
            $requestProjects = new Request\Catalog\Dimension(
                $office,
                $dimType
            );

            // Send the Request document and set the response to this instance.
            /** @var Response $response */
            $response = $service->send($requestProjects);
            $this->setResponse($response);

            // Loop through the results if successful
            if($response->isSuccessful()) {

                // Get the raw response document
                $responseDOM = $response->getResponseDocument();

                // Prepared empty customer array
                $profitLosses = [];

                // Store in an array by customer id
                /** @var \DOMElement $project */
                foreach($responseDOM->getElementsByTagName('dimension') as $project) {
                    $projectId = $project->textContent;

                    if(empty($projectId)) {
                        continue;
                    }

                    $profitLosses[$projectId] = [
                        'name'      => $project->getAttribute('name'),
                        'shortName' => $project->getAttribute('shortname'),
                    ];
                }
                unset($profitLosses[$dimType]);

                return $profitLosses;
            }
        }

        return false;
    }
}