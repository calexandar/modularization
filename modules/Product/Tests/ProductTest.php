<?php

namespace Modules\Product\Tests;

use Tests\TestCase;
use Modules\Product\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProductTest extends TestCase
{
    use DatabaseMigrations; 
    public function testProductCreation()
    {
        // Create a new product instance
        $product = Product::factory()->create(); 
 

        $product->name = 'Test Product';
        $product->price_in_cents = 99.99;
        
        // Save the product to the database
        $product->save();
        
        // Assert that the product was created successfully
        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'price_in_cents' => 99.99,
        ]);
    }
}