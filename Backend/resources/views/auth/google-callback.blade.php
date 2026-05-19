<!DOCTYPE html>
<html>

<head>
    <title>Google Login Success</title>
</head>

<body>
    <script>
        const token = @json($token);
        const userStr = @json($userData);

        if (window.opener) {
            window.opener.postMessage({
                type: 'GOOGLE_LOGIN_SUCCESS',
                token: token,
                userStr: userStr
            }, '*'); // In production, restrict origin
            window.close();
        } else {
            // Fallback if opener is lost (rare in popup flow)
            window.location.href = '/agent/dashboard'; // Or smart redirect
        }
    </script>
</body>

</html>