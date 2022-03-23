<?php

namespace App\Infrastructure\Repository;

use App\Infrastructure\Entity\Repo;
use App\Domain\Dto\Repo as repoDto;
use App\Domain\Repository\IReadRepoRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class OrmReadRepoRepository extends ServiceEntityRepository implements IReadRepoRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Repo::class);
    }

    public function getById(int $id): ?repoDto
    {
        /** @var Repo $repo */
        if ($repo = $this->find($id)) {
            return repoDto::fromArray([
                'id' => $repo->id(),
                'name' => $repo->name(),
                'url' => $repo->url(),
            ]);
        }

        return null;
    }
}
