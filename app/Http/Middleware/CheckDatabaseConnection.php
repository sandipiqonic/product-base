<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Schema;

class CheckDatabaseConnection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Schema::hasTable('your_table_name')) {
            // Handle the case when the database is not available
            // You can redirect to a different page or show an error message
            return redirect('/setup-wizard');
        }

        return $next($request);
    }
}
