<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AdresseRepository;

#[ORM\Entity(repositoryClass: AdresseRepository::class)]
class Adresse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'adresses')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Users $users = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $Adrprenom = null;

    #[ORM\Column(length: 255)]
    private ?string $Adrnom = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    private ?string $Adrcodepostal = null;

    #[ORM\Column(length: 255)]
    private ?string $Adrville = null;

    #[ORM\Column(length: 255)]
    private ?string $Adrtelephone = null;

    #[ORM\Column(length: 255)]
    private ?string $Adrpays = null;

    #[ORM\OneToMany(mappedBy: 'com_adr_livr', targetEntity: Commande::class)]
    private Collection $commandes;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Adremail = null;

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
    }



    public function __toString(): string
    {
        return $this->titre . '[-br]' . 
            $this->adresse . '-' . 
            $this->Adrville . '-' . 
            $this->Adrpays;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): static
    {
        $this->users = $users;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getAdrPrenom(): ?string
    {
        return $this->Adrprenom;
    }

    public function setAdrPrenom(string $Adrprenom): static
    {
        $this->Adrprenom = $Adrprenom;

        return $this;
    }

    public function getAdrNom(): ?string
    {
        return $this->Adrnom;
    }

    public function setAdrNom(string $Adrnom): static
    {
        $this->Adrnom = $Adrnom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getAdrCodepostal(): ?string
    {
        return $this->Adrcodepostal;
    }

    public function setAdrCodepostal(string $Adrcodepostal): static
    {
        $this->Adrcodepostal = $Adrcodepostal;

        return $this;
    }

    public function getAdrVille(): ?string
    {
        return $this->Adrville;
    }

    public function setAdrVille(string $Adrville): static
    {
        $this->Adrville = $Adrville;

        return $this;
    }

    public function getAdrTelephone(): ?string
    {
        return $this->Adrtelephone;
    }

    public function setAdrTelephone(string $Adrtelephone): static
    {
        $this->Adrtelephone = $Adrtelephone;

        return $this;
    }

    public function getAdrPays(): ?string
    {
        return $this->Adrpays;
    }

    public function setAdrPays(string $Adrpays): static
    {
        $this->Adrpays = $Adrpays;

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): static
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
            $commande->setComAdrLivr($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getComAdrLivr() === $this) {
                $commande->setComAdrLivr(null);
            }
        }

        return $this;
    }

    public function getAdrEmail(): ?string
    {
        return $this->Adremail;
    }

    public function setAdrEmail(string $Adremail): static
    {
        $this->Adremail = $Adremail;

        return $this;
    }

    
    


}