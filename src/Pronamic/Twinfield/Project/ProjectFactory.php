<?php

namespace Pronamic\Twinfield\Project;

use Pronamic\Twinfield\Factory\ParentFactory;
use Pronamic\Twinfield\Project\Mapper\ProjectMapper;
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
class ProjectFactory extends ParentFactory {
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
            $requestProject = new Request\Read\Project();
            $requestProject
                ->setOffice($office)
                ->setCode($code);

            // Send the Request document and set the response to this instance.
            /** @var Response $response */
            $response = $service->send($requestProject);
            $this->setResponse($response);

            // Return a mapped Customer if successful or false if not.
            if($response->isSuccessful()) {
                return ProjectMapper::map($response);
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
        $dimType = 'PRJ';

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
                $projects = [];

                // Store in an array by customer id
                /** @var \DOMElement $project */
                foreach($responseDOM->getElementsByTagName('dimension') as $project) {
                    $projectId = $project->textContent;

                    if(empty($projectId)) {
                        continue;
                    }

                    $projects[$projectId] = [
                        'name'      => $project->getAttribute('name'),
                        'shortName' => $project->getAttribute('shortname'),
                    ];
                }
                unset($projects[$dimType]);

                return $projects;
            }
        }

        return false;
    }

    /**
     * Sends a \Pronamic\Twinfield\Project\Project instance to Twinfield
     * to update or add.
     *
     * First attempts to login with the passed configuration in the constructor.
     * If successful will get the secure Service class.
     *
     * It will then make an instance of
     * \Pronamic\Twinfield\Project\DOM\ProjectsDocument where it will
     * pass in the Project class in this methods parameter.
     *
     * It will then attempt to send the DOM document from ProjectsDocument
     * and set the response to this instances method setResponse() (which you
     * can get with getResponse())
     *
     * If successful will return true, else will return false.
     *
     * If you want to map the response back into a project use getResponse()->
     * getResponseDocument()->asXML() into the ProjectMapper::map method.
     *
     * @param \Pronamic\Twinfield\Project\Project $project
     * @return boolean
     */
    public function send(Project $project) {
        // Attempts the process login
        if ($this->getLogin()->process()) {
            // Gets the secure service
            $service = $this->getService();

            // Gets a new instance of CustomersDocument and sets the $customer
            $projectsDocument = new DOM\ProjectsDocument();
            $projectsDocument->addProject($project);

            // Send the DOM document request and set the response
            $response = $service->send($projectsDocument);
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
     * Returns the very next free code available for the Project Code.  As adding a new project still requires
     * a code.  This method first retrieves all projects, sorts by key, gets the last element, and increments the code
     * by one.
     *
     * @return int
     */
    public function getFirstFreeCode() {
        $projects = $this->listAll();

        if(empty($projects)) {
            return 0;
        }

        // Get the keys
        $projectsKeys = array_keys($projects);

        // Sort the keys and reverse to get the last first
        asort($projectsKeys);
        $projectsKeys = array_reverse($projectsKeys);

        // Get the first of the reversed keys
        $latestProjectCode = $projectsKeys[0];

        // Increment by one and return.
        return (int) ++$latestProjectCode;
    }

}