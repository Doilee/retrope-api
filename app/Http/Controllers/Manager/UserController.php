<?php namespace App\Http\Controllers\Manager;

use App\Client;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->client()->users()->paginate(25);
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->validationRules());

        $fill = array_merge([
            'password' => bcrypt(uniqid())
        ], $request->only(['name', 'email']));

        /* @var User $user */
        $user = $this->client()->users()->create($fill);

        if ($request->has('roles')) {
            $user->assignRole($request->get('roles'));
        }

        return response()->json([
            'message' => 'Successfully stored user',
            'user' => $user
        ], 201);
    }

    public function sendVerificationToUser(User $user)
    {
        $this->validateUser($user);

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'User already verified.',
                'verified' => true,
            ], 422);
        }

        $user->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Verification E-mail has been sent!',
            'user' => $user,
        ]);
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
        $this->validateUser($user);

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
        $this->validateUser($user);

        $this->validate($request, $this->validationRules());

        $data = $request->only(['name', 'email']);

        $user->update($data);

        if ($request->has('roles'))
        {
            $user->assignRole($request->get('roles'));
        }

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
        $this->validateUser($user);

        $user->delete();

        return response()->json([
            'message' => 'User successfully deleted',
        ]);
    }

    private function validateUser(User $user)
    {
        if (Auth::user()->hasRole('manager')) {
            // Check to see if User exists within the client
            $this->client()->users()->where('id', $user->id)->firstOrFail();
        }
    }

    private function validationRules()
    {
        return [
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'roles.*' => 'nullable|string|in:manager,employee',
        ];
    }

    private function client()
    {
        $user = Auth::user();

        if ($user->hasRole('manager')) {
            return Auth::user()->client;
        }

        if ($user->hasRole('admin')) {
            if (!Input::get('client_id')) {
                throw new BadRequestHttpException('Please provide a client_id in your request.');
            }

            return Client::find(Input::get('client_id'));
        }

        throw new \Exception("Permission issue, please contact matthijs@retrope.com");
    }
}