<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserLogin;


class UserController extends Controller
{
    private $response = [];
    public function register(RegisterRequest $request)
    {
        try {

            //** Get requested data by RegisterRequest */
            $register_data = $request->validated();
            $user = new User();
            $user->name = $register_data['name'];
            $user->email = $register_data['email'];
            $user->password = bcrypt($register_data['password']);
            $user->save();

            # Create a token if not found
            $token = $user->revoke_tokens();

            //** Get User Object */
            $success_data = ['student' => (new UserLogin($user))->set_token($token)];
            $this->response = $this->show_success($this->response, 'Registered successfully!', $success_data);
        } catch (\Exception $e) {
            $this->response = $this->show_error($this->response, $e);
        } finally {
            //** Return json Object */
            return response()->json($this->response, 200);
        }

    }

    public
    function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $user = User::where('email', $email)->where('password', $password)->get();
        return UserLogin::collection($user);

    }
}
