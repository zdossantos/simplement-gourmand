<?php

return [

    /*
    |--------------------------------------------------------------------------
    | OpenFoodFacts Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration pour l'API OpenFoodFacts
    | Voir: https://github.com/openfoodfacts/openfoodfacts-php
    |
    */

    'default_database' => env('OPENFOODFACTS_DATABASE', 'food'),
    'default_country' => env('OPENFOODFACTS_COUNTRY', 'fr'),

    /*
    |--------------------------------------------------------------------------
    | Bases de données disponibles
    |--------------------------------------------------------------------------
    |
    | Les bases de données supportées par OpenFoodFacts:
    | - food: Produits alimentaires
    | - beauty: Produits cosmétiques  
    | - pet: Produits pour animaux
    |
    */
    'databases' => [
        'food' => 'food',
        'beauty' => 'beauty', 
        'pet' => 'pet',
    ],

    /*
    |--------------------------------------------------------------------------
    | Configurations par pays/langue
    |--------------------------------------------------------------------------
    |
    | Configurations spécifiques par pays pour différentes API
    |
    */
    'countries' => [
        'world' => 'world',
        'france' => 'fr'
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration du cache pour les requêtes API
    |
    */
    'cache' => [
        'enabled' => env('OPENFOODFACTS_CACHE_ENABLED', true),
        'ttl' => env('OPENFOODFACTS_CACHE_TTL', 3600), // 1 heure
    ],

    /*
    |--------------------------------------------------------------------------
    | HTTP Client Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration du client HTTP Guzzle
    |
    */
    'http' => [
        'timeout' => env('OPENFOODFACTS_TIMEOUT', 30),
        'connect_timeout' => env('OPENFOODFACTS_CONNECT_TIMEOUT', 10),
        'user_agent' => env('OPENFOODFACTS_USER_AGENT', 'Laravel-OpenFoodFacts/1.0'),
    ],

]; 