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
    $build['#cache']['max-age'] = 60 * 15;
    $build['#markup'] = implode('<br>', $this->getAPIInsults());

    return $build;
  }

  private function getAPIInsults(): array
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

}
