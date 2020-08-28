<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $customers = Customer::query();

        if (!empty($request->query())) {
            foreach ($request->query() as $key => $value) {
                if ($key == 'page') {
                    continue;
                } elseif ($key == 'date') {
                    $customers->whereDate('created_at', '>=', $value);
                } else {
                    $customers->where($key, $value);
                }
            }
        }

        return $customers->orderBy('created_at', 'desc')->paginate($request->query('per_page', 100));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        try {
            $create = Customer::create($data);
            $return = $create
                ? array(
                    'error' => false,
                    'message' => 'Successfully created customer ',
                    'data'    => $create,
                )
                : array(
                    'error' => true,
                    'message' => 'Failed to create customer ',
                );
        } catch (\Exception $e) {
            $return = array(
                'error' => true,
                'message' => $e->getMessage(),
            );
        }

        return $return;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Customer::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        try {
            $customer = Customer::find($id);
            $update = $customer->update($data);
            $return = $update
                ? array(
                    'error' => false,
                    'message' => 'Successfully updated customer ',
                    'data'    => $customer,
                )
                : array(
                    'error' => true,
                    'message' => 'Failed to update customer ',
                );
        } catch (\Exception $e) {
            $return = array(
                'error' => true,
                'message' => $e->getMessage(),
            );
        }

        return $return;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $delete = Customer::find($id)->delete();
            $return = $delete
                ? array(
                    'error' => false,
                    'message' => 'Successfully deleted customer ',
                )
                : array(
                    'error' => true,
                    'message' => 'Failed to delete customer ',
                );
        } catch (\Exception $e) {
            $return = array(
                'error' => true,
                'message' => $e->getMessage(),
            );
        }

        return $return;
    }
}
