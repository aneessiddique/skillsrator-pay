<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Payment Receipt - Extreme Commerce</title>

    <style>
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

        /** RTL **/
        .invoice-box.rtl {
            direction: rtl;
            font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .invoice-box.rtl table {
            text-align: right;
        }

        .invoice-box.rtl table tr td:nth-child(2) {
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <img src="https://pay.ec.com.pk/assets/img/ec-logo.png"
                                    style="width: 100%; max-width: 300px" />
                            </td>

                            <td>
                                Invoice #: {{ $txn_customer_bill_order_id}}<br />
                                Created: {{ date('dS M, Y H:s:i', strtotime($created_at)) }}<br />
                                Paid On: {{ date('dS M, Y H:s:i', strtotime($txn_datetime)) }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                {{$txn_customer_name}}<br />
                                {{$txn_customer_email}}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="heading">
                <td>Payment Method</td>

                <td>Ref #</td>
            </tr>

            <tr class="details">
                <td>{{$ec_pay_gateway_name}}</td>

                <td>{{$txn_response_ref}}</td>
            </tr>

            <tr class="heading">
                <td>Item</td>

                <td>Price</td>
            </tr>

            <tr class="item last">
                <td>{{$txn_description}}</td>

                <td> {{$txn_currency}} {{ number_format($txn_amount, 2) }}</td>
            </tr>

            @if(isset($txn_gateway_fee) && $txn_gateway_fee > 0)
            <tr class="item last">
                <td>Gateway Fee</td>

                <td> {{$txn_currency}} {{ number_format($txn_gateway_fee, 2) }} </td>
            </tr>
            @endif

            <tr class="total">
                <td></td>

                <td>Total: {{$txn_currency}} {{ number_format($txn_amount + $txn_gateway_fee, 2) }}</td>
            </tr>
        </table>
    </div>
</body>

</html>
