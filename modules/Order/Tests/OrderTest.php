<?php


namespace Modules\Order\Tests;

use Tests\TestCase;
use Modules\Order\Models\Order;

class OrderTest extends TestCase
{
    public function testOrderCreation()
    {
        // Create a new order instance
        $order = new Order();  
        
        // Set order properties
        $order->customer_name = 'John Doe';
        $order->customer_email = 'oE9lD@example.com';        
        $order->save(); // Save the order to the database
        
        // Assert that the order was created successfully        
        $this->assertDatabaseHas('orders', [
            'customer_name' => 'John Doe',
            'customer_email' => 'oE9lD@example.com',    
        ]);
     }
    }        