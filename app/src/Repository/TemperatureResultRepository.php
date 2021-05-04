<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TemperatureResult;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TemperatureResult|null find($id, $lockMode = null, $lockVersion = null)
 * @method TemperatureResult|null findOneBy(array $criteria, array $orderBy = null)
 * @method TemperatureResult[]    findAll()
 * @method TemperatureResult[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TemperatureResultRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TemperatureResult::class);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function insert(string $country, string $city, float $temperature, bool $isFromCache): void
    {
        $result = new TemperatureResult();
        $result->setCountry($country);
        $result->setCity($city);
        $result->setResult($temperature);
        $result->setIsFromCache($isFromCache);

        $this->getEntityManager()->persist($result);
        $this->getEntityManager()->flush();
    }
}
