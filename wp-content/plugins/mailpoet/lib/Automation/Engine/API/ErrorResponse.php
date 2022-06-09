<?php declare(strict_types = 1);

namespace MailPoet\Automation\Engine\API;

if (!defined('ABSPATH')) exit;


class ErrorResponse extends Response {
  public function __construct(
    int $status,
    string $message,
    string $code
  ) {
    parent::__construct(null, $status);
    $this->set_data([
      'code' => $code,
      'message' => $message,
      'data' => ['status' => $status],
    ]);
  }
}
