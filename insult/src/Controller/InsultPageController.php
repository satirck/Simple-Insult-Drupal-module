<?php

declare(strict_types=1);

namespace Drupal\insult\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provide route responses for the /insult page giving the insults
 */
class InsultPageController extends ControllerBase
{

  const INSULT_AMOUNT = 5;

  protected Drupal\insult\API\InsultAPIClient $insultAPIClient;

  /**
   * @param \Drupal\insult\API\InsultAPIClient $insultAPIClient
   */
  public function __construct(Drupal\insult\API\InsultAPIClient $insultAPIClient)
  {
    $this->insultAPIClient = $insultAPIClient;
  }

  public static function create(ContainerInterface $container): InsultPageController|static
  {
    /**
     * @var \Drupal\insult\API\InsultAPIClient $api_client
     */
    $api_client = $container->get('insult.insult_api_client');

    return new static(
        $api_client
    );
  }

  public function insultPage(): array
  {
    $build['#cache']['max-age'] = 60 * 15;
    $build['#markup'] = implode('<br>', $this->getAPIInsults());

    return $build;
  }

  private function getAPIInsults(): array
  {
    $insults = [];
    for ($i = 0; $i < self::INSULT_AMOUNT; $i++) {
      $insults[] = $this->insultAPIClient->getInsult();
    }

    return $insults;
  }

}
