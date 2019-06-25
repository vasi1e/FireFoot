<?php

namespace SiteBundle\Form;

use Doctrine\ORM\EntityManagerInterface;
use SiteBundle\Entity\Brand;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShoeType extends AbstractType
{
    private $em;

    /**
     * ShoeType constructor.
     * @param $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('condition', ChoiceType::class, [
                    'choices'  => [
                        'New' => 'new',
                        'Used' => 'used'
                    ],
                    'placeholder' => "Please select condition",
                    'required' => true
                ])
                ->add('conditionOutOf10', TextType::class)
                ->add('uploadImages', FileType::class, [
                    'multiple' => true,
                    'attr'     => [
                        'accept' => 'image/*',
                        'multiple' => 'multiple'
                    ]
                ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
        $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));
    }

    protected function addElements(FormInterface $form, Brand $brand = null) {
        $form->add('brand', EntityType::class, [
            'required' => true,
            'data' => $brand,
            'placeholder' => "Please select brand",
            'class' => 'SiteBundle\Entity\Brand'
        ]);

        $models = array();

        if ($brand) {
            $repoModels = $this->em->getRepository('SiteBundle:Model');

            $models = $repoModels->createQueryBuilder("q")
                ->where("q.brand = :brandid")
                ->setParameter("brandid", $brand->getId())
                ->getQuery()
                ->getResult();
        }

        $form->add('model', EntityType::class, [
            'required' => true,
            'placeholder' => "Please select model",
            'class' => 'SiteBundle\Entity\Model',
            'choices' => $models,
        ]);
    }

    function onPreSubmit(FormEvent $event) {
        $form = $event->getForm();
        $data = $event->getData();

        $brand = $this->em->getRepository('SiteBundle:Brand')->find($data['brand']);

        $this->addElements($form, $brand);
    }

    function onPreSetData(FormEvent $event) {
        $shoe = $event->getData();
        $form = $event->getForm();

        $brand = $shoe->getBrand() ? $shoe->getBrand() : null;

        $this->addElements($form, $brand);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SiteBundle\Entity\Shoe'
        ));
    }
}
