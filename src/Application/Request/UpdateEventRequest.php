<?php

namespace App\Application\Request;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateEventRequest extends AbstractRequest
{
    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(
     *     min = 20,
     *     maxMessage = "This value is too short. It should have 20 characters or more."
     * )
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
