<?php
/**
 * Created by PhpStorm.
 * User: Mim40
 * Date: 1/27/2019
 * Time: 11:15 PM
 */

namespace SiteBundle\Service;


use SiteBundle\Entity\Brand;
use SiteBundle\Entity\Model;

interface BrandModelServiceInterface
{
    public function getModelsForBrand($brandId);
    public function isBrandExisting(Brand $brand);
    public function isModelExisting(Model $model);
    public function generateRepositoryName($property);
    public function findPropertyByName($property, $name);
    public function getAllBrandsName();
}