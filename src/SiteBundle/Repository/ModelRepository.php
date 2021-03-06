<?php

namespace SiteBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping;
use SiteBundle\Entity\Model;

/**
 * ModelRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ModelRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * ModelRepository constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, new Mapping\ClassMetadata(Model::class));
    }

    /**
     * @param Model $model
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Model $model)
    {
        $em = $this->getEntityManager();
        $em->persist($model);
        $em->flush();
    }

    /**
     * @param Model $model
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(Model $model)
    {
        $em = $this->getEntityManager();
        $em->merge($model);
        $em->flush();
    }

    public function getModelsForBrand($brandId)
    {
        return $this->createQueryBuilder("q")
        ->where("q.brand = :brandid")
        ->setParameter("brandid", $brandId)
        ->getQuery()
        ->getResult();
    }

}
