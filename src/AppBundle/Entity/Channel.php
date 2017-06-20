<?php
/**
 * Created by PhpStorm.
 * User: Nouri
 * Date: 05/06/2017
 * Time: 17:44
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Channel
 * @package AppBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(name="Channel",
 *  uniqueConstraints={@ORM\UniqueConstraint(name="unique_channel_name", columns={"name"})}
 * )
 */
class Channel
{
    /**
     * @ORM\id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="string")
     */
    protected $currentInfo;

    /**
     * @ORM\Column(type="string")
     */
    protected $nextInfo;

    /**
     * @ORM\ManyToOne(targetEntity="Users", inversedBy="channels")
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
     * Set name
     *
     * @param string $name
     *
     * @return Channel
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set currentInfo
     *
     * @param string $currentInfo
     *
     * @return Channel
     */
    public function setCurrentInfo($currentInfo)
    {
        $this->currentInfo = $currentInfo;

        return $this;
    }

    /**
     * Get currentInfo
     *
     * @return string
     */
    public function getCurrentInfo()
    {
        return $this->currentInfo;
    }

    /**
     * Set newtInfo
     *
     * @param string $newtInfo
     *
     * @return Channel
     */
    public function setNextInfo($nextInfo)
    {
        $this->nextInfo = $nextInfo;

        return $this;
    }

    /**
     * Get newtInfo
     *
     * @return string
     */
    public function getNextInfo()
    {
        return $this->nextInfo;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\Users $user
     *
     * @return Channel
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
