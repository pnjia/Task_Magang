<?php

namespace App\Exports;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithCustomStartCell, WithEvents
{
    private int $rowNumber = 0;
    private float $totalRevenue = 0;
    private int $totalTransactions = 0;
    private int $totalProducts = 0;

    /**
     * Query data dari transaction_details dengan eager loading.
     * Di-scope berdasarkan tenant_id user yang login.
     */
    public function query()
    {
        $user = Auth::user();

        if (!$user) {
            // no authenticated user (safety for CLI/tests) -> return empty query
            $this->totalRevenue = 0;
            $this->totalTransactions = 0;
            $this->totalProducts = 0;

            return TransactionDetail::query()->whereRaw('0 = 1');
        }

        $tenantId = $user->tenant_id;

        // compute summary numbers scoped to tenant
        $this->totalRevenue = Transaction::where('tenant_id', $tenantId)->sum('total_amount');
        $this->totalTransactions = Transaction::where('tenant_id', $tenantId)->count();
        $this->totalProducts = Product::where('tenant_id', $tenantId)->count();

        return TransactionDetail::query()
            ->with(['transaction.user', 'product.category'])
            ->whereHas('transaction', function ($query) use ($tenantId) {
                $query->where('tenant_id', $tenantId);
            })
            ->orderBy('created_at', 'desc');
    }

    /**
     * Header kolom Excel (Baris 1).
     */
    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Jam',
            'Invoice',
            'Pelanggan',
            'Kasir',
            'Kategori',
            'Nama Produk',
            'Harga Satuan',
            'Qty',
            'Subtotal',
            'Status',
        ];
    }

    /**
     * Mapping setiap row data ke kolom Excel.
     * Handle null values dengan null coalescing.
     */
    public function map($detail): array
    {
        $this->rowNumber++;
        // Use transaction_date stored in DB and convert to Asia/Jakarta when formatting
        $transDt = $detail->transaction?->transaction_date;
        if ($transDt) {
            // Treat stored DB value as UTC and convert to Asia/Jakarta
            $dt = \Carbon\Carbon::parse($transDt, 'UTC')->setTimezone('Asia/Jakarta');
            $date = $dt->format('d/m/Y');
            $time = $dt->format('H:i');
        } else {
            $date = '-';
            $time = '-';
        }

        return [
            $this->rowNumber,
            $date,
            $time,
            $detail->transaction?->invoice_code ?? '-',
            $detail->transaction?->customer_name ?? 'Guest',
            $detail->transaction?->user?->name ?? '-',
            $detail->product?->category?->name ?? '-',
            $detail->product?->name ?? '-',
            // Format harga satuan sebagai Rupiah (contoh: Rp 12.345)
            'Rp ' . number_format($detail->price ?? 0, 0, ',', '.'),
            $detail->quantity,
            // Format subtotal sebagai Rupiah
            'Rp ' . number_format($detail->subtotal ?? 0, 0, ',', '.'),
            strtoupper($detail->transaction?->status ?? '-'),
        ];
    }

    /**
     * Styling header dan border.
     */
    public function styles(Worksheet $sheet): array
    {
        // Hitung jumlah kolom (A sampai L = 12 kolom) — ada kolom tambahan `Jam`
        $lastColumn = 'L';
        $lastRow = $sheet->getHighestRow();

        // Border untuk area transaksi (header + data)
        $sheet->getStyle("A7:{$lastColumn}{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);

        // Style ringkasan bisnis (rows 1..4)
        $sheet->getStyle('A1:A4')->getFont()->setBold(true);

        // Style header baris tabel transaksi (baris 7) - set explicit size so
        // the Ringkasan Bisnis title can match it.
        $sheet->getStyle('A7:' . $lastColumn . '7')->applyFromArray([
            'font' => ['bold' => true, 'size' => 11],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFC6EFCE'],
            ],
        ]);

        return [];
    }

    /**
     * Place the transactions table starting at A7 so we can render a separate
     * business summary table above it.
     */
    public function startCell(): string
    {
        return 'A7';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Title for summary: merge only A1:B1 (user requested)
                $sheet->mergeCells('A1:B1');
                $sheet->setCellValue('A1', 'Ringkasan Bisnis');
                // Match the title font size with the transaksi header (11)
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(11);
                // Center the Ringkasan Bisnis title within the merged A1:B1
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // Summary rows (label / value) - labels in A2:A4
                // Apply same background as transaksi header (green)
                $summaryFill = [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFC6EFCE'],
                ];

                $sheet->setCellValue('A2', 'Total Pendapatan');
                $sheet->setCellValue('B2', 'Rp ' . number_format($this->totalRevenue, 0, ',', '.'));

                $sheet->setCellValue('A3', 'Total Transaksi');
                $sheet->setCellValue('B3', $this->totalTransactions);

                $sheet->setCellValue('A4', 'Total Produk');
                $sheet->setCellValue('B4', $this->totalProducts);

                // Style the summary labels (A2:A4) to match transaksi header background
                $sheet->getStyle('A2:A4')->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => $summaryFill,
                ]);

                // Add thin border around the whole summary block A1:B4
                $sheet->getStyle('A1:B4')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);

                // Place the transaksi title centered across the table (merge A6:L6)
                $sheet->mergeCells('A6:L6');
                $sheet->setCellValue('A6', 'Transaksi');
                $sheet->getStyle('A6')->getFont()->setBold(true);
                $sheet->getStyle('A6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
