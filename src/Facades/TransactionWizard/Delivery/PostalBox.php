<?php

namespace xGrz\PayU\Facades\TransactionWizard\Delivery;

use xGrz\PayU\Interfaces\DeliveryTypeInterface;

/**
 * @method setRecipientName(string $string)
 * @method setRecipientEmail(string $email)
 * @method setRecipientPhone(string $phone)
 * @method setPostalBox(string $postalBox)
 */
class PostalBox extends Delivery implements DeliveryTypeInterface
{

    protected array $data = [
        'postalBox' => null
    ];
    public function __construct(string $email = null, string $name = null, string|int $phone = null, string $postalBox = null)
    {
        parent::__construct();
        if (!empty($email)) $this->setRecipientEmail($email);
        if (!empty($phone)) $this->setRecipientPhone($phone);
        if (!empty($name)) $this->setRecipientName($name);
        if (!empty($postalBox)) $this->setPostalBox($postalBox);
    }

    public static function make(string $email = null, string $name = null, string|int $phone = null, string $postalBox = null): static
    {
        return new static($email, $name, $phone, $postalBox);
    }
}
