<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use setasign\Fpdi\Tcpdf\Fpdi;

class GenerarCalibracionPdf extends Command
{
    protected $signature = 'pdf:calibrar {--pagina=1}';
    protected $description = 'Genera la plantilla de Solicitud de Gastos con una cuadrícula numerada, para leer coordenadas exactas';

    public function handle(): int
    {
        $pagina = (int) $this->option('pagina');
        $rutaPlantilla = storage_path('app/plantillas/solicitud_gastos.pdf');

        if (!file_exists($rutaPlantilla)) {
            $this->error("No encuentro el archivo en: {$rutaPlantilla}");
            $this->line('Sube el PDF oficial ahí primero (crea la carpeta storage/app/plantillas/ si no existe).');

            return self::FAILURE;
        }

        // Página carta: 612 x 792 pt. Unidad 'pt' para trabajar en los mismos números que pdfinfo.
        $pdf = new Fpdi('P', 'pt', [612, 792]);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();

        $pdf->setSourceFile($rutaPlantilla);
        $tplId = $pdf->importPage($pagina);
        $pdf->useTemplate($tplId, 0, 0, 612, 792);

        // Cuadrícula cada 20pt, con etiquetas cada 40pt (X arriba, Y a la izquierda)
        $pdf->SetLineWidth(0.3);
        $pdf->SetDrawColor(255, 0, 0);
        $pdf->SetTextColor(255, 0, 0);
        $pdf->SetFont('helvetica', '', 6);

        for ($x = 0; $x <= 612; $x += 20) {
            $pdf->Line($x, 0, $x, 792);
            if ($x % 40 === 0) {
                $pdf->SetXY($x + 1, 2);
                $pdf->Cell(30, 8, (string) $x, 0, 0);
            }
        }

        for ($y = 0; $y <= 792; $y += 20) {
            $pdf->Line(0, $y, 612, $y);
            if ($y % 40 === 0) {
                $pdf->SetXY(1, $y + 1);
                $pdf->Cell(30, 8, (string) $y, 0, 0);
            }
        }

        $rutaSalida = storage_path("app/plantillas/calibracion_pagina{$pagina}.pdf");
        $pdf->Output($rutaSalida, 'F');

        $this->info("Listo: {$rutaSalida}");
        $this->line('Descárgalo y abre — lee la X (arriba) e Y (izquierda) en pt donde debería ir cada dato.');

        return self::SUCCESS;
    }
}
