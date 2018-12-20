<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity(repositoryClass="App\Repository\LoansRepository")
 * @ORM\Table(name="loans")
 */
class Loan
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
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private $monthly_fee;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private $discount;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer", inversedBy="loans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\InstallmentPeriod", inversedBy="loans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $installment_period;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Installment", mappedBy="loan", cascade={"remove"})
     */
    private $installments;

    /**
     * Loan constructor.
     */
    public function __construct()
    {
        $this->installments = new ArrayCollection();
    }

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
    public function getBorrowedValue()
    {
        return $this->borrowed_value;
    }

    /**
     * @param mixed $borrowed_value
     * @return Loan
     */
    public function setBorrowedValue($borrowed_value)
    {
        $this->borrowed_value = $borrowed_value;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTotalInstallments()
    {
        return $this->total_installments;
    }

    /**
     * @param mixed $total_installments
     * @return Loan
     */
    public function setTotalInstallments($total_installments)
    {
        $this->total_installments = $total_installments;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMonthlyFee()
    {
        return $this->monthly_fee;
    }

    /**
     * @param mixed $monthly_fee
     * @return Loan
     */
    public function setMonthlyFee($monthly_fee)
    {
        $this->monthly_fee = $monthly_fee;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @param mixed $discount
     * @return Loan
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param mixed $comments
     * @return Loan
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param mixed $customer
     * @return Loan
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstallmentPeriod()
    {
        return $this->installment_period;
    }

    /**
     * @param mixed $installment_period
     * @return Loan
     */
    public function setInstallmentPeriod($installment_period)
    {
        $this->installment_period = $installment_period;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstallments()
    {
        return $this->installments;
    }

    /**
     * @param mixed $installments
     * @return Loan
     */
    public function setInstallments($installments)
    {
        $this->installments = $installments;
        return $this;
    }
}
