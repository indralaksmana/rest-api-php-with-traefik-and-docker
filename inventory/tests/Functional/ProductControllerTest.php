<?php

class ProductControllerTest extends TestCase
{
    private $products;

    const API_PREFIX = '/api/v1';

    public function setUp()
    {
        parent::setUp();
        $this->products = \App\Models\Product::all();
    }


    public function test_if_it_returns_all_products()
    {
        $this->get(self::API_PREFIX . '/product');

        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals(json_encode($this->products), $this->response->getContent());
    }

    public function test_if_it_returns_the_requested_product()
    {
        $product = 1;
        $this->get(self::API_PREFIX . '/product/' . $product);

        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals(json_encode($this->products[ $product ]), $this->response->getContent());
    }

    public function test_if_it_returns_not_found_response_for_a_non_existed_product()
    {
        $product = 4;
        $this->get(self::API_PREFIX . '/product/' . $product);

        $this->assertEquals(404, $this->response->getStatusCode());
    }
}
