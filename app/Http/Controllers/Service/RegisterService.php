<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Auth;
class RegisterService extends Controller
{
    /**
     * @var user 
     */
    public $user;
    
    /**
     * @var role
     */
    protected $role;

    /**
     * @var request
     */
    protected $request = [];

    /**
     * @var response
     */
    protected $response = [];
   
    /**
     * create Record Request
     * @return user
     */
    public function createRecord($data) {    
        $this->request['password'] = bcrypt($data['password']);
        $this->request['name'] = $data['name'];
        $this->request['role_id'] = $data['role_id'];
        $this->request['email'] = $data['email'];
        
        $counter = User::where('email', $this->request['email'])->get()->count();
        if($counter >0 ){
            $this->response['success'] = false;
            $this->response['message'] = 'User Already exist!';
            $this->response['code'] = 203;
            return $this->response;
        }
        
        try {
            $this->role = Role::find($this->request['role_id']);
            $this->request['role_id'] = $this->role->id;
            #echo '<pre>'; print_r($this->request); exit;
            if($this->role){
                $this->user = User::create($this->request);
                $this->response=  array('success' => true, 'user' => $this->user ,'message' => 'User registered Successfully.');
            } else {
                $this->response['success'] = false;
                $this->response['message'] = 'Request role is not valid!';
                $this->response['code'] = 400;
            }
            
        } catch (Exception $e) {
            $this->response['success'] = false;
            $this->response['message'] = $e->getMessage();
            $this->response['code'] = $e->getCode();            
        }
        return $this->response;
    }

    /**
     * login service
     * 
     */
    public function loginService($input)
    {
        $this->request = ['email' => $input['email'], 'password' => $input['password']];

        $users = User::where('email', $this->request['email'])->get()->count();

        if ($users != 1) {
            $this->response['success'] = false;
            $this->response['code'] = 404;
            $this->response['type'] = 'notFoundexception';      
        }
        if (Auth::attempt($this->request)) {
            $user = Auth::user();
            $this->response['token'] = $user->createToken('MyApp')->accessToken;
            $this->response['name'] = $user->name;
            $this->response['email'] = $user->email;
            $this->response['role_id'] = $user->role_id;
            $this->response['success'] = true;
        }
        return $this->response;
    }
}
