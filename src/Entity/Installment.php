<?php

namespace App\Entity;

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

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getDueDate()
    {
        return $this->due_date;
    }

    /**
     * @param mixed $due_date
     * @return Installment
     */
    public function setDueDate($due_date)
    {
        $this->due_date = $due_date;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return Installment
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLoan()
    {
        return $this->loan;
    }

    /**
     * @param mixed $loan
     * @return Installment
     */
    public function setLoan($loan)
    {
        $this->loan = $loan;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     * @return Installment
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
}
