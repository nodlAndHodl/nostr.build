<?php
// The purpose of this file is to store site-wide configuration that can be consumed by the application
// Path: SiteConfig.php

/**
 * Summary of SiteConfig
 * A static class that contains site-wide configuration
 */
class SiteConfig
{
  const ACCESS_SCHEME = 'https';
  const DOMAIN_NAME = 'nostr.build';
  const CDN_CONFIGS = [
    'image' => [
      'cdn_host' => 'cdn.nostr.build',
      'path' => '/i',
      'use_cdn' => false,
    ],
    'video' => [
      'cdn_host' => 'cdn.nostr.build',
      'path' => '/av',
      'use_cdn' => false,
    ],
    'audio' => [
      'cdn_host' => 'cdn.nostr.build',
      'path' => '/av',
      'use_cdn' => false,
    ],
    'profile_picture' => [
      'cdn_host' => 'cdn.nostr.build',
      'path' => '/i/p',
      'use_cdn' => false,
    ],
    'professional_account_image' => [
      'cdn_host' => 'cdn.nostr.build',
      'path' => '/p',
      'use_cdn' => true,
    ],
    'professional_account_video' => [
      'cdn_host' => 'cdn.nostr.build',
      'path' => '/p',
      'use_cdn' => true,
    ],
    'professional_account_audio' => [
      'cdn_host' => 'cdn.nostr.build',
      'path' => '/p',
      'use_cdn' => true,
    ]
  ];

  const ACCOUNT_TYPES = [
    99 => 'Admin account',
    89 => 'Admin Review account',
    5 => '5GB account',
    4 => 'View All Premium account',
    3 => '5GB + View All account',
    2 => 'Pro account',
    1 => 'Creator account',
    0 => 'Pending Account Verification'
  ];

  const STORAGE_LIMITS = [
    '99' => ['limit' => -1, 'message' => 'Unlimited'],
    '89' => ['limit' => 100 * 1024, 'message' => '100MiB'],
    '5' => ['limit' => 5 * 1024 * 1024 * 1024, 'message' => '5GiB'],
    '4' => ['limit' => 0, 'message' => 'No Storage, consider upgrading'],
    '3' => ['limit' => 5 * 1024 * 1024 * 1024, 'message' => '5GiB'],
    '2' => ['limit' => 10 * 1024 * 1024 * 1024, 'message' => '10GiB'],
    '1' => ['limit' => 20 * 1024 * 1024 * 1024, 'message' => '20GiB'],
    '0' => ['limit' => 0, 'message' => 'No Storage, consider upgrading'],
  ];

  const FREE_UPLOAD_LIMIT = 25 * 1024 * 1024; // 25MB in bytes

  /**
   * Summary of getHost
   * @param mixed $mediaType
   * @throws \Exception
   * @return mixed
   */
  public static function getHost($mediaType)
  {
    if (!array_key_exists($mediaType, self::CDN_CONFIGS)) {
      throw new Exception("Invalid media type: {$mediaType}");
    }

    $config = self::CDN_CONFIGS[$mediaType];
    return $config['use_cdn'] ? $config['cdn_host'] : self::DOMAIN_NAME;
  }

  /**
   * Summary of getPath
   * @param mixed $mediaType
   * @throws \Exception
   * @return mixed
   */
  public static function getPath($mediaType)
  {
    if (!array_key_exists($mediaType, self::CDN_CONFIGS)) {
      throw new Exception("Invalid media type: {$mediaType}");
    }

    return self::CDN_CONFIGS[$mediaType]['path'];
  }

  public static function getFullyQualifiedUrl($mediaType)
  {
    if (!array_key_exists($mediaType, self::CDN_CONFIGS)) {
      throw new Exception("Invalid media type: {$mediaType}");
    }

    $scheme = self::ACCESS_SCHEME;
    $host = self::getHost($mediaType);
    $path = self::getPath($mediaType);

    return "{$scheme}://{$host}{$path}";
  }

  public static function getAccountType($acctLevel)
  {
    if (!array_key_exists($acctLevel, self::ACCOUNT_TYPES)) {
      return 'Unknown account type'; // default message
    }

    return self::ACCOUNT_TYPES[$acctLevel];
  }

  public static function getStorageLimit($acctLevel)
  {
    if (!array_key_exists($acctLevel, self::STORAGE_LIMITS)) {
      return 0; // return 0 if account level doesn't exist
    }
    $limit = self::STORAGE_LIMITS[$acctLevel]['limit'];
    // Handle unlimited storage
    return $limit === -1 ? PHP_INT_MAX : $limit;
  }
}