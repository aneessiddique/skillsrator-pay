<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class KuickpayReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ReportData;
    public $ReportDataSum;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($kuickpay_type, $days)
    {
        $this->ReportData = $this->getReportData($kuickpay_type, $days);
        $this->ReportDataSum = $this->getReportData($kuickpay_type, $days, true);
    }

    public function getReportData($kuickpay_type, $days, $getsum = false){
        // $day = 1;
        if($getsum){
            $query = "SELECT SUM(txn_amount) AS total_amount FROM `transactions` WHERE txn_response_ref like $kuickpay_type AND txn_status = 'completed' AND updated_at > DATE_SUB(NOW(), INTERVAL ".$days." Day)";
            $res = DB::select($query);
        } else {
            $query = "SELECT * FROM `transactions` WHERE txn_response_ref like $kuickpay_type AND txn_status = 'completed' AND updated_at > DATE_SUB(NOW(), INTERVAL ".$days." Day)";
            $res = DB::select($query);
        }

        return $res;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Kuickpay Payments ['.Date('Y-m-d').'] - Report')
                ->view('Mail.report');
    }
}
