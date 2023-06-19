<?php

namespace App\Http\Controllers;

use App\Models\Invoice_attachments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceAttachmentsController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $imageName = $request->file_name->getClientOriginalName();

        $invoice_number = $request->invoice_number;

        Invoice_attachments::create([
            'file_name'         => $imageName,
            'invoice_number'    => $request->invoice_number,
            'created_by'        => Auth::user()->name,
            'invoice_id'        => $request->invoice_id
        ]);

        $request->file_name->move(public_path('Attachments/' . $invoice_number), $imageName);
        Session()->flash('Add', 'تم اضافة المرفق بنجاح');
        return back();
    }
}
