<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoMarkupTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_has_florist_schema_with_street_address(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('"@type": "Florist"', false)
            ->assertSee('"streetAddress": "ул. Академика Сахарова, 5"', false)
            ->assertSee('"openingHours": "Mo-Su 09:00-23:59"', false);
    }

    public function test_keywords_meta_is_not_rendered(): void
    {
        $this->get('/')->assertOk()->assertDontSee('name="keywords"', false);
    }

    public function test_robots_txt_has_single_rule_group_for_search_engines(): void
    {
        $robots = file_get_contents(public_path('robots.txt'));

        $this->assertStringNotContainsString('User-agent: Yandex', $robots);
        $this->assertStringNotContainsString('User-agent: Googlebot', $robots);
        $this->assertStringContainsString('Disallow: /cart', $robots);
        $this->assertStringContainsString('Clean-param:', $robots);
    }
}
