<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\InvoiceResource;
use App\Models\Invoice;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return InvoiceResource::collection(Invoice::with("user")->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "user_id" => "required",
            "type" => "required|max:1",
            "paid" => "required|numeric|between:0,1",
            "payment_date" => "nullable|date",
            "value"=> "required|numeric|between:1,9999.99"
        ]);

        if($validator->fails()){
            return $this->error("Invalid data in request", 422, $validator->errors()->toArray());
        }

        $created = Invoice::create($validator->validated());

        if(!$created){
            return $this->error("Error creating invoice", 400);
        }

        return $this->response("Invoice created successfully", 200, new InvoiceResource($created->load("user")));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new InvoiceResource(Invoice::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $validator = Validator::make($request->all(), [
            "user_id" => "required",
            "type" => "required|max:1|in:C,P,B",
            "paid" => "required|numeric|between:0,1",
            "value" => "required|numeric|between:1,9999.99",
            "payment_date" => "nullable|date_format:Y-m-d H:i:s"
        ]);

        if($validator->fails()){
            return $this->error("Invalid data in request", 422, $validator->errors()->toArray());
        }

        $validated = $validator->validated();

        $updated = Invoice::find($invoice->id)->update([
            "user_id" => $validated["user_id"],
            "type" => $validated["type"],
            "paid" => $validated["paid"],
            "value" => $validated["value"],
            "payment_date" => $validated["paid"] ? $validated["payment_date"] : null
        ]);

        if(!$updated){
            return $this->error("Error updating invoice", 400);
        }

        return $this->response("Invoice updated successfully", 200, new InvoiceResource($invoice->load("user")));

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
