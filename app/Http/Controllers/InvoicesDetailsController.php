<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Invoice_attachments;
use App\Models\Invoices_details;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InvoicesDetailsController extends Controller
{

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // mark notification as read if exist 

        if (Auth::user()->unreadNotifications->count() > 0) {
            $notifications =  Auth::user()->unreadNotifications;
            foreach ($notifications as $notification) {
                if ($notification->data['id'] == $id) {
                    $notification->markAsRead();
                    break;
                }
            }
        }


        $invoices = Invoice::where('id', $id)->first();
        $details  = Invoices_details::where('invoice_id', $id)->get();
        $attachments  = Invoice_attachments::where('invoice_id', $id)->get();

        return view('invoices.details_invoice', compact('invoices', 'details', 'attachments'));
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($attachments_id)
    {

        $file = Invoice_attachments::where('id', $attachments_id)->first();

        Storage::disk('Attachments_path')->delete($file->invoice_number . '/' . $file->file_name);

        $file->delete();

        if (empty(Storage::disk('Attachments_path')->files($file->invoice_number))) {
            Storage::disk('Attachments_path')->deleteDirectory($file->invoice_number);
        }

        session()->flash('delete', 'تم حذف المرفق بنجاح');

        return back();
    }
}
