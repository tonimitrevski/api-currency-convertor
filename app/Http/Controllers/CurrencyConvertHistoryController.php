<?php

namespace App\Http\Controllers;

use App\Http\Requests\CurrencyConvertHistoryRequest;
use App\Http\Resources\CurrencyConvertResource;
use App\Services\CurrencyHistoryRangeConvertService;
use Illuminate\Http\Request;

class CurrencyConvertHistoryController extends Controller
{
    public function __invoke(
        CurrencyConvertHistoryRequest $request,
        CurrencyHistoryRangeConvertService $convertService
    ) {
        $data = $convertService->handle($request->all());

        return new CurrencyConvertResource($data);
    }
}
