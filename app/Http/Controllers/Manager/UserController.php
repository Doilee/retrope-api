<?php namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $manager;

    public function __construct()
    {
        $this->manager = Auth::user();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->manager->client->users()->paginate(25);
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        $fill = array_merge(['password' => bcrypt(uniqid())],$request->all());

        $user = $this->manager->client->users()->create($fill);

        return response()->json([
            'message' => 'Successfully stored user',
            'user' => $user
        ], 201);
    }

    /**
     * Display the specified user.
     *
     * @param  \App\User $user
     *
     * @return User
     */
    public function show(User $user)
    {
        return $user;
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        $data = $request->only(['name', 'email']);

        $user->fill($data);

        $user->save();

        return response()->json([
            'message' => 'Edit successful!',
            'user' => $user
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message' => 'User successfully deleted',
        ]);
    }
}