<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ExportRespond implements FromView, WithColumnWidths
{
    protected $list_survey_respond;

    public function __construct($list_survey_respond)
    {
        $this->list_survey_respond = $list_survey_respond;
    }
    public function view(): View
    {
        return view('admin.survey.print', [
            'list_survey_respond' => $this->list_survey_respond
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 20,
            'C' => 30,
            'D' => 20,
            'E' => 20,
            'F' => 20,
            'G' => 20,
            'H' => 20,
            'I' => 20,
            'J' => 20,
        ];
    }
}
