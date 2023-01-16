<?php

declare(strict_types=1);

namespace Omnipay\Amex\Message;

final class CheckStatusRequest extends AbstractRequest
{
    private const PATH = '/%s/information';

    public function getData(): array
    {
        return [];
    }

    public function getEndpoint(): string
    {
        $host = parent::getEndpoint();

        return \sprintf(\rtrim($host, '/') . self::PATH, $this->getVersion());
    }
}
