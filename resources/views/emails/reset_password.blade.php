<!-- resources/views/emails/reset_password.blade.php -->

<p>Hello!</p>

<p>You are receiving this email because we received a password reset request for your account.</p>

<p>
    Please click on the following link to reset your password:
    <a href="{{ $token }}">Reset Password</a>
</p>
{{ $token }}
<p>If you did not request a password reset, no further action is required.</p>

<p>Thank you!</p>