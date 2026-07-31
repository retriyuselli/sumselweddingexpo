<?php

namespace Tests\Unit;

use App\Support\HtmlSanitizer;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HtmlSanitizerTest extends TestCase
{
    #[Test]
    public function it_strips_script_tags_and_event_handlers(): void
    {
        $dirty = '<p onclick="alert(1)">Hi<script>evil()</script><a href="javascript:alert(1)">x</a></p>';
        $clean = HtmlSanitizer::clean($dirty);

        $this->assertStringNotContainsString('<script', (string) $clean);
        $this->assertStringNotContainsString('onclick', (string) $clean);
        $this->assertStringNotContainsString('javascript:', (string) $clean);
        $this->assertStringContainsString('<p>', (string) $clean);
        $this->assertStringContainsString('Hi', (string) $clean);
    }

    #[Test]
    public function it_allows_basic_formatting(): void
    {
        $html = '<p><strong>Bold</strong> and <em>italic</em></p>';
        $this->assertSame($html, HtmlSanitizer::clean($html));
    }
}
