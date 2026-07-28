<?php

namespace App\Services;

use App\Models\Actividad;
use App\Models\ConfiguracionSistema;
use setasign\Fpdi\Tcpdf\Fpdi;

class SolicitudGastosPdfService
{
    /**
     * Coordenadas calibradas visualmente (Fase 12). Unidad: puntos (pt),
     * origen arriba-izquierda, página carta 612x792.
     */
    private const COORDS = [
        'barrio'         => ['x' => 82, 'y' => 40,  'size' => 11],
        'solicitante'    => ['x' => 35,  'y' => 90, 'size' => 11],
        'fecha'          => ['x' => 365, 'y' => 92, 'size' => 11],
        'fecha_3' => ['x' => 365, 'y' => 553, 'size' => 8],
        'cobrador'       => ['x' => 35,  'y' => 130, 'size' => 11],
        'cobrador_firma' => ['x' => 34, 'y' => 553, 'size' => 8],
        'checkbox_presupuesto' => ['x' => 128, 'y' => 224, 'size' => 12],
        'proposito'      => ['x' => 165, 'y' => 250, 'size' => 10],
        'organizacion'   => ['x' => 85,  'y' => 345, 'size' => 10],
        'monto_total'    => ['x' => 250, 'y' => 345, 'size' => 10],
    ];

    public function generar(Actividad $actividad): string
    {
        $pdf = new Fpdi('P', 'pt', [612, 792]);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Sin esto, TCPDF aplica márgenes por defecto (~27mm) y todas las coordenadas
        // "absolutas" terminan desplazadas — esta fue la causa del desalineamiento.
        $pdf->SetMargins(0, 0, 0);
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->setCellPaddings(0, 0, 0, 0);

        $pdf->AddPage();

        $pdf->setSourceFile(storage_path('app/plantillas/solicitud_gastos.pdf'));
        $tplId = $pdf->importPage(1);
        $pdf->useTemplate($tplId, 0, 0, 612, 792);

        $pdf->SetTextColor(38, 40, 43);

        $this->escribir($pdf, self::COORDS['barrio'], ConfiguracionSistema::obtener()->nombre_barrio);
        $this->escribir($pdf, self::COORDS['solicitante'], $actividad->nombre_solicitante ?? '');
        $this->escribir($pdf, self::COORDS['fecha'], now()->format('d/m/Y'));
        $this->escribir($pdf, self::COORDS['fecha_3'], now()->format('d/m/Y'));
        $this->escribir($pdf, self::COORDS['cobrador'], $actividad->nombre_cobrador ?? '');
        $this->escribir($pdf, self::COORDS['cobrador_firma'], $actividad->nombre_cobrador ?? '');

        // Marca automática del checkbox "Gasto de presupuesto" — todo lo que pasa
        // por este sistema es justamente eso, nunca "ofrenda de ayuno".
        $chk = self::COORDS['checkbox_presupuesto'];
        $pdf->SetFont('helvetica', 'B', $chk['size']);
        $pdf->SetXY($chk['x'], $chk['y']);
        $pdf->Cell(0, 0, 'X', 0, 0);

        // Propósito: nombre de la actividad (el ancho limita a 230pt, se ajusta solo si es largo)
        $pdf->SetFont('helvetica', '', self::COORDS['proposito']['size']);
        $pdf->SetXY(self::COORDS['proposito']['x'], self::COORDS['proposito']['y']);
        $pdf->MultiCell(230, 12, $actividad->nombre, 0, 'L');

        // Fila única: Categoría = organización solicitante, Cantidad = monto total del desglose
        $this->escribir($pdf, self::COORDS['organizacion'], $actividad->organizacion->nombre);
        $this->escribir($pdf, self::COORDS['monto_total'], number_format((float) $actividad->montoTotalSolicitado(), 2));

        return $pdf->Output('', 'S');
    }

    private function escribir(Fpdi $pdf, array $coord, string $texto): void
    {
        $pdf->SetFont('helvetica', '', $coord['size']);
        $pdf->SetXY($coord['x'], $coord['y']);
        $pdf->Cell(0, 0, $texto, 0, 0);
    }
}
