<?php

namespace App\Http\Controllers;

use App\Http\Requests\CurrencyConvertRequest;
use App\Http\Resources\CurrencyConvertResource;
use App\Services\CurrencyConvertService;

class CurrencyConvertController extends Controller
{
    public function __invoke(
        CurrencyConvertRequest $request,
        CurrencyConvertService $convertService
    ) {
        $data = $convertService->handle($request->all());

        return new CurrencyConvertResource($data);
    }
}
