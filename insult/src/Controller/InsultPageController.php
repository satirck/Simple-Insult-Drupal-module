<?php

declare(strict_types=1);

namespace Drupal\insult\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\insult\API\InsultAPIClient;

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
    $cache = \Drupal::cache()->get(self::CACHE_ID);

    if ($cache) {
      return $cache->data;
    }

    return null;
  }

  private function saveInsultsCache(array $insult): ?array
  {
    $cache_id = self::CACHE_ID;
    \Drupal::cache()->set($cache_id, $insult, time() + 900);

    return $this->getCachedData();
  }

  private function getAPIInsults(): ?array
  {
    $insults = [];
    for ($i = 0; $i < self::INSULT_AMOUNT; $i++) {
      $insults[] = InsultAPIClient::getInsult();
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
