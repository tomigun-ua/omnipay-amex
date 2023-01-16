<?php

declare(strict_types=1);

namespace Omnipay\Amex\Message;

class Response extends \Omnipay\Common\Message\AbstractResponse
{
    private const SUCCESS_STATUS = 'SUCCESS';

    public function isSuccessful(): bool
    {
        return \isset($this->data->status) && $this->data->status === self::SUCCESS_STATUS;
    }
}
