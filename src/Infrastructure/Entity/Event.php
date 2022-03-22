<?php

namespace App\Infrastructure\Entity;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="`event`",
 *    indexes={@ORM\Index(name="IDX_EVENT_TYPE", columns={"type"})}
 * )
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\Column(type="bigint")
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private int $id;

    /**
     * @ORM\Column(type="EventType", nullable=false)
     */
    private string $type;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private int $count = 1;

    /**
     * @ORM\ManyToOne(targetEntity="App\Infrastructure\Entity\Actor", cascade={"persist"})
     * @ORM\JoinColumn(name="actor_id", referencedColumnName="id")
     */
    private Actor $actor;

    /**
     * @ORM\ManyToOne(targetEntity="App\Infrastructure\Entity\Repo", cascade={"persist"})
     * @ORM\JoinColumn(name="repo_id", referencedColumnName="id")
     */
    private Repo $repo;

    /**
     * @ORM\Column(type="json", nullable=false, options={"jsonb": true})
     */
    private array $payload;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=false)
     */
    private \DateTimeImmutable $createAt;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $comment;

    public function __construct(int $id, string $type, Actor $actor, Repo $repo, array $payload, \DateTimeImmutable $createAt, ?string $comment)
    {
        $this->id = $id;
        EventType::assertValidChoice($type);
        $this->type = $type;
        $this->actor = $actor;
        $this->repo = $repo;
        $this->payload = $payload;
        $this->createAt = $createAt;
        $this->comment = $comment;

        if ($type === EventType::COMMIT) {
            $this->count = $payload['size'] ?? 1;
        }
    }

    public function id(): int
    {
        return $this->id;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function actor(): Actor
    {
        return $this->actor;
    }

    public function repo(): Repo
    {
        return $this->repo;
    }

    public function payload(): array
    {
        return $this->payload;
    }

    public function createAt(): \DateTimeImmutable
    {
        return $this->createAt;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }
}
