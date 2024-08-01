<?php

declare(strict_types=1);

namespace Drupal\insult\API;

/**
 * Provides static method for getting insult from WEB API
 */
class InsultAPIClient
{
  const API_PATH = 'https://evilinsult.com/generate_insult.php?lang=en&type=json';

  public static function getInsult(): string
  {
    $reqData = file_get_contents(self::API_PATH);
    \Drupal::logger('debug')->debug($reqData);

    $pattern = '/"insult":"(.*?)"/';
    preg_match($pattern, $reqData, $matches);

    return $matches[1] ?? 'Almost done';
  }
}
