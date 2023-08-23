<?php


namespace Inpush\Controllers;

use Inpush\Middlewares\Authentication;
use Inpush\Models\Plan;

class Invoice extends Controller {

    public function index() {

        Authentication::guard();

        $id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Make sure the campaign exists and is accessible to the user */
        if(!$payment = db()->where('id', $id)->getOne('payments')) {
            redirect('dashboard');
        }

        if($payment->user_id != $this->user->user_id && !Authentication::is_admin()) {
            redirect('dashboard');
        }

        /* Try to see if we get details from the billing */
        $payment->billing = json_decode($payment->billing);
        $payment->business = json_decode($payment->business);
        $payment->plan = json_decode($payment->plan);

        /* Get the plan details */
        $payment->plan_db = (new Plan())->get_plan_by_id($payment->plan_id);

        /* Check for potential taxes */
        $payment_taxes = (new \Inpush\Models\Plan())->get_plan_taxes_by_taxes_ids($payment->taxes_ids);

        /* Calculate the price if a discount was used */
        $payment->price = $payment->discount_amount ? $payment->base_amount - $payment->discount_amount : $payment->base_amount;

        /* Calculate taxes */
        if(!empty($payment_taxes)) {


            /* Check for the inclusives */
            $inclusive_taxes_total_percentage = 0;

            foreach($payment_taxes as $key => $row) {
                if($row->type == 'exclusive') continue;

                $inclusive_taxes_total_percentage += $row->value;
            }

            $total_inclusive_tax = $payment->price - ($payment->price / (1 + $inclusive_taxes_total_percentage / 100));

            $price_without_inclusive_taxes = $payment->price - $total_inclusive_tax;

            foreach($payment_taxes as $key => $row) {
                if($row->type == 'exclusive') continue;

                $percentage_of_total_inclusive_tax = $row->value * 100 / $inclusive_taxes_total_percentage;

                $inclusive_tax = number_format($total_inclusive_tax * $percentage_of_total_inclusive_tax / 100, 2);

                $payment_taxes[$key]->amount = $inclusive_tax;
            }

            /* Check for the exclusives */
            foreach($payment_taxes as $key => $row) {

                if($row->type == 'inclusive') {
                    continue;
                }

                $exclusive_tax = number_format($row->value_type == 'percentage' ? $price_without_inclusive_taxes * ($row->value / 100) : $row->value, 2);

                $payment_taxes[$key]->amount = $exclusive_tax;

            }

        }

        /* Prepare the View */
        $data = [
            'payment' => $payment,
            'payment_taxes' => $payment_taxes
        ];

        $view = new \Inpush\Views\View('invoice/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
