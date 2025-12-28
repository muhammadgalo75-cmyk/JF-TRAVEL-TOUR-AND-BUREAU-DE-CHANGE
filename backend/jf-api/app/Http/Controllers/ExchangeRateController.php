<?php

namespace App\Http\Controllers;

use App\Models\CurrencyRate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ExchangeRateController extends Controller
{
    /**
     * Get all exchange rates
     */
    public function index(): JsonResponse
    {
        try {
            Log::info('ExchangeRateController@index: Fetching all exchange rates');

            $rates = CurrencyRate::orderBy('code')->get();

            Log::info('ExchangeRateController@index: Retrieved ' . count($rates) . ' rates');

            return response()->json([
                'success' => true,
                'rates' => $rates,
                'total' => count($rates),
            ], 200);
        } catch (\Exception $e) {
            Log::error('ExchangeRateController@index: Error', [
                'message' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch exchange rates',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get a specific exchange rate by currency code or ID
     */
    public function show($id): JsonResponse
    {
        try {
            Log::info('ExchangeRateController@show: Fetching exchange rate', ['id' => $id]);

            // Try to find by ID first, then by code
            $rate = CurrencyRate::find($id) ?? CurrencyRate::where('code', $id)->first();

            if (!$rate) {
                Log::warning('ExchangeRateController@show: Rate not found', ['id' => $id]);
                return response()->json([
                    'success' => false,
                    'error' => 'Exchange rate not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'rate' => $rate,
            ], 200);
        } catch (\Exception $e) {
            Log::error('ExchangeRateController@show: Error', [
                'id' => $id,
                'message' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch exchange rate',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create a new exchange rate
     */
    public function store(Request $request): JsonResponse
    {
        try {
            Log::info('ExchangeRateController@store: Creating exchange rate', [
                'body' => $request->all(),
            ]);

            $validated = $request->validate([
                'code' => 'required|string|unique:currency_rates,code|size:3',
                'name' => 'required|string',
                'rate' => 'required|numeric|min:0.0001',
                'buy_rate' => 'required|numeric|min:0.0001',
                'sell_rate' => 'required|numeric|min:0.0001',
                'flag' => 'nullable|string',
            ]);

            $rate = CurrencyRate::create($validated);

            Log::info('ExchangeRateController@store: Rate created', [
                'id' => $rate->id,
                'code' => $rate->code,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Exchange rate created successfully',
                'rate' => $rate,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('ExchangeRateController@store: Validation failed', [
                'errors' => $e->errors(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('ExchangeRateController@store: Error', [
                'message' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to create exchange rate',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update an exchange rate
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            Log::info('ExchangeRateController@update: Updating exchange rate', [
                'id' => $id,
                'body' => $request->all(),
            ]);

            $rate = CurrencyRate::find($id) ?? CurrencyRate::where('code', $id)->first();

            if (!$rate) {
                Log::warning('ExchangeRateController@update: Rate not found', ['id' => $id]);
                return response()->json([
                    'success' => false,
                    'error' => 'Exchange rate not found',
                ], 404);
            }

            $validated = $request->validate([
                'code' => 'sometimes|string|unique:currency_rates,code,' . $rate->id . '|size:3',
                'name' => 'sometimes|string',
                'rate' => 'sometimes|numeric|min:0.0001',
                'buy_rate' => 'sometimes|numeric|min:0.0001',
                'sell_rate' => 'sometimes|numeric|min:0.0001',
                'flag' => 'sometimes|nullable|string',
            ]);

            $rate->update($validated);

            Log::info('ExchangeRateController@update: Rate updated', [
                'id' => $id,
                'code' => $rate->code,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Exchange rate updated successfully',
                'rate' => $rate,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('ExchangeRateController@update: Validation failed', [
                'id' => $id,
                'errors' => $e->errors(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('ExchangeRateController@update: Error', [
                'id' => $id,
                'message' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to update exchange rate',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete an exchange rate
     */
    public function destroy($id): JsonResponse
    {
        try {
            Log::info('ExchangeRateController@destroy: Deleting exchange rate', ['id' => $id]);

            $rate = CurrencyRate::find($id) ?? CurrencyRate::where('code', $id)->first();

            if (!$rate) {
                Log::warning('ExchangeRateController@destroy: Rate not found', ['id' => $id]);
                return response()->json([
                    'success' => false,
                    'error' => 'Exchange rate not found',
                ], 404);
            }

            $rate->delete();

            Log::info('ExchangeRateController@destroy: Rate deleted', ['id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Exchange rate deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            Log::error('ExchangeRateController@destroy: Error', [
                'id' => $id,
                'message' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to delete exchange rate',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
