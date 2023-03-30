<?php

namespace Lemuel\Exchange;

use Illuminate\Http\Request;

class ExchangeController
{
    public function __invoke(Request $request, ExchangeService $service)
    {
        $validator = validator($request->all(), [
            'amount' => 'required|numeric',
            'currency' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->error(422, 'Unprocessable Entity', $validator->errors()->toArray());
        }

        $request->validate([
            'amount' => 'required|numeric',
            'currency' => 'required|string',
        ]);

        try {
            $amount = $service->getExchangeRate(
                $request->input('amount'),
                $request->input('currency'),
            );

            return $this->success(200, [
                'amount' => $amount,
            ]);
        } catch (UnsupportedCurrency $e) {
            return $this->error(400, $e->getMessage());
        } catch (\Exception $e) {
            return $this->error();
        }
    }

    private function success($code = 200, array $data = [], $extra = [])
    {
        return response()->json([
            'success' => 1,
            'data' => $data,
            'error' => null,
            'errors' => [],
            'extra' => $extra,
        ], $code);
    }

    private function error(int $code = 500, string $error = 'Internal Server Error', array $errors = [], array $trace = []) {
        return response()->json([
            'success' => 0,
            'data' => [],
            'error' => $error,
            'errors' => $errors,
            'trace' => $trace,
        ], $code);
    }
}
