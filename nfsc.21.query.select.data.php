<?php
/*
* @Title: Consulta de Dados para gerar arquivos das NfSC Mod. 21 e NFST Mod. 22.
* @Description: o script faz a consulta dos dados para alimentar os arquivos da NfSC Mod. 21 e NFST Mod. 22.
* @Update: 2017-05-16, 2019-02-15
* @Class: Nfsc_21
* @Observation: A classe foi nomeado como Nfsc_21, no entanto ela tambem vai gerar os arquivos para o NFST Mod. 22.
*
*/

# record time execution - start
$time_start = microtime(true);

require 'config.php';
require 'class/nfsc.21.class.php';



/***************************************************************************************************
 * variaveis em comum
 *
 * URL para emissao de modelo 21
 * URL/?di=2019-02-01&df=2019-02-07&nf=0&ri=1&da=1902&de=20190207&mo=21&tu=4
 * URL para emissao de modelo 22
 * URL/?di=2019-02-01&df=2019-02-07&nf=0&ri=1&da=1902&de=20190207&mo=22&tu=1
 *
 ***************************************************************************************************/
$dtini           = $_GET['di'];  # "2017-01-01";  # periodo inicial para a consulta
$dtfim           = $_GET['df'];  # "2017-01-31";  # periodo final para a consulta
$nf_numero       = $_GET['nf'];  # 0;             # inicia em ZERO como padrao, sera incrementado na iteracao do loop
$nf_ref_item     = $_GET['ri'];  # 1;
$data_apuracao   = $_GET['da'];  # 1701;          #date("ym");       # formato "AAMM"
$data_emissao    = $_GET['de'];  # 20170120;      #date("Ymd");      # formato AAAAMMDD
$modelo          = $_GET['mo'];  # modelo (21 ou 22) modelo 06 - Energia Eletrica ainda nao foi implementado.
$tipo_utilizacao = $_GET['tu'];  # cliente tipo utilizacao (mod. 21: internet(4) parametro opicional | mod. 22: telefonia(1), tv assinatura(3), outros(6) parametro requerido)
$csv             = $_GET['csv']; # exportar dados para arquivo CSV.
$nfsc            = new Nfsc_21;



/***************************************************************************************************
 * validar e sanitizar(pendente) dados de entrada
 ***************************************************************************************************/
$expdi = explode("-",$dtini);
$expdf = explode("-",$dtfim);
if (checkdate($expdi[1] , $expdi[2] , $expdi[0]) == 1 and checkdate($expdf[1] , $expdf[2] , $expdf[0]) == 1)
{
    /***************************************************************************************************
     * Dados da empresa ativa.
     ***************************************************************************************************/
    $dados_empresa = $database->select("empresa", "*", array("id[=]" => 2));
    #print "<pre>EMPRESA <br>"; print_r($dados_empresa); print "</pre>";



    /***************************************************************************************************
     * @Query : Arquivo MESTRE (UF9999999999999921U  1902N01M.001)
     * `@InvoiceItemTotal` deve conter apenas o valor total dos items que compoem a NF, porem
     *  nao sera listado todos os items nessa consulta para o arquivo MESTRE. Todos os items
     *  serao listados na consulta para o arquivo ITEM.
     ***************************************************************************************************/
    $sql1 = "SELECT
                cli.id as `@ClientID`, cli.nome as `@ClientName`, cli.insc_uf as `@ClientIE`,
                cli.cpf as `@ClientCPF`, cli.cnpj as `@ClientCNPJ`, cli.endereco as `@ClientAddress`,
                cli.numero as `@ClientAddressNumber`, cli.complemento as `@ClientAddressComp`,
                cli.bairro as `@ClientAddressSuburb`, cli.cep as `@ClientAddressZipcode`,
                cli.estado as `@ClientState`, cli.cidade as `@ClientCity`, cli.tel1 as `@ClientPhone1`,
                con.id as `@ContractID`, con.tipo_assin,
                plan.id as `@PlanID`, plan.titulo as `@PlanTitle`, plan.valor as `@PlanAmount`,
                cid.nome as `@CityName`,
                inv.id as `@InvoiceID`, inv.due_date as `@InvoiceDueDate`, inv.paid_date as `@InvoicePaidDate`,
                inv.serv1rate as `@InvoiceAmount`, inv.subtotal as `@InvoiceSubtotal`,
                inv.misc as `@InvoiceDiscount`, inv.total as `@InvoiceTotal`, inv.referencia as `@InvoiceReference`,
                SUM(inv.total) as `@InvoiceItemTotal`,
                cfop.cfop as `@CfopCode`, cfop.descricao as `@CfopDescription`
            FROM
                cliente cli
            INNER JOIN
                contratos con ON con.idcliente = cli.id
            INNER JOIN
                planos plan ON plan.id = con.idplanos
            INNER JOIN
                cfop ON cfop.cfop = con.cfop
            INNER JOIN
                invoices inv ON inv.clientid = cli.id
            INNER JOIN
                tb_cidades cid ON cid.id = cli.cidade
            WHERE
                cli.ativo = 1 and
                con.ativo=1 and
                con.nf=1 and
                (
                    inv.referencia='PA' or inv.referencia='AD' or inv.referencia='RE' or
                    inv.referencia='PD' or inv.referencia='EX' or inv.referencia='SA'
                ) and
                (
                    inv.status='paid' or inv.status='pending' or inv.status='renegociado'
                ) and
                (
                    inv.due_date BETWEEN '$dtini' AND '$dtfim'
                )
            GROUP BY
                con.id
            ORDER BY
                con.id ASC
            ";
    $data1 = $database->query($sql1)->fetchAll();
    //print "<pre>MESTRE QUERY " . $sql1 . "</pre>";
    //print "<pre>MESTRE ARRAY <br>"; print_r($data1); print $count; print "</pre>";
    $arrayMESTRE = $data1;



    /***************************************************************************************************
     * CONSULTA PARA PEGAR O NUMERO DE ITEMS NA NOTA.
     * COM O RESULTADO RODAR UM FOREACH COM O ARRAY E COMPARAR O ID DO CONTRARO PARA
     * INCREMENTAR O CONTADOR.
     ***************************************************************************************************/
     $sql1_1 = "SELECT
                    con.id as `@ContractID`,
                    inv.id as `@InvoiceID`,
                    COUNT(con.id) as `@TotalItemsPerNF`
                FROM
                    cliente cli
                INNER JOIN
                    contratos con ON con.idcliente = cli.id
                INNER JOIN
                    planos plan ON plan.id = con.idplanos
                INNER JOIN
                    cfop ON cfop.cfop = con.cfop
                INNER JOIN
                    invoices inv ON inv.clientid = cli.id
                INNER JOIN
                    tb_cidades cid ON cid.id = cli.cidade
                WHERE
                    cli.ativo = 1 and
                    con.ativo=1 and
                    con.nf=1 and
                    (
                        inv.referencia='PA' or inv.referencia='AD' or inv.referencia='RE' or
                        inv.referencia='PD' or inv.referencia='EX' or inv.referencia='SA'
                    ) and
                    (
                        inv.status='paid' or inv.status='pending' or inv.status='renegociado'
                    ) and
                    (
                        inv.due_date BETWEEN '$dtini' AND '$dtfim'
                    )
                GROUP BY
                    con.id
                ORDER BY
                    con.id ASC
                ";
    $data1_1 = $database->query($sql1_1)->fetchAll();
    //print "<pre>COUNT ITEMS PER NF " . $sql1_1 . "</pre>";
    //print "<pre>COUNT ITEMS PER NF ARRAY <br>"; print_r($data1_1); print "</pre>";


    try
    {
        $response_mestre = $nfsc->Mestre($arrayMESTRE, $data1_1, $nf_numero=0, $nf_ref_item, $data_apuracao, $data_emissao, $dados_empresa, $modelo, $tipo_utilizacao, $database);
        echo $response_mestre['0']['msg']; // display return message
    }
    catch (Exception $e)
    {
    	echo "<pre><b>Caught exception:</b> ",  $e->getMessage(), "\n</pre>";
    }
    //print "<pre>"; print_r($response_mestre); print "</pre>";



    /***************************************************************************************************
     * @Query : Arquivo ITEM (UF9999999999999921U  1902N01I.001)
     * Remover group by e SUM para pegar todos os items de cada contrato do cliente.
     ***************************************************************************************************/
    $sql2 = "SELECT
                cli.id as `@ClientID`, cli.nome as `@ClientName`, cli.insc_uf as `@ClientIE`,
                cli.cpf as `@ClientCPF`, cli.cnpj as `@ClientCNPJ`, cli.endereco as `@ClientAddress`,
                cli.numero as `@ClientAddressNumber`, cli.complemento as `@ClientAddressComp`,
                cli.bairro as `@ClientAddressSuburb`, cli.cep as `@ClientAddressZipcode`,
                cli.estado as `@ClientState`, cli.cidade as `@ClientCity`, cli.tel1 as `@ClientPhone1`,
                con.id as `@ContractID`, con.tipo_assin,
                plan.id as `@PlanID`, plan.titulo as `@PlanTitle`, plan.valor as `@PlanAmount`,
                plan.teto_download as `@PlanDownload`, plan.teto_upload as `@PlanUpload`,
                cid.nome as `@CityName`,
                inv.id as `@InvoiceID`, inv.due_date as `@InvoiceDueDate`, inv.paid_date as `@InvoicePaidDate`,
                inv.serv1rate as `@InvoiceAmount`, inv.subtotal as `@InvoiceSubtotal`,
                inv.misc as `@InvoiceDiscount`, inv.total as `@InvoiceTotal`, inv.referencia as `@InvoiceReference`,
                cfop.cfop as `@CfopCode`, cfop.descricao as `@CfopDescription`
            FROM
                cliente cli
            INNER JOIN
                contratos con ON con.idcliente = cli.id
            INNER JOIN
                planos plan ON plan.id = con.idplanos
            INNER JOIN
                cfop ON cfop.cfop = con.cfop
            INNER JOIN
                invoices inv ON inv.clientid = cli.id
            INNER JOIN
                tb_cidades cid ON cid.id = cli.cidade
            WHERE
                cli.ativo = 1 and
                con.ativo=1 and
                con.nf=1 and
                (
                    inv.referencia='PA' or inv.referencia='AD' or inv.referencia='RE' or
                    inv.referencia='PD' or inv.referencia='EX' or inv.referencia='SA'
                ) and
                (
                    inv.status='paid' or inv.status='pending' or inv.status='renegociado'
                ) and
                (
                    inv.due_date BETWEEN '$dtini' AND '$dtfim'
                )
            ORDER BY
                con.id ASC
            ";
    $data2 = $database->query($sql2)->fetchAll();
    //echo "<pre>ITEM QUERY <br>" . $sql2 . "</pre>";

    $arrayITEM = $data2;
    //print "<pre>ITEM ARRAY <br>"; print_r($arrayITEM); print count($data2); print "</pre>";

    try
    {
        // Esse metodo recebe o array $response_mestre como parametro, esse array contem o valor total dos item do documento fiscal.
        echo $nfsc->Item($response_mestre, $arrayITEM, $nf_numero=1, $nf_ref_item, $data_apuracao, $data_emissao, $dados_empresa, $modelo, $tipo_utilizacao, $database);
    }
    catch (Exception $e)
    {
        echo "<pre><b>Caught exception:</b> ",  $e->getMessage(), "\n</pre>";
    }



    /***************************************************************************************************
     * @Query : Arquivo CADASTRO (UF9999999999999921U  1902N01D.001)
     * IMPORTANTE: Usamos os mesmos dados da consulta do arquivo MESTRE. Pois o array ja esta formado
     * e nao existe a necessidade de uma nova consulta.
     ***************************************************************************************************/
    try
    {
        echo $nfsc->Cadastro($arrayMESTRE, $nf_numero=0, $nf_ref_item, $data_apuracao, $data_emissao, $dados_empresa, $modelo, $database);
    }
    catch (Exception $e)
    {
        echo "<pre><b>Caught exception:</b> ",  $e->getMessage(), "\n</pre>";
    }



    /***************************************************************************************************
     * Exportar dados para CSV
     * $csv = 1 significa para gerar --- $csv = 0 significa para nao gerar o csv.
     ***************************************************************************************************/
    try
    {
        $nfsc->ExportCSV($arrayMESTRE, $arrayITEM, $nf_numero, $dtini, $dtfim, $data_apuracao, $data_emissao, $dados_empresa, $csv);
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
