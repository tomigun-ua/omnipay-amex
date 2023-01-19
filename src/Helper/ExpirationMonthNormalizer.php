<?php

declare(strict_types=1);

namespace Omnipay\Amex\Helper;

abstract class ExpirationMonthNormalizer
{
    /**
     * @param string|int $expMonth
     */
    public static function normalizer($expMonth): string
    {
        return \strlen((string)$expMonth) === 1 ? \sprintf('0%s', $expMonth) : (string)$expMonth;
    }
}
