<?php declare(strict_types=1);

namespace App\Interface;

use Psr\Http\Message\ResponseInterface;

interface ApiClientInterface
{
    public function get(string $url, array $options = []): ResponseInterface;
}