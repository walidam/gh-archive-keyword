<?php

namespace App\Infrastructure\Repository;

use App\Domain\Repository\IWriteRepoRepository;
use App\Infrastructure\Entity\Repo;
use App\Domain\Dto\Repo as RepoDto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class OrmWriteRepoRepository extends ServiceEntityRepository implements IWriteRepoRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Repo::class);
    }

    public function create(RepoDto $repo): int
    {
        $repo = Repo::fromArray([
            'id'    => $repo->getId(),
            'name'  => $repo->getName(),
            'url'   => $repo->getUrl(),
        ]);
        $this->getEntityManager()->persist($repo);
        $this->getEntityManager()->flush();

        return $repo->id();
    }
}
