<?php

namespace SiteBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping;
use SiteBundle\Entity\Brand;

/**
 * BrandRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BrandRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * ModelRepository constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, new Mapping\ClassMetadata(Brand::class));
    }

    /**
     * @param Brand $brand
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Brand $brand)
    {
        $em = $this->getEntityManager();
        $em->persist($brand);
        $em->flush();
    }

    /**
     * @param Brand $brand
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(Brand $brand)
    {
        $em = $this->getEntityManager();
        $em->merge($brand);
        $em->flush();
    }
}