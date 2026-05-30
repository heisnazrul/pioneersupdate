<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice['reference_no'] ?? '' }}</title>
    @include('pdf.bookings._invoice-styles')
</head>
<body>
<table class="pdf-margin-wrap" width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <td class="pdf-margin-cell" style="padding:14mm 16mm 14mm 16mm; vertical-align:top;">
            @include('pdf.bookings._invoice-body', ['invoice' => $invoice])
        </td>
    </tr>
</table>
</body>
</html>
