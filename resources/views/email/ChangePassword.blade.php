
<table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;">
    <tr>
        <td align="center" style="padding:0;">
            <table role="presentation"
                style="width:602px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;">
                <tr>
                    <td align="center" style="padding:40px 0 30px 0;background:#4b5563;">
                        <h2 style="color: #ffffff;font-family:Arial,sans-serif;">Web Authentication</h2>
                    </td>
                </tr>
                <tr>
                    <td style="padding:36px 30px 42px 30px;">
                        <table role="presentation"
                            style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                            <tr>
                                <td style="padding:0 0 36px 0;color:#153643;">
                                    <p
                                        style='margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;'>
                                        <br><br>
                                        <strong>Password Change Request.</strong><br /><br />
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td align="center" style="padding: 20px;">
                                <a href="{{ route('change.password', ['userId' => md5($userId)]) }}" style="display: inline-block; padding: 10px 20px; font-size: 16px; color: #ffffff; text-decoration: none; border-radius: 5px; background-color: #4b5563; border: 1px solid #312d2a;">
                                    Click here to change your password
                                </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
