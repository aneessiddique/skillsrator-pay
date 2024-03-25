<!DOCTYPE html>
<html lang="en">
    <head>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    </head>
    <body>
        <form id="manual_invoice_payment" method="post" action="{{env('APP_URL')}}">
            <input type="hidden" name="id" value="{{$id}}" />
            <input type="hidden" name="data" value="{{$data}}" />
        </form>
        <script>
            $(document).ready(function() {
                $('#manual_invoice_payment').submit();
            });
        </script>
    </body>
</html>