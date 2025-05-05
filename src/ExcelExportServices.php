<?php

namespace App;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelExportServices
{
    public function exportServicesToExcel(array $services): string
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // TITRE DU FICHIER
        $sheet->setCellValue('A1', 'Liste des Services');
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Ajouter les EN-TÊTES
        $headers = ['Nom du service', 'Type de service', 'Date Début du service', 'Date Fin du service', 'Status'];
        $column = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($column . '2', $header);
            $column++;
        }

        // Styliser les EN-TÊTES
        $sheet->getStyle('A2:E2')->getFont()->setBold(true);
        $sheet->getStyle('A2:E2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('4CAF50'); 
        $sheet->getStyle('A2:E2')->getFont()->getColor()->setRGB('FFFFFF'); 
        $sheet->getStyle('A2:E2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Remplir les lignes avec les données
        $row = 3;
        foreach ($services as $service) {
            $sheet->setCellValue('A' . $row, $service->getNomService());
            $sheet->setCellValue('B' . $row, $service->getTypeService());
            $sheet->setCellValue('C' . $row, $service->getDateDebutService()?->format('Y-m-d'));
            $sheet->setCellValue('D' . $row, $service->getDateFinService()?->format('Y-m-d'));
            $sheet->setCellValue('E' . $row, $service->getStatusService());

            // Alignement
            $sheet->getStyle('A' . $row . ':E' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $row++;
        }

        // Auto-ajuster les colonnes
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Ajouter des bordures
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $sheet->getStyle('A2:E' . ($row - 1))->applyFromArray($styleArray);

        // Enregistrer dans un fichier temporaire
        $writer = new Xlsx($spreadsheet);
        $filename = sys_get_temp_dir() . '/services_export.xlsx';
        $writer->save($filename);

        return $filename;
    }
}
