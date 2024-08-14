<?php

declare(strict_types=1);

namespace Omnipay\Amex\Message;

final class PurchaseRequest extends AuthorizeRequest
{
    protected const API_OPERATION = 'PAY';
}
