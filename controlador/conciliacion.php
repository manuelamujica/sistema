<?php
require_once "modelo/cuentabancaria.php";
require 'vendor/autoload.php';
use Smalot\PdfParser\Parser;

class ImportarExtractoController {
    private $modelo;
    
    public function __construct() {
        $this->modelo = new CuentaBancaria();
    }

    public function consultarTodas() {
        // Obtener lista de cuentas para el select
        $cuentas = $this->modelo->consultarTodas();
        
        // Procesar archivo si se envió
        $transactions = [];
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['pdfFile'])) {
            $transactions = $this->procesarPDF($_FILES['pdfFile']);
        }
        
        require_once "vista/importarextracto.php";
    }

    private function procesarPDF($file) {
        $transactions = [];
        
        if ($file['type'] == 'application/pdf') {
            $uploadDir = 'uploads/extractos/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $filePath = $uploadDir . basename($file['name']);
            
            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                $parser = new Parser();
                $pdf = $parser->parseFile($filePath);
                $text = $pdf->getText();
                
                $lines = explode("\n", $text);
                $referencia = '';
                
                foreach ($lines as $line) {
                    if (preg_match('/(\d{2}-\d{2}-\d{4}) - (\d{2}:\d{2})(\d{6,})/', $line, $matches)) {
                        $referencia = $matches[3];
                    }

                    if (preg_match('/(DEBITO|CREDITO)\s+([-+]?[0-9]+(?:,\d{2})?)/', $line, $montoMatch)) {
                        $monto = str_replace(',', '.', $montoMatch[2]);
                        $monto = abs(floatval($monto));
                        $monto = number_format($monto, 2, '.', '');
                        
                        if ($referencia !== '' && $monto !== '') {
                            $transactions[] = [
                                "Referencia" => $referencia, 
                                "Monto" => $monto,
                                "Tipo" => strtoupper($montoMatch[1])
                            ];
                        }
                    }
                }
            }
        }
        
        return $transactions;
    }
}
