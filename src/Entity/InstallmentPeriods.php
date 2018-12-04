<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InstallmentPeriodsRepository")
 */
class InstallmentPeriods
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
     * @ORM\OneToMany(targetEntity="App\Entity\Loans", mappedBy="installment_period")
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
