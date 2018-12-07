<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomersRepository")
 */
class Customers
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
     * @ORM\Column(type="string", length=14)
     */
    private $document_number;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", columnDefinition="CHAR(12)")
     */
    private $phone;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="customers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

     /**
     * @ORM\OneToMany(targetEntity="App\Entity\Loans", mappedBy="customer")
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDocumentNumber(): ?string
    {
        return $this->document_number;
    }

    public function setDocumentNumber(string $document_number): self
    {
        $this->document_number = $document_number;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Loans[]
     */
    public function getLoans(): Collection
    {
        return $this->loans;
    }

    public function addLoan(Loans $loan): self
    {
        if (!$this->loans->contains($loan)) {
            $this->loans[] = $loan;
            $loan->setCustomer($this);
        }

        return $this;
    }

    public function removeLoan(Loans $loan): self
    {
        if ($this->loans->contains($loan)) {
            $this->loans->removeElement($loan);
            // set the owning side to null (unless already changed)
            if ($loan->getCustomer() === $this) {
                $loan->setCustomer(null);
            }
        }

        return $this;
    }
}

