<?php


namespace Inpush\Controllers;

use Inpush\Alerts;
use Inpush\Date;
use Inpush\Models\Model;
use Inpush\Models\Payments;
use Inpush\Models\User;
use Inpush\PaymentGateways\Paystack;
use YooKassa\Model\Notification\NotificationSucceeded;
use YooKassa\Model\Notification\NotificationWaitingForCapture;
use YooKassa\Model\NotificationEventType;

class WebhookYookassa extends Controller {

    public function index() {

        if((strtoupper($_SERVER['REQUEST_METHOD']) != 'POST')) {
            die();
        }

        $yookassa = new \YooKassa\Client();
        $yookassa->setAuth(settings()->yookassa->shop_id, settings()->yookassa->secret_key);

        $payload = @file_get_contents('php://input');

        $data = json_decode($payload, true);

        try {
            $notification = new NotificationSucceeded($data);
        } catch (\Exception $exception) {
            http_response_code(400); die($exception->getCode() . ':' . $exception->getMessage());
        }

        if($notification->getEvent() == NotificationEventType::PAYMENT_SUCCEEDED) {
            $payment_subscription_id = null;

            /* Start getting the payment details */
            $external_payment_id = $notification->getObject()->getId();
            $payment_total = $notification->getObject()->getAmount()->getValue();
            $payment_currency = $notification->getObject()->getAmount()->getCurrency();
            $payment_type = $payment_subscription_id ? 'recurring' : 'one_time';

            /* Payment payer details */
            $payer_email = '';
            $payer_name = '';

            /* Process meta data */
            $metadata = $notification->getObject()->getMetadata();
            $user_id = (int) $metadata->user_id;
            $plan_id = (int) $metadata->plan_id;
            $payment_frequency = $metadata->payment_frequency;
            $code = isset($metadata->code) ? $metadata->code : '';
            $discount_amount = isset($metadata->discount_amount) ? $metadata->discount_amount : 0;
            $base_amount = isset($metadata->base_amount) ? $metadata->base_amount : 0;
            $taxes_ids = isset($metadata->taxes_ids) ? $metadata->taxes_ids : null;

            (new Payments())->webhook_process_payment(
                'mollie',
                $external_payment_id,
                $payment_total,
                $payment_currency,
                $user_id,
                $plan_id,
                $payment_frequency,
                $code,
                $discount_amount,
                $base_amount,
                $taxes_ids,
                $payment_type,
                $payment_subscription_id,
                $payer_email,
                $payer_name
            );

            die('successful');
        }

        print_r($notification);

        die();
    }

}
