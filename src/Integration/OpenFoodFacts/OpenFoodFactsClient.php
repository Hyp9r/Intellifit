<?php

declare(strict_types=1);

namespace App\Integration\OpenFoodFacts;

use OpenFoodFacts\Api;
use OpenFoodFacts\Document\FoodDocument;
use OpenFoodFacts\Exception\BadRequestException;
use OpenFoodFacts\Exception\ProductNotFoundException;
use Psr\SimpleCache\InvalidArgumentException;

class OpenFoodFactsClient
{
    private Api $api;

    public function __construct()
    {
        $this->api = new Api('food', 'world');
    }

    /**
     * @throws BadRequestException
     * @throws ProductNotFoundException
     * @throws InvalidArgumentException
     */
    public function getFood(string $barcode): FoodDocument
    {
        return $this->api->getProduct('7613032827014');
    }
}