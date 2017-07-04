--
-- @Date: 2017-03-16
-- @Description: CREATE TABLE `tb_municipios_ibge`
--
-- @Queries
-- select * from tb_cidades where nome LIKE "%itarar%"
-- select * from tb_cidades where nome LIKE "%mallet%"
--
-- CREATE TABLE `tb_municipios_ibge`
--
--
-- @Observacao: Caso precise adicionar mais cidade a essa tabela, use INSERT como modelo.
-- Onde `cod_mun`, `nom_mun`, `dt_ini`, `dt_fim` precisam vir da tabela de municipios do IBGE.
-- Onde `tb_cidades_id` é usado para criar a relação com a tabela de cidades do sistema do contribuinte.
--
CREATE TABLE IF NOT EXISTS `tb_municipios_ibge` (
  `id` int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `cod_mun` int(10) NOT NULL,
  `nom_mun` varchar(250) COLLATE 'latin1_swedish_ci' NOT NULL,
  `dt_ini` varchar(8) NOT NULL,
  `dt_fim` varchar(8) NOT NULL,
  `tb_cidades_id` int(4) unsigned zerofill NOT NULL
) ENGINE='MyISAM' CHARSET=latin1 COMMENT='tabelas de municipios do IBGE versao 11_14-01-2013';

-- INSERT DATA
INSERT INTO `tb_municipios_ibge` (`cod_mun`, `nom_mun`, `dt_ini`, `dt_fim`, `tb_cidades_id`)
VALUES ('3552205',	'Sorocaba',	'01012009',	'',	9555);



--
-- @Date: 2017-06-24
-- @Title: Tabela 1 - Nfsc_21_Mestre
-- @Description: CREATE TABLE `Nfsc_21_Mestre`
--
-- CREATE TABLE `Nfsc_21_Mestre`
--
-- @Observation: em caso de dúvida ler o conteudo do arquivo README.md localizado no mesmo diretorio desse script.
--
CREATE TABLE IF NOT EXISTS `Nfsc_21_Mestre` ( 
    `id` bigint(19) NOT NULL AUTO_INCREMENT, 
    `documento` int(14) NOT NULL, 
    `ie` varchar(14) NOT NULL, 
    `nome_cliente` varchar(35) NOT NULL, 
    `uf` varchar(2) NOT NULL, 
    `classe_consumo` int(1) NOT NULL, 
    `tipo_utilizacao` int(1) NOT NULL, 
    `grupo_tensao` int(2) NOT NULL, 
    `codigo_cliente` varchar(12) NOT NULL, 
    `data_emissao` int(8) NOT NULL, 
    `modelo` int(2) NOT NULL, 
    `serie` varchar(3) NOT NULL, 
    `numero` int(9) NOT NULL, 
    `hash_autenticacao_nf` varchar(32) NOT NULL, 
    `valor_total` int(12) NOT NULL, 
    `bc_icms` int(12) NOT NULL, 
    `icms_destacado` int(12) NOT NULL, 
    `isentas_nao_tributadas` int(12) NOT NULL, 
    `situacao_documento` varchar(1) NOT NULL, 
    `ano_mes_apuracao` int(4) NOT NULL, 
    `ref_item_nf` int(9) NOT NULL, 
    `subclasse_consumo` int(2) NOT NULL, 
    `num_terminal_telefonico_principal` varchar(12) NOT NULL, 
    `tipo_dado_campo_2` int(1) NOT NULL COMMENT 'campo 2 nessa tabela', 
    `outros_valores` int(12) NOT NULL, 
    `tipo_cliente` int(2) NOT NULL, 
    `num_terminal_telefonico` varchar(12) NOT NULL, 
    `valor_total_fatura_comercial` int(12) NOT NULL, 
    `brancos_50` varchar(50) NOT NULL, 
    `numero_fatura_comercial` varchar(20) NOT NULL, 
    `data_leitura_anterior` int(8) NOT NULL, 
    `data_leitura_atual` int(8) NOT NULL, 
    `info_adicional` varchar(30) NOT NULL, 
    `brancos_5` varchar(5) NOT NULL, 
    `cnpj_emitente` int(14) NOT NULL, 
    `brancos_8` int(8) NOT NULL, PRIMARY KEY (`id`), 
    `hash_campos` varchar(32) NOT NULL COMMENT 'hash md5 de todos os campos exceto o campo 1 (id)' 
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COMMENT='dados do arquivo Mestre NFSC'



--
-- @Date: 2017-06-24
-- @Title: Tabela 1 - Nfsc_21_Item
-- @Description: CREATE TABLE `Nfsc_21_Item`
--
-- CREATE TABLE `Nfsc_21_Item`
--
-- @Observation: em caso de dúvida ler o conteudo do arquivo README.md localizado no mesmo diretorio desse script.
--
CREATE TABLE IF NOT EXISTS `Nfsc_21_Item` ( 
    `id` int(19) NOT NULL, 
    `documento` int(14) NOT NULL, 
    `uf` varchar(2) COLLATE latin1_general_ci NOT NULL, 
    `classe_consumo` int(1) NOT NULL, 
    `tipo_utilizacao` int(1) NOT NULL, 
    `grupo_tensao` int(2) NOT NULL, 
    `data_emissao` int(8) NOT NULL, 
    `modelo` int(2) NOT NULL, 
    `serie` varchar(3) COLLATE latin1_general_ci NOT NULL, 
    `numero` int(9) NOT NULL COMMENT 'numero da nf', 
    `cfop` int(4) NOT NULL, 
    `ordem_item` int(3) NOT NULL, 
    `codigo_item` varchar(10) COLLATE latin1_general_ci NOT NULL, 
    `descricao_item` varchar(40) COLLATE latin1_general_ci NOT NULL, 
    `codigo_class_item` int(4) NOT NULL, 
    `unidade` varchar(6) COLLATE latin1_general_ci NOT NULL, 
    `quantidade_contratada` int(12) NOT NULL, 
    `bc_icms` int(11) NOT NULL COMMENT 'base de calculo do icms', 
    `total` int(11) NOT NULL, 
    `acrescimos_despesas_acessorias` int(11) NOT NULL, 
    `icms` int(11) NOT NULL, 
    `quantidade_medida` int(12) NOT NULL, 
    `aliquota_icms` int(4) NOT NULL, 
    `ano_mes_ref_apuracao` varchar(4) COLLATE latin1_general_ci NOT NULL, 
    `isentas_nao_tributadas` int(11) NOT NULL, 
    `outros_valores` int(11) NOT NULL, 
    `descontos` int(11) NOT NULL, 
    `situacao` varchar(1) COLLATE latin1_general_ci NOT NULL, 
    `quantidade_faturada` int(12) NOT NULL, 
    `cofins` int(11) NOT NULL, 
    `tarifa_aplicada` int(11) NOT NULL, 
    `aliquota_cofins` int(6) NOT NULL, 
    `pis_pasep` int(11) NOT NULL, 
    `aliquota_pis_pasep` int(6) NOT NULL, 
    `tipo_isencao` int(2) NOT NULL, 
    `numero_contrato` varchar(15) COLLATE latin1_general_ci NOT NULL, 
    `brancos_5` varchar(5) COLLATE latin1_general_ci NOT NULL, 
    `desconto_judicial` varchar(1) COLLATE latin1_general_ci NOT NULL, 
    `hash_registro` varchar(32) COLLATE latin1_general_ci NOT NULL COMMENT 'hash md5 de todos os campos exceto campo 1' 
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COMMENT='dados do arquivo Item NFSC'



--
-- @Date: 2017-06-24
-- @Title: Tabela 1 - Nfsc_21_Cadastro
-- @Description: CREATE TABLE `Nfsc_21_Cadastro`
--
-- CREATE TABLE `Nfsc_21_Cadastro`
--
-- @Observation: em caso de dúvida ler o conteudo do arquivo README.md localizado no mesmo diretorio desse script.
--
CREATE TABLE IF NOT EXISTS `Nfsc_21_Cadastro` ( 
    `id` bigint(19) NOT NULL AUTO_INCREMENT, 
    `documento` int(14) NOT NULL, 
    `ie` varchar(14) COLLATE latin1_general_ci NOT NULL, 
    `nome_cliente` varchar(35) COLLATE latin1_general_ci NOT NULL, 
    `logradouro` varchar(45) COLLATE latin1_general_ci NOT NULL, 
    `numero` int(5) NOT NULL, 
    `complemento` varchar(15) COLLATE latin1_general_ci NOT NULL, 
    `cep` int(8) NOT NULL, 
    `bairro` varchar(15) COLLATE latin1_general_ci NOT NULL, 
    `municipio` varchar(30) COLLATE latin1_general_ci NOT NULL, 
    `uf` varchar(2) COLLATE latin1_general_ci NOT NULL, 
    `telefone` varchar(12) COLLATE latin1_general_ci NOT NULL, 
    `codigo_cliente` varchar(12) COLLATE latin1_general_ci NOT NULL, 
    `terminal_telefonico` varchar(12) COLLATE latin1_general_ci NOT NULL, 
    `uf_terminal_telefonico` varchar(2) COLLATE latin1_general_ci NOT NULL, 
    `data_emissao` int(8) NOT NULL, 
    `numero_nf` int(9) NOT NULL, 
    `modelo` int(2) NOT NULL, 
    `codigo_municipio` int(7) NOT NULL, 
    `brancos_5` varchar(5) COLLATE latin1_general_ci NOT NULL, 
    `serie` varchar(3) COLLATE latin1_general_ci NOT NULL, 
    `hash_autenticacao_registro` varchar(32) COLLATE latin1_general_ci NOT NULL 
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COMMENT='dados do arquivo Cadastro NFSC'