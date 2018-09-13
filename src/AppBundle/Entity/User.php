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
}