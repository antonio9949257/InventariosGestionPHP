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
        $this->Cell(30, 10, 'Listado de Proveedores', 0, 0, 'C');
        
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
        $w = array(15, 35, 35, 25, 35, 45); 
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
            $nombre = mb_convert_encoding($row['nombre'], 'ISO-8859-1', 'UTF-8');
            $contacto = mb_convert_encoding($row['contacto'], 'ISO-8859-1', 'UTF-8');
            $telefono = mb_convert_encoding($row['telefono'], 'ISO-8859-1', 'UTF-8');
            $email = mb_convert_encoding($row['email'], 'ISO-8859-1', 'UTF-8');
            $direccion = mb_convert_encoding($row['direccion'], 'ISO-8859-1', 'UTF-8');

            // Calcular la altura necesaria para la MultiCell de la dirección
            $this->SetFont('', '', 8); // Usar fuente más pequeña para la dirección
            $nb_dir_lines = $this->NbLines($w[5], $direccion);
            $h_dir = $line_height * $nb_dir_lines;
            $this->SetFont(''); // Restaurar fuente

            // Calcular la altura necesaria para MultiCell de contacto y email (si se usaran)
            // Por ahora, solo reducimos la fuente para contacto y email
            $nb_contact_lines = $this->NbLines($w[2], $contacto);
            $h_contact = $line_height * $nb_contact_lines;
            
            $nb_email_lines = $this->NbLines($w[4], $email);
            $h_email = $line_height * $nb_email_lines;

            // La altura máxima de la fila será la mayor de todas las celdas que puedan envolver texto
            $max_row_height = max($line_height, $h_dir, $h_contact, $h_email);

            // Dibujar celdas antes de la dirección
            $this->Cell($w[0], $max_row_height, $id, 'LR', 0, 'C', $fill);
            $this->Cell($w[1], $max_row_height, $nombre, 'LR', 0, 'L', $fill);

            // Dibujar Contacto, Teléfono, Email con fuente reducida
            $this->SetFont('', '', 8); 
            $this->Cell($w[2], $max_row_height, $contacto, 'LR', 0, 'L', $fill);
            $this->Cell($w[3], $max_row_height, $telefono, 'LR', 0, 'L', $fill);
            $this->Cell($w[4], $max_row_height, $email, 'LR', 0, 'L', $fill);
            $this->SetFont(''); // Restaurar fuente

            // Dibujar la dirección con MultiCell
            $this->SetXY($x + $w[0] + $w[1] + $w[2] + $w[3] + $w[4], $y); // Posicionar para la MultiCell
            $this->SetFont('', '', 8); // Fuente más pequeña para la dirección
            $this->MultiCell($w[5], $line_height, $direccion, 'LR', 'L', $fill);
            $this->SetFont(''); // Restaurar fuente
            
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


$sql = "SELECT id, nombre, contacto, telefono, email, direccion FROM proveedores ORDER BY nombre ASC";
$result = $con->query($sql);
$data = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}
$con->close();


$header = array('ID', 'Nombre', 'Contacto', 'Teléfono', 'Email', 'Dirección');


$pdf->FancyTable($header, $data);

$pdf->Output();
?>