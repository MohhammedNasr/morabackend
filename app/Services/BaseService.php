<?php

namespace App\Services;

use App\Traits\ApiResponse;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseService
{
    use ApiResponse;

    protected $model;

    /**
     * Default pagination settings
     */
    protected const DEFAULT_PER_PAGE = 10;
    protected const MAX_PER_PAGE = 100;

    /**
     * Paginate query results with optional parameters
     */
    protected function paginate(Builder $query, array $params = []): LengthAwarePaginator
    {
        $perPage = isset($params['per_page']) && is_numeric($params['per_page'])
            ? min((int) $params['per_page'], self::MAX_PER_PAGE)
            : self::DEFAULT_PER_PAGE;

        $page = isset($params['page']) && is_numeric($params['page'])
            ? max(1, (int) $params['page'])
            : 1;

        return $query->paginate(
            perPage: $perPage,
            page: $page
        );
    }

    /**
     * Common method to handle exceptions
     */
    protected function handleException(\Exception $e): JsonResponse
    {
        return $this->errorResponse(
            message: $e->getMessage(),
            hint: 'An unexpected error occurred',
            statusCode: 500
        );
    }

    /**
     * Common method to validate data
     */
    protected function validateData(array $data, array $rules): JsonResponse|true
    {
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return $this->errorResponse(
                message: $validator->errors()->first(),
                hint: 'Please check your input',
                statusCode: 422
            );
        }

        return true;
    }

    /**
     * Determine the type of authenticated user
     * @return string 'store-owner', 'supplier' or 'representative'
     */
    protected function getUserType(): string
    {
        $user = request()->user();
        return $user->role->slug == 'store-owner' ? 'store-owner' : ($user->supplier ? 'supplier' : 'representative');
    }

    /**
     * Send SMS using Twilio
     */
    protected function sendSms(string $to, string $message): bool
    {
        try {
            // temp whats aap
            $params = array(
                'token' => 'c36naak3wqamd4yd',
                'to' => (string) $to,
                'body' => $message
            );
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.ultramsg.com/instance114414/messages/chat",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => http_build_query($params),
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/x-www-form-urlencoded"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            return true;
        } catch (Exception $e) {
            Log::error(var_dump($e));
        }
        return true;


        // comment this
        // $twilio = new \Twilio\Rest\Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));

        // try {
        //     $twilio->messages->create(
        //         $to,
        //         [
        //             'from' => env('TWILIO_PHONE_NUMBER'),
        //             'body' => $message
        //         ]
        //     );
        //     return true;
        // } catch (\Exception $e) {
        //     return false;
        // }
    }
}
