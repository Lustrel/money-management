<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RolesRepository")
 */
class Roles
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
     * @ORM\OneToMany(targetEntity="App\Entity\Users", mappedBy="role")
     */
    private $users;



    

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }
}
