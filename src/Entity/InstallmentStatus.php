<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InstallmentStatusRepository")
 */
class InstallmentStatus
{
    static $ID_TO_RECEIVE = 'to_receive';
    static $ID_IN_ARREARS = 'in_arrears';
    static $ID_PAID = 'paid';

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
     * @ORM\OneToMany(targetEntity="App\Entity\Installment", mappedBy="status")
     */
    private $installments;


    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getInstallments()
    {
        return $this->installments;
    }

    public function setInstallments($installments)
    {
        $this->installments = $installments;
        return $this;
    }

    public function isPaid()
    {
        return ($this->id == self::$ID_PAID);
    }

    public function isToReceive()
    {
        return ($this->id == self::$ID_TO_RECEIVE);
    }

    public function isInArrears()
    {
        return ($this->id == self::$ID_IN_ARREARS);
    }
}
