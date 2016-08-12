<?php
namespace Pronamic\Twinfield\Match;

use \Pronamic\Twinfield\Factory\ParentFactory;
use \Pronamic\Twinfield\Match\Mapper\MatchMapper;
use \Pronamic\Twinfield\Request as Request;

/**
 * MatchFactory
 * 
 * A facade factory to make interaction with the the twinfield service easier
 * when trying to retrieve or send information about Matchs.
 * 
 * Each function has detailed explanation over what is required, and what
 * happens.
 * 
 * If you require more complex interactions or a heavier amount of control
 * over the requests to/from then look inside the methods or see
 * the advanced guide detailing the required usages.
 * 
 * @package Pronamic\Twinfield
 * @subpackage Match
 * @author Willem van de Sande <W.vandeSande@MailCoupon.nl>
 * @version 0.0.1
 */
class MatchFactory extends ParentFactory
{
    /**
     * Requests a specific Match based off the passed in code
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
     * It makes a new instance of the Request\Read\Match() and sets the
     * office and code parameters.
     * 
     * Using the Service class it will attempt to send the DOM document from
     * Read\Match()
     * 
     * It sets the response to this instances method setResponse() (which you
     * can access with getResponse())
     * 
     * If the response was successful it will return a 
     * \Pronamic\Twinfield\Match\Match instance, made by the
     * \Pronamic\Twinfield\Match\Mapper\MatchMapper class.
     * 
     * @access public
     * @param int $code
     * @param int $office
     * @return \Pronamic\Twinfield\Match\Match | false
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

            // Make a request to read a single Match. Set the required values
            $requestMatch = new Request\Read\Match();
            $requestMatch
                ->setOffice($office)
                ->setCode($code);

            // Send the Request document and set the response to this instance.
            $response = $service->send($requestMatch);
            $this->setResponse($response);

            // Return a mapped Match if successful or false if not.
            if ($response->isSuccessful()) {
                return MatchMapper::map($response);
            } else {
                return false;
            }
        }
    }

    /**
     * Sends a \Pronamic\Twinfield\Match\Match instance to Twinfield
     * to update or add.
     * 
     * First attempts to login with the passed configuration in the constructor.
     * If successful will get the secure Service class.
     * 
     * It will then make an instance of 
     * \Pronamic\Twinfield\Customer\DOM\MatchDocument where it will
     * pass in the Match class in this methods parameter.
     * 
     * It will then attempt to send the DOM document from MatchsDocument
     * and set the response to this instances method setResponse() (which you
     * can get with getResponse())
     * 
     * If successful will return true, else will return false.
     * 
     * If you want to map the response back into a Artoc;e use getResponse()->
     * getResponseDocument()->asXML() into the MatchMapper::map method.
     * 
     * @access public
     * @param \Pronamic\Twinfield\Match\Match $match
     * @return boolean
     */
    public function send(Match $match)
    {
        // Attempts the process login
        if ($this->getLogin()->process()) {

            // Gets the secure service
            $service = $this->getService();

            // Gets a new instance of matchDocument and sets the $Match
            $matchDocument = new DOM\MatchDocument();
            $matchDocument->addMatch($match);

            // Send the DOM document request and set the response
            $response = $service->send($matchDocument);
            $this->setResponse($response);

            // Return a bool on status of response.
            if ($response->isSuccessful()) {
                return true;
            } else {
                return false;
            }
        }
    }
}
