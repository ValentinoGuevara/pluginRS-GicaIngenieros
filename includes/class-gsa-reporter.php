<?php
if (!defined('ABSPATH')) exit;

use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class GSA_Reporter {
    private $model;

    public function __construct() {
        $this->model = new GSA_Reporter_Model();
    }

    // ── GENERAR PDF ──
    public function generar_pdf($tipo, $desde, $hasta) {
        $datos  = $this->obtener_datos($tipo, $desde, $hasta);
        $titulo = $this->obtener_titulo($tipo);
        $total  = $this->model->total_clics($desde, $hasta);
        $fecha  = date('d/m/Y');

        $html = $this->generar_html_pdf($titulo, $datos, $tipo, $total, $desde, $hasta, $fecha);

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('reporte-gica-' . $tipo . '-' . date('Y-m-d') . '.pdf', array('Attachment' => true));
        exit;
    }

    // ── GENERAR EXCEL ──
    public function generar_excel($tipo, $desde, $hasta) {
        $datos  = $this->obtener_datos($tipo, $desde, $hasta);
        $titulo = $this->obtener_titulo($tipo);

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Reporte');

        // Estilos header
        $styleHeader = [
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 12],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1a3a6b']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];

        $styleSubHeader = [
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2b5ba8']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];

        // Título
        $sheet->mergeCells('A1:C1');
        $sheet->setCellValue('A1', 'GICA Social Analytics — ' . $titulo);
        $sheet->getStyle('A1')->applyFromArray($styleHeader);
        $sheet->getRowDimension(1)->setRowHeight(30);

        // Periodo
        $periodo = ($desde && $hasta) ? "Del $desde al $hasta" : 'Todo el tiempo';
        $sheet->mergeCells('A2:C2');
        $sheet->setCellValue('A2', 'Período: ' . $periodo . '   |   Generado: ' . date('d/m/Y H:i'));
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['italic' => true, 'color' => ['rgb' => '5a6a85']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Cabeceras de columnas
        $cabeceras = $this->obtener_cabeceras($tipo);
        $col = 'A';
        foreach ($cabeceras as $cabecera) {
            $sheet->setCellValue($col . '3', $cabecera);
            $sheet->getStyle($col . '3')->applyFromArray($styleSubHeader);
            $col++;
        }

        // Datos
        $total_general = array_sum(array_column((array)$datos, 'total'));
        $fila = 4;
        foreach ($datos as $i => $row) {
            $valores = $this->obtener_valores_fila($tipo, $row, $total_general);
            $col = 'A';
            foreach ($valores as $valor) {
                $sheet->setCellValue($col . $fila, $valor);
                $sheet->getStyle($col . $fila)->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $i % 2 === 0 ? 'f4f6fa' : 'FFFFFF']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $col++;
            }
            $fila++;
        }

        // Autosize columnas
        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="reporte-gica-' . $tipo . '-' . date('Y-m-d') . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    // ── GENERAR CSV ──
    public function generar_csv($tipo, $desde, $hasta) {
        $datos     = $this->obtener_datos($tipo, $desde, $hasta);
        $cabeceras = $this->obtener_cabeceras($tipo);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment;filename="reporte-gica-' . $tipo . '-' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($output, $cabeceras);

        $total_general = array_sum(array_column((array)$datos, 'total'));
        foreach ($datos as $row) {
            fputcsv($output, $this->obtener_valores_fila($tipo, $row, $total_general));
        }

        fclose($output);
        exit;
    }

    // ── HELPERS ──
    private function obtener_datos($tipo, $desde, $hasta) {
        switch ($tipo) {
            case 'general':     return $this->model->reporte_general($desde, $hasta);
            case 'dispositivos': return $this->model->reporte_dispositivos($desde, $hasta);
            case 'horarios':    return $this->model->reporte_horarios($desde, $hasta);
            case 'navegadores': return $this->model->reporte_navegadores($desde, $hasta);
            case 'paginas':     return $this->model->reporte_paginas($desde, $hasta);
            default:            return array();
        }
    }

    private function obtener_titulo($tipo) {
        $titulos = array(
            'general'      => 'Reporte General de Clics',
            'dispositivos' => 'Reporte de Dispositivos',
            'horarios'     => 'Reporte de Horarios',
            'navegadores'  => 'Reporte de Navegadores',
            'paginas'      => 'Reporte de Páginas Activas',
        );
        return $titulos[$tipo] ?? 'Reporte';
    }

    private function obtener_cabeceras($tipo) {
        switch ($tipo) {
            case 'general':      return array('Red Social', 'Total Clics', 'Porcentaje');
            case 'dispositivos': return array('Dispositivo', 'Total Clics', 'Porcentaje');
            case 'horarios':     return array('Hora', 'Total Clics', 'Porcentaje');
            case 'navegadores':  return array('Navegador', 'Total Clics', 'Porcentaje');
            case 'paginas':      return array('Página', 'Total Clics', 'Porcentaje');
            default:             return array();
        }
    }

    private function obtener_valores_fila($tipo, $row, $total_general = 0) {
    $porcentaje = $total_general > 0 ? round(($row->total / $total_general) * 100, 1) . '%' : '0%';
    switch ($tipo) {
        case 'general':
            return array($row->nombre, $row->total, $porcentaje);
        case 'dispositivos':
            return array(ucfirst($row->dispositivo), $row->total, $porcentaje);
        case 'horarios':
            return array($row->hora . ':00', $row->total, $porcentaje);
        case 'navegadores':
            return array($row->navegador, $row->total, $porcentaje);
        case 'paginas':
            return array($row->pagina, $row->total, $porcentaje);
        default:
            return array();
    }
    }

    private function generar_html_pdf($titulo, $datos, $tipo, $total, $desde, $hasta, $fecha) {
        $periodo = ($desde && $hasta) ? "Del $desde al $hasta" : 'Todo el tiempo';
        $filas   = '';
        $i       = 0;
        foreach ($datos as $row) {
            $total_general = array_sum(array_column($datos, 'total'));
            $valores = $this->obtener_valores_fila($tipo, $row, $total_general);
            $bg      = $i % 2 === 0 ? '#f4f6fa' : '#ffffff';
            $filas  .= "<tr style='background:$bg;'>";
            foreach ($valores as $valor) {
                $filas .= "<td style='padding:8px 12px;border-bottom:1px solid #d0d7e3;font-size:12px;'>$valor</td>";
            }
            $filas .= "</tr>";
            $i++;
        }

        $cabeceras     = $this->obtener_cabeceras($tipo);
        $html_cabeceras = '';
        foreach ($cabeceras as $cab) {
            $html_cabeceras .= "<th style='padding:10px 12px;text-align:left;font-size:12px;'>$cab</th>";
        }

        return "
        <!DOCTYPE html>
        <html>
        <head><meta charset='UTF-8'></head>
        <body style='font-family:Helvetica,sans-serif;margin:0;padding:20px;color:#1a2535;'>

            <div style='background:#1a3a6b;padding:20px 24px;border-radius:8px;margin-bottom:20px;'>
                <div style='font-size:22px;font-weight:bold;color:#ffffff;'>GICA Social Analytics</div>
                <div style='font-size:14px;color:rgba(255,255,255,0.7);margin-top:4px;'>$titulo</div>
            </div>

            <div style='display:flex;gap:20px;margin-bottom:20px;'>
                <div style='flex:1;background:#e8eef7;padding:12px 16px;border-radius:6px;border-left:4px solid #1a3a6b;'>
                    <div style='font-size:11px;color:#5a6a85;text-transform:uppercase;font-weight:600;'>Período</div>
                    <div style='font-size:14px;font-weight:700;margin-top:4px;'>$periodo</div>
                </div>
                <div style='flex:1;background:#fff3ea;padding:12px 16px;border-radius:6px;border-left:4px solid #f47920;'>
                    <div style='font-size:11px;color:#5a6a85;text-transform:uppercase;font-weight:600;'>Total clics</div>
                    <div style='font-size:14px;font-weight:700;margin-top:4px;'>$total</div>
                </div>
                <div style='flex:1;background:#e8eef7;padding:12px 16px;border-radius:6px;border-left:4px solid #1a3a6b;'>
                    <div style='font-size:11px;color:#5a6a85;text-transform:uppercase;font-weight:600;'>Generado</div>
                    <div style='font-size:14px;font-weight:700;margin-top:4px;'>$fecha</div>
                </div>
            </div>

            <table style='width:100%;border-collapse:collapse;'>
                <thead>
                    <tr style='background:#2b5ba8;color:#ffffff;'>
                        $html_cabeceras
                    </tr>
                </thead>
                <tbody>
                    $filas
                </tbody>
            </table>

            <div style='margin-top:30px;padding-top:12px;border-top:1px solid #d0d7e3;font-size:11px;color:#5a6a85;text-align:center;'>
                GICA Social Analytics v1.0.0 — Desarrollado por Valentino Guevara para GICA Ingenieros
            </div>

        </body>
        </html>";
    }
    
}