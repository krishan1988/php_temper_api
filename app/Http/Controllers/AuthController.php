<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Business\Domain\UseCases\Authenticator\Authenticator;


class AuthController extends Controller
{
    /**
     * @var Authenticator
     */
    private $authenticator;

    /**
     * AuthController constructor.
     *
     * @param Authenticator $authenticator
     */
    public function __construct(
                    Authenticator $authenticator
    )
    {
        $this->authenticator = $authenticator;
    }


    /**
     * Authenticate By Password.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function authenticateByPassword(Request $request)
    {

        // Get Data
        $arrData = $request->only([
            'user.name',
            'user.password'
        ]);

        // Validation
        $this->validate($request, [
            'user.name'       => 'required|string',
            'user.password' => 'required|string'
        ]);


        // Authenticate
        $token = $this->authenticator->login(
            (string) $arrData['user']['name'],
            (string) $arrData['user']['password']
        );
        $code["code"] =$token;

        return json_encode($code);

    }


    /**
     * Authenticate By Passcode.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function authenticateByPasscode(Request $request) : Response
    {
        // Get Data
        $arrData = $request->only([
            'passenger.id',
            'passenger.otp',
            'device.flag',
            'device.type',
            'device.id',
            'device.token',
            'device.os',
        ]);

        $deviceImei = $request->header('Device-IMEI');

        // Validation
        $this->validate($request, [
            'passenger.id'  => 'required|numeric',
            'passenger.otp' => 'required|numeric',
            'device.flag'   => 'required|numeric',
            'device.type'   => 'required|numeric',
            'device.id'     => 'string|nullable',
            'device.token'  => 'string|nullable',
            'device.os'     => 'string|nullable',
        ]);

        // Authenticate
        $token = $this->authenticator->authenticateByPasscode(
            (int) $arrData['passenger']['id'],
            (int) $arrData['passenger']['otp'],
            (string) $arrData['device']['id']
        );

        // Create Device Information | Device History
        if (DeviceHistoryEntity::FLAG_EXISTING !== $arrData['device']['flag'])
        {
            $deviceHistoryEntity = new DeviceHistoryEntity();

            $deviceHistoryEntity->passengerId = (int)$arrData['passenger']['id'];
            $deviceHistoryEntity->deviceId = (string) $arrData['device']['id'];
            $deviceHistoryEntity->deviceToken = (string) $arrData['device']['token'];
            $deviceHistoryEntity->deviceType = (int) $arrData['device']['type'];
            $deviceHistoryEntity->imei = $deviceImei;
            $deviceHistoryEntity->os = (string) $arrData['device']['os'];

            $this->deviceInfoUpdater->create($deviceHistoryEntity);
        }

        return $this->restHandler->toItem(
            [
                'auth'      => $token['auth'],
                'passenger' => $token['passenger'],
            ]
        )->withMeta(
            ['code' => $token['code'], 'message' => $token['message']]
        )->toJson(
            Response::HTTP_OK
        );
    }


    /**
     * Generate Token
     *
     * @param int                      $passengerId
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function generateTokenByPassengerId(int $passengerId, Request $request) : Response
    {
        // Get Device ID
        $deviceImei = $request->header('Device-IMEI');

        // Authenticate
        $token = $this->authenticator->generateTokenByPassengerId((int) $passengerId, (string) $deviceImei);

        return $this->restHandler->toItem(
            [
                'auth' => $token['auth'],
            ]
        )->withMeta(
            ['code' => $token['code'], 'message' => $token['message']]
        )->toJson(
            Response::HTTP_OK
        );
    }


    /**
     * Refresh token.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function refresh(Request $request) : Response
    {
        // Get Data
        $arrData = $request->only([
            'uuid',
        ]);

        // Validation
        $this->validate($request, [
            'uuid'  => 'required|string',
        ]);

        $token = $request->bearerToken();

        $refreshToken = $this->authenticator->refreshToken((string) $token, (string) $arrData['uuid']);

        return $this->restHandler->toItem(
            [
                'auth' => $refreshToken['auth'],
            ]
        )->withMeta(
            ['code' => $refreshToken['code'], 'message' => $refreshToken['message']]
        )->toJson(
            Response::HTTP_OK
        );
    }


    /**
     * Bounce back the request to check whether it is necessary to refresh the token.
     *
     * @param Request $request
     * @return Response
     */
    public function check(Request $request) : Response
    {
        return $this->restHandler->toJson(Response::HTTP_NO_CONTENT);
    }


}