<?php

namespace CfdiUtils\Validate;

class Assert implements \Stringable
{
    private Status $status;

    /**
     * Assert constructor.
     * @param Status|null $status If null the status will be NONE
     */
    public function __construct(
        private string $code,
        private string $title = '',
        ?Status $status = null,
        private string $explanation = '',
    ) {
        if ('' === $this->code) {
            throw new \UnexpectedValueException('Code cannot be an empty string');
        }
        $this->setStatus($status ?: Status::none());
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

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setStatus(Status $status, ?string $explanation = null): void
    {
        $this->status = $status;
        if (null !== $explanation) {
            $this->setExplanation($explanation);
        }
    }

    public function setExplanation(string $explanation): void
    {
        $this->explanation = $explanation;
    }

    public function __toString(): string
    {
        return sprintf('%s: %s - %s', $this->status, $this->code, $this->title);
    }
}
