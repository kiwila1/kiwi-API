<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Models\Account;
use Illuminate\Http\Request;
use App\Http\Resources\AccountResource;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends Controller
{
    protected $user;
 
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accounts = AccountResource::collection(Account::with('user')->orderBy('created_at', 'desc')->get());
        
        return response()->json(
            $accounts
        , Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         //Validate data
         $data = $request->only('name');
         $validator = Validator::make($data, [
             'name' => 'required|string|unique:accounts'
         ]);
 
         //Send failed response if request is not valid
         if ($validator->fails()) {
             return response()->json(['error' => $validator->messages()], 200);
         }
 
         //Request is valid, create new product
        $account = $this->user->accounts()->create([
             'name' => $request->name,
         ]);
 
         //Product created, return success response
         return response()->json([
            'success' => true,
            'notification' => [
                'message' => $account->name . ' account created successfully',
                'title' => 'Good job',
                'variant' => 'success'
            ],
            'account' =>  $account
        ], Response::HTTP_OK);
 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function show(Account $account)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Account $account)
    {
        //Validate data
        $data = $request->only('name');
        $validator = Validator::make($data, [
            'name' => 'required|string|unique:accounts'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        $data = [
            'name' => $request->name
        ];
        $update = $account->update($data);
        $response = ["success" => $update, 'message' => 'Account updated successfully'];
        return response()->json(
            $response
        , Response::HTTP_OK);
    }

    /**
     * Account list the specified resource from storage.
     *
      * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Account $account)
    {
        $account->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Account deleted successfully'
        ], Response::HTTP_OK);

    }

    /**
     * Account list .
     *
      * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function dataTable(Request $request)
    {
        
        $valueOfSearch = $request->search['value'];
        $pageLength = $request->length;
        $pageStart = $request->start;
        $columns = $request->columns;
        $order = $request->order;
        $orderColumn =   $columns[$order[0]['column']]['name'];
        $orderDirection = $order[0]['dir'];
        $response = [];
        $response['draw'] = $request->draw;
        $accounts = Account::with('user');
        if($valueOfSearch)  $accounts = $accounts->where('name','LIKE','%'.$valueOfSearch.'%');
        $accountLAll = clone $accounts;
        $accounts = $accounts->offset($pageStart)->limit($pageLength)->get();
        $response['recordsTotal'] = $accountLAll->count();
        $response['recordsFiltered'] = $accountLAll->count();
        $response['data'] = $accounts;

        return response()->json(
            $response
        , Response::HTTP_OK);
    }
    
}
