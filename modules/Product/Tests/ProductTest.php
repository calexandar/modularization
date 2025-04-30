<?php

namespace Modules\Product\Tests;

use Database\Factories\ProductFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use DatabaseMigrations; 
    public function testProductCreation()
    {
        // Create a new product instance
        $product = ProductFactory::new()->create(); 
        dd($product); 

        $product->name = 'Test Product';
        $product->price = 99.99;
        
        // Save the product to the database
        $product->save();
        
        // Assert that the product was created successfully
        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'price' => 99.99,
        ]);
    }
}