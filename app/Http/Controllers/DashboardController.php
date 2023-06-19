<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Section;
use Illuminate\Support\Js;
use Pest\Support\Arr;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        //=================احصائية نسبة تنفيذ الحالات======================



        $count_all = Invoice::count();
        $count_Invoice1 = Invoice::where('value_status', 1)->count();
        $count_Invoice2 = Invoice::where('value_status', 2)->count();
        $count_Invoice3 = Invoice::where('value_status', 3)->count();

        if ($count_Invoice2 == 0) {
            $nspaInvoice2 = 0;
        } else {
            $nspaInvoice2 = $count_Invoice2 / $count_all * 100;
        }

        if ($count_Invoice1 == 0) {
            $nspaInvoice1 = 0;
        } else {
            $nspaInvoice1 = $count_Invoice1 / $count_all * 100;
        }

        if ($count_Invoice3 == 0) {
            $nspaInvoice3 = 0;
        } else {
            $nspaInvoice3 = $count_Invoice3 / $count_all * 100;
        }


        $chartjs = app()->chartjs
            ->name('barChartTest')
            ->type('bar')
            ->size(['width' => 350, 'height' => 200])
            ->labels(['الفواتير الغير المدفوعة', 'الفواتير المدفوعة', 'الفواتير المدفوعة جزئيا'])
            ->datasets([
                [
                    "label" => "الفواتير الغير المدفوعة",
                    'backgroundColor' => ['#ec5858'],
                    'data' => [$nspaInvoice2]
                ],
                [
                    "label" => "الفواتير المدفوعة",
                    'backgroundColor' => ['#81b214'],
                    'data' => [$nspaInvoice1]
                ],
                [
                    "label" => "الفواتير المدفوعة جزئيا",
                    'backgroundColor' => ['#ff9642'],
                    'data' => [$nspaInvoice3]
                ],


            ])
            ->options([]);

        $sections = Section::pluck('id', 'section_name')->all();
        $sections_values = array();
        foreach (array_values($sections) as $section) {
            array_push($sections_values, Invoice::where('section_id', $section)->count() / $count_all * 100);
        }

        $chartjs_2 = app()->chartjs
            ->name('pieChartTest')
            ->type('pie')
            ->size(['width' => 340, 'height' => 200])
            ->labels(array_keys($sections))
            ->datasets([
                [
                    'backgroundColor' => ['#ec5858', '#81b214', '#ff9642'],
                    'data' => $sections_values
                ]
            ])
            ->options([]);

        return view('dashboard', compact('chartjs', 'chartjs_2'));
    }
}
