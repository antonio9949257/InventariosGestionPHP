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
        $this->Cell(30, 10, 'Listado de Productos', 0, 0, 'C');
        
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

        
        $w = array(10, 30, 25, 20, 20, 15, 15, 25, 25); 
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
            $this->Cell($w[1], 6, $row['nombre'], 'LR', 0, 'L', $fill);
            $this->Cell($w[2], 6, $row['descripcion'], 'LR', 0, 'L', $fill);
            $this->Cell($w[3], 6, $row['precio_compra'], 'LR', 0, 'R', $fill);
            $this->Cell($w[4], 6, $row['precio_venta'], 'LR', 0, 'R', $fill);
            $this->Cell($w[5], 6, $row['stock'], 'LR', 0, 'C', $fill);
            $this->Cell($w[6], 6, $row['stock_minimo'], 'LR', 0, 'C', $fill); 
            $this->Cell($w[7], 6, $row['categoria_nombre'], 'LR', 0, 'L', $fill);
            $this->Cell($w[8], 6, $row['proveedor_nombre'], 'LR', 0, 'L', $fill);
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


$sql = "SELECT p.id, p.nombre, p.descripcion, p.precio_compra, p.precio_venta, p.stock, p.stock_minimo, c.nombre AS categoria_nombre, pr.nombre AS proveedor_nombre 
        FROM productos p
        LEFT JOIN categorias c ON p.id_categoria = c.id
        LEFT JOIN proveedores pr ON p.id_proveedor = pr.id
        ORDER BY p.nombre ASC";
$result = $con->query($sql);
$data = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}
$con->close();


$header = array('ID', 'Producto', 'Descripción', 'P. Compra', 'P. Venta', 'Stock', 'Stock Min.', 'Categoría', 'Proveedor');


$pdf->FancyTable($header, $data);

$pdf->Output();
?>