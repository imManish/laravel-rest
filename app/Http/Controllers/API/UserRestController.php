<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
//rules 
use App\Rules\UsernameValidationRule;
use App\Rules\RoleValidationRule;
use App\Rules\EmailValidationRule;
use App\Rules\PasswordValidationRule;
// services 
use App\Http\Controllers\Service\RegisterService;
use Validator;
use Auth;
use App\Models\Role;

class UserRestController extends Controller
{
    protected $user;

    protected $response;
   
    protected $currentUserRole;

    protected $query;
    /**
     *  This used to return the list of all users basis on roles 
     * 
     */
    public function list() {
        $this->user = Auth::user();
        if(!$this->user){
            return response()->json(['data' => [],
            'error' => ['message' => 'Unauthorised access User'],
            'success' => false, 'code' => Response::HTTP_UNAUTHORIZED, ], Response::HTTP_UNAUTHORIZED);
        } 
        
        $this->currentUserRole = Role::find($this->user->role_id);
        
        switch($this->currentUserRole->slug){
            case 'admin':
                $this->response = $this->getUserAction($this->user->role_id);
                break;
            case 'manager':
                $this->response = $this->getUserAction($this->user->id, $this->currentUserRole->id, $this->currentUserRole->slug);
                break;
            default:
                $this->response = $this->getUserAction($this->user->id, $this->currentUserRole->id, $this->currentUserRole->slug);
                break;    

        }
        
        return response()->json(['data' => $this->response, 'success' => true,
             'message' => 'User list fetch successfully.', 'code' => Response::HTTP_OK,  ], Response::HTTP_OK);

    }

    /**
     * This return User list 
     * @return list
     */
    private function getUserAction($user_id , $current_role_id, $current_role_slug ) {
        $this->query = ($current_role_slug == 'admin') ? User::where('role_id' ,'>=' , $current_role_id) :  User::where('role_id', '>', $current_role_id);
        return $this->query->get();
        
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required'],
            'password' => ['required'],
            
        ]);

        if ($validator->fails()) {
            $return_message = $this->customValidation($validator->errors());
            return response()->json(['error' => array('message' => $return_message),
            'success' => false, 'code' => Response::HTTP_BAD_REQUEST, ], Response::HTTP_BAD_REQUEST);
        }

        $input_elements = $request->all();
        
        try {
            $object = new RegisterService();
            $result = $object->loginService($input_elements);
        } catch (Exception $e) {
            throw new ModelNotFoundException('User not found by ID '.$input_elements['email']);
        }

        if ($result['success'] == true) {
            return response()->json(['data' => $result, 'success' => true,
             'message' => 'User loggedIn successfully.', 'code' => Response::HTTP_OK,  ], Response::HTTP_OK);
        } else {
            return response()->json(['data' => [],
            'error' => ['message' => 'Unauthorised access for email "'.$input_elements['email'].'"'],
            'success' => false, 'code' => Response::HTTP_UNAUTHORIZED, ], Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Register api.
     *
     * @param Request
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator_condition = $this->validateUser($request);
        
        if ($validator_condition['validation'] != false) {
            return response()->json(['error' => array('message' => $validator_condition['message']), 'success' => false,
            'code' => Response::HTTP_BAD_REQUEST, ], Response::HTTP_BAD_REQUEST);
        }
        
        $data = $request->all();
        $result = [];
        try {
            switch ($data['role_id']) {
                case 1:
                    $object = new RegisterService();
                    $this->response = $object->createRecord($data);
                    break;
                default: 
                    $object = new RegisterService();
                    $this->response = $object->createRecord($data);
                    break;
            }
        } catch (Exception $e) {
            $return_message = 'Bad connection request.';
            return response()->json(['error' => array('message' => $return_message),
            'success' => false, 'code' => Response::HTTP_BAD_REQUEST, ], Response::HTTP_BAD_REQUEST);
        }

        if ($this->response['success'] == true) {
            return response()->json(['data' => $this->response['user'], 'success' => true,
             'code' => Response:: HTTP_CREATED, 'message' => $this->response['message'], ], Response:: HTTP_CREATED);
        } else {
            $return_message = 'Unauthorized Access';
            return response()->json(['error' => array('message' => $this->response['message']),
            'success' => false, 'code' => Response::HTTP_UNAUTHORIZED, ], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function validateUser($request){

        $validator = Validator::make($request->all(), [
            'email' => ['required', new EmailValidationRule()],
            'password' => ['required', new PasswordValidationRule()],
            'name' => ['required', new UserNameValidationRule()],
            'role_id' => ['required', new RoleValidationRule()]
        ]);
        if ($validator->fails()) {
            $return_message = $this->customValidation($validator->errors());
            $success['validation'] = true;
            $success['message'] = $return_message;
            return $success;
        }

        $success['validation'] = false;
        $success['message'] = '';

        return $success;

    }
   /**
     * @return validation message
     *
     * @param validation error function
     */
    public function customValidation($validation)
    {
        $errors = $validation;
        $return_message = '';
        $messages = $errors->all();
        $size = sizeof($messages);
        for ($i = 0; $i < $size; ++$i) {
            $return_message = $messages[0];
        }

        return $return_message;
    }

}
