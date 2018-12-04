<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InstallmentStatusRepository")
 */
class InstallmentStatus
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
     * @ORM\OneToMany(targetEntity="App\Entity\Installments", mappedBy="status")
     */
    private $installments;




    
    public function __construct()
    {
        $this->installments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
