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
include "config.php";
include "nfsc.21.class.php";


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


    $r_empresa = array();
    $r_empresa = array(
        "0" => array
            (
                "id" => 2,
                "nome_fantasia" => "Bavarian Illuminati Provedor de Internet",
                "razao_social" => "Sua Razao Sozial Aqui",
                "endereco" => "",
                "numero" => "",
                "bairro" => "",
                "complemento" => "",
                "estado" => "SP",
                "cidade" => 9999,
                "cep" => "",
                "cnpj" => "14.070.561/0001-71",
                "ie" => "",
                "obs" => "",
                "tel1" => "11-1111-1111",
                "tel2" => "",
                "resp1" => "Adam Weishaupt",
                "resp2" => "",
                "fax" =>"",
                "data" => "1776-05-01",
                "website" => "",
                "email" => "",
                "default" => "y",
                "logotipo" => "",
                "ativo" => "y",
                "IM" => 10107,
                "cnae" => 6190601,
                "crt" => 1
            )
    );
    //print "<pre>EMPRESA "; print_r($r_empresa);


    // MESTRE (usar o mesmo array para o arquivo CADASTRO)
    $r_mestre = array();
    $r_mestre = array(
            "0" => array
                (
                    "@ClientID" => 22222,
                    "0" => 22222,
                    "@ClientName" => "Terence K. McKenna",
                    "1" => "Terence K. McKenna",
                    "@ClientIE" => "00000000000000",
                    "2" => "00000000000000",
                    "@ClientCPF" => "060.417.111-41",
                    "3" => "060.417.111-41",
                    "@ClientCNPJ" =>"",
                    "4" =>"",
                    "@ClientAddress" => "Avenida Paulista",
                    "5" => "Avenida Paulista",
                    "@ClientAddressNumber" => 1260,
                    "6" => 1260,
                    "@ClientAddressComp" => "Proximo a Fiesp",
                    "7" => "Proximo a Fiesp",
                    "@ClientAddressSuburb" => "Jd. Paulista",
                    "8" => "Jd. Paulista",
                    "@ClientAddressZipcode" => "05145-000",
                    "9" => "05145-000",
                    "@ClientState" => "SP",
                    "10" => "SP",
                    "@ClientCity" => 9999,
                    "11" => 9999,
                    "@ClientPhone1" => "11-3232-3232",
                    "12" => "11-3232-3232",
                    "@ContractID" => 98765,
                    "13" => 98765,
                    "tipo_assin" => 3,
                    "14" => 3,
                    "@PlanID" => 790,
                    "15" => 790,
                    "@PlanTitle" => "Super Plano Banda Larga IV",
                    "16" => "Super Plano Banda Larga IV",
                    "@PlanAmount" => 280.90,
                    "17" => 280.90,
                    "@CityName" => "São Paulo",
                    "18" => "São Paulo",
                    "@InvoiceID" => 707442,
                    "19" => 707442,
                    "@InvoiceDueDate" => "2017-01-15",
                    "20" => "2017-01-15",
                    "@InvoicePaidDate" =>"",
                    "21" =>"",
                    "@InvoiceAmount" => 280.90,
                    "22" => 280.90,
                    "@InvoiceSubtotal" => 280.90,
                    "23" => 280.90,
                    "@InvoiceDiscount" => 0.00,
                    "24" => 0.00,
                    "@InvoiceTotal" => 280.90,
                    "25" => 280.90,
                    "@InvoiceReference" => "parcela",
                    "26" => "parcela",
                    "@InvoiceItemTotal" => 280.90,
                    "27" => 280.90,
                    "@CfopCode" => 307,
                    "28" => 307,
                    "@CfopDescription" => "Não contribuinte",
                    "29" => "Não contribuinte",
                ),
                "1" => array
                    (
                        "@ClientID" => 11111,
                        "0" => 11111,
                        "@ClientName" => "Robert Anton Wilson",
                        "1" => "Robert Anton Wilson",
                        "@ClientIE" => "00000000000000",
                        "2" => "00000000000000",
                        "@ClientCPF" => "407.229.699-61",
                        "3" => "407.229.699-61",
                        "@ClientCNPJ" => "",
                        "4" => "",
                        "@ClientAddress" => "Avenida Paulista",
                        "5" => "Avenida Paulista",
                        "@ClientAddressNumber" => 1047,
                        "6" => 1047,
                        "@ClientAddressComp" => "Teste",
                        "7" => "Teste",
                        "@ClientAddressSuburb" => "Bela Vista",
                        "8" => "Bela Vista",
                        "@ClientAddressZipcode" => "01311-200",
                        "9" => "01311-200",
                        "@ClientState" => "SP",
                        "10" => "SP",
                        "@ClientCity" => 9999,
                        "11" => 9999,
                        "@ClientPhone1" => "11-2323-2323",
                        "12" => "11-2323-2323",
                        "@ContractID" => 2323,
                        "13" => 2323,
                        "tipo_assin" => 3,
                        "14" => 3,
                        "@PlanID" => 23,
                        "15" => 23,
                        "@PlanTitle" => "Super Plano Banda Larga I",
                        "16" => "Super Plano Banda Larga I",
                        "@PlanAmount" => 249.75,
                        "17" => 249.75,
                        "@CityName" => "São Paulo",
                        "18" => "São Paulo",
                        "@InvoiceID" => 70742,
                        "19" => 70742,
                        "@InvoiceDueDate" => "2017-01-15",
                        "20" => "2017-01-15",
                        "@InvoicePaidDate" => "",
                        "21" => "",
                        "@InvoiceAmount" => 249.75,
                        "22" => 249.75,
                        "@InvoiceSubtotal" => 249.75,
                        "23" => 249.75,
                        "@InvoiceDiscount" => 0.00,
                        "24" => 0.00,
                        "@InvoiceTotal" => 249.75,
                        "25" => 249.75,
                        "@InvoiceReference" => "parcela",
                        "26" => "parcela",
                        "@InvoiceItemTotal" => 249.75,
                        "27" => 249.75,
                        "@CfopCode" => 307,
                        "28" => 307,
                        "@CfopDescription" => "Não contribuinte",
                        "29" => "Não contribuinte",
                    )

    );
    //print "<pre>MESTRE "; print_r($r_mestre);

    // arra recebe o total de itens no documento fiscal
    $r_total_itens_nf = array(
        "0" => array
            (
                "@ContractID" => 98765,
                "@InvoiceID" => 707442,
                "@TotalItemsPerNF" => 1
            ),
        "1" => array
            (
                "@ContractID" => 2323,
                "@InvoiceID" => 70742,
                "@TotalItemsPerNF" => 4
            ),
    );


    try
    {
        $response_mestre = $nfsc->Mestre($r_mestre, $r_total_itens_nf, $nf_numero=0, $nf_ref_item, $data_apuracao, $data_emissao, $r_empresa);
        echo $response_mestre['0']['msg']; // display return message
    }
    catch (Exception $e)
    {
    	echo "<pre><b>Caught exception:</b> ",  $e->getMessage(), "\n</pre>";
    }





    // ITEM
    $r_item = array();
    $r_item = array(
        "0" => array
            (
                "@ClientID" => 22222,
                "0" => 22222,
                "@ClientName" => "Terence K. McKenna",
                "1" => "Terence K. McKenna",
                "@ClientIE" => "00000000000000",
                "2" => "00000000000000",
                "@ClientCPF" => "060.417.111-41",
                "3" => "060.417.111-41",
                "@ClientCNPJ" =>"",
                "4" =>"",
                "@ClientAddress" => "Avenida Paulista",
                "5" => "Avenida Paulista",
                "@ClientAddressNumber" => 1047,
                "6" => 1047,
                "@ClientAddressComp" => "Teste",
                "7" => "Teste",
                "@ClientAddressSuburb" => "Bela Vista",
                "8" => "Bela Vista",
                "@ClientAddressZipcode" => "01311-200",
                "9" => "01311-200",
                "@ClientState" => "SP",
                "10" => "SP",
                "@ClientCity" => 9999,
                "11" => 9999,
                "@ClientPhone1" =>"",
                "12" =>"",
                "@ContractID" => 98765,
                "13" => 98765,
                "tipo_assin" => 3,
                "14" => 3,
                "@PlanID" => 23,
                "15" => 23,
                "@PlanTitle" => "",
                "16" => "",
                "@PlanAmount" => 49.95,
                "17" => 49.95,
                "@PlanDownload" => 15000,
                "18" => 15000,
                "@PlanUpload" => 5000,
                "19" => 5000,
                "@CityName" => "São Paulo",
                "20" => "São Paulo",
                "@InvoiceID" => 76348,
                "21" => 76348,
                "@InvoiceDueDate" => "2017-01-15",
                "22" => "2017-01-15",
                "@InvoicePaidDate" => "0000-00-00",
                "23" => "0000-00-00",
                "@InvoiceAmount" => 49.95,
                "24" => 49.95,
                "@InvoiceSubtotal" => 49.95,
                "25" => 49.95,
                "@InvoiceDiscount" => 0.00,
                "26" => 0.00,
                "@InvoiceTotal" => 49.95,
                "27" => 49.95,
                "@InvoiceReference" => "parcela",
                "28" => "parcela",
                "@CfopCode" => 307,
                "29" => 307,
                "@CfopDescription" => "Não contribuinte",
                "30" => "Não contribuinte"
            ),
        "1" => array
            (
                "@ClientID" => 11111,
                "0" => 11111,
                "@ClientName" => "Robert Anton Wilson",
                "1" => "Robert Anton Wilson",
                "@ClientIE" => "00000000000000",
                "2" => "00000000000000",
                "@ClientCPF" => "407.229.699-61",
                "3" => "407.229.699-61",
                "@ClientCNPJ" =>"",
                "4" =>"",
                "@ClientAddress" => "Avenida Paulista",
                "5" => "Avenida Paulista",
                "@ClientAddressNumber" => 1047,
                "6" => 1047,
                "@ClientAddressComp" => "Teste",
                "7" => "Teste",
                "@ClientAddressSuburb" => "Bela Vista",
                "8" => "Bela Vista",
                "@ClientAddressZipcode" => "01311-200",
                "9" => "01311-200",
                "@ClientState" => "SP",
                "10" => "SP",
                "@ClientCity" => 9999,
                "11" => 9999,
                "@ClientPhone1" =>"",
                "12" =>"",
                "@ContractID" => 2323,
                "13" => 2323,
                "tipo_assin" => 3,
                "14" => 3,
                "@PlanID" => 23,
                "15" => 23,
                "@PlanTitle" => "",
                "16" => "",
                "@PlanAmount" => 49.95,
                "17" => 49.95,
                "@PlanDownload" => 15000,
                "18" => 15000,
                "@PlanUpload" => 5000,
                "19" => 5000,
                "@CityName" => "São Paulo",
                "20" => "São Paulo",
                "@InvoiceID" => 76348,
                "21" => 76348,
                "@InvoiceDueDate" => "2017-01-15",
                "22" => "2017-01-15",
                "@InvoicePaidDate" => "0000-00-00",
                "23" => "0000-00-00",
                "@InvoiceAmount" => 49.95,
                "24" => 49.95,
                "@InvoiceSubtotal" => 49.95,
                "25" => 49.95,
                "@InvoiceDiscount" => 0.00,
                "26" => 0.00,
                "@InvoiceTotal" => 49.95,
                "27" => 49.95,
                "@InvoiceReference" => "parcela",
                "28" => "parcela",
                "@CfopCode" => 307,
                "29" => 307,
                "@CfopDescription" => "Não contribuinte",
                "30" => "Não contribuinte"
            ),
            "2" => array
                (
                    "@ClientID" => 11111,
                    "0" => 11111,
                    "@ClientName" => "Robert Anton Wilson",
                    "1" => "Robert Anton Wilson",
                    "@ClientIE" => "00000000000000",
                    "2" => "00000000000000",
                    "@ClientCPF" => "407.229.699-61",
                    "3" => "407.229.699-61",
                    "@ClientCNPJ" =>"",
                    "4" =>"",
                    "@ClientAddress" => "Avenida Paulista",
                    "5" => "Avenida Paulista",
                    "@ClientAddressNumber" => 1047,
                    "6" => 1047,
                    "@ClientAddressComp" => "Teste",
                    "7" => "Teste",
                    "@ClientAddressSuburb" => "Bela Vista",
                    "8" => "Bela Vista",
                    "@ClientAddressZipcode" => "01311-200",
                    "9" => "01311-200",
                    "@ClientState" => "SP",
                    "10" => "SP",
                    "@ClientCity" => 9999,
                    "11" => 9999,
                    "@ClientPhone1" =>"",
                    "12" =>"",
                    "@ContractID" => 2323,
                    "13" => 2323,
                    "tipo_assin" => 3,
                    "14" => 3,
                    "@PlanID" => 24,
                    "15" => 24,
                    "@PlanTitle" => "",
                    "16" => "",
                    "@PlanAmount" => 49.95,
                    "17" => 49.95,
                    "@PlanDownload" => 15000,
                    "18" => 15000,
                    "@PlanUpload" => 5000,
                    "19" => 5000,
                    "@CityName" => "São Paulo",
                    "20" => "São Paulo",
                    "@InvoiceID" => 85790,
                    "21" => 85790,
                    "@InvoiceDueDate" => "2017-01-15",
                    "22" => "2017-01-15",
                    "@InvoicePaidDate" => "0000-00-00",
                    "23" => "0000-00-00",
                    "@InvoiceAmount" => 49.95,
                    "24" => 49.95,
                    "@InvoiceSubtotal" => 49.95,
                    "25" => 49.95,
                    "@InvoiceDiscount" => 0.00,
                    "26" => 0.00,
                    "@InvoiceTotal" => 49.95,
                    "27" => 49.95,
                    "@InvoiceReference" => "parcela",
                    "28" => "parcela",
                    "@CfopCode" => 307,
                    "29" => 307,
                    "@CfopDescription" => "Não contribuinte",
                    "30" => "Não contribuinte"
                ),
                "3" => array
                    (
                        "@ClientID" => 11111,
                        "0" => 11111,
                        "@ClientName" => "Robert Anton Wilson",
                        "1" => "Robert Anton Wilson",
                        "@ClientIE" => "00000000000000",
                        "2" => "00000000000000",
                        "@ClientCPF" => "407.229.699-61",
                        "3" => "407.229.699-61",
                        "@ClientCNPJ" =>"",
                        "4" =>"",
                        "@ClientAddress" => "Avenida Paulista",
                        "5" => "Avenida Paulista",
                        "@ClientAddressNumber" => 1047,
                        "6" => 1047,
                        "@ClientAddressComp" => "Teste",
                        "7" => "Teste",
                        "@ClientAddressSuburb" => "Bela Vista",
                        "8" => "Bela Vista",
                        "@ClientAddressZipcode" => "01311-200",
                        "9" => "01311-200",
                        "@ClientState" => "SP",
                        "10" => "SP",
                        "@ClientCity" => 9999,
                        "11" => 9999,
                        "@ClientPhone1" =>"",
                        "12" =>"",
                        "@ContractID" => 2323,
                        "13" => 2323,
                        "tipo_assin" => 3,
                        "14" => 3,
                        "@PlanID" => 24,
                        "15" => 24,
                        "@PlanTitle" => "",
                        "16" => "",
                        "@PlanAmount" => 49.95,
                        "17" => 49.95,
                        "@PlanDownload" => 15000,
                        "18" => 15000,
                        "@PlanUpload" => 5000,
                        "19" => 5000,
                        "@CityName" => "São Paulo",
                        "20" => "São Paulo",
                        "@InvoiceID" => 85790,
                        "21" => 85790,
                        "@InvoiceDueDate" => "2017-01-15",
                        "22" => "2017-01-15",
                        "@InvoicePaidDate" => "0000-00-00",
                        "23" => "0000-00-00",
                        "@InvoiceAmount" => 49.95,
                        "24" => 49.95,
                        "@InvoiceSubtotal" => 49.95,
                        "25" => 49.95,
                        "@InvoiceDiscount" => 0.00,
                        "26" => 0.00,
                        "@InvoiceTotal" => 49.95,
                        "27" => 49.95,
                        "@InvoiceReference" => "parcela",
                        "28" => "parcela",
                        "@CfopCode" => 307,
                        "29" => 307,
                        "@CfopDescription" => "Não contribuinte",
                        "30" => "Não contribuinte"
                    ),
                    "4" => array
                        (
                            "@ClientID" => 11111,
                            "0" => 11111,
                            "@ClientName" => "Robert Anton Wilson",
                            "1" => "Robert Anton Wilson",
                            "@ClientIE" => "00000000000000",
                            "2" => "00000000000000",
                            "@ClientCPF" => "407.229.699-61",
                            "3" => "407.229.699-61",
                            "@ClientCNPJ" =>"",
                            "4" =>"",
                            "@ClientAddress" => "Avenida Paulista",
                            "5" => "Avenida Paulista",
                            "@ClientAddressNumber" => 1047,
                            "6" => 1047,
                            "@ClientAddressComp" => "Teste",
                            "7" => "Teste",
                            "@ClientAddressSuburb" => "Bela Vista",
                            "8" => "Bela Vista",
                            "@ClientAddressZipcode" => "01311-200",
                            "9" => "01311-200",
                            "@ClientState" => "SP",
                            "10" => "SP",
                            "@ClientCity" => 9999,
                            "11" => 9999,
                            "@ClientPhone1" =>"",
                            "12" =>"",
                            "@ContractID" => 2323,
                            "13" => 2323,
                            "tipo_assin" => 3,
                            "14" => 3,
                            "@PlanID" => 24,
                            "15" => 24,
                            "@PlanTitle" => "",
                            "16" => "",
                            "@PlanAmount" => 49.95,
                            "17" => 49.95,
                            "@PlanDownload" => 15000,
                            "18" => 15000,
                            "@PlanUpload" => 5000,
                            "19" => 5000,
                            "@CityName" => "São Paulo",
                            "20" => "São Paulo",
                            "@InvoiceID" => 85790,
                            "21" => 85790,
                            "@InvoiceDueDate" => "2017-01-15",
                            "22" => "2017-01-15",
                            "@InvoicePaidDate" => "0000-00-00",
                            "23" => "0000-00-00",
                            "@InvoiceAmount" => 49.95,
                            "24" => 49.95,
                            "@InvoiceSubtotal" => 49.95,
                            "25" => 49.95,
                            "@InvoiceDiscount" => 0.00,
                            "26" => 0.00,
                            "@InvoiceTotal" => 49.95,
                            "27" => 49.95,
                            "@InvoiceReference" => "parcela",
                            "28" => "parcela",
                            "@CfopCode" => 307,
                            "29" => 307,
                            "@CfopDescription" => "Não contribuinte",
                            "30" => "Não contribuinte"
                        ),
                        "5" => array
                            (
                                "@ClientID" => 11111,
                                "0" => 11111,
                                "@ClientName" => "Robert Anton Wilson",
                                "1" => "Robert Anton Wilson",
                                "@ClientIE" => "00000000000000",
                                "2" => "00000000000000",
                                "@ClientCPF" => "407.229.699-61",
                                "3" => "407.229.699-61",
                                "@ClientCNPJ" =>"",
                                "4" =>"",
                                "@ClientAddress" => "Avenida Paulista",
                                "5" => "Avenida Paulista",
                                "@ClientAddressNumber" => 1047,
                                "6" => 1047,
                                "@ClientAddressComp" => "Teste",
                                "7" => "Teste",
                                "@ClientAddressSuburb" => "Bela Vista",
                                "8" => "Bela Vista",
                                "@ClientAddressZipcode" => "01311-200",
                                "9" => "01311-200",
                                "@ClientState" => "SP",
                                "10" => "SP",
                                "@ClientCity" => 9999,
                                "11" => 9999,
                                "@ClientPhone1" =>"",
                                "12" =>"",
                                "@ContractID" => 2323,
                                "13" => 2323,
                                "tipo_assin" => 3,
                                "14" => 3,
                                "@PlanID" => 24,
                                "15" => 24,
                                "@PlanTitle" => "",
                                "16" => "",
                                "@PlanAmount" => 49.95,
                                "17" => 49.95,
                                "@PlanDownload" => 15000,
                                "18" => 15000,
                                "@PlanUpload" => 5000,
                                "19" => 5000,
                                "@CityName" => "São Paulo",
                                "20" => "São Paulo",
                                "@InvoiceID" => 85790,
                                "21" => 85790,
                                "@InvoiceDueDate" => "2017-01-15",
                                "22" => "2017-01-15",
                                "@InvoicePaidDate" => "0000-00-00",
                                "23" => "0000-00-00",
                                "@InvoiceAmount" => 49.95,
                                "24" => 49.95,
                                "@InvoiceSubtotal" => 49.95,
                                "25" => 49.95,
                                "@InvoiceDiscount" => 0.00,
                                "26" => 0.00,
                                "@InvoiceTotal" => 49.95,
                                "27" => 49.95,
                                "@InvoiceReference" => "parcela",
                                "28" => "parcela",
                                "@CfopCode" => 307,
                                "29" => 307,
                                "@CfopDescription" => "Não contribuinte",
                                "30" => "Não contribuinte"
                            )

    );
    //print "<pre>ITEM "; print_r($r_item);

    try
    {
        // Esse metodo recebe o array $response_mestre como parametro, esse array contem o valor total dos item do documento fiscal.
        echo $nfsc->Item($response_mestre, $r_item, $nf_numero=1, $nf_ref_item, $data_apuracao, $data_emissao, $r_empresa);
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
         echo $nfsc->Cadastro($r_mestre, $nf_numero=0, $nf_ref_item, $data_apuracao, $data_emissao, $r_empresa, $database);
     }
     catch (Exception $e)
     {
         echo "<pre><b>Caught exception:</b> ",  $e->getMessage(), "\n</pre>";
     }




}
