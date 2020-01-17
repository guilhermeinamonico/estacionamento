<?php
// Verificar se foi enviando dados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_entradaSaida = (isset($_POST["id_entradaSaida"]) && $_POST["id_entradaSaida"] != null) ? $_POST["id_entradaSaida"] : "";
    $horaEntrada = (isset($_POST["horaEntrada"]) && $_POST["horaEntrada"] != null) ? $_POST["horaEntrada"] : "";
    $placa = (isset($_POST["placa"]) && $_POST["placa"] != null) ? $_POST["placa"] : "";
    $idveiculo = (isset($_POST["idveiculo"]) && $_POST["idveiculo"] != null) ? $_POST["idveiculo"] : "";
    $horaSaida = (isset($_POST["horaSaida"]) && $_POST["horaSaida"] != null) ? $_POST["horaSaida"] : "";
    $hora = (isset($_POST["horaEstacionado"]) && $_POST["horaEstacionado"] != null) ? $_POST["horaEstacionado"] : "";
    


    
    


    

} else if (!isset($id_entradaSaida)) {
    // Se não se não foi setado nenhum valor para variável $id_entradaSaida
    $id_entradaSaida = (isset($_GET["id_entradaSaida"]) && $_GET["id_entradaSaida"] != null) ? $_GET["id_entradaSaida"] : "";
    $horaEntrada = NULL;
    $placa = NULL;
    $idveiculo = NULL;
    $horaSaida = NULL;
    $hora=NULL;
    
}

	//conexão
	try{
		$conexao = new PDO("mysql:host=localhost; dbname=estacionamento" , "root","");
		$conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$conexao->exec("set names utf8");
	} catch(PDOException $erro){
		echo "Erro na conexão: ". $erro->getMessage();
	}


	

	//insert
	if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save" && $horaEntrada != "") {
    try {
        if ($id_entradaSaida != "") {
            $stmt = $conexao->prepare("UPDATE tb_entradasaida SET idveiculo=?, horaEntrada=?, horaSaida=?, hora=?, preco=? WHERE id_entradaSaida = ?");
            $stmt->bindParam(5, $id_entradaSaida);}else{

        $verifica_placa = $conexao->query("SELECT * FROM tb_veiculo WHERE idveiculo = '$idveiculo'");
        if($verifica_placa->rowCount() ==0){
            echo "Veiculo não existe";
        }else{

        $stmt = $conexao->prepare("INSERT INTO tb_entradasaida (horaEntrada, idveiculo, horaSaida, (horaSaida - horaEntrada), hora*0.00003) VALUES (?, ?, ?, ?,?)");
}
        }
        $stmt->bindParam(1, $horaEntrada);
        $stmt->bindParam(2, $idveiculo);
        $stmt->bindParam(3, $horaSaida);
        $stmt->bindParam(4, $hora);
        $stmt->bindParam(5,$preco);
        
         
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo "Dados cadastrados com sucesso!";
                $id_entradaSaida = null;
                $horaEntrada = null;
                $placa = null;
                $horaSaida = null;
                $hora= null;
               
            } else {
                echo "Erro ao tentar efetivar cadastro";
            }
        } else {
               throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: " . $erro->getMessage();
    }

}

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "upd" && $id_entradaSaida != "") {
    try {
        $stmt = $conexao->prepare("SELECT * FROM tb_entradasaida WHERE id_entradaSaida = ?");
        $stmt->bindParam(1, $idveiculo, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $rs = $stmt->fetch(PDO::FETCH_OBJ);
            $id_entradaSaida= $rs->id_entradaSaida;
            $idveiculo = $rs->idveiculo;
            $placa = $rs->placa;
            $horaEntrada = $rs->horaEntrada;
            $horaSaida = $rs->horaSaida;
            
            
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Cadastro de Veiculo</title>
        <link href='https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB' crossorigin='anonymous' />
        <link href='https://use.fontawesome.com/releases/v5.1.0/css/all.css' rel='stylesheet' integrity='sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt' crossorigin='anonymous' />
        <script src='https://code.jquery.com/jquery-3.3.1.slim.min.js' integrity='sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo' crossorigin='anonymous'></script>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js' integrity='sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49' crossorigin='anonymous'></script>
        <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js' integrity='sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T' crossorigin='anonymous'></script>
    </head>
    <body>
        <div class="header clearfix">
        <h1>Estacionamento</h1>
        <hr>
    </div>
    <div class="container">
        
        <form action="?act=save" method="POST" name="form1"  >
        <div class="jumbotron">
          <h1>Estacionar Veiculo</h1>

          <hr>
          <input type="hidden" name="id_entradaSaida" <?php
            // Preenche o id no campo id com um valor "value"
            if (isset($id_entradaSaida) && $id_entradaSaida != null || $id_entradaSaida != "") {
                echo "value=\"{$id_entradaSaida}\"";
            }
            ?> />
            <div class="form-group">
            ID Veiculo:
             <input type="text" name="idveiculo" class="form-control" <?php
            // Preenche o modelo no campo modelo com um valor "value"
            if (isset($idveiculo) && $idveiculo != null || $idveiculo != "") {
                echo "value=\"{$idveiculo}\"";
            }
            ?> />
        </div>
        <div class="form-group">
           Placa:
           <input type="text" name="placa" class="form-control"<?php
            // Preenche o modelo no campo modelo com um valor "value"
            if (isset($placa) && $placa != null || $placa != "") {
                echo "value=\"{$placa}\"";
            }
            ?> />
        </div>
        <div class="form-group">
				
          
          
          Hora de entrada:
          <input type="text" name="horaEntrada" value="<?php echo date('H:i');?>"class="form-control" > <?php
            // Preenche o modelo no campo modelo com um valor "value"
            if (isset($horaEntrada) && $horaEntrada != null || $horaEntrada != "") {
                echo "value=\"{$horaEntrada}\"";
            }
            ?> 
        </div>
        <div class="form-group">
           Hora Saida: 
            <input type="text" name="horaSaida" class="form-control"<?php
            // Preenche o modelo no campo modelo com um valor "value"
            if (isset($horaSaida) && $horaSaida != null || $horaSaida != "") {
                echo "value=\"{$horaSaida}\"";
            }
            ?> />
        </div>
          
         
          

         <input type="submit" value="Estacionar" name="estacionar" class="btn btn-success"/>
         </div>
     </div>
         

         

        </form>
        <div class="row marketing">
            <div class="col-lg-12">
        <table border="1" width="100%" class="table table-hover">
        <tr style="background-color:#D2CDCD;">
            <th>Placa</th>
            <th>Hora Entrada</th>
            <th>Hora Saida</th>
            <th>Hora Estacionada</th>
            <th>Total preço</th>
            <th>Ação</th>
         </tr>
         <?php
            try {
 
                $stmt = $conexao->prepare("SELECT a.placa,b.horaEntrada,b.horaSaida,b.hora,b.preco FROM tb_veiculo a INNER JOIN tb_entradasaida b ON a.idveiculo = b.idveiculo");
 
                if ($stmt->execute()) {
                     while ($rs = $stmt->fetch(PDO::FETCH_OBJ)) {
                    echo "<tr>";
                    echo "<td>".$rs->placa."</td><td>".$rs->horaEntrada."</td><td>".$rs->horaSaida."</td><td>".$rs->hora."</td><td>".$rs->preco
                           ."</td><td><center><a href=\"?act=upd&id_entradaSaida=" . $id_entradaSaida . "\">[ALTERAR]</a>"
                           ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                           
                    echo "</tr>";
                 }
                } else {
                    echo "Erro: Não foi possível recuperar os dados do banco de dados";
                }
                } catch (PDOException $erro) {
                echo "Erro: ".$erro->getMessage();
                }
                ?>

            </table>
        </div>
    </div>



           


        

    </body>
</html>
