<?php

require 'vendor/autoload.php';
use Smalot\PdfParser\Parser;


class ImportarExtractoController extends Conexion {
    private $modelo;
    private $errores = [];


    public function __construct() {
        parent::__construct(_DB_HOST_, _DB_NAME_, _DB_USER_, _DB_PASS_); 
      
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
