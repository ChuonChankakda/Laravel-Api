<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\V1\DeleteCustomerRequest;
use App\Models\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Filters\V1\CustomersFilter;
use App\Http\Requests\V1\StoreCustomerRequest;
use App\Http\Requests\V1\UpdateCustomerRequest;
use App\Http\Resources\V1\CustomerResource;
use App\Http\Resources\V1\CustomerCollection;


class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $filter = new CustomersFilter();
        $filterItems = $filter -> transform($request);

        $includeInvoices = $request->query("includeInvoices");

        $customers = Customer::where($filterItems);

        if($includeInvoices){
            $customers->with("invoices");
        }

        return new CustomerCollection($customers->paginate()->appends($request->query()));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        //
        return new CustomerResource(Customer::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
        $includeInvoices = request()->query("includeInvoices");

        if( $includeInvoices ){
            $customer->loadMissing("invoices");
        }

        return new CustomerResource($customer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        //
        $customer -> update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteCustomerRequest $request, Customer $customer)
    {
        //
        $customer->delete();
    }
}
