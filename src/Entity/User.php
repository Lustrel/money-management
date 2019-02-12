<?php

namespace App\Entity;

use App\Entity\Role as RoleEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="users")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=50, unique=true, nullable=false)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Role", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $role;

     /**
     * @ORM\OneToMany(targetEntity="App\Entity\Customer", mappedBy="user")
     */
    private $customers;


    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }

    public function getCustomers()
    {
        return $this->customers;
    }

    public function setCustomers($customers)
    {
        $this->customers = $customers;
        return $this;
    }

    public function isAdministrator()
    {
        return ($this->role->getId() == RoleEntity::$ID_ADMIN);
    }

    public function isManager()
    {
        return ($this->role->getId() == RoleEntity::$ID_MANAGER);
    }

    public function isSeller()
    {
        return ($this->role->getId() == RoleEntity::$ID_SELLER);
    }

    /**
     * UserInterface methods
     */

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        if ($this->isAdministrator()) {
            return ['ROLE_ADMIN'];
        }

        return ['ROLE_USER'];
    }

    /**
     * @return mixed
     */
    public function getSalt(){}

    public function eraseCredentials(){}
}
