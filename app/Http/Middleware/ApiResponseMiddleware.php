<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiResponseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Force JSON responses for API routes
        $request->headers->set('Accept', 'application/json');

        $response = $next($request);

        // If the response is an HTML error response (like 401/403 redirects)
        // Convert it to JSON
        if ($response->getStatusCode() >= 400 &&
            str_contains($response->headers->get('Content-Type', ''), 'text/html')) {

            $statusCode = $response->getStatusCode();
            $message = $this->getStatusMessage($statusCode);

            return response()->json([
                'message' => $message,
                'status' => $statusCode,
                'error' => true,
            ], $statusCode);
        }

        return $response;
    }

    /**
     * Get appropriate error message for status code
     */
    private function getStatusMessage(int $statusCode): string
    {
        return match ($statusCode) {
            401 => 'Authentication required. Please include a valid Bearer token in the Authorization header.',
            403 => 'Access forbidden. You do not have permission to access this resource.',
            404 => 'Resource not found. Please check the URL and try again.',
            422 => 'Validation failed. Please check your request data.',
            429 => 'Too many requests. Please slow down and try again later.',
            500 => 'Internal server error. Please try again later.',
            default => 'An error occurred while processing your request.'
        };
    }
}
