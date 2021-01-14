<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Role as UserRole;


class Role
{
    /**
     * @var role
     */
    public $role;

    /**
     * @var step
     */
    protected $step; 

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {        
        #echo '<pre>'; print_r( $request->all()); exit;
        $userRole = $request->user();
        if($userRole){
            $this->role = UserRole::find($userRole->role_id);
            $this->step  = ($this->role->count() > 0 ) ? $next($request) : response()
                                                                       ->json(['error'=> 
                                                                            [
                                                                                'code' => Response::HTTP_UNAUTHORIZED , 
                                                                                'message' => 'UnAuthorized role access.'
                                                                            ]
                                                                            ]
                                                                        ); 
        
        } else {
            $this->step = $next($request);
        }
        
        return $this->step;
    }
}
