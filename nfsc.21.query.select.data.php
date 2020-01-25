<?php
/*
* @Title: Consulta de Dados para gerar arquivos das NfSC Mod. 21 e NFST Mod. 22.
* @Description: o script faz a consulta dos dados para alimentar os arquivos da NfSC Mod. 21 e NFST Mod. 22.
* @Update: 2017-05-16, 2019-02-15
* @Class: Nfsc_21
* @Observation: A classe foi nomeado como Nfsc_21, no entanto ela tambem vai gerar os arquivos para o NFST Mod. 22.
* @update: 2019-04-28
*
*/

# record time execution - start
$time_start = microtime(true);

require 'config.php';
require 'class/nfsc.21.class.php';
require 'nfsc.21.sintegra.php'; // mover essa classe para o dir class/

error_reporting(E_ALL);


/*******************************************************************************
 * variaveis em comum
 *
 * URL para emissao de modelo 21
 * URL/?di=2019-02-01&df=2019-02-07&nf=0&ri=1&da=1902&de=20190207&mo=21&tu=4
 * URL para emissao de modelo 22
 * URL/?di=2019-02-01&df=2019-02-07&nf=0&ri=1&da=1902&de=20190207&mo=22&tu=1
 *
 *******************************************************************************/
//print '<pre>GET ARRAY<br>'; print_r($_GET); print '</pre>';
if (isset($_GET['query_type']) and !empty($_GET['query_type']))
    $query_type = $_GET['query_type']; # tipo, status, select_cliente, select_cli_id.
else 
    $query_type = '';

// status do cliente
if (isset($_GET['status_cliente']) and !empty($_GET['status_cliente'])) {
    $status_cliente = $_GET['status_cliente']; # array com status (1=ativo,2=bloqueado,3=cancelado,..).
    $where = '( ';
    $iter  = 1;
    foreach ($status_cliente as $key => $value) {
        if (count($status_cliente) == $iter) {
            $where .= 'cli.ativo = '.$value.''; // (cli.ativo = 1 or cli.ativo = 2)
        }
        else {
            $where .= 'cli.ativo = '.$value.' or '; // (cli.ativo = 1 or cli.ativo = 2)
        }
        $iter +=1;
    }
    $where .= ' )';
}
else {
    $status_cliente = [];
    $where = '';
}

// tipo cliente (PF ou PJ)
if (isset($_GET['tipo_cliente']) and !empty($_GET['tipo_cliente'])) {
    $tipo_cliente = $_GET['tipo_cliente']; # tipo cliente (PF ou PJ).
    if ($tipo_cliente == 'pf') {
        $where .= " and cpf <> '' and ";
    } else {
        $where .= " and cnpj <> '' and ";
    }
}
else {
    $tipo_cliente = ''; // consulta PF e PJ
    $where .= '';
}

// lista de clientes (se usar lista de clientes entao ignore tipo de cliente e status do cliente)
if (isset($_GET['cliente']) and !empty($_GET['cliente'])) {

    $cliente = $_GET['cliente']; # array com os clientes para gerar NF.

    // encontre intervalos (nao funciona para campos ZEROFILL)
    $arr1 = $cliente; //[1,2,4,5,7];
    // criar novo Array 1,2,3,....max($arr1).
    $arr2 = range(min($arr1), max($arr1));
    // use array_diff() para encontrar os elementos faltando.
    $intervalos = array_diff($arr2, $arr1); // retorna array com elementos que nao contem no range() [6,9,13,14]
    //print '<pre>'; print_r($intervalos); print '</pre>';

    if (empty($intervalos)) {
        $where = ' cli.id BETWEEN '.min($arr1).' AND '.max($arr1).' and ';
    }
    else {
        $where = '( cli.id BETWEEN '.min($arr1).' AND '.max($arr1).' )';
        foreach ($intervalos as $ikey => $ival) {
            $where .= ' and cli.id <> '.$ival.'';
        }
        $where .= ' and ';
    }
}
else {
    $cliente = []; # se esse array for vazio entao a consulta ocorre com os outros parametros.
    $where .= '';
}


$dtini           = $_GET['di'];  # "2017-01-01";  # periodo inicial para a consulta
$dtfim           = $_GET['df'];  # "2017-01-31";  # periodo final para a consulta
$nf_ref_item     = $_GET['ri'];  # 1;
$data_apuracao   = $_GET['da'];  # 1901;          #date("ym");       # formato "AAMM"
$data_emissao    = $_GET['de'];  # 20190120;      #date("Ymd");      # formato AAAAMMDD
$modelo          = $_GET['mo'];  # modelo (21 ou 22) modelo 06 - Energia Eletrica ainda nao foi implementado.
$tipo_utilizacao = $_GET['tu'];  # cliente tipo utilizacao (mod. 21: internet(4) parametro opicional | mod. 22: telefonia(1), tv assinatura(3), outros(6) parametro requerido)

// validar numero da prox. NF a ser gerada   SELECT MAX(`num_nf`) FROM `Nfsc_21_Notas` WHERE `periodo_apuracao` = '1904'
$prox_nf = $database->max("Nfsc_21_Notas", "num_nf", [
	"periodo_apuracao" => $data_apuracao
]);
if (empty($prox_nf)) {
    $nf_numero   = $_GET['nf'];  # 0;             # inicia em ZERO como padrao, sera incrementado na iteracao do loop, no entanto deve iniciar a partir da ultima gerada no periodo.
} else {
    //$nf_numero   = ($prox_nf + 1); # incrementa em 1 o prox. numero da NF no periodo.
    $nf_numero   = $prox_nf; # nao incrementamos em 1 o prox. numero da NF no periodo, pois isso ja ocorre na classe `nfsc.21.class.php`.
}

if (isset($_GET['csv']) and !empty($_GET['csv'])) {
    $csv         = $_GET['csv']; # exportar dados para arquivo CSV.
} else {
    $csv         = '';
}
$arrayMESTRE     = [];           # array inicializa vazio
$nfsc            = new Nfsc_21;
$sintegra        = new Sintegra;


/*******************************************************************************
 * validar e sanitizar(pendente) dados de entrada
 *******************************************************************************/
$expdi = explode("-",$dtini);
$expdf = explode("-",$dtfim);
if (checkdate($expdi['1'] , $expdi['2'] , $expdi['0']) == 1 and checkdate($expdf['1'] , $expdf['2'] , $expdf['0']) == 1)
{
    /*******************************************************************************
     * Dados da empresa ativa.
     *******************************************************************************/
    $dados_empresa = $database->select("Test_Empresa", "*", array("id[=]" => 1));
    // se estiver vazio setamos manualmente essa informacao
    if (empty($dados_empresa)) 
    {
        $dados_empresa['0']['cnpj'] = '99.999.999/0001-99';
        $dados_empresa['0']['ie'] = '999999999'; // formato ex.: 'ISENTO' ou 012.345.678 ou 123456789
        $dados_empresa['0']['razao_social'] = 'PROVEDOR X LTDA ME';
        $dados_empresa['0']['cidade'] = 'CURITIBA';
        $dados_empresa['0']['estado'] = 'PR';
        $dados_empresa['0']['fax'] = '(41) 9999-9999';
        $dados_empresa['0']['cod_id_convenio'] = '3'; // ou cod_arquivo_magnetico_entregue ??
        $dados_empresa['0']['cod_natureza_op_informada'] = '3';
        $dados_empresa['0']['cod_finalidade_arquivo_magnetico'] = '1';
        // dados complementares para o registro 11
        $dados_empresa['0']['logradouro'] = 'RUA CENTRAL';
        $dados_empresa['0']['numero'] = '999';
        $dados_empresa['0']['complemento'] = 'SALA 99';
        $dados_empresa['0']['bairro'] = 'CENTRO';
        $dados_empresa['0']['cep'] = '99999-999';
        $dados_empresa['0']['nome_contato'] = 'BOB WILSON';
        $dados_empresa['0']['empresa_telefone'] = '(99) 9999-9999';
    }
    //print "<pre>EMPRESA <br>"; print_r($dados_empresa); print "</pre>";



    /*******************************************************************************
    * @Query : Arquivo MESTRE (UF9999999999999921U  1902N01M.001)
    * `@InvoiceItemTotal` deve conter apenas o valor total dos items que compoem a NF, porem
    *  nao sera listado todos os items nessa consulta para o arquivo MESTRE. Todos os items
    *  serao listados na consulta para o arquivo ITEM.

        SE nao houver `gaps` entao por exemplo isso aqui funciona
        WHERE id BETWEEN 1100 AND 3000 
        SE houver `gaps` no dataset 
        WHERE id BETWEEN 1100 AND 3000 AND id NOT BETWEEN 1757 AND 1923
        
        Cod. Equivalente
        WHERE (`id`=1 OR `id`=2 OR `id`=3 OR `id`=4 OR `id`=5 OR `id`=6 OR ..)

        SELECT * FROM `Test_Cliente` 
        WHERE id BETWEEN 1 AND 15 AND id NOT BETWEEN 6 AND 9

    * @update: 2019-04-28
     *******************************************************************************/
    $sql1 = "
        SELECT
            cli.id as `@ClientID`, cli.nome as `@ClientName`, cli.insc_uf as `@ClientIE`,
            cli.cpf as `@ClientCPF`, cli.cnpj as `@ClientCNPJ`, cli.endereco as `@ClientAddress`,
            cli.numero as `@ClientAddressNumber`, cli.complemento as `@ClientAddressComp`,
            cli.bairro as `@ClientAddressSuburb`, cli.cep as `@ClientAddressZipcode`, cli.estado as `@ClientState`, 
            cli.cidade as `@ClientCity`, cli.tel1 as `@ClientPhone1`, cli.cfop as `@CfopCode`, 
            con.id as `@ContractID`,
            plan.id as `@PlanID`, plan.titulo as `@PlanTitle`, plan.valor as `@PlanAmount`,
            cid.nom_mun as `@CityName`,
            inv.id as `@InvoiceID`, inv.paid_date as `@InvoicePaidDate` 
        FROM
            Test_Cliente cli
        INNER JOIN
            Test_Contratos con ON con.idcliente = cli.id
        INNER JOIN
            Test_Planos plan ON plan.id = con.idplanos
        INNER JOIN
            Test_Invoices inv ON inv.clientid = cli.id
        INNER JOIN
            tb_municipios_ibge cid ON cid.cod_mun = cli.cidade 
        WHERE 
            ".$where."
            con.ativo = 1 and 
            ( inv.status='paid' or inv.status='pending' or inv.status='renegociado' ) and
            ( inv.due_date BETWEEN '$dtini' AND '$dtfim' )
        GROUP BY
            cli.id 
        ORDER BY 
            cli.id ASC;
    ";
    $data1 = $database->query($sql1)->fetchAll();
    //print "<pre>MESTRE QUERY " . $sql1 . "</pre>";
    //print "<pre>MESTRE ARRAY ORIGINAL<br>"; print_r($data1); print "</pre>";



    // gravar apenas os clientes que nao tiveram NF emitidas e gravadas na tabela `Nfsc_21_Notas`.
    //$arrayMESTRE = $data1; // nao usamos mais o  array $data1 diretamente
    foreach ($data1 as $d1key => $d1val) {

        $sqlNfsc21Notas = "
            SELECT 
                `cliente_id`, `data_referente` 
            FROM 
                `Nfsc_21_Notas` 
            WHERE 
                `cliente_id` = ".$d1val['@ClientID']." and 
                ( `data_referente` BETWEEN '$dtini' AND '$dtfim' );
        ";
        $dataNfsc21Notas = $database->query($sqlNfsc21Notas)->fetchAll();
        if (empty($dataNfsc21Notas))
            $arrayMESTRE[] = $d1val;

        // gravar o documento(cpf/cnpj) do cliente para fazer a validacao antes de gerar o arquivo ITEM.
    }
    //print "<pre>MESTRE ARRAY NF VALIDADO<br>"; print_r($arrayMESTRE); print "</pre>";




    /*******************************************************************************
     * CONSULTA PARA PEGAR O NUMERO DE ITEMS NA NOTA.
     * COM O RESULTADO RODAR UM FOREACH COM O ARRAY E COMPARAR O ID DO CONTRARO PARA
     * INCREMENTAR O CONTADOR.
     *******************************************************************************/
    $sql1_1 = "SELECT
                    con.id as `@ContractID`,
                    inv.id as `@InvoiceID`,
                    COUNT(con.id) as `@TotalItemsPerNF`
                FROM
                    Test_Cliente cli
                INNER JOIN
                    Test_Contratos con ON con.idcliente = cli.id 
                INNER JOIN
                    Test_Planos plan ON plan.id = con.idplanos 
                INNER JOIN
                    Test_Invoices inv ON inv.clientid = cli.id 
                INNER JOIN
                    tb_municipios_ibge cid ON cid.cod_mun = cli.cidade 
                WHERE
                    ".$where."
                    con.ativo = 1 and 
                    ( inv.status='paid' or inv.status='pending' or inv.status='renegociado' ) and
                    ( inv.due_date BETWEEN '$dtini' AND '$dtfim' )
                GROUP BY
                    con.id
                ORDER BY
                    con.id ASC;
                ";
    $data1_1 = $database->query($sql1_1)->fetchAll();
    //print "<pre>COUNT ITEMS PER NF " . $sql1_1 . "</pre>";
    //print "<pre>COUNT ITEMS PER NF ARRAY <br>"; print_r($data1_1); print "</pre>";


    try
    {
        $response_mestre = $nfsc->Mestre($arrayMESTRE, $data1_1, $nf_numero, $nf_ref_item, $data_apuracao, $data_emissao, $dados_empresa, $modelo, $tipo_utilizacao, $database);
        echo $response_mestre['0']['msg']; // display return message
    }
    catch (Exception $e)
    {
        echo "<pre><b>Caught exception:</b> ",  $e->getMessage(), "\n</pre>";
    }
    //print "<pre>"; print_r($response_mestre); print "</pre>";




    /*******************************************************************************
     * @Query : Arquivo ITEM (UF9999999999999921U  1902N01I.001)
     * Remover group by e SUM para pegar todos os items de cada contrato do cliente.
     *******************************************************************************/
    $sql2 = "SELECT
                cli.id as `@ClientID`, cli.nome as `@ClientName`, cli.insc_uf as `@ClientIE`,
                cli.cpf as `@ClientCPF`, cli.cnpj as `@ClientCNPJ`, cli.endereco as `@ClientAddress`,
                cli.numero as `@ClientAddressNumber`, cli.complemento as `@ClientAddressComp`,
                cli.bairro as `@ClientAddressSuburb`, cli.cep as `@ClientAddressZipcode`, cli.estado as `@ClientState`, 
                cli.cidade as `@ClientCity`, cli.tel1 as `@ClientPhone1`, cli.cfop as `@CfopCode`, 
                con.id as `@ContractID`, 
                plan.id as `@PlanID`, plan.titulo as `@PlanTitle`, plan.valor as `@PlanAmount`,
                plan.teto_download as `@PlanDownload`, plan.teto_upload as `@PlanUpload`,
                cid.nom_mun as `@CityName`,
                inv.id as `@InvoiceID`, inv.paid_date as `@InvoicePaidDate` 
            FROM 
                Test_Cliente cli 
            INNER JOIN 
                Test_Contratos con ON con.idcliente = cli.id 
            INNER JOIN 
                Test_Planos plan ON plan.id = con.idplanos 
            INNER JOIN 
                Test_Invoices inv ON inv.clientid = cli.id 
            INNER JOIN 
                tb_municipios_ibge cid ON cid.cod_mun = cli.cidade 
            WHERE 
                ".$where."
                con.ativo = 1 and 
                ( inv.status='paid' or inv.status='pending' or inv.status='renegociado' ) and
                ( inv.due_date BETWEEN '$dtini' AND '$dtfim' )
            ORDER BY 
                cli.id ASC;
            ";
    $data2 = $database->query($sql2)->fetchAll();
    //echo "<pre>ITEM QUERY <br>" . $sql2 . "</pre>";



    // gravar apenas os clientes que nao tiveram NF emitidas e gravadas na tabela `Nfsc_21_Notas`.
    //$arrayMESTRE = $data1; // nao usamos mais o  array $data1 diretamente
    foreach ($data2 as $d2key => $d2val) {

        $sqlNfsc21Notas = "
            SELECT 
                `cliente_id`, `data_referente` 
            FROM 
                `Nfsc_21_Notas` 
            WHERE 
                ( `cliente_documento` = '".$d2val['@ClientCPF']."' OR `cliente_documento` = '".$d2val['@ClientCNPJ']."' ) and 
                ( `data_referente` BETWEEN '$dtini' AND '$dtfim' );
        ";
        $dataNfsc21Notas = $database->query($sqlNfsc21Notas)->fetchAll();
        if (empty($dataNfsc21Notas))
            $arrayITEM[] = $d2val;

        // gravar o documento(cpf/cnpj) do cliente para fazer a validacao antes de gerar o arquivo ITEM.
    }
    //print "<pre>ITEM ARRAY NF VALIDADO<br>"; print_r($arrayITEM); print "</pre>";


    // so gravamos o array ITEM caso o array MESTRE contenha dados apos validacao se houve NF gerada ou nao.
    //$arrayITEM = $data2;
    //print "<pre>ITEM ARRAY <br>"; print_r($arrayITEM); print count($data2); print "</pre>";


    try
    {
        // PHP trata NULL, false, 0 e string vazio como sendo a mesma coisa.
        //if ($response_mestre !== NULL)
        // Esse metodo recebe o array $response_mestre como parametro, esse array contem o valor total dos item do documento fiscal.
        echo @$nfsc->Item($response_mestre, $arrayITEM, $nf_numero+1, $nf_ref_item, $data_apuracao, $data_emissao, $dados_empresa, $modelo, $tipo_utilizacao, $database);
    }
    catch (Exception $e)
    {
        echo "<pre><b>Caught exception:</b> ",  $e->getMessage(), "\n</pre>";
    }

    

    /*******************************************************************************
     * @Query : Arquivo CADASTRO (UF9999999999999921U  1902N01D.001)
     * IMPORTANTE: Usamos os mesmos dados da consulta do arquivo MESTRE. Pois o 
     * array ja esta formado e nao existe a necessidade de uma nova consulta.
     *******************************************************************************/
    try
    {
        echo $nfsc->Cadastro($arrayMESTRE, $nf_numero, $nf_ref_item, $data_apuracao, $data_emissao, $dados_empresa, $modelo, $database);
    }
    catch (Exception $e)
    {
        echo "<pre><b>Caught exception:</b> ",  $e->getMessage(), "\n</pre>";
    }



    /*******************************************************************************
     * Exportar dados para CSV
     * $csv = 1 significa para gerar --- $csv = 0 significa para nao gerar o csv.
     *******************************************************************************/
    try
    {
        // PHP trata NULL, false, 0 e string vazio como sendo a mesma coisa.
        //if ($arrayITEM !== NULL)
        echo @$nfsc->ExportCSV($arrayMESTRE, $arrayITEM, $nf_numero, $dtini, $dtfim, $data_apuracao, $data_emissao, $dados_empresa, $csv);
    }
    catch (Exception $e)
    {
        echo "<pre><b>Caught exception:</b> ",  $e->getMessage(), "\n</pre>";
    }



    /*******************************************************************************
     * @title : Arquivo Sintegra
     * IMPORTANTE: Usamos os mesmos dados da consulta do arquivo MESTRE e ITEM. 
     * Pois o array ja esta formado e nao existe a necessidade de uma nova consulta.
     * 
     * Obs.: Para gerar o arquivo Sintegra separadamente das NF, vai ser necessario
     * fazer todas consultas em um novo script e passar os dados para o metodo 
     * gerarArquivo().
     *******************************************************************************/
    try
    {
        // PHP trata NULL, false, 0 e string vazio como sendo a mesma coisa.
        $sintegra->gerarArquivo(
            $arrayMESTRE, $arrayITEM, $dtini, $dtfim, 
            $data_apuracao, $data_emissao, $dados_empresa, 
            $modelo, $tipo_utilizacao, $database
        );
    }
    catch (Exception $e)
    {
        echo "<pre><b>Caught exception:</b> ",  $e->getMessage(), "\n</pre>";
    }

}
else
{
    echo "erro: dados de entrada n&atilde;o foram devidamente validados.";
}



// record time execution - end
$time_end = microtime(true);

//dividing by 60 will give the execution time in minutes otherwise seconds
$execution_time = ($time_end - $time_start);
$hours   = (int)($execution_time/60/60);
$minutes = (int)($execution_time/60)-$hours*60;
$seconds = (int)$execution_time-$hours*60*60-$minutes*60;
print '<pre>this script was executed @'.date("Y-m-d H:i:s").'</pre>';
print "
    <pre>execution time: 
        microseconds: ".$execution_time."
        seconds:      ".$seconds."
        minutes:      ".$minutes."
        hours:        ".$hours."
    </pre>
";
