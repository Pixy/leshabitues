<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ShopsModelTest extends TestCase
{
    use DatabaseTransactions, DatabaseMigrations;

    public function testAShopHasAHash()
    {
        $shop = factory('App\Shop')->create();

        $this->assertNotEmpty($shop->fresh()->hash);
    }

    public function testShopNeedsToUpdate()
    {
        $shop = factory('App\Shop')->create();

        $firstHash = $shop->hash;
        $shopAttributes = $shop->attributesToArray();

        $shopAttributes['name'] = 'Updated Name';
        $newHash = computeHash($shopAttributes);

        $this->assertNotEquals($firstHash, $newHash);
    }

    public function testShopNotNeedsToUpdate()
    {
        $shop = factory('App\Shop')->create();

        $firstHash = $shop->hash;
        $shopAttributes = $shop->attributesToArray();

        // Returned value of json was not modified since the last creation
        $newHash = computeHash($shopAttributes);

        $this->assertEquals($firstHash, $newHash);
    }
}
