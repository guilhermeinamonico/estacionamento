<?php

// Verificar se foi enviando dados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idveiculo = (isset($_POST["idveiculo"]) && $_POST["idveiculo"] != null) ? $_POST["idveiculo"] : "";
    $modelo = (isset($_POST["modelo"]) && $_POST["modelo"] != null) ? $_POST["modelo"] : "";
    $ano = (isset($_POST["ano"]) && $_POST["ano"] != null) ? $_POST["ano"] : "";
    $placa = (isset($_POST["placa"]) && $_POST["placa"] != null) ? $_POST["placa"] : NULL;
    $cor = (isset($_POST["cor"]) && $_POST["cor"] != null) ? $_POST["cor"] : NULL;
    $id_entradaSaida = (isset($_POST["id_entradaSaida"]) && $_POST["id_entradaSaida"] != null) ? $_POST["id_entradaSaida"] : NULL;
    


    

} else if (!isset($idveiculo)) {
    // Se não se não foi setado nenhum valor para variável $idveiculo
    $idveiculo = (isset($_GET["idveiculo"]) && $_GET["idveiculo"] != null) ? $_GET["idveiculo"] : "";
    $modelo = NULL;
    $ano = NULL;
    $placa = NULL;
    $cor = NULL;
    $id_entradaSaida = NULL;
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
	if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save" && $modelo != "") {
    try {

        if ($idveiculo != "") {
            $stmt = $conexao->prepare("UPDATE tb_veiculo SET modelo=?, ano=?, placa=?, cor=? WHERE idveiculo = ?");
            $stmt->bindParam(5, $idveiculo);
        } else {
        	$verifica_placa = $conexao->query("SELECT * FROM tb_veiculo WHERE placa = '$placa'");
        	if($verifica_placa->rowCount() >=1){  

 				
 				header('location:conexao.php');

 			} else{
            

            $stmt = $conexao->prepare("INSERT INTO tb_veiculo (modelo, ano, placa, cor) VALUES (?, ?, ?, ?)");
           
            
            
        }
        }
        $stmt->bindParam(1, $modelo);
        $stmt->bindParam(2, $ano);
        $stmt->bindParam(3, $placa);
        $stmt->bindParam(4, $cor);
        

        






        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo "Dados cadastrados com sucesso!";
               
                $idveiculo = null;
                $modelo = null;
                $ano = null;
                $placa = null;
                $cor = null;
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

// Bloco if que recupera as informações no formulário, etapa utilizada pelo Update
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "upd" && $idveiculo != "") {
    try {
        $stmt = $conexao->prepare("SELECT * FROM tb_veiculo WHERE idveiculo = ?");
        $stmt->bindParam(1, $idveiculo, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $rs = $stmt->fetch(PDO::FETCH_OBJ);
            $idveiculo = $rs->idveiculo;
            $modelo = $rs->modelo;
            $ano = $rs->ano;
            $placa = $rs->placa;
            $cor = $rs->cor;
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
    }
}

// Bloco if utilizado pela etapa Delete
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "del" && $idveiculo != "") {
   
    try {
        $stmt = $conexao->prepare("DELETE FROM tb_veiculo WHERE idveiculo = ?");
        $stmt->bindParam(1, $idveiculo, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo "Registo foi excluído com êxito";
            $id = null;
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
        <form action="?act=save" method="POST" name="form1" >
          
          <div class="jumbotron">
            <h3>Cadastro de Veiculo</h3>
          <hr>


          <input type="hidden" name="idveiculo" <?php
            // Preenche o id no campo id com um valor "value"
            if (isset($idveiculo) && $idveiculo != null || $idveiculo != "") {
                echo "value=\"{$idveiculo}\"";
            }
            ?> />

            <input type="hidden" name="id_entradaSaida" <?php
            // Preenche o id no campo id com um valor "value"
            if (isset($id_entradaSaida) && $id_entradaSaida != null || $id_entradaSaida != "") {
                echo "value=\"{$id_entradaSaida}\"";
            }
            ?> />
            <div class="form-group">
          
          Modelo:
          <input type="text" name="modelo" class="form-control"<?php
            // Preenche o modelo no campo modelo com um valor "value"
            if (isset($modelo) && $modelo != null || $modelo != "") {
                echo "value=\"{$modelo}\"";
            }
            ?> />
            </div>
            <div class="form-group">
          
          Ano:
          <input type="text" name="ano" class="form-control" <?php
            // Preenche o ano no campo ano com um valor "value"
            if (isset($ano) && $ano != null || $ano != "") {
                echo "value=\"{$ano}\"";
            }
            ?> />
        </div>
        <div class="form-group">
          
          Placa:
         <input type="text" name="placa" class="form-control" <?php
            // Preenche o ano no campo ano com um valor "value"
            if (isset($placa) && $placa != null || $placa != "") {
                echo "value=\"{$placa}\"";
            }
            ?> />
        </div>
        <div class="form-group">
         
          Cor:
         <input type="text" name="cor" class="form-control" <?php
            // Preenche o ano no campo ano com um valor "value"
            if (isset($cor) && $cor != null || $cor != "") {
                echo "value=\"{$placa}\"";
            }
            ?> />
        </div>

          

         <input type="submit" value="Salvar" class="btn btn-primary" />
         
     </div>
 </div>
         <hr>
       </form>
       <div class="row marketing">
        <div class="col-lg-12">
       <table border="1" width="100%" class="table table-hover">
    	<tr style="background-color:#D2CDCD;">
        	<th>Modelo</th>
        	<th>Ano</th>
        	<th>placa</th>
        	<th>cor</th>
        	<th>Ação</th>
   		 </tr>
   		 <?php
			try {
 
    			$stmt = $conexao->prepare("SELECT * FROM tb_veiculo");
 
        		if ($stmt->execute()) {
           			 while ($rs = $stmt->fetch(PDO::FETCH_OBJ)) {
                	echo "<tr>";
                	echo "<td>".$rs->modelo."</td><td>".$rs->ano."</td><td>".$rs->placa."</td><td>".$rs->cor
                           ."</td><td><center><a href=\"?act=upd&idveiculo=" . $rs->idveiculo . "\">[Alterar]</a>"
                           ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                           ."<a href=\"?act=del&idveiculo=" . $rs->idveiculo . "\">[Excluir]</a></center></td>";
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
            <div style="float: right;"><a href="estacionamento.php">Ir para estacionamento</a></div>
        </div>
        </div>

				

    </body>
</html>
