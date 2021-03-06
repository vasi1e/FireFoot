<?php

namespace SiteBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping;
use SiteBundle\Entity\ShoeSize;

/**
 * ShoeSizeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ShoeSizeRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * ModelRepository constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, new Mapping\ClassMetadata(ShoeSize::class));
    }

    /**
     * @param ShoeSize $shoeSize
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(ShoeSize $shoeSize)
    {
        $em = $this->getEntityManager();
        $em->persist($shoeSize);
        $em->flush();
    }

    /**
     * @param ShoeSize $shoeSize
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(ShoeSize $shoeSize)
    {
        $em = $this->getEntityManager();
        $em->merge($shoeSize);
        $em->flush();
    }

    /**
     * @param ShoeSize $shoeSize
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(ShoeSize $shoeSize)
    {
        $em = $this->getEntityManager();
        $em->remove($shoeSize);
        $em->flush();
    }
}
