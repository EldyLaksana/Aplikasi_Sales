<?php

namespace App\Exports;

use App\Models\Toko;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TokoExport implements FromCollection, WithHeadings, WithMapping, WithDrawings, WithCustomStartCell, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $filteredToko;
    protected $filters;
    protected $title;

    public function __construct($filters, $title = 'Laporan Sales Mingguan')
    {
        // $this->filters = $filters;
        $this->filters = $filters;
        $this->title = $title;

        // Terapkan filter pada data toko
        $query = Toko::query();

        if (!empty($this->filters['date_range'])) {
            $dates = explode(' to ', $this->filters['date_range']);
            $query->whereBetween('created_at', [$dates[0], $dates[1]]);
        }

        if (!empty($this->filters['sales'])) {
            $query->where('sales_id', $this->filters['sales']);
        } else {
            if (auth()->user()->isAdmin == 0) {
                $query->where('sales_id', auth()->user()->id);
            }
        }

        $this->filteredToko = $query->get();
    }

    public function collection()
    {
        return $this->filteredToko;
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
            'Foto' // Kolom untuk gambar
        ];
    }

    public function map($toko): array
    {
        static $rowNumber = 1;
        return [
            $rowNumber++,
            $toko->user->name,
            $toko->created_at->format('d-m-Y'),
            $toko->toko,
            $toko->alamat,
            $toko->status,
            $toko->alasan,
            '' // Placeholder untuk gambar
        ];
    }

    public function startCell(): string
    {
        return 'A3';
    }

    public function styles(Worksheet $sheet)
    {
        // Menetapkan judul di A1
        $sheet->setCellValue('A1', $this->title);
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ]
        ]);

        // Mengatur tinggi baris
        $sheet->getRowDimension(1)->setRowHeight(30); // Tinggi baris 1 untuk judul
        $sheet->getRowDimension(3)->setRowHeight(20); // Tinggi baris header

        // Mengatur tinggi semua baris data yang dimulai dari baris ke-4
        for ($i = 4; $i <= $this->filteredToko->count() + 3; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(60); // Set tinggi untuk setiap baris data
        }

        // Mengatur lebar kolom
        $sheet->getColumnDimension('A')->setWidth(5);   // Lebar untuk kolom 'No'
        $sheet->getColumnDimension('B')->setWidth(20);  // Lebar untuk kolom 'Nama Sales'
        $sheet->getColumnDimension('C')->setWidth(15);  // Lebar untuk kolom 'Tanggal'
        $sheet->getColumnDimension('D')->setWidth(25);  // Lebar untuk kolom 'Toko'
        $sheet->getColumnDimension('E')->setWidth(40);  // Lebar untuk kolom 'Alamat'
        $sheet->getColumnDimension('F')->setWidth(15);  // Lebar untuk kolom 'Status'
        $sheet->getColumnDimension('G')->setWidth(30);  // Lebar untuk kolom 'Alasan'
        $sheet->getColumnDimension('H')->setWidth(30);  // Lebar untuk kolom 'Foto'

        $sheet->getStyle('B4:H' . ($this->filteredToko->count() + 3))->getAlignment()->setWrapText(true);
        $sheet->getStyle('A4:H' . ($this->filteredToko->count() + 3))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $sheet->getStyle('A3:H' . ($this->filteredToko->count() + 3))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => [
                        'argb' => 'FF000000'
                    ],
                ],
            ],
        ]);

        // $sheet->getStyle('A3:H3')->applyFromArray([
        //     'borders' => [
        //         'bottom' => [
        //             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
        //             'color' => [
        //                 'argb' => 'FF000000'
        //             ],
        //         ],
        //     ],
        // ]);
    }

    public function drawings()
    {
        $drawings = [];

        foreach ($this->filteredToko as $index => $toko) {
            if (!empty($toko->foto)) {
                $drawing = new Drawing();
                $drawing->setName($toko->toko);
                $drawing->setDescription($toko->toko);
                $drawing->setPath(public_path('storage/' . $toko->foto));
                $drawing->setHeight(60);
                $drawing->setWidth(30);
                $drawing->setResizeProportional(false);
                $drawing->setCoordinates('H' . ($index + 4)); // Mulai dari baris ke-4 karena judul dan header
                $drawings[] = $drawing;
            }
        }

        return $drawings;
    }
}
