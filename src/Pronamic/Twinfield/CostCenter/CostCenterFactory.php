<?php

namespace Pronamic\Twinfield\CostCenter;

use Pronamic\Twinfield\Factory\ProcessXmlFactory;

class CostCenterFactory extends ProcessXmlFactory {

    public function listAll($office, $factoryParams = null) {
        if ($this->getLogin()->process()) {
            $browseCode = self::BROWSE_CODE_COST_CENTERS;
            $browseData = $this->browseData($office, $browseCode);
            $xmlBrowseDefinition = simplexml_load_string($browseData->ProcessXmlStringResult);
        }
    }
}