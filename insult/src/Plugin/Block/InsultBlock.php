<?php

declare(strict_types=1);

namespace Drupal\insult\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\insult\Api\InsultAPIClient;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a block that generates an insult,
 */
#[Block(
    id: "insult_block",
    admin_label: new TranslatableMarkup('Insult block'),
    category: new TranslatableMarkup('Insult block')
)]
class InsultBlock extends BlockBase implements ContainerFactoryPluginInterface
{

  /**
   * @var \Drupal\insult\Api\InsultAPIClient $insultAPIClient
   */
  protected InsultAPIClient $insultAPIClient;

  /**
   * Constructs an Insult Block
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\insult\API\InsultAPIClient $api_client
   *   The current_user.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    InsultAPIClient $api_client
  )
  {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->insultAPIClient = $api_client;
  }

  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition
  ): InsultBlock|static
  {
    /**
     * @var \Drupal\insult\API\InsultAPIClient $api_client
     */
    $api_client = $container->get('insult.insult_api_client');

    return new static(
        $configuration, $plugin_id, $plugin_definition, $api_client
    );
  }

  /**
   * @inheritDoc
   */
  public function build(): array
  {
    $build['#cache']['max-age'] = 60 * 15;
    $build['#markup'] = $this->getAPIInsult();

    return $build;
  }

  private function getAPIInsult(): string
  {
    return $this->insultAPIClient->getInsult();
  }

}
