<?php

namespace App\Tests\Entity;

use App\Entity\VideoLink;
use PHPUnit\Framework\TestCase;

class VideoLinkTest extends TestCase
{
    public function testStartCountdownSetsExpiry(): void
    {
        $link = new VideoLink();
        $now = new \DateTimeImmutable('2025-01-01 12:00:00');

        $updated = $link->startCountdown($now, 24);

        $this->assertTrue($updated);
        $this->assertSame('2025-01-02 12:00:00', $link->getExpiresAt()?->format('Y-m-d H:i:s'));
    }
}
