<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ExportParticipant implements FromView, WithColumnWidths
{
    protected $listWebinarParticipant;

    public function __construct($listWebinarParticipant)
    {
        $this->listWebinarParticipant = $listWebinarParticipant;
    }
    public function view(): View
    {
        return view('admin.webinar.download', [
            'listWebinarParticipant' => $this->listWebinarParticipant
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 30,
            'C' => 30,
            'D' => 60,
            'E' => 30,
            'F' => 30,
            'G' => 30,
        ];
    }
}
