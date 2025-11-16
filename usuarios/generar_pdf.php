<?php
session_start();


if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'gerente') {
    header("Location: ../index.php"); 
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
        $this->Cell(30, 10, 'Listado de Usuarios', 0, 0, 'C');
        
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
        $w = array(20, 80, 80); 
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
            $id = mb_convert_encoding($row['id'], 'ISO-8859-1', 'UTF-8');
            $usuario = mb_convert_encoding($row['usuario'], 'ISO-8859-1', 'UTF-8');
            $rol = mb_convert_encoding($row['rol'], 'ISO-8859-1', 'UTF-8');

            $this->Cell($w[0], 6, $id, 'LR', 0, 'C', $fill);
            $this->Cell($w[1], 6, $usuario, 'LR', 0, 'L', $fill);
            $this->Cell($w[2], 6, $rol, 'LR', 0, 'L', $fill);
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


$sql = "SELECT id, usuario, rol FROM usuarios ORDER BY usuario ASC";
$result = $con->query($sql);
$data = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}
$con->close();


$header = array('ID', 'Usuario', 'Rol');


$pdf->FancyTable($header, $data);

$pdf->Output();
?>