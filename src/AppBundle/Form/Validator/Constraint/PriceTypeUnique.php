<?php

/**
 * Created by PhpStorm.
 * User: Nouri
 * Date: 18/06/2017
 * Time: 21:49
 */

namespace AppBundle\Form\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

class PriceTypeUnique extends Constraint
{

    public $message = "A place cannot contain prices with same type";
}