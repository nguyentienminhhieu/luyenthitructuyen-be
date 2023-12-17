<!-- resources/views/emails/reset_password.blade.php -->

<p>Hello!</p>

<p>You are receiving this email because we received a verification request for your account.</p>

<p>
    Please click on the following link to verify your account:
    <a href="http://localhost:4000/verify-email/{{ $token }}">Verify email</a>
</p>
{{ $token }}
<p>If you did not request a verification, no further action is required.</p>

<p>Thank you!</p>