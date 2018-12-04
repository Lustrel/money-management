<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LoansRepository")
 */
class Loans
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $borrowed_value;

    /**
     * @ORM\Column(type="integer")
     */
    private $total_installments;

    /**
     * @ORM\Column(type="decimal", precision=3, scale=2)
     */
    private $monthly_fee;

    /**
     * @ORM\Column(type="decimal", precision=3, scale=2)
     */
    private $discount;

    /**
     * @ORM\Column(type="text")
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Customers", inversedBy="loans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\InstallmentPeriods", inversedBy="loans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $installment_period;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Installments", mappedBy="loan")
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
