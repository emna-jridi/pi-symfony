<?php

namespace App\Service;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ExcelExportService
{
    public function exportUsers(array $users): string
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $headers = ['Nom', 'Prénom', 'Email', 'Téléphone', 'Rôle', 'Statut'];
        $sheet->fromArray([$headers], null, 'A1');

        // Style the headers
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FF3E6C'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ];
        
        $sheet->getStyle('A1:F1')->applyFromArray($headerStyle);

        // Add data
        $row = 2;
        foreach ($users as $user) {
            $sheet->setCellValue('A' . $row, $user->getNomUser());
            $sheet->setCellValue('B' . $row, $user->getPrenomUser());
            $sheet->setCellValue('C' . $row, $user->getEmailUser());
            $sheet->setCellValue('D' . $row, $user->getTelephoneUser());
            $sheet->setCellValue('E' . $row, $user->getRole());
            $sheet->setCellValue('F' . $row, $user->getIsActive() ? 'Actif' : 'Inactif');

            // Style for active/inactive status
            $statusStyle = [
                'font' => [
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $user->getIsActive() ? '00D2D3' : 'FF3E6C'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ];
            $sheet->getStyle('F' . $row)->applyFromArray($statusStyle);

            // Style for the row
            $rowStyle = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ];
            $sheet->getStyle('A' . $row . ':F' . $row)->applyFromArray($rowStyle);

            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Create the Excel file
        $writer = new Xlsx($spreadsheet);
        $fileName = 'liste_employes_' . date('Y-m-d_His') . '.xlsx';
        $filePath = sys_get_temp_dir() . '/' . $fileName;
        $writer->save($filePath);

        return $filePath;
    }
} 