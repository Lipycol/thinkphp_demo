<?php

namespace app\http\middleware;

class Check
{
    public function handle($request, \Closure $next)
    {
        if ($request->module() == 'index') {
            return redirect('student/index/index');
        }
        return $next($request);
    }
}
