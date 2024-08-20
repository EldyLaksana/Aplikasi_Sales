<?php

namespace App\Exports;

use App\Models\Toko;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class TokoExport implements FromCollection, WithHeadings, WithMapping, WithDrawings
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }
    public function collection()
    {
        $query = Toko::query();

        if (!empty($this->filters['date_range'])) {
            $dates = explode('to', $this->filters['date_range']);
            $query->whereBetween('created_at', [$dates[0], $dates[1]]);
        }

        if (!empty($this->filters['sales'])) {
            $query->where('sales_id', $this->filters['sales']);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Sales',
            'Tanggal',
            'Toko',
            'Alamat',
            'Status',
            'Alasan',
            'foto'
        ];
    }

    public function map($toko): array
    {
        return [
            $toko->id,
            $toko->user->name,
            $toko->created_at->format('d-m-Y'),
            $toko->toko,
            $toko->alamat,
            $toko->status,
            $toko->alasan,
            ''
        ];
    }

    public function drawings()
    {
        $drawings = [];
        $tokos = Toko::all();

        foreach ($tokos as $index => $toko) {
            $drawing = new Drawing();
            $drawing->setName($toko->toko);
            $drawing->setDescription($toko->toko);
            $drawing->setPath(public_path('storage/' . $toko->foto));
            $drawing->setHeight(50);
            $drawing->setResizeProportional(false);
            $drawing->setCoordinates('H' . ($index + 2));
            $drawing->setOffsetX(5);
            $drawing->setOffsetY(5);
            $drawings[] = $drawing;
        }

        return $drawings;
    }
}
