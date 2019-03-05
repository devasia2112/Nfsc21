<?php
include '../class/template.class.php';
require '../config.php';

/**
 * Query
 */
$data_company = $database->select("empresa", "*", array("id[=]" => 2));

// Usar o ID da sessao do cliente para nao permitir acesso indevido.
if (isset($_GET['client']) and !empty($_GET['client'])) {
	if (filter_var($_GET['client'], FILTER_VALIDATE_INT)) {
		$codigo_cliente = $_GET['client'];
	} else {
		die('aviso: O ID do cliente precisa ser um inteiro.');
	}
}

/**
 * no banco de dados foi criado um index para as colunas -> `nfi`.`numero`, `nfm`.`numero` e `nfc`.`numero_nf` 
 */
$data_nf = $database->query(
	"SELECT 
	    `nfm`.`documento` as `@clienteDocumento`, `nfm`.`ie` as `@clienteIE`, `nfm`.`nome_cliente` as `@clienteNome`, 
	    `nfm`.`numero` as `@numeroNF`, `nfm`.`data_emissao` as `@dataEmissao`, `nfm`.`modelo` as `@modelo`, `nfm`.`serie` as `@serie`, 
	    `nfm`.`situacao_documento` as `@situacaoDocumento`, `nfm`.`valor_total` as `@valorTotal`, `nfi`.`codigo_item` as `@codigoItem`, 
	    `nfi`.`descricao_item` as `@itemDescricao`, `nfi`.`cfop` as `@itemCFOP`, `nfi`.`aliquota_icms` as `@itemAliquotaICMS`, 
	    `nfi`.`total` as `@itemTotal`, `nfi`.`bc_icms` as `@itemBcICMS`, `nfi`.`icms` as `@itemICMS`, 
	    `nfc`.`logradouro` as `@cadastroLogradouro`, `nfc`.`numero` as `@cadastroNumero`, `nfc`.`complemento` as `@cadastroComplemento`, 
	    `nfc`.`cep` as `@cadastroCEP`, `nfc`.`bairro` as `@cadastroBairro`, `nfc`.`municipio` as `@cadastroMunicipio`, 
	    `nfc`.`uf` as `@cadastroUF`, `nfc`.`telefone` as `@cadastroTelefone` 
	FROM Nfsc_21_Mestre nfm 
	INNER JOIN Nfsc_21_Item nfi ON `nfi`.`numero` = `nfm`.`numero` 
	INNER JOIN Nfsc_21_Cadastro nfc ON `nfc`.`numero_nf` = `nfm`.`numero` 
	WHERE `nfm`.`codigo_cliente` = {$codigo_cliente} and ( `nfm`.`data_emissao` BETWEEN '20190201' AND '20190228' ) 
	GROUP BY `nfm`.`numero` ASC"
)->fetchAll();

$nf_modelo = $data_nf['0']['@modelo'];  # 21 ou 22
$nf_serie  = $data_nf['0']['@serie'];   # U = SERIE ÚNICA
$nf_title  = "NOTA FISCAL DE SERVIÇO DE COMUNICAÇÃO - MODELO {$nf_modelo} SÉRIE {$nf_serie}";

/**
 * Creates a new template for the user's profile.
 * Fills it with mockup data just for testing.
 */
$layout = new Template("Layout-Servico-Comunicacao-Multimidia.tpl");

/**
 * Loads our layout template, settings its title and content.
 * //$layout = new Template("Layout-Servico-Comunicacao-Multimidia.tpl");
 */
$layout->set("title", $nf_title);

/**
 * empresa
 */
$layout->set("empresa_operadora", $data_company['0']['razao_social']);
$layout->set("empresa_cnpj", $data_company['0']['cnpj']);
$layout->set("empresa_inscricao", $data_company['0']['ie']);
$layout->set("empresa_endereco", $data_company['0']['endereco']);
$layout->set("empresa_bairro", $data_company['0']['bairro']);
$layout->set("empresa_cep", $data_company['0']['cep']);
$layout->set("empresa_cidade", $data_company['0']['cidade']);
$layout->set("empresa_email", $data_company['0']['email']);
$layout->set("empresa_tel", $data_company['0']['tel1']);

/**
 * cliente
 */
$endereco_numero = $data_nf['0']['@cadastroLogradouro'].', '.$data_nf['0']['@cadastroNumero'];
$layout->set("cliente_nome", $data_nf['0']['@clienteNome']);
$layout->set("cliente_documento1", $data_nf['0']['@clienteDocumento']);
$layout->set("cliente_documento2", $data_nf['0']['@clienteIE']);
$layout->set("cliente_endereco", $endereco_numero);
$layout->set("cliente_bairro", $data_nf['0']['@cadastroBairro']);
$layout->set("cliente_cep", $data_nf['0']['@cadastroCEP']);
$layout->set("cliente_cidade", $data_nf['0']['@cadastroMunicipio']);
$layout->set("cliente_email", $data_company['0']['email']); 
$layout->set("cliente_tel", $data_nf['0']['@cadastroTelefone']);

/**
 * nf
 */
$newDate = date("d/m/Y", strtotime($data_nf['0']['@dataEmissao']));
$layout->set("nf_title", $nf_title);
$layout->set("nf_numero", $data_nf['0']['@numeroNF']);
$layout->set("cfop", $data_nf['0']['@itemCFOP']);
$layout->set("data_emissao", $newDate);

/**
 * item
 */
$top = 235; # usado na formatacao do layout
$item_valor_total = 0;
foreach ($data_nf as $item) {

	if ($item['@valorTotal']) {
		$item_valor_total += $item['@valorTotal'];
	}

	$row = new Template("Layout-Servico-Comunicacao-Multimidia-Item.tpl");	
	foreach ($item as $key => $value) {

		if ($key == '@codigoItem' or $key == '@itemDescricao' or $key == '@itemAliquotaICMS' or $key == '@valorTotal') {
			$row->set($key, $value);
			$row->set('top', $top);
			$top = $top+3;
		}

	}
	$itemsTemplates[] = $row;
}

/**
 * Merges all our users' templates into a single variable.
 * This will allow us to use it in the main template.
 */
$itemsContents = Template::merge($itemsTemplates);

/**
 * Defines the main template and sets the users' content.
 */
$layout->set("items", $itemsContents);

$layout2 = new Template("Layout-Servico-Comunicacao-Multimidia.tpl");

$layout->set("item_sequencial", $data_nf['0']['@codigoItem']); // usando o cod. do item
$layout->set("item_descricao", $data_nf['0']['@itemDescricao']);
$layout->set("item_aliq_icms", $data_nf['0']['@itemAliquotaICMS']);
$layout->set("item_total", $data_nf['0']['@valorTotal']);
$layout->set("item_valor_total", $item_valor_total); // total dos itens na nota

/**
 * imposto
 */
$layout->set("imposto_bc_icms", $data_nf['0']['@itemBcICMS']);
$layout->set("imposto_aliq_icms", $data_nf['0']['@itemAliquotaICMS']);
$layout->set("imposto_icms", $data_nf['0']['@itemICMS']); # valor do icms
$layout->set("imposto_situacao_doc_fiscal", $data_nf['0']['@situacaoDocumento']);
$layout->set("imposto_obs", 'N/D');

/**
 * Outputs the page with the user's profile.
 */
$layout->set("content", $layout->output());
echo $layout->output();
