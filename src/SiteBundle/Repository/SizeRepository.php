<?php

namespace SiteBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping;
use SiteBundle\Entity\Size;

/**
 * SizeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SizeRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * ModelRepository constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, new Mapping\ClassMetadata(Size::class));
    }

    /**
     * @param Size $size
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Size $size)
    {
        $em = $this->getEntityManager();
        $em->persist($size);
        $em->flush();
    }
}
