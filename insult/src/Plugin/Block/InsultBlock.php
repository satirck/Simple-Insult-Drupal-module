<?php

declare(strict_types=1);

namespace Drupal\insult\Plugin\Block;

use Drupal;
use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Provides a block that generates an insult,
 */
#[Block(
    id: "insult_block",
    admin_label: new TranslatableMarkup('Insult block'),
    category: new TranslatableMarkup('Insult block')
)]
class InsultBlock extends BlockBase
{

  /**
   * @inheritDoc
   */
  public function build(): array
  {
    $build['#cache']['max-age'] = 60 * 15;
    $build['#markup'] =  $this->getAPIInsult();

    return $build;
  }

  private function getAPIInsult(): string
  {
    /**
     * @var \Drupal\insult\API\InsultAPIClient $apiClient
     */
    $apiClient = Drupal::service('insult.api_client');

    return $apiClient->getInsult();
  }

}
