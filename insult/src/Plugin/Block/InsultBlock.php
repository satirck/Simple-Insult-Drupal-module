<?php

declare(strict_types=1);

namespace Drupal\insult\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\insult\API\InsultAPIClient;

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
  const CACHE_ID = 'insult_block:insult';

  /**
   * @inheritDoc
   */
  public function build(): array
  {
    $insult = $this->getInsult();
    return [
      '#markup' => $insult,
    ];
  }

  private function getCachedData(): ?string
  {
    $cache = \Drupal::cache()->get(self::CACHE_ID);

    if ($cache) {
      return $cache->data;
    }

    return null;
  }

  private function saveInsultCache(string $insult): ?string
  {
    $cache_id = self::CACHE_ID;
    \Drupal::cache()->set($cache_id, $insult, time() + 900);

    return $this->getCachedData();
  }

  private function getAPIInsult(): string
  {
    return InsultAPIClient::getInsult();
  }

  private function getInsult(): string
  {
    $data = $this->getCachedData();

    return $data ?? $this->saveInsultCache(
      $this->getAPIInsult()
    );
  }
}
