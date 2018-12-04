<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomersRepository")
 */
class Customers
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
     * @ORM\Column(type="string", length=14)
     */
    private $document_number;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="customers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

     /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Phones", inversedBy="customers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $phone;

     /**
     * @ORM\OneToMany(targetEntity="App\Entity\Loans", mappedBy="customer")
     */
    private $loans;




    
    public function __construct()
    {
        $this->loans = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}

