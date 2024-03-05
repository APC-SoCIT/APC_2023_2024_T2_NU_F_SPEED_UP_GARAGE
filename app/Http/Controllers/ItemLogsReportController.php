<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransactionItemLog;

class ItemLogsReportController extends Controller
{
    public function index()
    {
        // Retrieve all item logs from the database with associated product information
        $itemLogs = TransactionItemLog::with('product')->get();
        
        // Pass the retrieved item logs to the view
        return view('item-logs-report', ['itemLogs' => $itemLogs]);
    }
}
