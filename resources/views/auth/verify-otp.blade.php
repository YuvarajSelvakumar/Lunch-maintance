<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OTP Verification</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
<div class="card p-4" style="max-width: 400px; width: 100%;">
    <h4 class="text-center mb-3">OTP Verification</h4>

    <form id="otp-form">
        @csrf
        <div class="mb-3">
            <label>Enter OTP:</label>
            <input type="text" name="otp" class="form-control" required>
        </div>
        <button class="btn btn-primary w-100" type="submit">Verify OTP</button>
    </form>

    <form id="resend-form" class="mt-3">
        @csrf
        <button id="resend-btn" class="btn btn-link w-100 p-0" type="submit" disabled>Resend OTP (30s)</button>
    </form>

    <div id="status-msg" class="text-center mt-3 text-danger fw-bold"></div>
</div>

<script>
    const otpForm = document.getElementById('otp-form');
    const resendForm = document.getElementById('resend-form');
    const resendBtn = document.getElementById('resend-btn');
    const statusMsg = document.getElementById('status-msg');

    function startCooldown(seconds) {
        let remaining = seconds;
        resendBtn.disabled = true;
        resendBtn.innerText = `Resend OTP (${remaining}s)`;

        const interval = setInterval(() => {
            remaining--;
            resendBtn.innerText = `Resend OTP (${remaining}s)`;

            if (remaining <= 0) {
                clearInterval(interval);
                resendBtn.disabled = false;
                resendBtn.innerText = 'Resend OTP';
            }
        }, 1000);
    }

    document.addEventListener("DOMContentLoaded", () => {
        startCooldown(30);
    });

    otpForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(otpForm);
        fetch("{{ route('otp.verify') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            statusMsg.textContent = data.message;
            statusMsg.classList.toggle('text-danger', !data.success);
            statusMsg.classList.toggle('text-success', data.success);

            if (data.success && data.redirect) {
                setTimeout(() => window.location.href = data.redirect, 1000);
            }
        })
        .catch(() => {
            statusMsg.textContent = 'Something went wrong.';
        });
    });

    resendForm.addEventListener('submit', function (e) {
        e.preventDefault();

        fetch("{{ route('resend.otp') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            statusMsg.textContent = data.message;
            statusMsg.classList.toggle('text-danger', !data.success);
            statusMsg.classList.toggle('text-success', data.success);

            if (data.success) {
                startCooldown(30);
            } else if (data.message.includes('wait')) {
                const secondsMatch = data.message.match(/(\d+)\sseconds/);
                if (secondsMatch) startCooldown(parseInt(secondsMatch[1]));
            }
        })
        .catch(() => {
            statusMsg.textContent = 'Resend failed.';
        });
    });
</script>
</body>
</html>
