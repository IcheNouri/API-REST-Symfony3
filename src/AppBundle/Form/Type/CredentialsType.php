<?php
/**
 * Created by PhpStorm.
 * User: Nouri
 * Date: 04/06/2017
 * Time: 17:31
 */

namespace AppBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CredentialsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("login");
        $builder->add("password");
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => 'AppBundle\Entity\Credentials',
            'csrf_protection' => false
        ]);
    }
}