<?php

declare(strict_types=1);

namespace Drupal\insult\API;

use GuzzleHttp\Client;

/**
 * Provides method for getting insult from WEB API
 */
class InsultAPIClient {

  const API_PATH = 'https://evilinsult.com/generate_insult.php?lang=en&type=json';

  protected Client $httpClient;

  /**
   * @param \GuzzleHttp\Client $client
   * Http Client for sending requests
   */
  public function __construct(Client $client) {
    $this->httpClient = $client;
  }

  /**
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getInsult(): string {
    $response = $this->httpClient->get(self::API_PATH);
    $jsonData = json_decode($response->getBody()->getContents(), TRUE);
    
    return $jsonData['insult'] ?? 'None';
  }

}