<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>

<div>
    You've been invited to a session of RETROPE by {{ $session->host->nickname }}.

    <br>

    Click <a href="{{ env('FRONT_END_URL') . '/session/' . $session->invitationCode }}">here</a> to join!
</div>

</body>
</html>