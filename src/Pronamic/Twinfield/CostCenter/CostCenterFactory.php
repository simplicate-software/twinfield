<?php

namespace Pronamic\Twinfield\CostCenter;

use Pronamic\Twinfield\Factory\FinderFactory;
use Pronamic\Twinfield\Factory\ProcessXmlFactory;

class CostCenterFactory extends FinderFactory {

    /**
     * @param string $pattern
     * @param int    $field
     * @param int    $firstRow
     * @param int    $maxRows
     * @param array  $options
     *
     * @return array
     */
    public function listAll($pattern = '*', $field = 0, $firstRow = 1, $maxRows = 100, $options = []) {
        $response = $this->searchFinder(self::TYPE_COST_CENTERS, $pattern, $field, $firstRow, $maxRows, $options);
        $costCenters = [];
        if ($response->data->TotalRows !== 0) {
            foreach($response->data->Items->ArrayOfString as $costCenterArray) {
                $costCenter = new CostCenter();
                $costCenter->setName($costCenterArray->string[2]);
                $costCenter->setCode($costCenterArray->string[3]);
                $costCenter->setInUse($costCenterArray->string[5]);
                $costCenter->setBehaviour($costCenterArray->string[7]);
                $costCenters[] = $costCenter;
            }
        }

        return $costCenters;
    }
}