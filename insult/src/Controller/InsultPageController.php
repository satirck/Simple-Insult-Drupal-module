<?php

declare(strict_types=1);

namespace Drupal\insult\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;

/**
 * Provide route responses for the /insult page giving the insults
 */
class InsultPageController extends ControllerBase
{

  const CACHE_ID = 'insult_page:insult';

  const INSULT_AMOUNT = 5;

  public function insultPage(): array
  {
    $insults = $this->getInsults();

    return [
      '#markup' => implode('<br>', $insults),
    ];
  }

  private function getCachedData(): ?array
  {
    $cache = Drupal::cache()->get(self::CACHE_ID);

    if ($cache) {
      return $cache->data;
    }

    return NULL;
  }

  private function saveInsultsCache(array $insult): ?array
  {
    $cacheId = self::CACHE_ID;
    Drupal::cache()->set($cacheId, $insult, time() + 900);

    return $this->getCachedData();
  }

  private function getAPIInsults(): ?array
  {
    /**
     * @var \Drupal\insult\API\InsultAPIClient $apiClient
     */
    $apiClient = Drupal::service('insult.api_client');

    $insults = [];
    for ($i = 0; $i < self::INSULT_AMOUNT; $i++) {
      $insults[] = $apiClient->getInsult();
    }

    return $insults;
  }

  private function getInsults(): array
  {
    $data = $this->getCachedData();

    return $data ?? $this->saveInsultsCache(
        $this->getAPIInsults()
    );
  }

}
