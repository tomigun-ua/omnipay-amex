<?php

declare(strict_types=1);

namespace Omnipay\Amex;

use Omnipay\Amex\Exception\InvalidCustomerBrowserException;
use Omnipay\Common\ParametersTrait;

class CustomerBrowser
{
    use ParametersTrait;

    public function __construct($parameters = null)
    {
        $this->initialize($parameters);
    }

    /**
     * @throws InvalidCustomerBrowserException
     */
    public function validate(): void
    {
        $requiredParameters = array(
            'userAgent' => 'User browser.',
            'ipAddress' => 'User IP address.',
        );

        foreach ($requiredParameters as $key => $val) {
            if (!$this->getParameter($key)) {
                throw new InvalidCustomerBrowserException("The $val is required");
            }
        }
    }

    public function getUserAgent(): ?string
    {
        return $this->getParameter('userAgent');
    }

    /**
     * @return static
     */
    public function setUserAgent(string $value): self
    {
        return $this->setParameter('userAgent', $value);
    }

    public function getIpAddress(): ?string
    {
        return $this->getParameter('ipAddress');
    }

    /**
     * @return static
     */
    public function setIpAddress(string $value): self
    {
        return $this->setParameter('ipAddress', $value);
    }

    public function get3DSecureChallengeWindowSize(): ?string
    {
        return $this->getParameter('3DSecureChallengeWindowSize');
    }

    /**
     * @return static
     */
    public function set3DSecureChallengeWindowSize(string $value): self
    {
        return $this->setParameter('3DSecureChallengeWindowSize', $value);
    }

    public function getAcceptHeaders(): ?string
    {
        return $this->getParameter('acceptHeaders');
    }

    /**
     * @return static
     */
    public function setAcceptHeaders(string $value): self
    {
        return $this->setParameter('acceptHeaders', $value);
    }

    public function getColorDepth(): ?int
    {
        return $this->getParameter('colorDepth');
    }

    /**
     * @return static
     */
    public function setColorDepth(int $value): self
    {
        return $this->setParameter('colorDepth', $value);
    }

    public function getJavaEnabled(): ?bool
    {
        return $this->getParameter('javaEnabled');
    }

    /**
     * @return static
     */
    public function setJavaEnabled(bool $value): self
    {
        return $this->setParameter('javaEnabled', $value);
    }

    public function getLanguage(): ?string
    {
        return $this->getParameter('language');
    }

    /**
     * @return static
     */
    public function setLanguage(string $value): self
    {
        return $this->setParameter('language', $value);
    }

    public function getScreenHeight(): ?int
    {
        return $this->getParameter('screenHeight');
    }

    /**
     * @return static
     */
    public function setScreenHeight(int $value): self
    {
        return $this->setParameter('screenHeight', $value);
    }

    public function getScreenWidth(): ?int
    {
        return $this->getParameter('screenWidth');
    }

    /**
     * @return static
     */
    public function setScreenWidth(int $value): self
    {
        return $this->setParameter('screenWidth', $value);
    }

    public function getTimeZone(): ?int
    {
        return $this->getParameter('timeZone');
    }

    /**
     * @return static
     */
    public function setTimeZone(int $value): self
    {
        return $this->setParameter('timeZone', $value);
    }
}
