<?php

namespace Pronamic\Twinfield\DimensionType;

use Pronamic\Twinfield\DimensionType\Mapper\DimensionTypeMapper;
use Pronamic\Twinfield\Factory\FinderFactory;
use Pronamic\Twinfield\Response\Response;

/**
 * DimensionTypeFactory
 *
 * A facade factory to make interaction with the the twinfield service easier
 * when trying to retrieve or send information about dimension types.
 *
 * @package    Pronamic\Twinfield
 * @subpackage DimensionType
 */
class DimensionTypeFactory extends FinderFactory {

    /**
     * List all dimension types
     *
     * @param string $pattern  The search pattern. May contain wildcards * and ?
     * @param int    $field    The search field determines which field or fields will be searched. The available fields
     *                         depends on the finder type. Passing a value outside the specified values will cause an
     *                         error.
     * @param int    $firstRow First row to return, useful for paging
     * @param int    $maxRows  Maximum number of rows to return, useful for paging
     * @param array  $options  The Finder options. Passing an unsupported name or value causes an error. It's possible
     *                         to add multiple options. An option name may be used once, specifying an option multiple
     *                         times will cause an error.
     *
     * @return DimensionType[] the VAT codes found
     */
    public function listAll($pattern = '*', $field = 0, $firstRow = 1, $maxRows = 100, $options = []) {
        $response = $this->searchFinder(self::TYPE_DIMENSION_TYPES, $pattern, $field, $firstRow, $maxRows, $options);
        $dimTypes = [];
        if($response->data->TotalRows !== 0) {
            foreach($response->data->Items->ArrayOfString as $dimTypeArray) {
                $dimType = new DimensionType();
                $dimType->setCode($dimTypeArray->string[0]);
                $dimType->setName($dimTypeArray->string[1]);
                $dimTypes[] = $dimType;
            }
        }

        return $dimTypes;
    }

    /**
     * @param string $code    Dimension type code (Projects = PRJ, Activities = ACT)
     * @param null   $office
     *
     * @return bool|DimensionType
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
            $requestDimensionType = new \Pronamic\Twinfield\Request\Read\DimensionType();
            $requestDimensionType
                ->setOffice($office)
                ->setCode($code);

            // Send the Request document and set the response to this instance.
            /** @var Response $response */
            $response = $service->send($requestDimensionType);
            $this->setResponse($response);

            // Return a mapped Customer if successful or false if not.
            if($response->isSuccessful()) {
                return DimensionTypeMapper::map($response);
            } else {
                return false;
            }
        }

        return false;
    }
}
