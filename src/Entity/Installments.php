<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InstallmentsRepository")
 */
class Installments
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $due_date;

    /**
     * @ORM\Column(type="integer")
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Loans", inversedBy="installments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $loan;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\InstallmentStatus", inversedBy="installments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;
    
   

    

    public function getId(): ?int
    {
        return $this->id;
    }
}
