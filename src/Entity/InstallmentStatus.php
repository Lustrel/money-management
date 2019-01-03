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
     * @return InstallmentStatus
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * @return InstallmentStatus
     */
    public function setInstallments($installments)
    {
        $this->installments = $installments;
        return $this;
    }
}
