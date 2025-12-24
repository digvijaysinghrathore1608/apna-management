<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DigiLocker Verification</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .card {
            background: #fff;
            padding: 30px;
            width: 100%;
            max-width: 420px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,.08);
            text-align: center;
        }

        .success {
            color: #16a34a;
        }

        .failed {
            color: #dc2626;
        }

        .pending {
            color: #f59e0b;
        }

        .icon {
            font-size: 48px;
            margin-bottom: 15px;
        }

        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 18px;
            background: #2563eb;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
        }
    </style>
</head>
<body>

<div class="card">
    @php
        $status = $status ?? 'pending';
    @endphp

    @if($status === 'success')
        <div class="icon success">✔</div>
        <h2 class="success">Verification Successful</h2>
        <p>Your DigiLocker verification has been completed successfully.</p>

    @elseif($status === 'failed')
        <div class="icon failed">✖</div>
        <h2 class="failed">Verification Failed</h2>
        <p>We could not verify your DigiLocker account.</p>

    @else
        <div class="icon pending">⏳</div>
        <h2 class="pending">Verification In Progress</h2> {{ $status }}
        <p>Please wait while we complete your verification.</p>
    @endif

    <a href="/" class="btn">Go to Home</a>
</div>

</body>
</html>
