<?php
namespace App\Message;

class PostMessage
{
    private string $content;
    private ?int $commissionId; // Null pour les posts globaux
    private int $authorId;

    public function __construct(string $content, ?int $commissionId, int $authorId)
    {
        $this->content = $content;
        $this->commissionId = $commissionId;
        $this->authorId = $authorId;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getCommissionId(): ?int
    {
        return $this->commissionId;
    }

    public function getAuthorId(): int
    {
        return $this->authorId;
    }
}
