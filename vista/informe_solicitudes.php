<?php

    include '../librerias/fpdf/fpdf.php';
    date_default_timezone_set('America/Caracas');

    class PDF extends FPDF{
        function header(){
            $this->Image('https://www.umss.edu.bo/wp-content/uploads/2019/04/marcaHorizontal-01.png',8,1,53,40,'png');

            $ancho = 190;
            $this->setFont('times', 'B',  8);

            $this->SetY(12);
            $this->Cell($ancho, 10, 'XXX@gmail.com', 0, 0, 'R');
            $this->SetY(16);
            $this->Cell($ancho, 10, utf8_decode('Av. Oquendo y Jordan (Campus Central)'), 0, 0, 'R');
            $this->SetY(20);
            $this->Cell($ancho, 10, utf8_decode('Facultad de Ciencias y Tecnologia'), 0, 0, 'R');
            $this->SetY(24);
            $this->Cell($ancho, 10, utf8_decode('Universidad Mayor de San Simon'), 0, 0, 'R');
        }
        function body(){
            $yy = 40;
            $y = $this->GetY();
            $x = 12;
            $this->AddPage ($this->CurOrientation);

            $this->SetFont('arial', 'BU', 20);

            $this->SetXY(0, $y + $yy);
            $this->Cell(220, 10, utf8_decode('INFORME DE ATENCION'), 0, 1, 'C');
            $this->Cell(200, 20, utf8_decode('DE SOLICITUDES'), 0, 1, 'C');
            $this->SetFont('times', 'IB', 15);
            $y = $this->GetY();
            $this->SetXY(0, $y);
            $this->Cell(220, 10, utf8_decode("FCyT - UMSS"), 0, 1, 'C');

            $this->SetFont('arial', '', 13);
            
            $this->setXY(20,90);
            $this->Cell(50,10,utf8_decode('Realizado Por       : '),0,1,'L');
            $this->setXY(20,97);
            $this->Cell(50,10,utf8_decode('Administrativo de la Facultad de Ciencias y Tecnologia'),0,1,'L');
            $this->setXY(20,104);
            $this->Cell(50,10,utf8_decode('Fecha de Informe : '),0,1,'L');
            

            $this->SetFont('times','IB',14);
            $this->SetXY(20, 130);
            $this->SetTextColor(255,255,255);
            $this->SetFillColor(79,78,77);

            $this->Cell(20, 10, utf8_decode('Nº'), 1, 0, 'C', 1);
            $this->Cell(100, 10, utf8_decode('Reservas'), 1, 0, 'C', 1);
            $this->Cell(50, 10, utf8_decode('Cantidad'), 1, 1, 'C', 1);
            }

            function Footer()
            {
                // Posición: a 1,5 cm del final
                $this->SetY(-15);
                // Arial italic 8
                $this->SetFont('times','I',8);
                // Número de página
                $this->Cell(50,10,utf8_decode('FCyT - UMSS'),0,0,'C');
                $this->Cell(80,10,utf8_decode('Pagina ').$this->PageNo().'/{nb}',0,0,'C');
                $this->Cell(0,10,utf8_decode('@2022 Gerf Software S.R.L'),0,0,'C');
            }
    }

    require '../conexiones/conexion.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();
    $fecha = date("Y-m-d");
    $id_docente = 1;

    $consulta = "SELECT nombre_docente from docentes WHERE id_docente = $id_docente";
    $resultado = $conexion->prepare($consulta);
    $resultado->execute();
    $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
    /*echo "<pre>";
    print_r($data);
    echo $data['0']['count(solicitudes.id_solicitudes)'];
    echo "</pre>";*/
    $nombre_docente = $data['0']['nombre_docente'];

    $fecha = date('m-d-Y h:i a', time());  

    $consulta = "SELECT count(reserva.id_reserva) from reserva";
    $resultado = $conexion->prepare($consulta);
    $resultado->execute();
    $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
    /*echo "<pre>";
    print_r($data);
    echo $data['0']['count(solicitudes.id_solicitudes)'];
    echo "</pre>";*/
    $numero_pendiente = $data['0']['count(reserva.id_reserva)'];

    $consulta = "SELECT count(reservas_atendidas.id_reserva_atendida) from reservas_atendidas WHERE estado='Aceptado'";
    $resultado = $conexion->prepare($consulta);
    $resultado->execute();
    $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
    /*echo "<pre>";
    print_r($data);
    echo $data['0']['count(solicitudes.id_solicitudes)'];
    echo "</pre>";*/
    $numero_aceptada = $data['0']['count(reservas_atendidas.id_reserva_atendida)'];

    $consulta = "SELECT count(reservas_atendidas.id_reserva_atendida) from reservas_atendidas WHERE estado='Rechazado'";
    $resultado = $conexion->prepare($consulta);
    $resultado->execute();
    $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
    /*echo "<pre>";
    print_r($data);
    echo $data['0']['count(solicitudes.id_solicitudes)'];
    echo "</pre>";*/
    $numero_rechazada = $data['0']['count(reservas_atendidas.id_reserva_atendida)'];

    $total = $numero_pendiente + $numero_aceptada + $numero_rechazada;

    $pdf = new PDF();
    $pdf->Body();
    $pdf->AliasNbPages();
    $pdf->SetFont('times','IB',15);
    $pdf->SetTextColor(0,0,0);

    $pdf->setXY(65,90);
    $pdf->Cell(50,10,utf8_decode($nombre_docente),0,0,'L');
    $pdf->setXY(65,104);
    $pdf->Cell(50,10,utf8_decode($fecha),0,1,'L');

    $pdf->SetFont('times','I',15);
    $pdf->SetTextColor(0,0,0);

    $pdf->SetXY(20, 140);
    $pdf->Cell(20, 10, utf8_decode('1'), 1, 0, 'C', 0);
    $pdf->Cell(100, 10, utf8_decode('Pendientes'), 1, 0, 'C', 0);
    $pdf->Cell(50, 10, utf8_decode($numero_pendiente), 1, 1, 'C', 0);
    $pdf->SetXY(20, 150);
    $pdf->Cell(20, 10, utf8_decode('2'), 1, 0, 'C', 0);
    $pdf->Cell(100, 10, utf8_decode('Aceptadas'), 1, 0, 'C', 0);
    $pdf->Cell(50, 10, $numero_aceptada, 1, 1, 'C', 0);
    $pdf->SetXY(20, 160);
    $pdf->Cell(20, 10, utf8_decode('3'), 1, 0, 'C', 0);
    $pdf->Cell(100, 10, utf8_decode('Rechazadas'), 1, 0, 'C', 0);
    $pdf->Cell(50, 10, $numero_rechazada, 1, 1, 'C', 0);
    $pdf->SetXY(20, 170);
    $pdf->Cell(20, 10, utf8_decode('4'), 1, 0, 'C', 0);
    $pdf->Cell(100, 10, utf8_decode('Total'), 1, 0, 'C', 0);
    $pdf->Cell(50, 10, $total, 1, 1, 'C', 0);
    
    
    $pdf->Output();
    
?>