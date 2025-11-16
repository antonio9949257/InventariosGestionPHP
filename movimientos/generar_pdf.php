<?php
session_start();


if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'gerente') {
    header("Location: ../index.html"); 
    exit();
}

require_once __DIR__ . '/../vendor/autoload.php';
require('db.php');

use Fpdf\Fpdf;

class PDF extends Fpdf
{
    
    function Header()
    {
        
        $this->SetFont('Arial', 'B', 20);
        
        $this->Cell(80);
        
        $this->SetTextColor(40, 40, 40);
        $this->Cell(30, 10, 'Listado de Movimientos', 0, 0, 'C');
        
        $this->SetFont('Arial', '', 10);
        $this->Cell(80, 10, date('d/m/Y'), 0, 1, 'R');
        
        $this->Ln(20);
    }

    
    function Footer()
    {
        
        $this->SetY(-15);
        
        $this->SetFont('Arial', 'I', 8);
        
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    
    function FancyTable($header, $data)
    {
        
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(40);
        $this->SetDrawColor(150);
        $this->SetLineWidth(.3);
        $this->SetFont('', 'B');

        
        $w = array(10, 40, 25, 20, 35, 30, 30); 
        for ($i = 0; $i < count($header); $i++) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
        }
        $this->Ln();

        
        $this->SetFillColor(245, 245, 245);
        $this->SetTextColor(0);
        $this->SetFont('');

        
        $fill = false;
        foreach ($data as $row) {
            $this->Cell($w[0], 6, $row['id'], 'LR', 0, 'C', $fill);
            $this->Cell($w[1], 6, $row['producto_nombre'], 'LR', 0, 'L', $fill);
            $this->Cell($w[2], 6, $row['tipo_movimiento'], 'LR', 0, 'C', $fill);
            $this->Cell($w[3], 6, $row['cantidad'], 'LR', 0, 'R', $fill);
            $this->Cell($w[4], 6, $row['fecha_movimiento'], 'LR', 0, 'C', $fill);
            $this->Cell($w[5], 6, $row['observaciones'], 'LR', 0, 'L', $fill);
            $this->Cell($w[6], 6, $row['usuario_nombre'], 'LR', 0, 'L', $fill); 
            $this->Ln();
            $fill = !$fill;
        }
        
        $this->Cell(array_sum($w), 0, '', 'T');
    }
}


$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);


$sql = "SELECT m.id, p.nombre AS producto_nombre, m.tipo_movimiento, m.cantidad, m.fecha_movimiento, m.observaciones, u.usuario AS usuario_nombre
        FROM movimientos m
        JOIN productos p ON m.id_producto = p.id
        LEFT JOIN usuarios u ON m.id_usuario = u.id
        ORDER BY m.fecha_movimiento DESC";
$result = $con->query($sql);
$data = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}
$con->close();


$header = array('ID', 'Producto', 'Tipo', 'Cantidad', 'Fecha', 'Observaciones', 'Usuario');


$pdf->FancyTable($header, $data);

$pdf->Output();
?>