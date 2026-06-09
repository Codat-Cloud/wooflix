@extends('layouts.front')

@section('content')
<div class="auth-page-wrapper py-5" style="background-color: #f8f9fa; min-height: 70vh; display: flex; align-items: center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-4">
                
                <!-- Main Authentication Card -->
                <div class="card border-0 shadow p-4" style="border-radius: 12px; background: #ffffff;">
                    
                    <!-- Store Branding Title Header -->
                    <div class="text-center mb-4">
                        <h2 style="margin: 0; padding: 0; font-size: 32px; font-family: 'Arial Black', Gadget, sans-serif; letter-spacing: -1px; line-height: 1;">
                            <span style="color: #1a1a1a; font-weight: 900;">WOOF</span><span style="color: #f26522; font-weight: 900;">LIX</span>🐾
                        </h2>
                        <p class="text-muted small mt-2">Enter your email to login or register instantly.</p>
                    </div>

                    <div class="auth-form-body">
                        
                        <!-- ================= STEP 1: EMAIL ENTRY INPUT FORM ================= -->
                        <form id="emailEntryForm">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Email Address</label>
                                <input type="email" class="form-control py-2 shadow-none fs-6" id="authEmail" placeholder="name@example.com" required>
                            </div>
                            
                            <button type="submit" class="btn w-100 fw-bold py-2 text-uppercase text-white" id="btnSendOtp" style="background-color: #f26522; border-radius: 6px;">
                                Get Verification OTP
                            </button>
                        </form>

                        <!-- ================= STEP 2: OTP VERIFICATION FIELD FORM ================= -->
                        <form id="otpVerifyForm" class="d-none">
                            <div class="alert alert-success border-0 small text-success bg-success bg-opacity-10 mb-3 rounded py-2">
                                📩 OTP code sent! Please check your email inbox.
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold text-secondary mb-1">Enter 6-Digit Code</label>
                                <input type="number" class="form-control text-center fw-bold fs-4 shadow-none" id="authOtp" placeholder="000000" required style="letter-spacing: 6px; border-color: #f26522;">
                            </div>

                            <button type="submit" class="btn w-100 fw-bold py-2 text-uppercase text-white" id="btnVerifyOtp" style="background-color: #f26522; border-radius: 6px;">
                                Verify & Authenticate
                            </button>

                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-link btn-sm text-decoration-none text-muted small" onclick="window.location.reload();">← Change Email Address</button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const emailEntryForm = document.getElementById('emailEntryForm');
    const otpVerifyForm = document.getElementById('otpVerifyForm');

    // 1. Send OTP Request
    emailEntryForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const email = document.getElementById('authEmail').value;
        const btnSend = document.getElementById('btnSendOtp');
        
        btnSend.disabled = true;
        btnSend.innerText = "Processing...";

        const res = await fetch("{{ route('auth.send-otp') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ email })
        });
        
        const data = await res.json();
        if (res.ok) {
            emailEntryForm.classList.add('d-none');
            otpVerifyForm.classList.remove('d-none');
        } else {
            alert(data.message || 'Error processing email verification.');
            btnSend.disabled = false;
            btnSend.innerText = "Get Verification OTP";
        }
    });

    // 2. Verify OTP Request
    otpVerifyForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const otp = document.getElementById('authOtp').value;
        const btnVerify = document.getElementById('btnVerifyOtp');
        
        btnVerify.disabled = true;
        btnVerify.innerText = "Verifying...";

        const res = await fetch("{{ route('auth.verify-otp') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ otp })
        });
        
        const data = await res.json();
        if (res.ok) {
            window.location.href = data.redirect;
        } else {
            alert(data.message || 'Invalid or mismatched OTP code verification entry.');
            btnVerify.disabled = false;
            btnVerify.innerText = "Verify & Authenticate";
        }
    });
});
</script>
@endsection