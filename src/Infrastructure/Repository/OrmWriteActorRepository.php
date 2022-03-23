<?php

namespace App\Infrastructure\Repository;

use App\Domain\Repository\IWriteActorRepository;
use App\Infrastructure\Entity\Actor;
use App\Domain\Dto\Actor as ActorDto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class OrmWriteActorRepository extends ServiceEntityRepository implements IWriteActorRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Actor::class);
    }

    public function create(ActorDto $actor): int
    {
        $actor = Actor::fromArray([
            'id'            => $actor->getId(),
            'login'         => $actor->getLogin(),
            'url'           => $actor->getUrl(),
            'avatar_url'    => $actor->getAvatarUrl(),
        ]);
        $this->getEntityManager()->persist($actor);
        $this->getEntityManager()->flush();

        return $actor->id();
    }
}
