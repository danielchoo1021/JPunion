<?php

namespace App\Exports;

use App\Transaction;
use App\TransactionDetail;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Auth, DB;

class CP58Export 
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $user;

	 function __construct($user) {
	        $this->user = $user;
	 }


    public function export()
    {
    	   Excel::selectSheets('Borang_CP58_1.xlsx', function($file) {

         })->load();
        //  \Excel::create('Document', function($excel) {
        //     $excel->sheet('Sheet', function($sheet) {
        //         $sheet->cell('A2', function($cell) {
        //             $cell->setValue('this is the cell value.');
        //         });
        //     });
        // })->download('xlsx');
         Excel::import(new UserImports, 'Borang_CP58_1.xlsx');

         return redirect('/')->with('success', 'All good!');

        // return view('backend.reports.download_order_report', ['transactions'=>$transactions, 'start'=>$this->start, 'end'=>$this->end,
        //                                                       'totalT'=>$totalT, 'totalT2'=>$totalT2],
        //                                                       compact('details', 'details2'));

    }

    public function registerEvents(): array
    {
        return [
            BeforeExport::class => function(BeforeExport $event){
            $event->writer->reopen(new \Maatwebsite\Excel\Files\LocalTemporaryFile(storage_path('filename.xlsx')),Excel::XLSX);

            $event->writer->getSheetByIndex(0);
            $event->getWriter()->getSheetByIndex(0)->setCellValue('A1','Your Value');
            return $event->getWriter()->getSheetByIndex(0);
            }
        ];
    }

    
}
