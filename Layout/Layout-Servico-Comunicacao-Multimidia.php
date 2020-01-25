<?php
include '../class/template.class.php';
require '../config.php';
error_reporting(0);
// check se alguma funcao esta habilitada
$func = 'shell_exec';
if (function_exists($func)) {
	//echo "function ".$func." is enabled!";
}

/**
 * Consulta dados da empresa
 */
$data_company = $database->select("Test_Empresa", "*", array("id[=]" => 1));


/**
 * Pode usar o ID da sessao do cliente para nao permitir acesso indevido caso seja necessario.
 */
if (isset($_GET['cliente']) and !empty($_GET['cliente'])) {

	if (filter_var($_GET['cliente'], FILTER_VALIDATE_INT) or is_array($_GET['cliente'])) {

		$codigo_cliente = $_GET['cliente'];

	} else {

		die('aviso: ID do cliente precisa ser um array ou inteiro.');
	}
}


/**
 * data inicial - formato: YYYYMMDD 20190228
 */
if (isset($_GET['data_referente']) and !empty($_GET['data_referente'])) {

	$data_referente = $_GET['data_referente'];
	$exp = explode('-', $data_referente);
	$data_inicial = $exp['0'].$exp['1'].'01';
	$data_final = $exp['0'].$exp['1'].'31';
}


/**
 * define o protocolo
 */
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");


/**
 * consulta multiplos clientes
 */
if (is_array($_GET['cliente'])) {

	foreach ($_GET['cliente'] as $clikey => $clival) {

		/**
		 * no banco de dados foi criado um index para as colunas -> `nfi`.`numero`, `nfm`.`numero` e `nfc`.`numero_nf` 
		 */
		$query = "
			SELECT 
				`nfm`.`documento` as `@clienteDocumento`, `nfm`.`ie` as `@clienteIE`, `nfm`.`nome_cliente` as `@clienteNome`, `nfm`.`uf` as `@UFCliente`, 
				`nfm`.`numero` as `@numeroNF`, `nfm`.`codigo_cliente` as `@codigoCliente`, `nfm`.`data_emissao` as `@dataEmissao`, `nfm`.`modelo` as `@modelo`, 
				`nfm`.`serie` as `@serie`, `nfm`.`situacao_documento` as `@situacaoDocumento`, `nfm`.`ano_mes_apuracao` as `@periodoApuracao`, 
				`nfm`.`valor_total` as `@valorTotal`, `nfm`.`hash_campos` as `@hashCampos`, `nfi`.`codigo_item` as `@codigoItem`, `nfi`.`descricao_item` as `@itemDescricao`, 
				`nfi`.`cfop` as `@itemCFOP`, `nfi`.`aliquota_icms` as `@itemAliquotaICMS`, `nfi`.`total` as `@itemTotal`, `nfi`.`bc_icms` as `@itemBcICMS`, 
				`nfi`.`icms` as `@itemICMS`, `nfc`.`logradouro` as `@cadastroLogradouro`, `nfc`.`numero` as `@cadastroNumero`, 
				`nfc`.`complemento` as `@cadastroComplemento`, `nfc`.`cep` as `@cadastroCEP`, `nfc`.`bairro` as `@cadastroBairro`, 
				`nfc`.`municipio` as `@cadastroMunicipio`, `nfc`.`uf` as `@cadastroUF`, `nfc`.`telefone` as `@cadastroTelefone` 
			FROM 
				Nfsc_21_Mestre nfm 
			INNER JOIN 
				Nfsc_21_Item nfi ON `nfi`.`numero` = `nfm`.`numero` 
			INNER JOIN 
				Nfsc_21_Cadastro nfc ON `nfc`.`numero_nf` = `nfm`.`numero` 
			WHERE 
				`nfm`.`codigo_cliente` = {$clival} and 
				( `nfm`.`data_emissao` BETWEEN '{$data_inicial}' AND '{$data_final}' ) 
			GROUP BY 
				`nfm`.`numero` ASC;
		";		
		$data_nf[] = $database->query($query)->fetchAll();
	}

	//print '<pre>'; print_r($data_nf); print '</pre>';
	foreach ($data_nf as $knf => $vnf) {

		/**
		 * Imprimir URI usando o binario phantomjs.
		 * execute o comando `phantomjs` e o PDF vai ser gerado no seu disco, 
		 * execute `pkill -9 phantomjs` para matar o processo que as vezes 
		 * persiste e faz uso extensivo da CPU.
		 * Obs.: phantomjs requer o uso de libfontconfig, instalacao para ubuntu 
		 * `apt-get install libfontconfig`
		 * 
		 * IMPORTANTE: phantomjs e problema com ubuntu 18, NAO USAR.
		 */
		$url = $protocol . "://$_SERVER[HTTP_HOST]/nfsc/Layout/Layout-Servico-Comunicacao-Multimidia.php?cliente=".trim($vnf['0']['@codigoCliente'])."&data_referente=".$data_referente."&periodo_apuracao=".$vnf['0']['@periodoApuracao'];  // NF em HTML $_SERVER[REQUEST_URI]
		$url = escapeshellarg($url);
		$pdf = trim($vnf['0']['@codigoCliente']) . "_" . $vnf['0']['@dataEmissao'] . "_" . $vnf['0']['@periodoApuracao'] . ".pdf";
		//$pdf_filename = $protocol . "://$_SERVER[HTTP_HOST]/nfsc/Layout/" . $pdf; // NF em PDF
		$pdf_filename = "PDF/" . $pdf; // NF em PDF
		// adicione 2>&1 no final do comando para retornar STDERR returned e STDOUT
		$cmdPhantom = '/var/www/nfsc/bin/phantomjs /var/www/nfsc/assets/rasterize.js ' . $url . ' ' . $pdf_filename . ' A4 2>&1';
		$cmdPkill = 'pkill -9 phantomjs 2>&1';
		//$outputpkill = shell_exec($cmdPkill);
		//$output = shell_exec($cmdPhantom);
		//$outputpkill = shell_exec($cmdPkill);
		//print '<br>PDF ' . $pdf_filename;
		//var_dump($output); // para debugar
		// reinicializar variaveis
		$url = '';
		$pdf = '';
		$pdf_filename = '';

		/**
		 * atualizar o status 
		 * UPDATE `Nfsc_21_Notas` SET `status` = 'impressa' WHERE `Nfsc_21_Notas`.`id` = 1;
		 */
	}

	

/**
 * consulta cliente individualmente
 */
} else {


	/**
	 * no banco de dados foi criado um index para as colunas -> `nfi`.`numero`, `nfm`.`numero` e `nfc`.`numero_nf` 
	 */
	$query = "
		SELECT 
			`nfm`.`documento` as `@clienteDocumento`, `nfm`.`ie` as `@clienteIE`, `nfm`.`nome_cliente` as `@clienteNome`, `nfm`.`uf` as `@UFCliente`, 
			`nfm`.`numero` as `@numeroNF`, `nfm`.`codigo_cliente` as `@codigoCliente`, `nfm`.`data_emissao` as `@dataEmissao`, `nfm`.`modelo` as `@modelo`, 
			`nfm`.`serie` as `@serie`, `nfm`.`situacao_documento` as `@situacaoDocumento`, `nfm`.`ano_mes_apuracao` as `@periodoApuracao`, 
			`nfm`.`valor_total` as `@valorTotal`, `nfm`.`hash_campos` as `@hashCampos`, `nfi`.`codigo_item` as `@codigoItem`, `nfi`.`descricao_item` as `@itemDescricao`, 
			`nfi`.`cfop` as `@itemCFOP`, `nfi`.`aliquota_icms` as `@itemAliquotaICMS`, `nfi`.`total` as `@itemTotal`, `nfi`.`bc_icms` as `@itemBcICMS`, 
			`nfi`.`icms` as `@itemICMS`, `nfc`.`logradouro` as `@cadastroLogradouro`, `nfc`.`numero` as `@cadastroNumero`, 
			`nfc`.`complemento` as `@cadastroComplemento`, `nfc`.`cep` as `@cadastroCEP`, `nfc`.`bairro` as `@cadastroBairro`, 
			`nfc`.`municipio` as `@cadastroMunicipio`, `nfc`.`uf` as `@cadastroUF`, `nfc`.`telefone` as `@cadastroTelefone` 
		FROM 
			Nfsc_21_Mestre nfm 
		INNER JOIN 
			Nfsc_21_Item nfi ON `nfi`.`numero` = `nfm`.`numero` 
		INNER JOIN 
			Nfsc_21_Cadastro nfc ON `nfc`.`numero_nf` = `nfm`.`numero` 
		WHERE 
			`nfm`.`codigo_cliente` = {$codigo_cliente} and 
			( `nfm`.`data_emissao` BETWEEN '{$data_inicial}' AND '{$data_final}' ) 
		GROUP BY 
			`nfm`.`numero` ASC;
	";
	$data_nf = $database->query($query)->fetchAll();

	/**
	 * impressao de NF individualmente
	 */
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
	// se estiver vazio setamos manualmente essa informacao
	if (empty($data_company)) 
	{
		//$data_company['0']['cnpj'] = '15.671.427/0001-99';
		//$data_company['0']['estado'] = 'SC';
		//$data_company['0']['razao_social'];
		//$data_company['0']['ie'];
		//$data_company['0']['endereco'];
		//$data_company['0']['bairro'];
		//$data_company['0']['cep'];
		//$data_company['0']['cidade'];
		//$data_company['0']['email'];
		//$data_company['0']['tel1'];
	
		$layout->set("empresa_operadora", 'PROVEDOR X LTDA ME');
		$layout->set("empresa_cnpj", '99.999.999/0001-99');
		$layout->set("empresa_inscricao", '999.999.999');
		$layout->set("empresa_endereco", 'RUA CENTRAL, 123');
		$layout->set("empresa_bairro", 'CENTRO');
		$layout->set("empresa_cep", '99999999');
		$layout->set("empresa_cidade", 'CURITIBA');
		$layout->set("empresa_uf", 'PR');
		$layout->set("empresa_email", 'contato@example.com');
		$layout->set("empresa_tel", '(41) 9999-9999');
	}
	else
	{
		$layout->set("empresa_operadora", $data_company['0']['razao_social']);
		$layout->set("empresa_cnpj", $data_company['0']['cnpj']);
		$layout->set("empresa_inscricao", $data_company['0']['ie']);
		$layout->set("empresa_endereco", $data_company['0']['endereco']);
		$layout->set("empresa_bairro", $data_company['0']['bairro']);
		$layout->set("empresa_cep", $data_company['0']['cep']);
		$layout->set("empresa_cidade", $data_company['0']['cidade']);
		$layout->set("empresa_uf", $data_company['0']['estado']);
		$layout->set("empresa_email", $data_company['0']['email']);
		$layout->set("empresa_tel", $data_company['0']['tel1']);
	}

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
	$layout->set("cliente_email", ''); 
	$layout->set("cliente_tel", $data_nf['0']['@cadastroTelefone']);
	$layout->set("uf_cliente", $data_nf['0']['@UFCliente']);
	$layout->set("codigo_cliente", $data_nf['0']['@codigoCliente']);
	
	/**
	 * nf
	 */
	$data_nf_numero = str_pad($data_nf['0']['@numeroNF'], 7, "0", STR_PAD_LEFT);
	$newDate = date("d/m/Y", strtotime($data_nf['0']['@dataEmissao']));
	$layout->set("nf_title", $nf_title);
	$layout->set("nf_numero", $data_nf_numero);
	$layout->set("cfop", $data_nf['0']['@itemCFOP']);
	$layout->set("data_emissao", $newDate);
	$layout->set("data_referente", date("d/m/Y", strtotime($data_referente)));
	$layout->set("periodo_apuracao", $data_nf['0']['@periodoApuracao']);
	$layout->set("hash_campos", $data_nf['0']['@hashCampos']);
	
	/**
	 * item
	 */
	$top = 235; # usado na formatacao do layout, nao remover.
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
	$item_aliq_icms_dec = ($data_nf['0']['@itemAliquotaICMS']/100);
	$item_total_dec = ($data_nf['0']['@valorTotal']/100);
	$item_valor_total_dec = ($item_valor_total/100);
	$layout->set("item_sequencial", $data_nf['0']['@codigoItem']); // usando o cod. do item
	$layout->set("item_descricao", $data_nf['0']['@itemDescricao']);
	$layout->set("item_aliq_icms", $item_aliq_icms_dec);
	$layout->set("item_total", $item_total_dec);
	$layout->set("item_valor_total", $item_valor_total_dec); // total dos itens na nota
	
	/**
	 * imposto
	 */
	$imposto_bc_icms_dec = ($data_nf['0']['@itemBcICMS']/100);
	$imposto_aliq_icms_dec = ($data_nf['0']['@itemAliquotaICMS']/100);
	$imposto_icms_dec = ($data_nf['0']['@itemICMS']/100);
	$layout->set("imposto_bc_icms", $imposto_bc_icms_dec);
	$layout->set("imposto_aliq_icms", $imposto_aliq_icms_dec);
	$layout->set("imposto_icms", $imposto_icms_dec); # valor do icms
	$layout->set("imposto_situacao_doc_fiscal", $data_nf['0']['@situacaoDocumento']);
	$layout->set("imposto_obs1", 'I - Documento emitido por ME ou EPP, optante pelo Simples Nacional.');
	$layout->set("imposto_obs2", 'II - Não gera direito a Crédito Fiscal de ICMS, ISS e IPI conforme Lei Complementar 123/2006.');
		
	/**
	 * Outputs the page with the user's profile.
	 */
	$layout->set("content", $layout->output());
	echo $layout->output();

	/**
	 * Imprimir URI usando o binario phantomjs.
	 * execute o comando `phantomjs` e o PDF vai ser gerado no seu disco, 
	 * execute `pkill -9 phantomjs` para matar o processo que as vezes 
	 * persiste e faz uso extensivo da CPU.
	 * Obs.: phantomjs requer o uso de libfontconfig, instalacao para ubuntu 
	 * `apt-get install libfontconfig`
	 * 
	 * IMPORTANTE: phantomjs e problema com ubuntu 18, NAO USAR.
	 */
	$url = $protocol . "://$_SERVER[HTTP_HOST]/nfsc/Layout/Layout-Servico-Comunicacao-Multimidia.php?cliente=".trim($codigo_cliente)."&data_referente=".$data_referente."&periodo_apuracao=".$data_nf['0']['@periodoApuracao'];  // NF em HTML $_SERVER[REQUEST_URI]
	$url = escapeshellarg($url);
	$pdf = trim($codigo_cliente) . "_" . $data_nf['0']['@dataEmissao'] . "_" . $data_nf['0']['@periodoApuracao'] . ".pdf";
	//$pdf_filename = $protocol . "://$_SERVER[HTTP_HOST]/nfsc/Layout/" . $pdf; // NF em PDF
	$pdf_filename = "PDF/" . $pdf; // NF em PDF
	// adicione 2>&1 no final do comando para retornar STDERR returned e STDOUT
	$cmdPhantom = '/var/www/nfsc/bin/phantomjs /var/www/nfsc/assets/rasterize.js ' . $url . ' ' . $pdf_filename . ' A4 2>&1';
	$cmdPkill = 'pkill -9 phantomjs 2>&1';
	//$outputpkill = shell_exec($cmdPkill);
	//$output = shell_exec($cmdPhantom);
	//$outputpkill = shell_exec($cmdPkill);
	var_dump($output); // para debugar
	// reinicializar variaveis
	$url = '';
	$pdf = '';
	$pdf_filename = '';


	/**
	 * atualizar o status da nota para impressa
	 * UPDATE `Nfsc_21_Notas` SET `status` = 'impressa' WHERE `Nfsc_21_Notas`.`id` = $_GET['tblnotasid'];
	 */
	$database->update("Nfsc_21_Notas", [
		"status" => "impressa",
		"impressa" => 1
	], [
		"id[=]" => $_GET['tblnotasid'] 
	]);

}
//print '<pre>'; print_r($data_nf); print '</pre>';
