<?php

namespace App;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelExportContratsClients
{
    public function exportContratsToExcel(array $contrats): string
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // TITRE DU FICHIER
        $sheet->setCellValue('A1', 'Liste des Contrats des clients');
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Ajouter les EN-TÊTES
        $headers = ['Nom Client', 'Email Client', 'Date Début du contrat', 'Date Fin du contrat', 'Montant', 'Status', 'Mode de Paiement'];
        $column = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($column . '2', $header);
            $column++;
        }

        // Styliser les EN-TÊTES
        $sheet->getStyle('A2:G2')->getFont()->setBold(true);
        $sheet->getStyle('A2:G2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('4CAF50');
        $sheet->getStyle('A2:G2')->getFont()->getColor()->setRGB('FFFFFF'); 
        $sheet->getStyle('A2:G2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Remplir les lignes avec les données
        $row = 3;
        foreach ($contrats as $contrat) {
            $sheet->setCellValue('A' . $row, $contrat->getNomClient());
            $sheet->setCellValue('B' . $row, $contrat->getEmailClient());
            $sheet->setCellValue('C' . $row, $contrat->getDateDebutContrat()?->format('Y-m-d'));
            $sheet->setCellValue('D' . $row, $contrat->getDateFinContrat()?->format('Y-m-d'));
            $sheet->setCellValue('E' . $row, $contrat->getMontantContrat());
            $sheet->setCellValue('F' . $row, $contrat->getStatusContrat());
            $sheet->setCellValue('G' . $row, $contrat->getModePaiement()?->value ?? '-');

            // Alignement
            $sheet->getStyle('A' . $row . ':G' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $row++;
        }

        // Auto-ajuster les colonnes
        foreach (range('A', 'G') as $col) {
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
        $sheet->getStyle('A2:G' . ($row - 1))->applyFromArray($styleArray);

        // Enregistrer dans un fichier temporaire
        $writer = new Xlsx($spreadsheet);
        $filename = sys_get_temp_dir() . '/contrats_export.xlsx';
        $writer->save($filename);

        return $filename;
    }
}
