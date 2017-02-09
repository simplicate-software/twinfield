<?php

namespace Pronamic\Twinfield\DimensionType;

/**
 * Class DimenstionType
 *
 * @package Pronamic\Twinfield\DimensionType
 */
class LineDimensionType {

    /** @var string The code of the dimension  */
    private $code;

    /** @var string The name of the dimension */
    private $name;

    /** @var string The short name of the dimension */
    private $shortname;

    /** @var string|int Dimension type code */
    private $dimensionTypeCode;

    /**
     * @return string The dimension type code
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * @param string $code The dimension type code
     *
     * @return $this
     */
    public function setCode($code) {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string The name of the dimension type
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name The name of the dimension type
     *
     * @return $this
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getShortname() {
        return $this->shortname;
    }

    /**
     * @param string $shortname
     *
     * @return $this
     */
    public function setShortname($shortname) {
        $this->shortname = $shortname;

        return $this;
    }

    /**
     * @return string|int
     */
    public function getDimensionTypeCode() {
        return $this->dimensionTypeCode;
    }

    /**
     * @param string|int $dimensionTypeCode
     *
     * @return $this
     */
    public function setDimensionTypeCode($dimensionTypeCode) {
        $this->dimensionTypeCode = $dimensionTypeCode;

        return $this;
    }

}
