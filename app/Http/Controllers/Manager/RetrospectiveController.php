<?php namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Invite;
use App\Notifications\PlayerInvited;
use App\Retrospective;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;
use Mail;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class RetrospectiveController
 * @package App\Http\Controllers
 */
class RetrospectiveController extends ManagerController
{
    /**
     * @param Retrospective $retrospective
     *
     * @return Retrospective
     */
    public function show(Retrospective $retrospective)
    {
        $manager = Auth::user();

        if ($retrospective->host_id !== $manager->id) {
            throw new UnauthorizedException('You are not authorized to do this.');
        }

        $retrospective->load('actions');

        return $retrospective;
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|min:2',
            'scheduled_at' => 'nullable|date|after:' . now()->toDateTimeString(),
            'starts_at' => 'nullable|date|after:' . now()->toDateTimeString(),
        ]);

        /** @var User $manager */
        $manager = auth()->user();

        $retrospective = $manager->retrospectives()->create([
            'name' => $request->get('name'),
            'scheduled_at' => $request->get('scheduled_at'),
        ]);

        if ($request->has('starts_at'))
        {
            $retrospective->update([
                'starts_at' => $request->get('starts_at'),
            ]);
        }

        return response()->json([
            'message' => 'Success',
            'retrospective' => $retrospective
        ], 201);
    }

    /**
     * @param Request $request
     * @param Retrospective $retrospective
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function start(Request $request, Retrospective $retrospective)
    {
        $this->validate($request, [
            'timer' => 'nullable|integer|max:600'
        ]);

        $retrospective->start($request->get('timer'));

        return response()->json([
            'message' => 'Retrospective started.'
        ]);
    }

    /**
     * @param Retrospective $retrospective
     *
     * @param User $user
     *
     * @return \Illuminate\Http\JsonResponse
     * @internal param Request $request
     */
    public function invite(Retrospective $retrospective, User $user)
    {
        $manager = Auth::user();

        $users = $manager->client->users;

        // Throw exception when user isn't part of the client
        if (!$users->where('id', $user->id)->first()) {
            throw new BadRequestHttpException('User doesn\'t exist');
        }

        // Create player field if doesnt exist
        $player = $retrospective->players()->where('user_id', $user->id)->first() ?? $retrospective->players()->create([
            'user_id' => $user->id,
        ]);

        $user->notify(new PlayerInvited());

        return response()->json([
            'message' => 'User invited, he/she has been notified!'
        ]);
    }
}