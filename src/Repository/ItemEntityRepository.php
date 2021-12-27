<?php

namespace App\Repository;

use App\Entity\ItemEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @method ItemEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method ItemEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method ItemEntity[]    findAll()
 * @method ItemEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemEntity::class);
    }

    /**
     * @return ItemEntity[] Returns an array of ItemEntity objects
    */
    public function findAll()
    {
        return $this->createQueryBuilder('i')
            ->orderBy('i.id', 'ASC')
            ->getQuery()
            ->getArrayResult()
        ;
    }

    public function findOneByUuid($value): ?ItemEntity
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.uuid = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function insertOne(ItemEntity $data)
    {
        $connection = $this->getEntityManager()->getConnection();
        $this->getEntityManager()->getClassMetadata(get_class($data))->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);

        $id = $connection->executeQuery('SELECT UUID_SHORT() AS uuid_short;')
            ->fetchAllAssociative()[0]['uuid_short'];

        $data->setId($id);

        $result = $this->getEntityManager()
            ->persist($data);
        $this->getEntityManager()
            ->flush();

        return $result;
    }

    public function updateOne(ItemEntity $data)
    {
        $connection = $this->getEntityManager()->getConnection();
        $this->getEntityManager()->getClassMetadata(get_class($data))->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);

        $id = $connection->executeQuery('SELECT UUID_SHORT() AS uuid_short;')
            ->fetchAllAssociative()[0]['uuid_short'];

        $data->setId($id);

        $result = $this->getEntityManager()
            ->persist($data);
        $this->getEntityManager()
            ->flush();

        return $result;
    }
}
