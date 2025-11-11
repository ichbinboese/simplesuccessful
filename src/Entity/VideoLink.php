<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

#[ORM\Entity]
class VideoLink
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Video::class)]
    #[ORM\JoinColumn(nullable:false, onDelete:'CASCADE')]
    private Video $video;

    #[ORM\Column(type:'string', length:64, unique:true)]
    private string $token;

    #[ORM\Column(type:'datetime_immutable', nullable:true)]
    private ?\DateTimeImmutable $expiresAt = null;

    #[ORM\Column(type:'string', length:255, nullable:true)]
    private ?string $recipientEmail = null;

    #[ORM\Column(type:'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->token = bin2hex(random_bytes(16));
    }

    public function getId(): ?int { return $this->id; }
    public function getVideo(): Video { return $this->video; }
    public function setVideo(Video $v): self { $this->video = $v; return $this; }

    public function getToken(): string { return $this->token; }
    public function getExpiresAt(): ?\DateTimeImmutable { return $this->expiresAt; }
    public function setExpiresAt(?\DateTimeImmutable $t): self { $this->expiresAt = $t; return $this; }

    public function getRecipientEmail(): ?string { return $this->recipientEmail; }
    public function setRecipientEmail(?string $e): self { $this->recipientEmail = $e; return $this; }

    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function isValid(): bool {
        return $this->expiresAt === null || $this->expiresAt > new DateTimeImmutable();
    }
}
