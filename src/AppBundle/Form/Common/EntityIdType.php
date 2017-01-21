<?php

namespace AppBundle\Form\Common;

use AppBundle\Form\Transformer\EntityIdTypeTransformer;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class EntityIdType.
 */
class EntityIdType extends AbstractType
{
    /**
     * @var ObjectManager
     */
    protected $manager;

    /**
     * EntityIdType constructor.
     *
     * @param ObjectManager $manager
     */
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new EntityIdTypeTransformer($this->manager, $options['data_type']);
        $builder->addModelTransformer($transformer);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefault('data_type', \stdClass::class);
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return HiddenType::class;
    }
}
