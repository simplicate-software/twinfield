<?php

namespace Pronamic\Twinfield\CostCenter\Mapper;

use Pronamic\Twinfield\CostCenter\CostCenter;

/**
 * Class CostCenterMapper
 *
 * Maps XML-formatted response to CostCenter objects.
 *
 * @package Pronamic\Twinfield\CostCenter\Mapper
 * @author Johannes Smit <johannes.smit@simplicate.nl>
 */
class CostCenterMapper
{
    /**
     * Maps a response object to a clean CostCenter entity
     *
     * @access public
     *
     * @param \Pronamic\Twinfield\Response\Response $response
     *
     * @return CostCenter[]
     */
    public static function map($response)
    {
        $responseDOM = new \DOMDocument();

        $responseDOM->loadXML($response);

        $costCenterTags = [
            'status'    => 'setStatus',
            'code'      => 'setCode',
            'name'      => 'setName',
            'inuse'     => 'setInUse',
            'behaviour' => 'setBehaviour'
        ];

        $costCenter = new CostCenter();

        foreach ($costCenterTags as $tag => $method) {
            $_tag = $responseDOM->getElementsByTagName($tag)->item(0);
            if(isset($_tag) && isset($_tag->textContent)) {
                $costCenter->$method($_tag->textContent);
            }
        }
    }
}