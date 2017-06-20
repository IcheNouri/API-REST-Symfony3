<?php
/**
 * Created by PhpStorm.
 * User: Nouri
 * Date: 28/05/2017
 * Time: 22:31
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PriceType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("type");
        $builder->add("value");

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Price',
            'csrf_protection' => false
        ]);
    }
}