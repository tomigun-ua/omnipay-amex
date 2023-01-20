<?php

declare(strict_types=1);

namespace Omnipay\Amex\Message;

use Omnipay\Amex\Helper\ExpirationMonthNormalizer;
use Omnipay\Amex\Helper\ExpirationYearNormalizer;

trait CardDataTrait
{
    private function getCardData(): array
    {
        $card = $this->getCard();
        $cardData = [
            'number' => $card->getNumber(),
            'securityCode' => $card->getCvv(),
            'expiry' => [
                'month' => ExpirationMonthNormalizer::normalizer($card->getExpiryMonth()),
                'year' => ExpirationYearNormalizer::normalizer($card->getExpiryYear()),
            ],
        ];

        if ($this->getCard()->getFirstName()) {
            $cardData['nameOnCard'] = $card->getFirstName() . ' ' . $card->getLastName();
        }

        return $cardData;
    }
}
