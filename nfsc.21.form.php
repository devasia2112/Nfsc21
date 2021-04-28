<?php
  date_default_timezone_set("America/Sao_Paulo");
?>
<html>
<head>
    <meta charset="utf-8">

    <!-- lib multi-select -->
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="assets/prism.css">
    <link rel="stylesheet" href="assets/chosen.css">
    <!-- <meta http-equiv="Content-Security-Policy" content="default-src &apos;self&apos;; script-src &apos;self&apos; https://ajax.googleapis.com; style-src &apos;self&apos;; img-src &apos;self&apos; data:"> -->
    <!-- lib multi-select -->

</head>
<body>

    <!-- bloco de informacao -->
    <div id="container">
        <div id="content">
            <div class="side-by-side clearfix">
                <div><em>Data do servidor: </em></div>
                <div><em><i><?php echo date("d/m/Y H:i:s"); ?></i></em></div>
            </div>
            <h3>INFO</h3><br>
            <ul>
                <li>Selecione a tipo da consulta dos clientes.</li> 
                <li>Gerador do arquivo ITEM (UF0000000000000022U  AAMMN01I.001). </li> 
                <li>Gerador do arquivo MESTRE (UF0000000000000022U  AAMMN01M.001).  </li>
                <li>Gerador do arquivo CADASTRO (UF0000000000000022U  AAMMN01D.001). </li>
                <li>Exportar Dados no formato CSV (para uso em programas de terceiros). </li>
                <li>Toda emiss&atilde;o deve iniciar em ZERO, com exceção das NF emitidas separadamente no mesmo periodo. </li>
                <li>Dias v&aacute;lidos entre 01 e 31.</li>
                <li>Todos os campos requeridos.</li>
                <li>Use as fun&ccedil;&otilde;es para imprimir NF e Gerar Arquivo Sintegra apenas ap&oacute;s as NF j&aacute; terem sido criadas.</li>
            </ul>
        </div>
    </div>
    <!--
    http://127.0.0.1/nfsc/nfsc.21.query.select.data.php?di=2019-02-01&df=2019-02-15&nf=0&ri=1&da=1902&de=20190207&mo=22&tu=1 
    +$dtini           = $_GET['di'];  # "2017-01-01";  # periodo inicial para a consulta
    +$dtfim           = $_GET['df'];  # "2017-01-31";  # periodo final para a consulta
    +$nf_numero       = $_GET['nf'];  # 0;             # inicia em ZERO como padrao, sera incrementado na iteracao do loop
    +$data_emissao    = $_GET['de'];  # 20170120;      #date("Ymd");      # formato AAAAMMDD
    +$data_apuracao   = $_GET['da'];  # 1701;          #date("ym");       # formato "AAMM"
    +$nf_ref_item     = $_GET['ri'];  # 1;
    +$modelo          = $_GET['mo'];  # modelo (21 ou 22) modelo 06 - Energia Eletrica ainda nao foi implementado.
    +$tipo_utilizacao = $_GET['tu'];  # cliente tipo utilizacao (mod. 21: internet(4) parametro opicional | mod. 22: telefonia(1), tv assinatura(3), outros(6) parametro requerido)
    -->
    <!-- bloco de informacao -->



    <!-- form -->
    
   <!-- <form name="form1" action="nfsc.21.query.select.data.php" method="get">-->
<form name="form1" action="nfsc.21.array.php" method="GET">
        <?php // precisa buscar apenas os clientes que nao tiveram NF emitidas ainda ?>
        <?php
        require 'config.php'; 
        $lista_clientes = $database->select("Test_Cliente", "*");
        //$lista_clientes = $database->select("Test_Cliente", "*", array("ativo[=]" => 1));
        ?>
        <div id="container">
            <div id="content">

                <!-- menu select cliente -->
                <div class="side-by-side clearfix">
                    <div>
                        <fieldset>
                            <legend>Escolher Tipo da Consulta de Clientes</legend>
                        </fieldset>
                    </div>
                    <div>
                        <input type="radio" id="cli_tipo" name="query_type" value="tipo_cliente">Tipo de Cliente<br>
                        <input type="radio" id="cli_status" name="query_type" value="status_cliente">Status do Cliente<br>
                        <input type="radio" id="cli_manual" name="query_type" value="select_cliente">Selecione Um a Um<br>
                        <input type="radio" id="cli_id" name="query_type" value="select_cli_id">Selecione por ID<br>
                    </div>
                </div>


                <!-- cliente por tipo (pf/pj) -->
                <div class="side-by-side clearfix" id="display_tipo" style="display: none;">
                    <div>
                        <em>Tipo de Clientes</em>
                    </div>
                    <div>
                        <select name="tipo_cliente" id="validate_tipo">
                            <option value="0">Selecione</option>
                            <option value="pf">PF</option>
                            <option value="pj">PJ</option>
                        </select>
                    </div>
                </div>


                <!-- cliente por status -->
                <div class="side-by-side clearfix" id="display_status" style="display: none;">
                    <div>
                        <em>Status de Clientes</em>
                    </div>
                    <div>
                        <select size="7" name="status_cliente[]" id="validate_status" multiple>
                            <option value="0">[SELECIONE]</option>
                            <option value="1" selected>ATIVO</option>
                            <option value="2" selected>BLOQUEADO</option>
                            <option value="3">CANCELADO</option>
                            <option value="4">PROTESTADO</option>
                            <option value="5">SUSPENSO</option>
                            <option value="6">ISENTO</option>
                            <option value="7">RELIGA</option>
                        </select>
                    </div>
                </div>


                <!-- cliente manualmente - multi-select -->
                <div class="side-by-side clearfix" id="display_manual" >
                    <div>
                        <em>Clientes</em>
                    </div>
                    <div>
                      <em>Selecione os Clientes Manualmente</em>
                      <select name="cliente[]" data-placeholder="Entre com as iniciais do nome" class="chosen-select-no-results" id="validate_cli_manual" tabindex="11" multiple>
                        <?php foreach ($lista_clientes as $key => $value) { echo '<option value="'.$value['id'].'">'.$value['nome'].' - '.$value['cpf'].$value['cnpj'].'</option>'; } ?>
                      </select>
                    </div>
                </div>


                <!-- cliente por ID - multi-select -->
                <div class="side-by-side clearfix" id="display_id" style="display: none;">
                    <div>
                        <em>Clientes por ID</em>
                    </div>
                    <div>
                        <select size="16" name="cliente[]" id="validate_cli_id" multiple>
                            <?php foreach ($lista_clientes as $key => $value) { echo '<option value="'.$value['id'].'">'.$value['id'].' - '.$value['nome'].' ('.$value['cpf'].$value['cnpj'].')</option>'; } ?>
                        </select>
                    </div>
                </div>
                <!-- cliente multi-select -->

                <hr>

                <div class="side-by-side clearfix">
                    <div>
                        <em>&Uacute;ltima e-NF emitida</em>
                    </div>
                    <div>
                        <em></em>
                        <input type="text" name="nf" value="0" maxlength="10" size="15" required="required" />
                    </div>
                </div>

                <div class="side-by-side clearfix">
                    <div>
                        <em>Data Inicial</em>
                    </div>
                    <div>
                        <em></em>
                        <input type="text" name="di" value="" maxlength="10" size="15" onkeypress="formatar_mascara(this, '####-##-##')" required="required" /> <small> # formato AAAA-MM-DD </small>
                    </div>
                </div>

                <div class="side-by-side clearfix">
                    <div>
                        <em>Data Final</em>
                    </div>
                    <div>
                        <em></em>
                        <input type="text" name="df" value="" maxlength="10" size="15" onkeypress="formatar_mascara(this, '####-##-##')" required="required" /> <small> # formato AAAA-MM-DD </small>
                    </div>
                </div>

                <div class="side-by-side clearfix">
                    <div>
                        <em>Data da Emiss&atilde;o</em>
                    </div>
                    <div>
                        <em></em>
                        <input type="text" name="de" value="<?=date('Ymd');?>" maxlength="8" size="15" onkeypress="formatar_mascara(this, '########')" required="required" /> <small> # formato AAAAMMDD </small>
                    </div>
                </div>

                <div class="side-by-side clearfix">
                    <div>
                        <em>Data da Apura&ccedil;&atilde;o</em>
                    </div>
                    <div>
                        <em></em>
                        <input type="text" name="da" value="<?=date('ym');?>" maxlength="4" size="15" onkeypress="formatar_mascara(this, '####')" required="required" /> <small> # formato AAMM </small>
                    </div>
                </div>

                <div class="side-by-side clearfix">
                    <div>
                        <em>Numero Ref.: Item</em>
                    </div>
                    <div>
                        <em></em>
                        <input type="text" name="ri" value="1" maxlength="15" size="15" required="required" />
                    </div>
                </div>

                <div class="side-by-side clearfix">
                    <div>
                        <em>Modelo</em>
                    </div>
                    <div>
                        <em></em>
                        <select name="mo" style="min-width: 250px;" required>
                            <option value="">[Selecione o Modelo da Nota]</option>
                            <option value="21">21</option>
                            <option value="22">22</option>
                        </select>                        
                    </div>
                </div>

                <div class="side-by-side clearfix">
                    <div>
                        <em>Tipo Utiliza&ccedil;&atilde;o</em>
                    </div>
                    <div>
                        <em></em>
                        <select name="tu" style="min-width: 250px;" required>
                            <option value="">[Selecione o Tipo de Utiliza&ccedil;&atilde;o]</option>
                            <option value="1">1 - Telefonia (Mod. 22)</option>
                            <option value="3">3 - TV Assinatura (Mod. 22)</option>
                            <option value="6">6 - Outros (Mod. 22)</option>
                            <option value="--" disabled="disabled">----------------</option>
                            <option value="4">4 - Internet (Mod. 21)</option>
                        </select>
                    </div>
                </div>

                <div class="side-by-side clearfix">
                    <div>
                        <em>Exportar arquivo CSV?</em>
                    </div>
                    <div>
                        <em></em>
                        <input type="checkbox" name="csv" value="1" />
                    </div>
                </div>

                <div class="side-by-side clearfix">
                    <div>
                        <em></em>
                    </div>
                    <div>
                        <em></em>
                        <input type="submit" value="Gerar Arquivos" />
                    </div>
                </div>

            </div>
        </div>
        <!-- cliente multi-select -->


        <div id="container">
            <div id="content">
                <h3>ARQUIVOS</h3><br>
                <div class="side-by-side clearfix">
                    <div><em>Arquivos 001: </em></div>
                    <div><a href="Files/001/" target="_blank">Visualizar</a></div>
                </div>
                <div class="side-by-side clearfix">
                    <div><em>Arquivos CSV: </em></div>
                    <div><a href="Files/CSV/" target="_blank">Visualizar</a></div>
                </div>
                <div class="side-by-side clearfix">
                    <div><em>Arquivos Sintegra: </em></div>
                    <div><a href="Files/Sintegra/" target="_blank">Visualizar</a></div>
                </div>
                <hr>
                <h3>IMPRIMIR</h3><br>
                <div class="side-by-side clearfix">
                    <div><em>Imprimir NF: </em></div>
                    <div><a href="nfsc.21.print.php" target="_blank">Imprimir</a></div>
                </div>
                <hr>
                <h3>SINTEGRA</h3><br>
                <div class="side-by-side clearfix">
                    <div><em>Arquivo Sintegra: </em></div>
                    <div><a href="nfsc.21.sintegra.php" target="_blank">Gerar</a></div>
                </div>
            </div>
        </div>


        <!-- multi-select lib -->
        <script src="assets/jquery-3.2.1.min.js" type="text/javascript"></script>
        <script src="assets/chosen.jquery.js" type="text/javascript"></script>
        <script src="assets/prism.js" type="text/javascript" charset="utf-8"></script>
        <script src="assets/init.js" type="text/javascript" charset="utf-8"></script>

        <script type="text/javascript">
        $(document).ready(function() {
            $("#cli_tipo").click(function(){

                /* se por tipo entao limpa todos os multi-select */
                $('#validate_cli_manual').val('-1');
                $('#validate_cli_id').val('-1');

                $("#display_tipo").show();
                $("#display_status").hide();
                $("#display_manual").hide();
                $("#display_id").hide();
            });
            $("#cli_status").click(function(){

                /* se por status entao limpa todos os multi-select */
                $('#validate_cli_manual').val('-1');
                $('#validate_cli_id').val('-1');

                $("#display_tipo").hide();
                $("#display_status").show();
                $("#display_manual").hide();
                $("#display_id").hide();
            });
            $("#cli_manual").click(function(){

                /* se manual (um a um) entao limpa multi-select por id, tipo e status */
                $('#validate_cli_id').val('-1');
                $('#validate_tipo').val('-1');
                $('#validate_status').val('-1');

                $("#display_tipo").hide();
                $("#display_status").hide();
                $("#display_manual").show();
                $("#display_id").hide();
            });
            $("#cli_id").click(function(){

                /* se por id entao limpa multi-select manual, tipo e status */
                $('#validate_cli_manual').val('-1');
                $('#validate_tipo').val('-1');
                $('#validate_status').val('-1');

                $("#display_tipo").hide();
                $("#display_status").hide();
                $("#display_manual").hide();
                $("#display_id").show();
            });
        });
        </script>

    </form>

</body>
</html>
