<?php

namespace App\Entity;

use App\Entity\Fournisseur;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Entity\File;
use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
#[Vich\Uploadable]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $rubriqueart = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $sousrubriqueart = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le nom du produit ne peut pas être vide')]
    private ?string $libcourt = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $liblong = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $reffou = null;

    
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null; 

    #[Vich\UploadableField(mapping: 'produit', fileNameProperty: 'photo')] 
    private ?File $photoFile = null;

    #[ORM\Column]
    private ?int $prixachat = null;
    

    #[ORM\ManyToOne(inversedBy: 'Produits')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Fournisseur $fournisseur = null;

    #[ORM\ManyToOne(inversedBy: 'Produits')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Categorie $categorie = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\OneToMany(mappedBy: 'produits', targetEntity: RecapDetails::class)]
    private Collection $recap_prod;

    public function __construct()
    {
        $this->recap_prod = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }
    

    public function getRubriqueart(): ?string
    {
        return $this->rubriqueart;
    }

    public function setRubriqueart(string $rubriqueart): self
    {
        $this->rubriqueart = $rubriqueart;

        return $this;
    }

    public function getSousrubriqueart(): ?string
    {
        return $this->sousrubriqueart;
    }

    public function setSousrubriqueart(string $sousrubriqueart): self
    {
        $this->sousrubriqueart = $sousrubriqueart;

        return $this;
    }

    public function getLibcourt(): ?string
    {
        return $this->libcourt;
    }

    public function setLibcourt(string $libcourt): self
    {
        $this->libcourt = $libcourt;

        return $this;
    }

    public function getLiblong(): ?string
    {
        return $this->liblong;
    }

    public function setLiblong(string $liblong): self
    {
        $this->liblong = $liblong;

        return $this;
    }

    public function getReffou(): ?string
    {
        return $this->reffou;
    }

    public function setReffou(string $reffou): self
    {
        $this->reffou = $reffou;

        return $this;
    }

    

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getPhotoFile(): ?File
    {
        return $this->photoFile;
    }

    public function setPhotoFile(?File $photoFile): void
    {
        $this->photoFile = $photoFile;

    }

    

    public function getFournisseur(): ?Fournisseur
    {
        return $this->fournisseur;
    }

    public function setFournisseur(?Fournisseur $fournisseur): self
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }



    public function getPrixAchat(): ?int
    {
        return $this->prixachat;
    }

    public function setPrixAchat(int $prixachat): static
    {
        $this->prixachat = $prixachat;

        return $this;
    }


    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * @return Collection<int, RecapDetails>
     */
    public function getRecapProd(): Collection
    {
        return $this->recap_prod;
    }

    public function addRecapProd(RecapDetails $recapProd): static
    {
        if (!$this->recap_prod->contains($recapProd)) {
            $this->recap_prod->add($recapProd);
            $recapProd->setProduits($this);
        }

        return $this;
    }

    public function removeRecapProd(RecapDetails $recapProd): static
    {
        if ($this->recap_prod->removeElement($recapProd)) {
            // set the owning side to null (unless already changed)
            if ($recapProd->getProduits() === $this) {
                $recapProd->setProduits(null);
            }
        }

        return $this;
    }

    
}
