<?php
/**
 * Created by PhpStorm.
 * User: Nouri
 * Date: 18/06/2017
 * Time: 21:50
 */

namespace AppBundle\Form\Validator\Constraint;


use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PriceTypeUniqueValidator extends ConstraintValidator
{

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param \Symfony\Component\Validator\Constraint $constraint The constraint for the validation
     */
    public function validate($prices, Constraint $constraint)
    {
        if (!$prices instanceof ArrayCollection)
            return;

        $pricesType = [];

        foreach ($prices as $price) {
            if (in_array($price->getType(), $pricesType)) {
                $this->context->buildViolation($constraint->message)->addViolation();
                return;
            }
            else
                $pricesType[] = $price->getType();
        }
    }
}