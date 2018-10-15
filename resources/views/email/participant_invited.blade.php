<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>

<div>
    You've been invited to a retrospective of RETROPE.
    <br>

    @if($invite && $invite->player->user->isGuest())
        Click <a href="{{ env('FRONT_END_URL') . '/invite/' . $invite->token }}">here</a> to join!
    @else
        Click <a href="{{ env('FRONT_END_URL') }}">here</a> to join!
    @endif
</div>

</body>
</html>