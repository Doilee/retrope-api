<?php

namespace App\Http\Controllers\Admin;

use App\Client;
use App\Http\Controllers\Controller;
use App\Notifications\SubscriptionStarted;
use App\Notifications\SubscriptionUpdated;
use App\Subscription;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;

class SubscriptionController extends Controller
{
    public function create(Request $request, Client $client)
    {
        $this->validate($request, $this->validationRules());

        $data = $request->only(array_keys($this->validationRules()));

        $subscription = $client->subscriptions()->create($data);

        $this->notifyManagers($subscription->client, new SubscriptionStarted($subscription));

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

        $this->notifyManagers($subscription->client, new SubscriptionUpdated($subscription));

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

    private function notifyManagers(Client $client, Notification $notification)
    {
        $client->users()->role('managers')->each(function(User $user) use ($notification) {
            $user->notify($notification);
        });
    }
}
