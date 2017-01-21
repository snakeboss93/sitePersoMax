<?php

namespace AppBundle\Form\Common;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * widget de selection (select2).
 */
class SelectXhrType extends AbstractCommonType
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Les propriétés suivantes doivent être ajoutées à toute classe étendant celle-ci :
     * - placeholder
     * - label
     * - class
     * - remote_route
     * - transformer
     * - attr.
     *
     * Il faut obligatoirement fournir un DataTransformer.
     *
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     *
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options.
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'primary_key' => 'id',
                'text_property' => 'id',
                'required' => true,
                'multiple' => true,
                'choices_as_values' => true,
                'compound' => false,
                'expanded' => false,
            ]
        );
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @throws \Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // add custom data transformer
        if ($options['transformer']) {
            if (!is_string($options['transformer'])) {
                throw new \Exception('The option transformer must be a string');
            }
            if (!class_exists($options['transformer'])) {
                throw new \Exception('Unable to load class: '.$options['transformer']);
            }
            $class = $options['transformer'];
            $transformer = new $class($this->em, $options['class']);

            if (!$transformer instanceof DataTransformerInterface) {
                throw new \Exception(
                    sprintf(
                        'The custom transformer %s must implement "Symfony\Component\Form\DataTransformerInterface"',
                        get_class($transformer)
                    )
                );
            }
            // add the default data transformer
        } else {
            $transformerClass = $options['transformer'];
            $transformer = new $transformerClass(
                $this->em,
                $options['class'],
                $options['text_property'],
                $options['primary_key']
            );
        }

        $builder->addViewTransformer($transformer, true);
    }

    /**
     * @param FormView      $view
     * @param FormInterface $form
     * @param array         $options
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        parent::finishView($view, $form, $options);
        // make variables available to the view

        $varNames = [
            'multiple',
            'primary_key',
        ];
        foreach ($varNames as $varName) {
            $view->vars[$varName] = $options[$varName];
        }

        if ($options['multiple']) {
            $view->vars['full_name'] .= '[]';
        }
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'select_xhr';
    }
}
