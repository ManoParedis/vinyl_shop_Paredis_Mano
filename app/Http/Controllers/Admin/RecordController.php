<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Facades\App\Helpers\Json;
use App\Http\Controllers\Controller;

class RecordController extends Controller {
    public function index(){
        $records = [
            'Queen - Greatest Hits',
            'The Rolling Stones - Sticky Fingers',
            'The Beatles - Abbey Road',
            'The Who - Tommy'
        ];

        Json::dump($records);
        return view('admin.records.index', [
            'records' => $records
        ]);

    }
}
