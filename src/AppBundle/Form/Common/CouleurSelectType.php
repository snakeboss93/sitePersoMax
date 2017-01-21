<?php

namespace AppBundle\Form\Common;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Champs de la sélection de la couleur.
 *
 * Les couleurs sont définies dans le Sass app/Resources/scss/bootstrap/_variables.scss
 *
 * $mousse:                #6aa84f !default; // Vert "mousse"
 * $avocat:                #568203 !default; // Vert "avocat"
 * $emeraude:              #01D758 !default; // Vert "emeraude"
 * $olive:                 #708D23 !default; // Vert "olive"
 * $vertdegris:            #95A595 !default; // Vert de gris
 * $mais:                  #ffd966 !default; // Jaune "maïs"
 * $souffre:               #FFFF6B !default; // Jaune "souffre"
 * $mandarine:             #f6b26b !default; // Orange "mandarine"
 * $vanille                #E1CE9A !default; // Vanille
 * $tabac:                 #9F551E !default; // Tabac
 * $fauve:                 #b45f06 !default; // Marron "fauve"
 * $roux:                  #AD4F09 !default; // Roux
 * $coquelicot:            #cc0000 !default; // Rouge "coquelicot"
 * $vermeil:               #FF0921 !default; // Vermeil
 * $dodonee:               #c27ba0 !default; // Mauve "dodonee visqueuse"
 * $zinzolin:              #6C0277 !default; // Zinzolin
 * $prune:                 #DDA0DD !default; // Prune
 * $indigo:                #791CF8 !default; // Indigo
 * $royal:                 #3c78d8 !default; // Bleu "royal"
 * $nuit:                  #0b5394 !default; // Bleu "nuit"
 * $paon:                  #45818e !default; // Bleu "paon"
 * $turquoise:             #25FDE9 !default; // Turquoise
 * $bisque:                #fce5cd !default; // Orange "bisque"
 * $acier:                 #b7b7b7 !default; // Gris "acier"
 * $caramel:               #783f04 !default; // Marron "caramel"
 */
class CouleurSelectType extends ChoiceType
{
    /**
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $label = [
            'mousse' => '#6aa84f',
            'avocat' => '#568203',
            'emeraude' => '#01D758',
            'olive' => '#708D23',
            'vertdegris' => '#95A595',
            'mais' => '#ffd966',
            'souffre' => '#FFFF6B',
            'mandarine' => '#f6b26b',
            'vanille' => '#E1CE9A',
            'tabac' => '#9F551E',
            'fauve' => '#b45f06',
            'roux' => '#AD4F09',
            'coquelicot' => '#cc0000',
            'vermeil' => '#FF0921',
            'dodonee' => '#c27ba0',
            'zinzolin' => '#6C0277',
            'prune' => '#DDA0DD',
            'indigo' => '#791CF8',
            'royal' => '#3c78d8',
            'nuit' => '#0b5394',
            'paon' => '#45818e',
            'turquoise' => '#25FDE9',
            'bisque' => '#fce5cd',
            'acier' => '#b7b7b7',
            'caramel' => '#783f04',
        ];

        $resolver->setDefaults(
            [
                'label' => 'appbundle.agence.form.couleur.label',
                'required' => true,
                'choices' => [
                    'Vert "mousse"' => 'mousse',
                    'Vert "avocat"' => 'avocat',
                    'Vert "emeraude"' => 'emeraude',
                    'Vert "olive"' => 'olive',
                    'Vert de gris' => 'vertdegris',
                    'Jaune "maïs"' => 'mais',
                    'Jaune "souffre"' => 'souffre',
                    'Orange "mandarine"' => 'mandarine',
                    'Vanille' => 'vanille',
                    'Tabac' => 'tabac',
                    'Marron "fauve"' => 'fauve',
                    'Roux' => 'roux',
                    'Rouge "coquelicot"' => 'coquelicot',
                    'Vermeil' => 'vermeil',
                    'Mauve "dodonee visqueuse"' => 'dodonee',
                    'Zinzolin' => 'zinzolin',
                    'Prune' => 'prune',
                    'Indigo' => 'indigo',
                    'Bleu "royal"' => 'royal',
                    'Bleu "nuit"' => 'nuit',
                    'Bleu "paon"' => 'paon',
                    'Turquoise' => 'turquoise',
                    'Orange "bisque"' => 'bisque',
                    'Gris "acier"' => 'acier',
                    'Marron "caramel"' => 'caramel',
                ],
                'choices_as_values' => true,
                'choice_name' => function ($allChoices, $currentChoiceKey) {
                    return strtolower($currentChoiceKey);
                },
                'choice_attr' => function ($value, $key, $index) use ($label) {
                    return ['data-value' => $label[$value]];
                },
            ]
        );
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'couleur_select';
    }
}
