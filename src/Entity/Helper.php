<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HelperRepository")
 * @ORM\Table(name="helpers")
 */
class Helper
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
    private $last_installment_actualization;

    /**
     * @return mixed
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getLastInstallmentActualization()
    {
        return $this->last_installment_actualization;
    }

    /**
     * @param mixed $last_installment_actualization
     * @return Helper
     */
    public function setLastInstallmentActualization($last_installment_actualization)
    {
        $this->last_installment_actualization = $last_installment_actualization;
        return $this;
    }
}
