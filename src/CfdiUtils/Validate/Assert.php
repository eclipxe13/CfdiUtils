<?php

namespace CfdiUtils\Validate;

class Assert
{
    /** @var string */
    private $title;

    /** @var Status */
    private $status;

    /** @var string */
    private $explanation;

    /** @var string */
    private $code;

    /**
     * Assert constructor.
     * @param string $code
     * @param string $title
     * @param Status|null $status If null the status will be NONE
     * @param string $explanation
     */
    public function __construct(string $code, string $title = '', Status $status = null, string $explanation = '')
    {
        if ('' === $code) {
            throw new \UnexpectedValueException('Code cannot be an empty string');
        }
        $this->code = $code;
        $this->title = $title;
        $this->setStatus($status ? : Status::none());
        $this->explanation = $explanation;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getExplanation(): string
    {
        return $this->explanation;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function setStatus(Status $status, string $explanation = null)
    {
        $this->status = $status;
        if (null !== $explanation) {
            $this->setExplanation($explanation);
        }
    }

    public function setExplanation(string $explanation)
    {
        $this->explanation = $explanation;
    }

    public function __toString()
    {
        return sprintf('%s: %s - %s', $this->status, $this->code, $this->title);
    }
}
