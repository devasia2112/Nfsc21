-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 28-Abr-2021 às 19:36
-- Versão do servidor: 10.4.18-MariaDB
-- versão do PHP: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `mydb`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `cfop`
--

CREATE TABLE `cfop` (
  `id` int(11) NOT NULL,
  `cfop` int(11) NOT NULL,
  `descricao` text NOT NULL,
  `aplicacao` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `nfsc_21_cadastro`
--

CREATE TABLE `nfsc_21_cadastro` (
  `id` bigint(19) NOT NULL,
  `documento` int(14) NOT NULL,
  `ie` varchar(14) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `nome_cliente` varchar(35) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `logradouro` varchar(45) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `numero` int(5) NOT NULL,
  `complemento` varchar(15) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `cep` int(8) NOT NULL,
  `bairro` varchar(15) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `municipio` varchar(30) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `uf` varchar(2) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `telefone` varchar(12) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `codigo_cliente` varchar(12) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `terminal_telefonico` varchar(12) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `uf_terminal_telefonico` varchar(2) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `data_emissao` int(8) NOT NULL,
  `numero_nf` int(9) NOT NULL,
  `modelo` int(2) NOT NULL,
  `codigo_municipio` int(7) NOT NULL,
  `brancos_5` varchar(5) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `serie` varchar(3) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `hash_autenticacao_registro` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='dados do arquivo Cadastro NFSC';

--
-- Extraindo dados da tabela `nfsc_21_cadastro`
--

INSERT INTO `nfsc_21_cadastro` (`id`, `documento`, `ie`, `nome_cliente`, `logradouro`, `numero`, `complemento`, `cep`, `bairro`, `municipio`, `uf`, `telefone`, `codigo_cliente`, `terminal_telefonico`, `uf_terminal_telefonico`, `data_emissao`, `numero_nf`, `modelo`, `codigo_municipio`, `brancos_5`, `serie`, `hash_autenticacao_registro`) VALUES
(1, 2147483647, 'ISENTO        ', 'ROBERT ANTON WILSON                ', 'AVENIDA PAULISTA                             ', 1047, 'TESTE          ', 1311200, 'BELA VISTA     ', 'Sorocaba                      ', 'SP', '1123232323  ', '11111       ', '1123232323  ', '  ', 20210428, 1, 21, 3552205, '     ', 'U  ', 'bf426e611283f285f2d141117a991ad8');

-- --------------------------------------------------------

--
-- Estrutura da tabela `nfsc_21_item`
--

CREATE TABLE `nfsc_21_item` (
  `id` int(19) NOT NULL,
  `documento` varchar(14) COLLATE latin1_general_ci NOT NULL,
  `uf` varchar(2) COLLATE latin1_general_ci NOT NULL,
  `classe_consumo` int(1) NOT NULL,
  `tipo_utilizacao` int(1) NOT NULL,
  `grupo_tensao` int(2) NOT NULL,
  `data_emissao` int(8) NOT NULL,
  `modelo` int(2) NOT NULL,
  `serie` varchar(3) COLLATE latin1_general_ci NOT NULL,
  `numero` int(9) NOT NULL COMMENT 'numero da nf',
  `cfop` int(4) NOT NULL,
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
  `hash_registro` varchar(32) COLLATE latin1_general_ci NOT NULL COMMENT 'hash md5 de todos os campos exceto campo 1',
  `desconto_judicial` varchar(1) COLLATE latin1_general_ci NOT NULL,
  `ordem_item` int(3) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci COMMENT='dados do arquivo Item NFSC';

--
-- Extraindo dados da tabela `nfsc_21_item`
--

INSERT INTO `nfsc_21_item` (`id`, `documento`, `uf`, `classe_consumo`, `tipo_utilizacao`, `grupo_tensao`, `data_emissao`, `modelo`, `serie`, `numero`, `cfop`, `codigo_item`, `descricao_item`, `codigo_class_item`, `unidade`, `quantidade_contratada`, `bc_icms`, `total`, `acrescimos_despesas_acessorias`, `icms`, `quantidade_medida`, `aliquota_icms`, `ano_mes_ref_apuracao`, `isentas_nao_tributadas`, `outros_valores`, `descontos`, `situacao`, `quantidade_faturada`, `cofins`, `tarifa_aplicada`, `aliquota_cofins`, `pis_pasep`, `aliquota_pis_pasep`, `tipo_isencao`, `numero_contrato`, `brancos_5`, `hash_registro`, `desconto_judicial`, `ordem_item`) VALUES
(1, '06041711141000', 'SP', 0, 4, 0, 20210428, 21, 'U  ', 1, 307, '23        ', '                                        ', 307, '      ', 1000, 4995, 4995, 0, 0, 0, 125, '2104', 0, 0, 0, 'N', 14648, 0, 0, 0, 0, 0, 7, '98765          ', '     ', '28a1e3f8efdef4fb790a1150a0d9bc03', ' ', 1),
(2, '40722969961000', 'SP', 0, 4, 0, 20210428, 21, 'U  ', 2, 307, '23        ', '                                        ', 307, '      ', 1000, 4995, 4995, 0, 0, 0, 125, '2104', 0, 0, 0, 'N', 14648, 0, 0, 0, 0, 0, 7, '2323           ', '     ', '9c101b439d79a0075ecf39a5e767de7e', ' ', 1),
(3, '40722969961000', 'SP', 0, 4, 0, 20210428, 21, 'U  ', 2, 307, '24        ', '                                        ', 307, '      ', 1000, 4995, 4995, 0, 0, 0, 125, '2104', 0, 0, 0, 'N', 14648, 0, 0, 0, 0, 0, 7, '2323           ', '     ', '40fdc4a58d822a207f7a220328da33df', ' ', 2),
(4, '40722969961000', 'SP', 0, 4, 0, 20210428, 21, 'U  ', 2, 307, '24        ', '                                        ', 307, '      ', 1000, 4995, 4995, 0, 0, 0, 125, '2104', 0, 0, 0, 'N', 14648, 0, 0, 0, 0, 0, 7, '2323           ', '     ', 'bd047031a710a14bd878f1b56bd7518f', ' ', 3),
(5, '40722969961000', 'SP', 0, 4, 0, 20210428, 21, 'U  ', 2, 307, '24        ', '                                        ', 307, '      ', 1000, 4995, 4995, 0, 0, 0, 125, '2104', 0, 0, 0, 'N', 14648, 0, 0, 0, 0, 0, 7, '2323           ', '     ', '0f561e43a25316ce3326e1e5d4c38dd8', ' ', 4),
(6, '40722969961000', 'SP', 0, 4, 0, 20210428, 21, 'U  ', 2, 307, '24        ', '                                        ', 307, '      ', 1000, 4995, 4995, 0, 0, 0, 125, '2104', 0, 0, 0, 'N', 14648, 0, 0, 0, 0, 0, 7, '2323           ', '     ', 'f88db0b6af8fa979e3dd4962d89d5365', ' ', 5);

-- --------------------------------------------------------

--
-- Estrutura da tabela `nfsc_21_mestre`
--

CREATE TABLE `nfsc_21_mestre` (
  `id` bigint(19) NOT NULL,
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
  `brancos_8` int(8) NOT NULL,
  `hash_campos` varchar(32) NOT NULL COMMENT 'hash md5 de todos os campos exceto o campo 1 (id)'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='dados do arquivo Mestre NFSC';

--
-- Extraindo dados da tabela `nfsc_21_mestre`
--

INSERT INTO `nfsc_21_mestre` (`id`, `documento`, `ie`, `nome_cliente`, `uf`, `classe_consumo`, `tipo_utilizacao`, `grupo_tensao`, `codigo_cliente`, `data_emissao`, `modelo`, `serie`, `numero`, `hash_autenticacao_nf`, `valor_total`, `bc_icms`, `icms_destacado`, `isentas_nao_tributadas`, `situacao_documento`, `ano_mes_apuracao`, `ref_item_nf`, `subclasse_consumo`, `num_terminal_telefonico_principal`, `tipo_dado_campo_2`, `outros_valores`, `tipo_cliente`, `num_terminal_telefonico`, `valor_total_fatura_comercial`, `brancos_50`, `numero_fatura_comercial`, `data_leitura_anterior`, `data_leitura_atual`, `info_adicional`, `brancos_5`, `cnpj_emitente`, `brancos_8`, `hash_campos`) VALUES
(1, 2147483647, 'ISENTO        ', 'ROBERT ANTON WILSON                ', 'SP', 0, 4, 0, '11111       ', 20210428, 21, 'U  ', 1, 'f06c22c9f51e5232340fd9f198ea9428', 24975, 24975, 0, 0, 'N', 2104, 1, 0, '1123232323  ', 0, 0, 3, '1123232323  ', 24975, '                                                  ', '70742               ', 0, 0, '                              ', '     ', 2147483647, 0, '33044379f62e517b47d0a2146d0f274a');

-- --------------------------------------------------------

--
-- Estrutura da tabela `nfsc_21_nf_regencia`
--

CREATE TABLE `nfsc_21_nf_regencia` (
  `id` int(11) NOT NULL,
  `data_gerado` datetime NOT NULL,
  `arquivo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `nfsc_21_nf_regencia`
--

INSERT INTO `nfsc_21_nf_regencia` (`id`, `data_gerado`, `arquivo`) VALUES
(1, '2021-04-28 11:23:41', 'SP1407056100017121U  2104N01M.001'),
(2, '2021-04-28 11:23:42', 'PR9999999900019921U  2104N01I.001'),
(3, '2021-04-28 11:23:42', 'PR9999999900019921U  2104N01D.001'),
(4, '2021-04-28 11:23:42', '99999999000199-0000000020210428112342-Sintegra.txt');

-- --------------------------------------------------------

--
-- Estrutura da tabela `nfsc_21_notas`
--

CREATE TABLE `nfsc_21_notas` (
  `id` int(11) NOT NULL,
  `num_nf` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `cliente_documento` int(23) NOT NULL,
  `data_gerada` datetime NOT NULL,
  `data_referente` date NOT NULL,
  `periodo_apuracao` varchar(6) NOT NULL,
  `status` varchar(255) NOT NULL,
  `criada` int(11) NOT NULL,
  `gerada` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `nfsc_21_notas`
--

INSERT INTO `nfsc_21_notas` (`id`, `num_nf`, `cliente_id`, `cliente_documento`, `data_gerada`, `data_referente`, `periodo_apuracao`, `status`, `criada`, `gerada`) VALUES
(1, 1, 11111, 407229, '2021-04-28 11:23:42', '2021-04-28', '2104', 'gerada', 1, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_municipios_ibge`
--

CREATE TABLE `tb_municipios_ibge` (
  `id` int(10) NOT NULL,
  `cod_mun` int(10) NOT NULL,
  `nom_mun` varchar(250) NOT NULL,
  `dt_ini` varchar(8) NOT NULL,
  `dt_fim` varchar(8) NOT NULL,
  `tb_cidades_id` int(4) UNSIGNED ZEROFILL NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='tabelas de municipios do IBGE versao 11_14-01-2013';

--
-- Extraindo dados da tabela `tb_municipios_ibge`
--

INSERT INTO `tb_municipios_ibge` (`id`, `cod_mun`, `nom_mun`, `dt_ini`, `dt_fim`, `tb_cidades_id`) VALUES
(1, 3552205, 'Sorocaba', '01012009', '', 9555);

-- --------------------------------------------------------

--
-- Estrutura da tabela `test_cliente`
--

CREATE TABLE `test_cliente` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `rg` varchar(255) NOT NULL,
  `insc_uf` varchar(255) NOT NULL,
  `cpf` varchar(255) NOT NULL,
  `cnpj` varchar(255) NOT NULL,
  `nascimento` date NOT NULL,
  `tipo` varchar(2) NOT NULL,
  `cidade` varchar(255) NOT NULL,
  `estado` varchar(2) NOT NULL,
  `endereco` varchar(255) NOT NULL,
  `numero` varchar(255) NOT NULL,
  `bairro` varchar(255) NOT NULL,
  `cep` varchar(255) NOT NULL,
  `complemento` varchar(255) NOT NULL,
  `cobranca` varchar(255) NOT NULL,
  `tel1` varchar(255) NOT NULL,
  `tel2` varchar(255) NOT NULL,
  `ramal` int(11) NOT NULL,
  `fax` varchar(255) NOT NULL,
  `cel` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `ativo` int(11) NOT NULL,
  `data_registro` datetime NOT NULL,
  `perm` int(11) NOT NULL,
  `cfop` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `test_cliente`
--

INSERT INTO `test_cliente` (`id`, `nome`, `rg`, `insc_uf`, `cpf`, `cnpj`, `nascimento`, `tipo`, `cidade`, `estado`, `endereco`, `numero`, `bairro`, `cep`, `complemento`, `cobranca`, `tel1`, `tel2`, `ramal`, `fax`, `cel`, `email`, `ativo`, `data_registro`, `perm`, `cfop`) VALUES
(1, 'ANTONIO SILVA', '28900245', '00000000000000', '620.548.853-12', '', '1978-01-05', 'pf', '9555', 'SP', 'RUA CENTRAL', '23', 'CENTRO', '18010-080', 'AP 23', 'RUA CENTRAL, 23 - CENTRO', '15-98765-8765', '15-98765-8765', 23, '15-98765-8765', '15-98765-8765', 'antonio@domain.tld', 1, '2021-04-26 13:52:27', 1, 301);

-- --------------------------------------------------------

--
-- Estrutura da tabela `test_contratos`
--

CREATE TABLE `test_contratos` (
  `id` int(11) NOT NULL,
  `idcliente` int(11) NOT NULL,
  `idplanos` int(11) NOT NULL,
  `idtipo` int(11) NOT NULL,
  `data_ini` date NOT NULL,
  `data_fim` date NOT NULL,
  `desconto` int(11) NOT NULL,
  `dia_pgto` int(11) NOT NULL,
  `end` varchar(255) NOT NULL,
  `cobranca` varchar(255) NOT NULL,
  `idcontr_ant` int(11) NOT NULL,
  `obs` varchar(255) NOT NULL,
  `ativo` int(11) NOT NULL,
  `bloqueado` int(11) NOT NULL,
  `dt_assinatura` date NOT NULL,
  `idservico` int(11) NOT NULL,
  `empresa` int(11) NOT NULL,
  `nf` int(11) NOT NULL,
  `tipo_assin` int(11) NOT NULL,
  `cfop` int(11) NOT NULL,
  `senha_trans` int(11) NOT NULL,
  `id_metodo_acesso` int(11) NOT NULL,
  `perm` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `test_empresa`
--

CREATE TABLE `test_empresa` (
  `id` int(11) NOT NULL,
  `cnpj` varchar(255) NOT NULL,
  `ie` varchar(255) NOT NULL,
  `razao_social` varchar(255) NOT NULL,
  `cidade` varchar(255) NOT NULL,
  `estado` varchar(2) NOT NULL,
  `fax` varchar(255) NOT NULL,
  `cod_id_convenio` varchar(255) NOT NULL,
  `cod_natureza_op_informada` varchar(255) NOT NULL,
  `cod_finalidade_arquivo_magnetico` varchar(255) NOT NULL,
  `logradouro` varchar(255) NOT NULL,
  `numero` varchar(255) NOT NULL,
  `complemento` varchar(255) NOT NULL,
  `bairro` varchar(255) NOT NULL,
  `cep` varchar(255) NOT NULL,
  `nome_contato` varchar(255) NOT NULL,
  `empresa_telefone` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `test_empresa`
--

INSERT INTO `test_empresa` (`id`, `cnpj`, `ie`, `razao_social`, `cidade`, `estado`, `fax`, `cod_id_convenio`, `cod_natureza_op_informada`, `cod_finalidade_arquivo_magnetico`, `logradouro`, `numero`, `complemento`, `bairro`, `cep`, `nome_contato`, `empresa_telefone`) VALUES
(1, '99.999.999/0001-99', '999999999', 'PROVEDOR X LTDA ME', 'CURITIBA', 'PR', '(41) 9999-9999', '3', '3', '1', 'RUA CENTRAL', '999', 'SALA 99', 'CENTRO', '99999-999', 'BOB WILSON', '(99) 9999-9999');

-- --------------------------------------------------------

--
-- Estrutura da tabela `test_invoices`
--

CREATE TABLE `test_invoices` (
  `id` int(11) NOT NULL,
  `clientid` int(11) NOT NULL,
  `date` date NOT NULL,
  `bill_date` date NOT NULL,
  `due_date` date NOT NULL,
  `paid_date` date NOT NULL,
  `serv1desc` varchar(255) NOT NULL,
  `serv1qty` int(11) NOT NULL,
  `serv1rate` decimal(10,0) NOT NULL,
  `serv1amt` decimal(10,0) NOT NULL,
  `serv1tax` varchar(2) NOT NULL,
  `shipping` decimal(10,0) NOT NULL,
  `subtotal` decimal(10,0) NOT NULL,
  `salestax` decimal(10,0) NOT NULL,
  `misc` decimal(10,0) NOT NULL,
  `note` varchar(255) NOT NULL,
  `total` decimal(10,0) NOT NULL,
  `status` varchar(255) NOT NULL,
  `contratoid` int(11) NOT NULL,
  `referencia` varchar(2) NOT NULL,
  `company` int(11) NOT NULL,
  `usuario_cadastro` int(11) NOT NULL,
  `usuario_baixa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `test_planos`
--

CREATE TABLE `test_planos` (
  `id` int(11) NOT NULL,
  `idservicos` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `valor` decimal(10,0) NOT NULL,
  `valor_adesao` decimal(10,0) NOT NULL,
  `multa` decimal(10,0) NOT NULL,
  `juros_dia` decimal(10,0) NOT NULL,
  `venc_contrato` date NOT NULL,
  `dt_inicio_venda` date NOT NULL,
  `dt_fim_venda` date NOT NULL,
  `ativo` int(11) NOT NULL,
  `duracao_plano` int(11) NOT NULL,
  `min_down` int(11) NOT NULL,
  `min_up` int(11) NOT NULL,
  `teto_download` int(11) NOT NULL,
  `teto_upload` int(11) NOT NULL,
  `conn_limit` int(11) NOT NULL,
  `tipo_plano` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `nfsc_21_cadastro`
--
ALTER TABLE `nfsc_21_cadastro`
  ADD PRIMARY KEY (`id`),
  ADD KEY `numero_nf` (`numero_nf`);

--
-- Índices para tabela `nfsc_21_item`
--
ALTER TABLE `nfsc_21_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `numero` (`numero`);

--
-- Índices para tabela `nfsc_21_mestre`
--
ALTER TABLE `nfsc_21_mestre`
  ADD PRIMARY KEY (`id`),
  ADD KEY `numero` (`numero`);

--
-- Índices para tabela `nfsc_21_nf_regencia`
--
ALTER TABLE `nfsc_21_nf_regencia`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `nfsc_21_notas`
--
ALTER TABLE `nfsc_21_notas`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_municipios_ibge`
--
ALTER TABLE `tb_municipios_ibge`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `test_cliente`
--
ALTER TABLE `test_cliente`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `test_contratos`
--
ALTER TABLE `test_contratos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `test_empresa`
--
ALTER TABLE `test_empresa`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `test_invoices`
--
ALTER TABLE `test_invoices`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `test_planos`
--
ALTER TABLE `test_planos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `nfsc_21_cadastro`
--
ALTER TABLE `nfsc_21_cadastro`
  MODIFY `id` bigint(19) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `nfsc_21_item`
--
ALTER TABLE `nfsc_21_item`
  MODIFY `id` int(19) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `nfsc_21_mestre`
--
ALTER TABLE `nfsc_21_mestre`
  MODIFY `id` bigint(19) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `nfsc_21_nf_regencia`
--
ALTER TABLE `nfsc_21_nf_regencia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `nfsc_21_notas`
--
ALTER TABLE `nfsc_21_notas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tb_municipios_ibge`
--
ALTER TABLE `tb_municipios_ibge`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `test_cliente`
--
ALTER TABLE `test_cliente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `test_contratos`
--
ALTER TABLE `test_contratos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `test_empresa`
--
ALTER TABLE `test_empresa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `test_invoices`
--
ALTER TABLE `test_invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `test_planos`
--
ALTER TABLE `test_planos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
