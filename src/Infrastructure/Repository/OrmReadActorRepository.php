<?php

namespace App\Infrastructure\Repository;

use App\Domain\Repository\IReadActorRepository;
use App\Infrastructure\Entity\Actor;
use App\Domain\Dto\Actor as ActorDto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class OrmReadActorRepository extends ServiceEntityRepository implements IReadActorRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Actor::class);
    }

    public function getById(int $id): ?ActorDto
    {
        /** @var Actor $actor */
        if ($actor = $this->find($id)) {
            return ActorDto::fromArray([
                'id' => $actor->id(),
                'login' => $actor->login(),
                'url' => $actor->url(),
                'avatar_url' => $actor->avatarUrl(),
            ]);
        }

        return null;
    }
}
