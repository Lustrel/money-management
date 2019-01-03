<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InstallmentPeriodsRepository")
 * @ORM\Table(name="installment_periods")
 */
class InstallmentPeriod
{
    static $ID_DAILY = 'daily';
    static $ID_WEEKLY = 'weekly';
    static $ID_FORTNIGHTLY = 'fortnightly';
    static $ID_MONTHLY = 'monthly';

    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=25)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Loan", mappedBy="installment_period")
     */
    private $loans;

    /**
     * Construct.
     * @param int $id
     */
    public function __construct($id)
    {
        $this->id = $id;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return InstallmentPeriod
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLoans()
    {
        return $this->loans;
    }

    /**
     * @param mixed $loans
     * @return InstallmentPeriod
     */
    public function setLoans($loans)
    {
        $this->loans = $loans;
        return $this;
    }
}
