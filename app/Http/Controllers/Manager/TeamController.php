<?php

namespace App\Http\Controllers\Manager;

use App\Team;
use App\User;
use Auth;
use Illuminate\Http\Request;

class TeamController extends ManagerController
{
    /**
     * Display a listing of the teams.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->client()->teams;
    }

    /**
     * Display the specified team.
     *
     * @param Team $team
     *
     * @return Team
     */
    public function show(Team $team)
    {
        $this->validateTeam($team);

        return $team;
    }

    /**
     * Store a newly created team in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|min:2|max:255'
        ]);

        $fill = $request->only(['name']);

        /* @var Team $team */
        $team = $this->client()->teams()->create($fill);

        return response()->json([
            'message' => 'Successfully stored team',
            'team' => $team
        ], 201);
    }

    /**
     * Update the specified team in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Team $team)
    {
        $this->validateTeam($team);

        $this->validate($request, [
            'name' => 'required|string|min:2|max:255'
        ]);

        $data = $request->only(['name']);

        $team->update($data);

        return response()->json([
            'message' => 'Edit successful!',
            'team' => $team
        ]);
    }

    public function attachUser(Team $team, User $user)
    {
        $this->validateTeam($team);

        $team->users()->attach($user->id);

        return response()->json([
            'message' => 'User added!',
            'team' => $team
        ]);
    }

    public function detachUser(Team $team, User $user)
    {
        $this->validateTeam($team);

        $team->users()->detach($user->id);

        return response()->json([
            'message' => 'User removed from team!',
            'team' => $team
        ]);
    }

    /**
     * Remove the specified team from storage.
     *
     * @param  \App\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team)
    {
        $this->validateTeam($team);

        $team->delete();

        return response()->json([
            'message' => 'Team deleted!',
        ]);
    }

    private function validateTeam(Team $team)
    {
        if (Auth::user()->hasRole('manager')) {
            // Check to see if team exists within the client
            $team->client()->findOrFail(Auth::user()->client_id);
        }
    }
}
