--
-- tb_municipios_ibge
--
id,cod_mun,nom_mun,dt_ini,dt_fim,tb_cidades_id
1,0,,01012009,,0000
2,0,,01012009,,0000
3,0,,01012009,,0000
4,3552205,Sorocaba,01012009,,9450


--
-- tb_cidades
--
id,estado,uf,nome
0001,01,AC,Acrelandia


--
-- planos
--
id,idservicos,titulo,descricao,valor,valor_adesao,multa,juros_dia,venc_contrato,dt_inicio_venda,dt_fim_venda,ativo,duracao_plano,min_down,min_up,teto_download,teto_upload,conn_limit,tipo_plano
1,1,"GlobalNet Gen5 10MB","Perfect for the single user or couple who is passionate about fast internet.",59.99,99.00,1.00,1.00,2020-12-31,2019-01-01,2020-12-31,1,24,1000,1000,10000,10000,200,1


--
-- faturas
--
id,clientid,date,bill_date,due_date,paid_date,serv1desc,serv1qty,serv1rate,serv1amt,serv1tax,shipping,subtotal,salestax,misc,note,total,status,contratoid,referencia,company,usuario_cadastro,usuario_baixa
1,1,2019-02-05,2019-02-05,2019-02-15,2019-02-10,"GlobalNet Gen5 10MB",1,59.99,59.99,no,0.00,59.99,0.00,0.00,teste,59.99,paid,1,PA,2,1,1


--
-- contratos
--
id,idcliente,idplanos,idtipo,data_ini,data_fim,desconto,dia_pgto,end,cobranca,idcontr_ant,obs,ativo,bloqueado,dt_assinatura,idservico,empresa,nf,tipo_assin,cfop,senha_trans,id_metodo_acesso,perm
1,1,1,1,2019-02-01,2020-02-01,1,10,"RUA CENTRAL, 23","RUA CENTRAL, 23",0,teste,1,0,2019-02-01,1,2,1,1,307,123456,3,1


--
-- clientes
--
id,nome,rg,insc_uf,cpf,cnpj,nascimento,tipo,cidade,estado,endereco,numero,bairro,cep,complemento,cobranca,tel1,tel2,ramal,fax,cel,email,ativo,data_registro,perm
1,"ANTONIO SILVA",28900245,00000000000000,"620.548.853-12",,"1978-01-05",pf,9450,"SP","RUA CENTRAL",23,"CENTRO","18010-080","AP 23","RUA CENTRAL, 23 - CENTRO","15-98765-8765","15-98765-8765",23,"15-98765-8765","15-98765-8765","antonio@domain.tld",1,"2019-01-23 00:00:00",1


--
-- CFOP
--
id,cfop,descricao,aplicacao
1,301,"Execução de serviço da mesma natureza","Classificam-se neste código as prestações de serviços de comunicação destinados às prestações de serviços da mesma natureza."
2,302,"Estabelecimento industrial","Classificam-se neste código as prestações de serviços de comunicação a estabelecimento industrial. Também serão classificados neste código os serviços de comunicação prestados a estabelecimento industrial de cooperativa."
3,303,"Estabelecimento comercial","Classificam-se neste código as prestações de serviços de comunicação a estabelecimento comercial. Também serão classificados neste código os serviços de comunicação prestados a estabelecimento comercial de cooperativa."
4,304,"Estabelecimento de prestador de serviço de transporte","Classificam-se neste código as prestações de serviços de comunicação a estabelecimento prestador de serviço de transporte."
5,305,"Estabelecimento de geradora ou de distribuidora de energia elétrica","Classificam-se neste código as prestações de serviços de comunicação a estabelecimento de geradora ou de distribuidora de energia elétrica."
6,306,"Estabelecimento de produtor rural","Classificam-se neste código as prestações de serviços de comunicação a estabelecimento de produtor rural."
7,307,"Não contribuinte","Classificam-se neste código as prestações de serviços de comunicação a pessoas físicas ou a pessoas jurídicas não indicadas nos códigos anteriores."

