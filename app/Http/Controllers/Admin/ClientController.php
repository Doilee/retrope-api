<?php

namespace App\Http\Controllers\Admin;

use App\Client;
use App\Http\Controllers\Controller;
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
        $this->validate($request, [
            'name' => 'required|string|min:2|max:255|unique:clients',
            'subscription_expires_at' => 'required|date'
        ]);

        $data = $request->only(['name', 'subscription_expires_at']);

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
        $this->validate($request, [
            'name' => 'required|string|min:2|max:255',
            'subscription_expires_at' => 'required|date'
        ]);

        $data = $request->only(['name', 'subscription_expires_at']);

        $client->fill($data);

        $client->save();

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
}
