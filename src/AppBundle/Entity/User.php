<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Device", mappedBy="owner")
     */
    private $devices;

    /**
     * @ORM\OneToMany(targetEntity="License", mappedBy="owner")
     */
    private $licenses;

    /**
     * @ORM\OneToMany(targetEntity="Presence", mappedBy="user")
     */
    private $presence;

    public function __construct()
    {
        parent::__construct();
    }

    public function getRole() {
        return $this->getRoles()[0];
    }

    public function setRole($role) {
        $this->setRoles([$role]);
    }

    /**
     * @return mixed
     */
    public function getDevices()
    {
        return $this->devices;
    }

    /**
     * @param mixed $devices
     */
    public function setDevices($devices)
    {
        $this->devices = $devices;
    }

    /**
     * @return mixed
     */
    public function getLicenses()
    {
        return $this->licenses;
    }

    /**
     * @param mixed $licenses
     */
    public function setLicenses($licenses)
    {
        $this->licenses = $licenses;
    }
}