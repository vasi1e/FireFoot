<?php
/**
 * Created by PhpStorm.
 * User: Mim40
 * Date: 6/25/2019
 * Time: 3:00 PM
 */

namespace SiteBundle\Service;


interface SUDServiceInterface
{
    public function saveProperty($property, $object);
    public function updateProperty($property, $object);
    public function deleteProperty($property, $object);
}