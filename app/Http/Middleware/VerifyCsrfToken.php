<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerifyCsrfToken
{
    /**
     * Các URI mà CSRF token sẽ không được kiểm tra.
     *
     * @var array
     */
    protected $except = [
        'admin/category/*',
    ];

    /**
     * Kiểm tra CSRF token cho yêu cầu.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->shouldSkip($request)) {
            return $next($request);
        }

        if ($this->tokensMatch($request)) {
            return $next($request);
        }

        return response()->json(['message' => 'CSRF token mismatch.'], 419);
    }

    /**
     * Xác định liệu yêu cầu có bỏ qua CSRF token không.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function shouldSkip(Request $request)
    {
        // Kiểm tra các route không cần CSRF protection tại đây
        return in_array($request->path(), $this->except);
    }

    /**
     * Kiểm tra CSRF token có hợp lệ không.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function tokensMatch(Request $request)
    {
        $token = $request->session()->token();
        return $request->input('_token') === $token;
    }
}
