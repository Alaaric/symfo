<?php

namespace App\Entity;

use ApiPlatform\Metadata\Delete;
use App\Repository\ImageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\GetCollection;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(uriTemplate: '/images', name: 'get_all_image'),
        new Get(uriTemplate: '/images/{id}', name: 'get_image'),
        new Get(uriTemplate: '/images/{id}/file', name: 'view_image'),
        new Get(uriTemplate: '/images/download/{id}', name: 'get_image_file'),
        new Post(uriTemplate: '/images/upload', name: 'new_image'),
        new Delete(uriTemplate: '/images/{id}', name: 'delete_image')
    ]
)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['image:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['image:read'])]
    private ?string $file_name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['image:read'])]
    private ?string $name = null;

    /**
     * @var Collection<int, Stat>
     */
    #[ORM\OneToMany(targetEntity: Stat::class, mappedBy: 'image', orphanRemoval: true)]
    private Collection $stats;

    public function __construct()
    {
        $this->stats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileName(): ?string
    {
        return $this->file_name;
    }

    public function setFileName(string $file_name): static
    {
        $this->file_name = $file_name;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Stat>
     */
    public function getStats(): Collection
    {
        return $this->stats;
    }

    public function addStat(Stat $stat): static
    {
        if (!$this->stats->contains($stat)) {
            $this->stats->add($stat);
            $stat->setImage($this);
        }

        return $this;
    }

    public function removeStat(Stat $stat): static
    {
        if ($this->stats->removeElement($stat)) {
            // set the owning side to null (unless already changed)
            if ($stat->getImage() === $this) {
                $stat->setImage(null);
            }
        }

        return $this;
    }
}
