<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Symfony\Component\Validator\Constraints as Assert;
use Hateoas\Configuration\Annotation as Hateoas;


/**
 * @ORM\Entity(repositoryClass="App\Repository\MobilePhoneRepository")
 * 
 *  * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "show_product_details",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *      )
 * )
  * @Hateoas\Relation(
 *      "read_all",
 *      href = @Hateoas\Route(
 *          "show_products_list",
 *          absolute = true
 *      )
 * )
 * 
 * @Hateoas\Relation(
 *     "user",
 *     embedded = @Hateoas\Embedded("expr(object.getUser())")
 * )
 */
class MobilePhone
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * 
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"list"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=55)
     * 
     * @Assert\NotBlank
     * 
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"list", "detail"})
     */
    private $model;

    /**
     * @ORM\Column(type="string", length=55)
     * 
     * @Assert\NotBlank
     * 
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"list", "detail"})
     */
    private $brand;

    /**
     * @ORM\Column(type="string", length=55)
     * 
     * @Assert\NotBlank
     * 
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"detail"})
     */
    private $color;

    /**
     * @ORM\Column(type="integer")
     * 
     * @Assert\NotBlank
     * @Assert\Choice({"32", "64", "128", "256"})
     * 
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"detail"})
     */
    private $storage;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2)
     * 
     * @Assert\NotBlank
     * 
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"detail"})
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="phoneChoice")
     * 
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"detail"})
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getStorage(): ?int
    {
        return $this->storage;
    }

    public function setStorage(int $storage): self
    {
        $this->storage = $storage;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

}
