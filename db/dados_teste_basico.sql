--
-- dados para teste de emissao de notas com volume de 10.000 registros
-- Nota Fiscal de Serviço de Comunicação, modelo 21
-- Nota Fiscal de Serviço de Telecomunicação, modelo 22
-- @update: 2019-02-14
--


--
-- clientes - gerar por volta de 10.000 inserts de clientes
--
INSERT INTO `synnet`.`cliente` (`nome`, `rg`, `insc_uf`, `cpf`, `cnpj`, `nascimento`, `tipo`, `cidade`, `estado`, `endereco`, `numero`, `bairro`, `cep`, `complemento`, `cobranca`, `tel1`, `tel2`, `ramal`, `fax`, `cel`, `email`, `ativo`, `data_registro`, `perm`) VALUES ('ANTONIO SILVA', '28900245', '00000', '620.548.853-12', '', '1978-01-05', 'pf', '9450', 'SP', 'RUA CENTRAL', '23', 'CENTRO', '18010-080', 'AP 23', 'RUA CENTRAL, 23 - CENTRO', '15-98765-8765', '15-98765-8765', '23', '15-98765-8765', '15-98765-8765', 'antonio@domain.tld', '1', '2019-01-23', '1');


--
-- contratos - gerar 1 contrato para cada cliente inserido
--
INSERT INTO `synnet`.`contratos` (`idcliente`, `idplanos`, `idtipo`, `data_ini`, `data_fim`, `desconto`, `dia_pgto`, `end`, `cobranca`, `idcontr_ant`, `obs`, `ativo`, `bloqueado`, `dt_assinatura`, `idservico`, `empresa`, `nf`, `tipo_assin`, `cfop`, `senha_trans`, `id_metodo_acesso`, `perm`) VALUES ('1', '1', '1', '2019-02-01', '2020-02-01', '1', '10', 'RUA CENTRAL, 23', 'RUA CENTRAL, 23', '0', 'teste', '1', '0', '2019-02-01', '1', '2', '1', '1', '307', '123456', '3', '1');
-- ajustar id do cliente para um sequencial
SET @a = 0 ;
UPDATE synnet.contratos SET idcliente = @a:=@a+1 WHERE id > 0 LIMIT 10000;


--
-- invoices - gerar 1 invoice para cada contrato
--
INSERT INTO `synnet`.`invoices` (`clientid`, `date`, `bill_date`, `due_date`, `paid_date`, `serv1desc`, `serv1qty`, `serv1rate`, `serv1amt`, `serv1tax`, `shipping`, `subtotal`, `salestax`, `misc`, `note`, `total`, `status`, `contratoid`, `referencia`, `company`, `usuario_cadastro`, `usuario_baixa`) VALUES ('1', '2019-02-05', '2019-02-05', '2019-02-15', '2019-02-10', 'GlobalNet Gen5 10MB', '1', '59.99', '59.99', 'no', '0.00', '59.99', '0.00', '0.00', 'teste', '59.99', 'paid', '1', 'PA', '2', '1', '1');
-- ajustar id do cliente para um sequencial
SET @a = 0 ;
UPDATE synnet.invoices SET clientid = @a:=@a+1 WHERE id > 0 LIMIT 10000;
-- ajustar id do contrato para um sequencial
SET @a = 0 ;
UPDATE synnet.invoices SET contratoid = @a:=@a+1 WHERE id > 0 LIMIT 10000;


--
-- EOF
--