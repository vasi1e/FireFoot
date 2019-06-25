<?php

namespace SiteBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping;
use SiteBundle\Entity\CartOrder;

/**
 * CartOrderRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CartOrderRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * ModelRepository constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, new Mapping\ClassMetadata(CartOrder::class));
    }

    /**
     * @param CartOrder $order
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(CartOrder $order)
    {
        $em = $this->getEntityManager();
        $em->persist($order);
        $em->flush();
    }

    /**
     * @param CartOrder $order
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(CartOrder $order)
    {
        $em = $this->getEntityManager();
        $em->merge($order);
        $em->flush();
    }

    /**
     * @param CartOrder $order
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(CartOrder $order)
    {
        $em = $this->getEntityManager();
        $em->remove($order);
        $em->flush();
    }

    public function findSameOrderButPaidByShoeUserId($id)
    {
        return $this->createQueryBuilder("q")
            ->where("q.shoeUser = :shoeuserid")
            ->andWhere("q.paid = true")
            ->setParameter("shoeuserid", $id)
            ->getQuery()
            ->getResult();
    }
}
