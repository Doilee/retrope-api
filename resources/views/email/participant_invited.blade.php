<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>

<div>
    You've been invited to a session of RETROPE.

    <br>

    @if($invite)
        Click <a href="{{ env('FRONT_END_URL') . '/invite/' . $invite->code }}">here</a> to join!
    @else
        Click <a href="{{ env('FRONT_END_URL') }}">here</a> to join!
    @endif
</div>

</body>
</html>