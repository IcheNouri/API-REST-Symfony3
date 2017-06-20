<?php
/**
 * Created by PhpStorm.
 * User: Nouri
 * Date: 04/06/2017
 * Time: 17:16
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class AuthToken
 * @package AppBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(name="auth_token ",
 *  uniqueConstraints={@ORM\UniqueConstraint(name="token_value_unique", columns={"value"})}
 * )
 */
class AuthToken
{
    /**
     * @ORM\id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $value;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $createdDate;

    /**
     * @ORM\ManyToOne(targetEntity="Users")
     */
    protected $user;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return AuthToken
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return AuthToken
     */
    public function setCreatedDate(\DateTime $createdDate)
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * Get createdDate
     *
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\Users $user
     *
     * @return AuthToken
     */
    public function setUser(\AppBundle\Entity\Users $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\Users
     */
    public function getUser()
    {
        return $this->user;
    }
}
