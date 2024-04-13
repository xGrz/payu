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

    public function __construct(
        string     $postalBox = null,
        string     $recipientEmail = null,
        string     $recipientFullName = null,
        string|int $recipientPhone = null)
    {
        parent::__construct();
        if (!empty($recipientEmail)) $this->setRecipientEmail($recipientEmail);
        if (!empty($recipientPhone)) $this->setRecipientPhone($recipientPhone);
        if (!empty($recipientFullName)) $this->setRecipientName($recipientFullName);
        if (!empty($postalBox)) $this->setPostalBox($postalBox);
    }

    public static function make(string $postalBox = null, string $recipientEmail = null, string $recipientFullName = null, string|int $recipientPhone = null): static
    {
        return new static($postalBox, $recipientEmail, $recipientFullName, $recipientPhone,);
    }
}
