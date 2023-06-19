<?php

namespace App\Http\Controllers;

use App\Exports\InvoiceExport;
use App\Models\Invoice;
use App\Models\Invoice_attachments;
use App\Models\Invoices_details;
use App\Models\Section;
use App\Models\User;
use App\Notifications\addInvoiceNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    // get all invoices
    public function index()
    {
        $invoices = Invoice::all();
        return view('invoices.invoices', compact('invoices'));
    }

    public function paid()
    {
        $invoices = Invoice::where('value_status', 1)->get();
        return view('invoices.paid_invoices', compact('invoices'));
    }
    public function unpaid()
    {
        $invoices = Invoice::where('value_status', 2)->get();
        return view('invoices.unpaid_invoices', compact('invoices'));
    }
    public function partial_paid()
    {
        $invoices = Invoice::where('value_status', 3)->get();
        return view('invoices.partialPaid_invoices', compact('invoices'));
    }
    public function archived_invoices()
    {
        $invoices = Invoice::onlyTrashed()->get();
        return view('invoices.archived_invoices', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = Section::all();

        return view('invoices.add-invoices', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Invoice::create([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'Status' => 'غير مدفوعة',
            'value_status' => 2,
            'note' => $request->note,
        ]);

        $invoice_id = Invoice::latest()->first()->id;

        Invoices_details::create([
            'invoice_id' => $invoice_id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'Section' => $request->Section,
            'Status' => 'غير مدفوعة',
            'value_status' => 2,
            'note' => $request->note,
            'user' => (Auth::user()->name)
        ]);

        if ($request->hasFile('pic')) {

            $imageName = $request->pic->getClientOriginalName();

            $invoice_number = $request->invoice_number;

            Invoice_attachments::create([
                'file_name'         => $imageName,
                'invoice_number'    => $request->invoice_number,
                'created_by'        => Auth::user()->name,
                'invoice_id'        => $invoice_id
            ]);

            $request->pic->move(public_path('Attachments/' . $invoice_number), $imageName);
        }

        $admin = User::where('roles_name', 'LIKE', '%owner%')->get();
        Notification::send($admin, new addInvoiceNotification($invoice_id));

        session()->flash('Add', 'تم اضافة الفاتورة بنجاح');
        return redirect('/invoices/create');
    }

    /**
     * Display the specified resource.
     */
    public function show($invoice_id)
    {
        $invoices = Invoice::where('id', $invoice_id)->first();
        return view('invoices.status_update', compact('invoices'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($invoice_id)
    {
        $sections = Section::all();
        $invoice = Invoice::where('id', $invoice_id)->first();
        return view('invoices.edit-invoice', compact('invoice', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $invoice)
    {

        // change attachments folder name
        $old_invoice_number = Invoice_attachments::where('invoice_id', $invoice)->pluck('invoice_number')->first();

        Storage::disk('Attachments_path')->move($old_invoice_number, $request->invoice_number);

        Invoice_attachments::where('invoice_id', $invoice)->update([
            'invoice_number'  => $request->invoice_number
        ]);

        Invoice::where('id', $invoice)->update([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'Status' => 'غير مدفوعة',
            'value_status' => 2,
            'note' => $request->note,
        ]);

        Invoices_details::where('invoice_id', $invoice)->update([
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'Section' => $request->Section,
            'Status' => 'غير مدفوعة',
            'value_status' => 2,
            'note' => $request->note,
            'user' => (Auth::user()->name)
        ]);

        session()->flash('update', 'تم تعديل الفاتورة بنجاح');
        return redirect('/invoices');
    }

    /**
     * Remove the specified resource from storage.
     */

    // force delete invoices

    public function destroy($invoice_id)
    {
        $invoice = Invoice::where('id', $invoice_id)->first();

        // if invoice id doesn't exist check it in the archive invoices
        if ($invoice == null) {
            $invoice = Invoice::withTrashed()->where('id', $invoice_id)->first();
        }

        // delete invoice folder
        Storage::disk('Attachments_path')->deleteDirectory($invoice->invoice_number);

        $invoice->forceDelete();

        session()->flash('delete', 'تم حذف الفاتورة بنجاح');

        return redirect('/invoices');
    }

    public function archive($invoice)
    {
        Invoice::where('id', $invoice)->first()->delete();
        session()->flash('delete', 'تم أرشفة الفاتورة بنجاح');
        return redirect('/invoices');
    }

    // restore invoices from archive

    public function restore_invoice($invoice)
    {
        Invoice::withTrashed()->where('id', $invoice)->restore();
        session()->flash('add', 'تم الغاء أرشفة الفاتورة بنجاح');
        $invoices = Invoice::all();
        return view('invoices.invoices', compact('invoices'));
    }

    public function print($invoice_id)
    {
        $invoice = Invoice::where('id', $invoice_id)->first();
        return view('invoices.print_invoice', compact('invoice'));
    }

    public function getproducts(Request $request)
    {
        $products = DB::table("products")->where("section_id", $request->id)->pluck("Product_name", "id");
        return json_encode($products);
    }

    public function status_Update($id, Request $request)
    {
        $invoices = Invoice::findOrFail($id);

        if ($request->Status === 'مدفوعة') {

            $invoices->update([
                'value_status' => 1,
                'status' => $request->Status,
                'payment_Date' => $request->Payment_Date,
            ]);

            Invoices_details::create([
                'invoice_id' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'section' => $request->Section,
                'status' => $request->Status,
                'value_status' => 1,
                'note' => $request->note,
                'payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        } else {
            $invoices->update([
                'value_status' => 3,
                'status' => $request->Status,
                'payment_Date' => $request->Payment_Date,
            ]);
            Invoices_details::create([
                'invoice_id' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'section' => $request->Section,
                'status' => $request->Status,
                'value_status' => 3,
                'note' => $request->note,
                'payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        }
        session()->flash('Status_Update');
        return redirect('/invoices');
    }

    // export invoices as excel

    public function export()
    {
        return Excel::download(new InvoiceExport, 'invoices.xlsx');
    }

    public function markNotificationsAsRead(Request $request)
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back();
    }
}
