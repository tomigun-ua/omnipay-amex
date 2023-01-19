<?php

declare(strict_types=1);

namespace Omnipay\Amex\Helper;

abstract class ExpirationYearNormalizer
{
    /**
     * @param string|int $expYear
     */
    public static function normalizer($expYear): string
    {
        return \strlen((string)$expYear) === 4 ? \substr((string)$expYear, 2) : (string)$expYear;
    }
}
