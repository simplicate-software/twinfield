<?php

namespace Pronamic\Twinfield\Project;

class ProjectRemittanceAdvice {

    /** @var string */
    private $sendType;

    /** @var string */
    private $sendMail;

    /**
     * @param string $sendType
     *
     * @return $this
     */
    public function setSendType($sendType) {
        $this->sendType = $sendType;

        return $this;
    }

    /**
     * @return string
     */
    public function getSendType() {
        return $this->sendType;
    }

    /**
     * @param string $sendMail
     *
     * @return $this
     */
    public function setSendMail($sendMail) {
        $this->sendMail = $sendMail;

        return $this;
    }

    /**
     * @return string
     */
    public function getSendMail() {
        return $this->sendMail;
    }

}
