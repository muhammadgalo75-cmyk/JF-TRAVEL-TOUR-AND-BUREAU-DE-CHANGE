/**
 * Exchange Rate API Service Functions
 * Handles all currency exchange rate operations
 */

const API_BASE_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api';

export interface ExchangeRateData {
  id?: number;
  code: string;
  name: string;
  rate: number | string;
  buy_rate: number | string;
  sell_rate: number | string;
  flag?: string;
  created_at?: string;
  updated_at?: string;
}

/**
 * Get all exchange rates
 * @returns Promise with array of exchange rates
 */
export async function getAllExchangeRates(): Promise<{
  success: boolean;
  rates?: ExchangeRateData[];
  error?: string;
}> {
  try {
    console.log('getAllExchangeRates: Fetching all exchange rates');

    const response = await fetch(`${API_BASE_URL}/exchange-rates`, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
      },
    });

    console.log('getAllExchangeRates: Response status', response.status);

    if (!response.ok) {
      const errorText = await response.text();
      console.error('getAllExchangeRates: Failed with status', response.status, errorText);
      return {
        success: false,
        error: `HTTP ${response.status}: ${response.statusText}`,
      };
    }

    const data = await response.json();
    console.log('getAllExchangeRates: Success, received', data.total || 0, 'rates');
    return {
      success: true,
      rates: data.rates || [],
    };
  } catch (error) {
    const errorMsg = error instanceof Error ? error.message : String(error);
    console.error('getAllExchangeRates: Error', errorMsg);
    return {
      success: false,
      error: errorMsg,
    };
  }
}

/**
 * Get a specific exchange rate by ID or code
 * @param id - Exchange rate ID or currency code
 * @returns Promise with exchange rate data
 */
export async function getExchangeRateById(id: number | string): Promise<{
  success: boolean;
  rate?: ExchangeRateData;
  error?: string;
}> {
  try {
    console.log('getExchangeRateById: Fetching exchange rate', { id });

    const response = await fetch(`${API_BASE_URL}/exchange-rates/${id}`, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
      },
    });

    console.log('getExchangeRateById: Response status', response.status);

    if (!response.ok) {
      const errorText = await response.text();
      console.error('getExchangeRateById: Failed with status', response.status, errorText);
      return {
        success: false,
        error: `HTTP ${response.status}: ${response.statusText}`,
      };
    }

    const data = await response.json();
    console.log('getExchangeRateById: Success', data.rate);
    return {
      success: true,
      rate: data.rate,
    };
  } catch (error) {
    const errorMsg = error instanceof Error ? error.message : String(error);
    console.error('getExchangeRateById: Error', errorMsg);
    return {
      success: false,
      error: errorMsg,
    };
  }
}

/**
 * Create a new exchange rate
 * @param rateData - Exchange rate information
 * @returns Promise with created rate
 */
export async function createExchangeRate(rateData: Partial<ExchangeRateData>): Promise<{
  success: boolean;
  rate?: ExchangeRateData;
  error?: string;
}> {
  try {
    console.log('createExchangeRate: Creating exchange rate', rateData);

    // Validate required fields
    if (!rateData.code || !rateData.name || rateData.rate === undefined || rateData.buy_rate === undefined || rateData.sell_rate === undefined) {
      console.error('createExchangeRate: Missing required fields', rateData);
      return {
        success: false,
        error: 'Missing required fields: code, name, rate, buy_rate, sell_rate',
      };
    }

    const response = await fetch(`${API_BASE_URL}/exchange-rates`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(rateData),
    });

    console.log('createExchangeRate: Response status', response.status);

    if (!response.ok) {
      const errorText = await response.text();
      console.error('createExchangeRate: Failed with status', response.status, errorText);
      return {
        success: false,
        error: `HTTP ${response.status}: ${response.statusText}`,
      };
    }

    const data = await response.json();
    console.log('createExchangeRate: Success', data.rate);
    return {
      success: true,
      rate: data.rate,
    };
  } catch (error) {
    const errorMsg = error instanceof Error ? error.message : String(error);
    console.error('createExchangeRate: Error', errorMsg);
    return {
      success: false,
      error: errorMsg,
    };
  }
}

/**
 * Update an exchange rate
 * @param id - Exchange rate ID or code
 * @param rateData - Updated rate data
 * @returns Promise with updated rate
 */
export async function updateExchangeRate(
  id: number | string,
  rateData: Partial<ExchangeRateData>
): Promise<{
  success: boolean;
  rate?: ExchangeRateData;
  error?: string;
}> {
  try {
    console.log('updateExchangeRate: Updating exchange rate', { id, rateData });

    const response = await fetch(`${API_BASE_URL}/exchange-rates/${id}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(rateData),
    });

    console.log('updateExchangeRate: Response status', response.status);

    if (!response.ok) {
      const errorText = await response.text();
      console.error('updateExchangeRate: Failed with status', response.status, errorText);
      return {
        success: false,
        error: `HTTP ${response.status}: ${response.statusText}`,
      };
    }

    const data = await response.json();
    console.log('updateExchangeRate: Success', data.rate);
    return {
      success: true,
      rate: data.rate,
    };
  } catch (error) {
    const errorMsg = error instanceof Error ? error.message : String(error);
    console.error('updateExchangeRate: Error', errorMsg);
    return {
      success: false,
      error: errorMsg,
    };
  }
}

/**
 * Delete an exchange rate
 * @param id - Exchange rate ID or code
 * @returns Promise with success status
 */
export async function deleteExchangeRate(id: number | string): Promise<{
  success: boolean;
  error?: string;
}> {
  try {
    console.log('deleteExchangeRate: Deleting exchange rate', { id });

    const response = await fetch(`${API_BASE_URL}/exchange-rates/${id}`, {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
      },
    });

    console.log('deleteExchangeRate: Response status', response.status);

    if (!response.ok) {
      const errorText = await response.text();
      console.error('deleteExchangeRate: Failed with status', response.status, errorText);
      return {
        success: false,
        error: `HTTP ${response.status}: ${response.statusText}`,
      };
    }

    console.log('deleteExchangeRate: Success');
    return { success: true };
  } catch (error) {
    const errorMsg = error instanceof Error ? error.message : String(error);
    console.error('deleteExchangeRate: Error', errorMsg);
    return {
      success: false,
      error: errorMsg,
    };
  }
}
