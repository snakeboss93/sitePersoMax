<?php

namespace AppBundle\Form\Common;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Ivory\CKEditorBundle\Model\ConfigManagerInterface;
use Ivory\CKEditorBundle\Model\PluginManagerInterface;
use Ivory\CKEditorBundle\Model\StylesSetManagerInterface;
use Ivory\CKEditorBundle\Model\TemplateManagerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Champs pour le widget WysiwygBasic (texte riche).
 */
class WysiwygBasicType extends CKEditorType
{
    /**
     * WysiwygBasicType constructor.
     *
     * @param ConfigManagerInterface    $configManager
     * @param PluginManagerInterface    $pluginManager
     * @param StylesSetManagerInterface $stylesSetManager
     * @param TemplateManagerInterface  $templateManager
     */
    public function __construct(
        ConfigManagerInterface $configManager,
        PluginManagerInterface $pluginManager,
        StylesSetManagerInterface $stylesSetManager,
        TemplateManagerInterface $templateManager
    ) {
        parent::__construct($configManager, $pluginManager, $stylesSetManager, $templateManager);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(['config_name' => 'config_basic']);
    }
}
