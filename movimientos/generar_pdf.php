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
    // Función para calcular el número de líneas que ocupará un texto en un MultiCell
    function NbLines($w, $txt)
    {
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb == 0)
            return 1;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
    }
    
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
        // Colores y fuentes para el encabezado
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(40);
        $this->SetDrawColor(150);
        $this->SetLineWidth(.3);
        $this->SetFont('', 'B');

        // Anchos de las columnas
        $w = array(10, 40, 25, 20, 35, 45, 30); 
        for ($i = 0; $i < count($header); $i++) {
            $this->Cell($w[$i], 7, mb_convert_encoding($header[$i], 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', true);
        }
        $this->Ln();

        // Colores y fuentes para las filas de datos
        $this->SetFillColor(245, 245, 245);
        $this->SetTextColor(0);
        $this->SetFont('');

        $fill = false;
        foreach ($data as $row) {
            $x = $this->GetX();
            $y = $this->GetY();
            $line_height = 6; // Altura de línea base para celdas de una sola línea

            // Convertir todos los datos a ISO-8859-1 para FPDF
            $id = mb_convert_encoding($row['id'], 'ISO-8859-1', 'UTF-8');
            $producto_nombre = mb_convert_encoding($row['producto_nombre'], 'ISO-8859-1', 'UTF-8');
            $tipo_movimiento = mb_convert_encoding($row['tipo_movimiento'], 'ISO-8859-1', 'UTF-8');
            $cantidad = mb_convert_encoding($row['cantidad'], 'ISO-8859-1', 'UTF-8');
            $fecha_movimiento = mb_convert_encoding($row['fecha_movimiento'], 'ISO-8859-1', 'UTF-8');
            $observaciones = mb_convert_encoding($row['observaciones'], 'ISO-8859-1', 'UTF-8');
            $usuario_nombre = mb_convert_encoding($row['usuario_nombre'], 'ISO-8859-1', 'UTF-8');

            // Calcular la altura necesaria para la MultiCell de las observaciones
            $nb_obs_lines = $this->NbLines($w[5], $observaciones);
            $h_obs = $line_height * $nb_obs_lines;

            // La altura máxima de la fila será la de las observaciones o la altura de línea base
            $max_row_height = max($line_height, $h_obs);

            // Dibujar celdas antes de las observaciones
            $this->Cell($w[0], $max_row_height, $id, 'LR', 0, 'C', $fill);
            $this->Cell($w[1], $max_row_height, $producto_nombre, 'LR', 0, 'L', $fill);
            $this->Cell($w[2], $max_row_height, $tipo_movimiento, 'LR', 0, 'C', $fill);
            $this->Cell($w[3], $max_row_height, $cantidad, 'LR', 0, 'R', $fill);
            $this->Cell($w[4], $max_row_height, $fecha_movimiento, 'LR', 0, 'C', $fill);

            // Dibujar las observaciones con MultiCell
            $this->SetXY($x + $w[0] + $w[1] + $w[2] + $w[3] + $w[4], $y); // Posicionar para la MultiCell
            $this->MultiCell($w[5], $line_height, $observaciones, 'LR', 'L', $fill);

            // Dibujar la celda restante, ajustando la posición Y
            $this->SetXY($x + $w[0] + $w[1] + $w[2] + $w[3] + $w[4] + $w[5], $y); // Posicionar para la siguiente celda
            $this->Cell($w[6], $max_row_height, $usuario_nombre, 'LR', 0, 'L', $fill); 
            
            $this->Ln($max_row_height); // Avanzar a la siguiente línea por la altura máxima de la fila
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