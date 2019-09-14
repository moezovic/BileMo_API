<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;


/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({ "list"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=55)
     * @Serializer\Groups({"list", "detail"})
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=55)
     * @Serializer\Groups({"list", "detail"})
     */
    private $lastName;

    /**
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"detail"})
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"detail"})
     */
    private $address;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Client", inversedBy="user", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Groups({"restricted"})
     */
    private $client;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MobilePhone", mappedBy="user")
     * @Serializer\Groups({"detail"})
     */
    private $phoneChoice;

    public function __construct()
    {
        $this->phoneChoice = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPhoneNumber(): ?int
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(int $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return Collection|MobilePhone[]
     */
    public function getPhoneChoice(): Collection
    {
        return $this->phoneChoice;
    }

    public function addPhoneChoice(MobilePhone $phoneChoice): self
    {
        if (!$this->phoneChoice->contains($phoneChoice)) {
            $this->phoneChoice[] = $phoneChoice;
            $phoneChoice->setUser($this);
        }

        return $this;
    }

    public function removePhoneChoice(MobilePhone $phoneChoice): self
    {
        if ($this->phoneChoice->contains($phoneChoice)) {
            $this->phoneChoice->removeElement($phoneChoice);
            // set the owning side to null (unless already changed)
            if ($phoneChoice->getUser() === $this) {
                $phoneChoice->setUser(null);
            }
        }

        return $this;
    }
}
