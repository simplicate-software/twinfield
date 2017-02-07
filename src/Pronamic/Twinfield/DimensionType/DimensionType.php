<?php

namespace Pronamic\Twinfield\DimensionType;

/**
 * Class DimenstionType
 *
 * @package Pronamic\Twinfield\DimensionType
 */
class DimensionType {

    /** @var string The status of the dimension type */
    private $status;

    /** @var string The office that belongs to the dimension type */
    private $office;

    /** @var string The code of the dimension type */
    private $code;

    /** @var string The name of the dimension type */
    private $name;

    /** @var string The short name of the dimension type */
    private $shortname;

    /** @var string The mask of the dimension type */
    private $mask;

    /** @var string Times this dimension type has been touched */
    private $touched;

    /**
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return $this
     */
    public function setStatus($status) {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getOffice() {
        return $this->office;
    }

    /**
     * @param string $office
     *
     * @return $this
     */
    public function setOffice($office) {
        $this->office = $office;

        return $this;
    }

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
     * @return string
     */
    public function getMask() {
        return $this->mask;
    }

    /**
     * @param string $mask
     *
     * @return $this
     */
    public function setMask($mask) {
        $this->mask = $mask;

        return $this;
    }

    /**
     * @return string
     */
    public function getTouched() {
        return $this->touched;
    }

    /**
     * @param string $touched
     *
     * @return $this
     */
    public function setTouched($touched) {
        $this->touched = $touched;

        return $this;
    }

}
