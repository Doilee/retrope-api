<?php

namespace App\Http\Controllers\Admin;

use App\Client;
use App\Http\Controllers\Controller;
use App\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function create(Request $request, Client $client)
    {
        $this->validate($request, $this->validationRules());

        $data = $request->only(array_keys($this->validationRules()));

        $subscription = $client->subscriptions()->create($data);

        return response()->json([
            'message' => 'Subscription succesfully stored!',
            'subscription' => $subscription,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Subscription $subscription
     *
     * @return \Illuminate\Http\Response
     * @internal param Client $client
     */
    public function update(Request $request, Subscription $subscription)
    {
        $this->validate($request, $this->validationRules());

        $data = $request->only(array_keys($this->validationRules()));

        $subscription->update($data);

        return response()->json([
            'message' => 'Subscription succesfully updated!',
            'subscription' => $subscription,
        ]);
    }

    /**
     * Remove the specified Subscription from storage.
     *
     * @param Subscription $subscription
     *
     * @return \Illuminate\Http\Response
     * @internal param Client $client
     */
    public function destroy(Subscription $subscription)
    {
        $subscription->delete();

        return response()->json([
            'message' => 'Successfully deleted subscription'
        ]);
    }

    /**
     * Validation Rules for subscription
     *
     * @return array
     */
    private function validationRules()
    {
        return [
            'type' => 'required|string|in:' . implode(',', Subscription::TYPES),
            'expires_at' => 'required|date|after:' . now()->toDateTimeString(),
        ];
    }
}
