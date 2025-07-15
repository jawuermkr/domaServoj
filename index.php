<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Calculadora de Consumo por Metros</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="css/estilos.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</head>

<body>
  <div class="container mt-5">
    <div class="row">
      <div class="col-md-12 text-center">
        <h2>CALCULADORA DE CONSUMO</h2>
      </div>
      <div class="col-md-6">
        <div class="card p-5">
          <p>Se debe ingresar el valor total del recibo y los metros que aparecen en la evidencia fotográfica registrada según la fecha de corte del recibo actual. (El sistema hará el cálculo de los metros correspondientes a este corte y los mostrará en pantalla.)</p>
          <?php
          include('conexion.php');

          $resultados = mysqli_query($conexion, "SELECT * FROM registro ORDER BY id DESC LIMIT 1");
          while ($consulta = mysqli_fetch_array($resultados)) {
            $f1 = $consulta['fechaCorteA'];
            $f2 = $consulta['fechaCorteB'];

            include('desconectar.php');
          }
          echo "<p>El último corte fue realizado desde el <b>" . $f1 . "</b> hasta el <b>" . $f2 . "</b></p>";
          ?>
          <hr>
          <form method="post">
            <div class="row d-none">
              <div class="col-md-6">
                <label><small><b>Desde:</b></small></label>
                <input class="form-control" type="date" name="fechaCorteA">
              </div>
              <div class="col-md-6">
                <label><small><b>Hasta:</b></small></label>
                <input class="form-control" type="date" name="fechaCorteB">
              </div>
            </div>

            <label><small><b>Consumo piso 1 (m³):</b></small></label>
            <input class="form-control" type="number" name="pisoUno" required>

            <label><small><b>Consumo piso 2 (m³):</b></small></label>
            <input class="form-control" type="number" name="pisoDos" required>

            <label><small><b>Consumo piso 3 (m³):</b></small></label>
            <input class="form-control" type="number" name="pisoTres" required>

            <label><small><b>Total del recibo (COP):</b></small></label>
            <input class="form-control" type="number" name="valorRecibo" required>

            <input class="form-control btn btn-success" name="calcular" type="submit" value="Generar resultados">
            <input class="form-control btn btn-danger d-none" name="almacenar" type="submit" value="Almacenar datos">
          </form>
        </div>

      </div>
      <div class="col-md-6">

        <?php

        if (isset($_POST["calcular"])) {

          // Se consultan los metros del corte pasado
          include('conexion.php');
          $resultados = mysqli_query($conexion, "SELECT * FROM registro ORDER BY id DESC LIMIT 1");
          while ($consulta = mysqli_fetch_array($resultados)) {
            $p1 = $consulta['pisoUno'];
            $p2 = $consulta['pisoDos'];
            $p3 = $consulta['pisoTres'];
            include('desconectar.php');
          }

          //VALIDAR ERROR DE CALCULO. SUMAR LOS CORTES PASADOS PARA PODER RESTAR EL NUEVO

          // Se reciben los datos del formulario
          $total = floatval($_POST['valorRecibo']);
          $c1 = floatval($_POST['pisoUno']);
          $c2 = floatval($_POST['pisoDos']);
          $c3 = floatval($_POST['pisoTres']);

          // Se saca la diferencia entre los datos almacenados y los nuevos metros ingresados
          $t1 = $c1 - $p1;
          $t2 = $c2 - $p2;
          $t3 = $c3 - $p3;

          $total_consumo = $c1 + $c2 + $c3;

          if ($total_consumo > 0) {
            $valor_metro = $total / $total_consumo;

            $pago1 = round($t1 * $valor_metro, 0);
            $pago2 = round($t2 * $valor_metro, 0);
            $pago3 = round($t3 * $valor_metro, 0);

            echo "<div class='card p-5'>";
            echo "<div class='resultado'>";
            echo "<h3>Resultado:</h3>";
            echo "<p>Costo a pagar para el piso 1 : <strong>$pago1</strong> COP</p>";
            echo "<p>Costo a pagar para el piso 2 : <strong>$pago2</strong> COP</p>";
            echo "<p>Costo a pagar para el piso 3 : <strong>$pago3</strong> COP</p>";
            echo "<hr>";
            echo "<p><em>Valor por metro: " . round($valor_metro, 2) . " COP</em></p>";
            echo "<hr>";
            echo "<h3>Metros totales:</h3>";
            echo "<h4>Piso 1</h4>";
            echo "<p>Consumo (m³) corte pasado <b> " . $p1 . "</b>";
            echo " Consumo (m³) corte actual <b> " . $t1 . "</b></p>";
            echo "<h4>Piso 2</h4>";
            echo "<p>Consumo (m³) corte pasado <b> " . $p2 . "</b>";
            echo " Consumo (m³) corte actual <b> " . $t2 . "</b></p>";
            echo "<h4>Piso 3</h4>";
            echo "<p>Consumo (m³) corte pasado <b> " . $p3 . "</b>";
            echo " Consumo (m³) corte actual <b> " . $t3 . "</b></p>";
            echo "</div>";
          } else {
            echo "<p style='color:red; text-align:center;'>El consumo total debe ser mayor a cero.</p>";
          }
        } else if (isset($_POST['almacenar'])) {

          $fechaCorteA = $_POST['fechaCorteA'];
          $fechaCorteB = $_POST['fechaCorteB'];
          $pisoUno = floatval($_POST['pisoUno']);
          $pisoDos = floatval($_POST['pisoDos']);
          $pisoTres = floatval($_POST['pisoTres']);
          $valorRecibo = floatval($_POST['valorRecibo']);

          include('conexion.php');

          $sql = "INSERT INTO registro (pisoUno, pisoDos, pisoTres, fechaCorteA, fechaCorteB, valorRecibo)
          VALUES ('$pisoUno', '$pisoDos', '$pisoTres', '$fechaCorteA', '$fechaCorteB', '$valorRecibo')";

          if ($conexion->query($sql) === TRUE) {
            echo "<div class='card p-5'>";
            echo "<br/>¡Registro almacenado.";
            echo "</div>";
          }

          include('desconectar.php');
        }
        ?>

      </div>
    </div>
  </div>


</body>

</html>