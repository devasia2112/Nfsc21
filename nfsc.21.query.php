<?php
/*
 * @Title: queries
 * @Description: o script faz a consulta dos dados para alimentar os arquivos da Nfsc_21.
 * @Date: 2017-05-16
 * @Class: Nfsc_21
 * @File Encode: ISO 8859 - 1 (Latin - 1)
 * @Coder: <deepcell@gmail.com>
 * @Notes:
 *
 */
include_once "../../config/config.php";
include_once "../Sql/sql.class.php";
include      "nfsc.21.class.php";


/***************************************************************************************************
 * variaveis em comum
 ***************************************************************************************************/
$dtini         = $_GET['di'];       # "2017-01-01";  # periodo inicial para a consulta
$dtfim         = $_GET['df'];       # "2017-01-31";  # periodo final para a consulta
$nf_numero     = $_GET['nf'];       # 0;             # inicia em ZERO como default, sera incrementado na iteracao do loop
$nf_ref_item   = $_GET['ri'];       # 1;
$data_apuracao = $_GET['da'];       # 1701;          #date("ym");       # formato "AAMM"
$data_emissao  = $_GET['de'];       # 20170120;      #date("Ymd");      # formato AAAAMMDD
$nfsc          = new Nfsc_21;



/***************************************************************************************************
 * validar e sanitizar(pendente) dados de entrada
 ***************************************************************************************************/
$expdi = explode("-",$dtini);
$expdf = explode("-",$dtfim);
if (checkdate($expdi[1] , $expdi[2] , $expdi[0]) == 1 and checkdate($expdf[1] , $expdf[2] , $expdf[0]) == 1)
{

    /***************************************************************************************************
     * pegar sempre a empresa padrao e ativa(sempre existira apenas 1 nessa condicao).
     ***************************************************************************************************/
    $dados_empresa = $database->select("empresa", "*", array("id[=]" => 2));



    /***************************************************************************************************
     * @Query : Arquivo MESTRE (SP1102933400010421U  1701N01M.001)
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
    //print "<pre>EMPRESA ARRAY <br>"; print_r($dados_empresa); print "</pre>";
    $arrayMESTRE = $data1;



    /* Nao existe a necessidade de usar esse bloco pois a consulta passou a satisfazer a condicao.
    $prev_contract_id = '';    // o mesmo que group by
    $count = 0;
    foreach ($data1 as $dat1)
    {
        # usa-se o primeiro item [diferente] que repete na consulta
        if ($dat1['@ContractID'] !== $prev_contract_id && $prev_contract_id !== '')
        {
            # gerar o array para o arquivo MESTRE
            $arrayMESTRE[] = $dat1;
            $count +=1;
        }

        # set o ID do contrato atual como ID do contrato anterior antes de finalizar o loop para o ID atual.
        $prev_contract_id = $dat1['@ContractID'];
    }
    */



    /***************************************************************************************************
     * CONSULTA PARA PEGAR O NUMERO DE ITEMS NA NOTA.
     * COM O RESULTADO RODAR UM FOREACH COM O ARRAY E COMPARAR O ID DO CONTRARO PARA
     * COMAR O CONTADOR.
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
        $response_mestre = $nfsc->Mestre($arrayMESTRE, $data1_1, $nf_numero=0, $nf_ref_item, $data_apuracao, $data_emissao, $dados_empresa);
        echo $response_mestre['0']['msg']; // display return message
    }
    catch (Exception $e)
    {
    	echo "<pre><b>Caught exception:</b> ",  $e->getMessage(), "\n</pre>";
    }
    //print "<pre>"; print_r($response_mestre); print "</pre>";





    /***************************************************************************************************
     * @Query : Arquivo ITEM (SP1102933400010421U  1701N01I.001)
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
         echo $nfsc->Item($response_mestre, $arrayITEM, $nf_numero=1, $nf_ref_item, $data_apuracao, $data_emissao, $dados_empresa, $database);
     }
     catch (Exception $e)
     {
         echo "<pre><b>Caught exception:</b> ",  $e->getMessage(), "\n</pre>";
     }





    /***************************************************************************************************
     * @Query : Arquivo CADASTRO (SP1102933400010421U  1701N01D.001)
     * IMPORTANTE: Usamos os mesmos dados da consulta do arquivo MESTRE. Pois o array ja esta formado
     * e nao existe a necessidade de uma nova consulta.
     ***************************************************************************************************/
     try
     {
         echo $nfsc->Cadastro($arrayMESTRE, $nf_numero=0, $nf_ref_item, $data_apuracao, $data_emissao, $dados_empresa, $database);
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
