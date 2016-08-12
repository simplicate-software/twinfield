<?php

namespace Pronamic\Twinfield\Match;

class MatchLine
{
    private $ID;
    private $transCode;
    private $transNumber;
    private $transLine;
    private $matchValue;

    public function __construct()
    {
        $this->ID = uniqid();
    }

    public function getID()
    {
        return $this->ID;
    }

    public function setID($ID)
    {
        $this->ID = $ID;
        return $this;
    }

    public function getTransCode()
    {
        return $this->transCode;
    }

    public function setTransCode($transCode)
    {
        $this->transCode = $transCode;
        return $this;
    }

    public function getTransNumber()
    {
        return $this->transNumber;
    }

    public function setTransNumber($transNumber)
    {
        $this->transNumber = $transNumber;
        return $this;
    }

    public function getMatchValue()
    {
        return $this->matchValue;
    }

    public function setMatchValue($matchValue)
    {
        $this->matchValue = $matchValue;
        return $this;
    }

    public function getTransLine ()
    {
        return $this->transLine;
    }

    public function setTransLine($transLine)
    {
        $this->transLine = $transLine;
        return $this;
    }

}
