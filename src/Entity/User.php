<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Hateoas\Configuration\Annotation as Hateoas;


/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * 
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "show_user_details",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups = {"list","detail"})
 * )
  * @Hateoas\Relation(
 *      "read_all",
 *      href = @Hateoas\Route(
 *          "show_users_list",
 *          absolute = true
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups = {"list","detail"})
 * )
 * @Hateoas\Relation(
 *      "create",
 *      href = @Hateoas\Route(
 *          "create_user",
 *          absolute = true
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups = {"list","detail"})
 * )
 * @Hateoas\Relation(
 *      "delete",
 *      href = @Hateoas\Route(
 *          "delete_user",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups = {"list","detail"})
 * )
 * 
 * @Hateoas\Relation(
 *     "phone_choice",
 *     embedded = @Hateoas\Embedded("expr(object.getPhoneChoice())"),
 *     exclusion = @Hateoas\Exclusion(groups = {"list","detail","private"})
 * )
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * 
     * @Serializer\Since("1.0")
     * @Serializer\Groups({ "list"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=55)
     * 
     * @Assert\NotBlank
     * 
     * @JMS\Serializer\Annotation\Type("string")
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"list", "detail"})
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=55)
     * 
     * @Assert\NotBlank
     * 
     * @JMS\Serializer\Annotation\Type("string")
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"list", "detail"})
     */
    private $lastName;

    /**
     * @ORM\Column(type="integer")
     * 
     * @Assert\NotBlank
     * @Assert\Regex("/^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/")
     * 
     * @JMS\Serializer\Annotation\Type("string")
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"detail"})
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank
     * 
     * @JMS\Serializer\Annotation\Type("string")
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"detail"})
     */
    private $address;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="user", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MobilePhone", mappedBy="user", cascade={"persist"})
     * 
     * @Serializer\Since("1.0")
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
