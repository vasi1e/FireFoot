<?php
/**
 * Created by PhpStorm.
 * User: Mim40
 * Date: 1/27/2019
 * Time: 11:16 PM
 */

namespace SiteBundle\Service;


use SiteBundle\Entity\Brand;
use SiteBundle\Entity\Model;
use SiteBundle\Repository\BrandRepository;
use SiteBundle\Repository\ModelRepository;

class BrandModelService implements BrandModelServiceInterface
{
    private $modelRepository;
    private $brandRepository;

    /**
     * BrandModelService constructor.
     * @param ModelRepository $modelRepository
     * @param BrandRepository $brandRepository
     */
    public function __construct(ModelRepository $modelRepository, BrandRepository $brandRepository)
    {
        $this->modelRepository = $modelRepository;
        $this->brandRepository = $brandRepository;
    }

    public function getModelsForBrand($brandId)
    {
        return $this->modelRepository->getModelsForBrand($brandId);
    }

    public function isBrandExisting(Brand $brand)
    {
        $anotherBrandWithSameName = $this
            ->brandRepository
            ->findOneBy(['name' => $brand->getName()]);

        if ($anotherBrandWithSameName != null) return true;
        else return false;
    }

    public function findPropertyByName($property, $name)
    {
        $repositoryName = $this->generateRepositoryName($property);
        return $this->$repositoryName->findOneBy(['name' => $name]);
    }

    public function generateRepositoryName($property)
    {
        return $property . "Repository";
    }

    public function isGoingToAddBrand()
    {
        if ($_POST['shoe']['brand'] == "" && $_POST["brandToAdd"] == "") return "You must choose one brand or write a new one";
        if ($_POST['shoe']['brand'] == "") return true;
        else return false;
    }

    public function isModelExisting(Model $currModel, $brandId)
    {
        $modelNames = [];

        /** @var Model $model */
        foreach ($this->modelRepository->getModelsForBrand($brandId) as $model) {
            $modelNames[] = $model->getName();
        }

        return in_array($currModel->getName(), $modelNames);
    }

    public function getAllBrandsName()
    {
        $brandNames = [];

        /** @var Brand $brand */
        foreach ($this->brandRepository->findAll() as $brand) {
            $brandNames[] = $brand->getName();
        }

        return $brandNames;
    }
}