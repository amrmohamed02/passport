<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /** Custom API responses */
    protected $api_success = 1;
    protected $api_fail = -2;
    protected $api_validation_error = -3;


    protected function show_success($response, $message = null, $data = [], $status = null)
        {
            $response['status'] = is_null($status) ? $this->api_success : $status;
            $response['message'] = is_null($message) ? "Success!" : $message;

            /** Data supposed to be a key-value structure example: ['user' => ['first_name' => .., 'last_name' =>..]] */
            foreach ($data as $key => $value) {
                $response[$key] = $value;
            }

            return $response;
        }

    protected function show_error($response, $message = 'Error, something went wrong!', $status = null)
    {
        $response['status'] = is_null($status) ? $this->api_fail : $status;
        $response['message'] = $message instanceof \Exception ? $message->getMessage() : $message;

        return $response;
    }
}
