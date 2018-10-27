<?php

namespace App\Http\Controllers\Admin;

use App\Client;
use App\Http\Controllers\Controller;
use App\Subscription;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Client::paginate(25);
    }

    /**
     * Store a newly created client in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->validationRules());

        $data = $request->only(array_keys($this->validationRules()));

        $client = new Client($data);

        $client->save();

        return response()->json([
            'message' => 'Successfully made client',
            'client' => $client
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Client $client
     *
     * @return Client
     */
    public function show(Client $client)
    {
        $client->load('users', 'subscriptions');

        return $client;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        $this->validate($request, $this->validationRules());

        $data = $request->only(array_keys($this->validationRules()));

        $client->update($data);

        return response()->json([
            'message' => 'Edit successful!',
            'user' => $client
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        $client->delete();

        return response()->json([
            'message' => 'Successfully deleted client'
        ]);
    }

    private function validationRules()
    {
        return [
            'name' => 'required|string|min:2|max:255',
        ];
    }
}
