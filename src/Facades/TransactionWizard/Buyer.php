<?php

namespace xGrz\PayU\Facades\TransactionWizard;

use xGrz\PayU\Traits\Arrayable;

/**
 * @method setFirstName(string $firstName)
 * @method setPhone(int|string $phone)
 * @method setEmail(string $email)
 * @method setLastName(string $lastName)
 * @method setLanguage(mixed $language)
  */
class Buyer
{
    use Arrayable;

    private array $data = [
        'extCustomerId' => null,
        'email' => null,
        'phone' => null,
        'firstName' => null,
        'lastName' => null,
        'language' => null,
    ];

    public function __construct(string $email = null, string|int $phone = null, string $firstName = null, string $lastName = null, string|int $customerId = null, $language = null)
    {
        if (!empty($email)) $this->setEmail($email);
        if (!empty($phone)) $this->setPhone($phone);
        if (!empty($firstName)) $this->setFirstName($firstName);
        if (!empty($lastName)) $this->setLastName($lastName);
        empty($language)
            ? $this->setLanguage(app()->getLocale())
            : $this->setLanguage($language);
        if (!empty($customerId)) {
            $this->setCustomerId($customerId);
        }
    }

    public function setCustomerId(string|int $customerId): static
    {
        $this->data['extCustomerId'] = $customerId;
        return $this;
    }
    public function __set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function __call(string $name, $arguments): static
    {
        if (str($name)->startsWith('set')) {
            $key = (string)str($name)->replaceStart('set', '')->camel();
            if (array_key_exists($key, $this->data)) {
                $this->data[$key] = $arguments[0];
                return $this;
            }
        }
        throw new \TypeError('Method ' . $name . ' not found');
    }

    public static function make(string $email = null, string|int $phone = null, string $firstName = null, string $lastName = null, string|int $customerId = null, string $language = null): static
    {
        return new static($email, $phone, $firstName, $lastName, $customerId, $language);
    }

}
