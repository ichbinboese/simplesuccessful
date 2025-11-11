<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

#[ORM\Entity]
class Video
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:'integer')]
    private ?int $id = null;

    #[ORM\Column(type:'string', length:255)]
    private string $title;

    #[ORM\Column(type:'string', length:255)]
    private string $filename; // stored filename on disk

    #[ORM\Column(type:'text', nullable:true)]
    private ?string $description = null;

    #[ORM\Column(type:'datetime_immutable')]
    private \DateTimeImmutable $uploadedAt;

    #[ORM\Column(type:'boolean')]
    private bool $isPublic = true;

    public function __construct()
    {
        $this->uploadedAt = new DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getTitle(): string { return $this->title; }
    public function setTitle(string $title): self { $this->title = $title; return $this; }

    public function getFilename(): string { return $this->filename; }
    public function setFilename(string $filename): self { $this->filename = $filename; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $d): self { $this->description = $d; return $this; }

    public function getUploadedAt(): \DateTimeImmutable { return $this->uploadedAt; }
    public function setUploadedAt(\DateTimeImmutable $t): self { $this->uploadedAt = $t; return $this; }

    public function isPublic(): bool { return $this->isPublic; }
    public function setIsPublic(bool $b): self { $this->isPublic = $b; return $this; }
}
