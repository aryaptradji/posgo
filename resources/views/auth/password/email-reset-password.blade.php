<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#F4F4F4;">
    <tr>
        <td align="left">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="600"
                style="background-color:#F4F4F4; padding:20px;">
                <tr>
                    <td style="text-align:left;">
                        <img src="{{ asset('img/posgo-logo.png') }}" alt="Logo" style="width: 120px;">
                        <h2>Reset Password</h2>
                        <p>Halo Posgo User,</p>
                        <p>Klik link di bawah untuk mengatur ulang password Anda:</p>
                        <p>
                            <a href="{{ route('password.reset', $token) }}?email={{ $email }}"
                                style="background-image: linear-gradient(to right, #E4763F, #7A24F9); background-clip: text; -webkit-background-clip: text; color: transparent; font-weight: 600;">
                                Reset Password
                            </a>
                        </p>
                        <p>Link ini hanya berlaku hingga 1 jam kedepan. Jangan bagikan kepada siapa pun.</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
