<?php

namespace App\Application\Request;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateEventRequest extends AbstractRequest
{
    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    private ?string $comment = null;

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }
}

