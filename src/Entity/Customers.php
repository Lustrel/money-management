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
     * @ORM\Column(type="string", columnDefinition="CHAR(12)")
     */
    private $phone;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="customers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

     /**
     * @ORM\OneToMany(targetEntity="App\Entity\Loans", mappedBy="customer")
     */
    private $loans;

    public function getId(): ?int
    {
        return $this->id;
    }
}

