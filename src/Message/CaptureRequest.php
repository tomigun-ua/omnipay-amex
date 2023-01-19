<?php

declare(strict_types=1);

namespace Omnipay\Amex\Message;

final class CaptureRequest extends AuthorizeRequest
{
    protected const API_OPERATION = 'PAY';
}
