<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhonesRepository")
 */
class Phones
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", columnDefinition="CHAR(2)")
     */
    private $ddd;

    /**
     * @ORM\Column(type="string", columnDefinition="CHAR(9)")
     */
    private $number;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Customers", mappedBy="phone")
     */
    private $customers;




    
    public function __construct()
    {
        $this->customers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
