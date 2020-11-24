<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\EditRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserLogin;
use App\Http\Requests\UserLogin as UserLoginRequest;


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
            $success_data = ['user' => (new UserLogin($user))->set_token($token)];
            $this->response = $this->show_success($this->response, 'Registered successfully!', $success_data);
        } catch (\Exception $e) {
            $this->response = $this->show_error($this->response, $e);
        } finally {
            //** Return json Object */
            return response()->json($this->response, 200);
        }

    }

    public function login(UserLoginRequest $request)
    {
        try {
            $login_data = $request->validated();
            $user = User::where('email', $login_data['email'])->first();
            if ($user) {
                if (Hash::check($login_data['password'], $user->password)) {
                    $token = $user->revoke_tokens();
                    $success_data = ['user' => (new UserLogin($user))->set_token($token)];
                    $this->response = $this->show_success($this->response, 'Login successfully!', $success_data);
                } else {
                    $this->response = $this->show_error($this->response, 'Incorrect Password!');
                }
            } else {
                $this->response = $this->show_error($this->response, 'This Email Not Match With Any Account!');
            }
        } catch (\Exception $e) {
            $this->response = $this->show_error($this->response, $e);
        } finally {
            return response()->json($this->response, 200);
        }

    }

    public function get_profile(Request $request)
    {
        try {
            $user = Auth::guard('user-api')->user();
            $token = $request->bearertoken();
            $success_data = ['user' => (new UserLogin($user))->set_token($token)];
            $this->response = $this->show_success($this->response, 'Login successfully!', $success_data);
        } catch (\Exception $e) {
            $this->response = $this->show_error($this->response, $e);
        } finally {
            return response()->json($this->response, 200);
        }
    }


    public function edit_profile(EditRequest $request)
    {
        try {
            $user = Auth::guard('user-api')->user();
            $token = $request->bearertoken();
            $register_data = $request->validated();
            $user->name = $register_data['name'] ?? $user->name;
            $user->email = $register_data['email'] ?? $user->email;
            $user->password = isset($register_data['password']) ? bcrypt($register_data['password']) : $user->password;
            $user->save();
            $success_data = ['user' => (new UserLogin($user))->set_token($token)];
            $this->response = $this->show_success($this->response, 'Edit successfully!', $success_data);
        } catch (\Exception $e) {
            $this->response = $this->show_error($this->response, $e);
        } finally {
            return response()->json($this->response, 200);
        }
    }
}
