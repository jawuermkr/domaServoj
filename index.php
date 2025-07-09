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
        <h2>Calculadora de consumo</h2>
      </div>
      <div class="col-md-6">
        <div class="card p-5">
          <p>Se debe ingresar el valor total del recibo. Los metros debes ingresarse según las evidencias fotográficas registradas.</p>
          <hr>
          <form method="post">
            <label><small><b>Total del recibo (COP):</b></small></label>
            <input class="form-control" type="number" name="total" required>

            <label><small><b>Consumo piso 1 (m³):</b></small></label>
            <input class="form-control" type="number" name="consumo1" required>

            <label><small><b>Consumo piso 2 (m³):</b></small></label>
            <input class="form-control" type="number" name="consumo2" required>

            <label><small><b>Consumo piso 3 (m³):</b></small></label>
            <input class="form-control" type="number" name="consumo3" required>

            <input class="form-control btn btn-success" type="submit" value="Generar resultados">
          </form>
        </div>

      </div>
      <div class="col-md-6">

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
          $total = floatval($_POST['total']);
          $c1 = floatval($_POST['consumo1']);
          $c2 = floatval($_POST['consumo2']);
          $c3 = floatval($_POST['consumo3']);

          $total_consumo = $c1 + $c2 + $c3;

          if ($total_consumo > 0) {
            $valor_metro = $total / $total_consumo;

            $pago1 = round($c1 * $valor_metro, 0);
            $pago2 = round($c2 * $valor_metro, 0);
            $pago3 = round($c3 * $valor_metro, 0);

            echo "<div class='card p-5'>";
            echo "<div class='resultado'>";
            echo "<h3>Resultado:</h3>";
            echo "<p>Costo a pagar para el piso 1 : <strong>$pago1</strong> COP</p>";
            echo "<p>Costo a pagar para el piso 2 : <strong>$pago2</strong> COP</p>";
            echo "<p>Costo a pagar para el piso 3 : <strong>$pago3</strong> COP</p>";
            echo "<hr>";
            echo "<p><em>Valor por metro: " . round($valor_metro, 2) . " COP</em></p>";
            echo "</div>";
            echo "</div>";
          } else {
            echo "<p style='color:red; text-align:center;'>El consumo total debe ser mayor a cero.</p>";
          }
        }
        ?>

      </div>
    </div>
  </div>


</body>

</html>