<?php namespace App\Http\Controllers\Manager;

use App\Client;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ManagerController extends Controller
{
    protected function client()
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