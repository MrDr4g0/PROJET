<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table (name="im22_user")
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */

class User
{
    /**
     * @ORM\Column(name="id",type="integer"
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="Auto")
     */

    private $id;

    /**
     *
     * @ORM\Column(name="login",type"string",length=100)
     * @Assert\NotBlank(message = "un login est obligatoire")
     * @Assert\Length(max = 20)
     */
    private $login;

    /**
     *
     * @ORM\Column(name="password",type"string",length=100)
     * @Assert\NotBlank(message = "un password est obligatoire")
     * @Assert\Length(max = 20)
     */

    private $password;


    /**
     *
     * @ORM\Column(name="name",type"string",length=100)
     * @Assert\NotBlank(message = "un nom est obligatoire")
     * @Assert\Length(max = 20)
     */
    private $name;


    /**
     *
     * @ORM\Column(name="first_name",type"string",length=100)
     * @Assert\NotBlank(message = "un prénom est obligatoire")
     * @Assert\Length(max = 20)
     */
    private $first_name;


    /**
     *
     * @ORM\Column(name="date",type"date")
     * @Assert\NotBlank(message = "une date est obligatoire")
     */
    private $birth_date;

    /**
     * @ORM\Column(name="is_admin",type="boolean" , options={"default"=false})
     * @Assert\Type(type = "bool", message = "{{ value }} n'est pas un {{ type }}")
     */
    private $is_admin;

    /**
     * @ORM\Column(name="is_super_admin",type="boolean" , options={"default"=false})
     * @Assert\Type(type = "bool", message = "{{ value }} n'est pas un {{ type }}")
     */
    private $is_super_admin;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity=ShoppingCart::class,mappedBy="id_user")
     */

    private $shoppingCarts;

    public function __construct()
    {
        $this->shoppingCarts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
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

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birth_date;
    }

    public function setBirthDate(\DateTimeInterface $birth_date): self
    {
        $this->birth_date = $birth_date;

        return $this;
    }

    public function getIsAdmin(): ?bool
    {
        return $this->is_admin;
    }

    public function setIsAdmin(bool $is_admin): self
    {
        $this->is_admin = $is_admin;

        return $this;
    }

    public function getIsSuperAdmin(): ?bool
    {
        return $this->is_super_admin;
    }

    public function setIsSuperAdmin(bool $is_super_admin): self
    {
        $this->is_super_admin = $is_super_admin;

        return $this;
    }

    /**
     * @return Collection<int, ShoppingCart>
     */
    public function getShoppingCarts(): Collection
    {
        return $this->shoppingCarts;
    }

    public function addShoppingCart(ShoppingCart $shoppingCart): self
    {
        if (!$this->shoppingCarts->contains($shoppingCart)) {
            $this->shoppingCarts[] = $shoppingCart;
            $shoppingCart->setIdUser($this);
        }

        return $this;
    }

    public function removeShoppingCart(ShoppingCart $shoppingCart): self
    {
        if ($this->shoppingCarts->removeElement($shoppingCart)) {
            // set the owning side to null (unless already changed)
            if ($shoppingCart->getIdUser() === $this) {
                $shoppingCart->setIdUser(null);
            }
        }

        return $this;
    }


}
