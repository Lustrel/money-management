<?php

namespace App\Entity;

use App\Entity\InstallmentStatus;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InstallmentsRepository")
 * @ORM\Table(name="installments")
 */
class Installment
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Loan", inversedBy="installments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $loan;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\InstallmentStatus", inversedBy="installments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;


    public function getId()
    {
        return $this->id;
    }

    public function getDueDate()
    {
        return $this->due_date;
    }

    public function setDueDate($due_date)
    {
        $this->due_date = $due_date;
        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    public function getLoan()
    {
        return $this->loan;
    }

    public function setLoan($loan)
    {
        $this->loan = $loan;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
}
