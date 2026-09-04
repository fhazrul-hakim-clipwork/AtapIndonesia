<?php

namespace Tests\Feature;

use Tests\TestCase;

class CartMultipleProductsTest extends TestCase
{
    public function test_katalog_page_allows_selecting_quantity_for_each_product(): void
    {
        $response = $this->get('/katalog');

        $response->assertStatus(200);
        $response->assertSee('name="quantity"', false);
    }
}
