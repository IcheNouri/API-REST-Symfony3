<?php
/**
 * Created by PhpStorm.
 * User: Nouri
 * Date: 08/05/2017
 * Time: 13:30
 */

namespace AppBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("firstname");
        $builder->add("lastname");
        $builder->add("plainPassword");
        $builder->add("email", EmailType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => 'AppBundle\Entity\Users',
            'csrf_protection' => false
        ]);
    }
}