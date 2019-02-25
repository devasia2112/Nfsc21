<script>
function formatar_mascara(src, mascara) {
	var campo = src.value.length;
	var saida = mascara.substring(0,1);
	var texto = mascara.substring(campo);
	if(texto.substring(0,1) != saida) { src.value += texto.substring(0,1); }
}
</script>
<style type="text/css">fieldset{width:650px;padding:5px 5px 5px 5px;} fieldset label{float:left;width:200px;margin-right:15px;}</style>

<?php echo date("d/m/Y H:i:s"); ?>
<fieldset width="500">
<legend> Info </legend>
<ul>
    <li>Gerador do arquivo ITEM (UF0000000000000022U  AAMMN01I.001) </li> 
    <li>Gerador do arquivo MESTRE (UF0000000000000022U  AAMMN01M.001)  </li>
    <li>Gerador do arquivo CADASTRO (UF0000000000000022U  AAMMN01D.001) </li>
    <li>Exportar Dados no formato CSV (para uso em programas de terceiros) </li>
    <li>Toda emiss&atilde;o deve iniciar em ZERO </li>
    <li>Dias v&aacute;lidos entre 01 e 28</li>
    <li>Todos os campos sao requeridos</li>
</ul>
</fieldset>

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

<form name="form1" action="nfsc.21.query.select.data.php" method="get">
    <fieldset width="500"><legend> Parametros </legend>
        <div>&nbsp;</div>
        <label>&Uacute;ltima e-NF emitida</label>
        <input type="text" name="nf" value="0" maxlength="10" size="15" required="required" /><br>
        <label>Data Inicial</label>
        <input type="text" name="di" value="" maxlength="10" size="15" onkeypress="formatar_mascara(this, '####-##-##')" required="required" /> <small> # formato AAAA-MM-DD </small><br />
        <label>Data Final</label>
        <input type="text" name="df" value="" maxlength="10" size="15" onkeypress="formatar_mascara(this, '####-##-##')" required="required" /> <small> # formato AAAA-MM-DD </small><br />
        <label>Data da Emiss&atilde;o</label>
        <input type="text" name="de" value="<?=date('Ymd');?>" maxlength="8" size="15" onkeypress="formatar_mascara(this, '########')" required="required" /> <small> # formato AAAAMMDD </small><br />
        <label>Data da Apura&ccedil;&atilde;o</label>
        <input type="text" name="da" value="<?=date('ym');?>" maxlength="4" size="15" onkeypress="formatar_mascara(this, '####')" required="required" /> <small> # formato AAMM </small><br />
        <label>Numero Ref.: Item</label>
        <input type="text" name="ri" value="1" maxlength="15" size="15" required="required" /><br>
        <label>Modelo</label>
        <select name="mo" style="min-width: 250px;" required>
            <option value="">[Selecione o Modelo da Nota]</option>
            <option value="21">21</option>
            <option value="22">22</option>
        </select><br>
        <label>Tipo Utiliza&ccedil;&atilde;o</label>
        <select name="tu" style="min-width: 250px;" required>
            <option value="">[Selecione o Tipo de Utiliza&ccedil;&atilde;o]</option>
            <option value="1">1 - Telefonia (Mod. 22)</option>
            <option value="3">3 - TV Assinatura (Mod. 22)</option>
            <option value="6">6 - Outros (Mod. 22)</option>
            <option value="--" disabled="disabled">----------------</option>
            <option value="4">4 - Internet (Mod. 21)</option>
        </select><br>
        <label>Exportar arquivo CSV?</label>
        <input type="checkbox" name="csv" value="1" /> <br />
        <label>&nbsp;</label><br />
        <label>&nbsp;</label><input type="submit" value="Gerar Arquivos" />
        <div>&nbsp;</div>
    </fieldset>
</form>
<div><a href="001/" target="_blank">Visualizar Arquivos 001</a></div>
<div><a href="CSV/" target="_blank">Visualizar Arquivos CSV</a></div>
