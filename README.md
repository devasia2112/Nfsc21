## Nota Fiscal de Serviço de Comunicação e Telecomunicação, modelo 21 e 22.

Essa classe deve ser usada unica e exclusivamente para gerar Nota Fiscal de Serviço de Comunicação e Telecomunicação,
modelo 21 e 22, para empresas prestadoras de serviços de internet, TV a cabo e Energia Eletrica.
Com um pouco de adaptação a classe pode ser usada tambem para empresas do setor de fornecimento de
energia elétrica.

Com a intenção de ajudar as pequenas empresas do setor de comunicação (prestadoras de serviços de internet)
a se enquadrarem no novo modelo de emissão de nota fiscal exijido pelo governo brasileiro, essa classe
esta sendo disponibilizada como código aberto para a comunidade.



## AMBIENTE TESTADO
**Servidor Web:**
- Linux Debian x86_64_(Jessie) PHP version 5.5 - nginx/1.1.19 - MySQL 5.5
- Linux 4.19.88-1-MANJARO - PHP 7.4.1 nginx/1.14.2 - mysql Ver 15.1 Distrib 10.3.12-MariaDB
- Linux 5.4.93-rt51-MANJARO - PHP 8.0.2-PHP 7.3.9 Apache/2.4.41 OpenSSL/1.1.1c - MySQL Version 5.5.5-10.4.6-MariaDB

**Nota:** O script foi testado em diversas versões do PHP: 5.3, 5.4, 5.5. Em versões anteriores a
5.3 precisa fazer alguns ajustes no script para funcionar corretamente.


## INSTALAÇÃO
Existem duas maneiras de usar esse script, com ou sem banco de dados, se desejar gravar os dados
dos arquivos no banco de dados, então será necessário criar as tabelas abaixo no base de dados
do contribuinte.

Veja que esse projeto não cobre instalação e uso de banco de dados, então presumo que o
contribuinte já esta com o seu banco de dados instalado.
Veja: **BANCO DE DADOS - TABELAS**.


## COMO USAR

Para executar o script, aponte o seu browser para o seguinte endereço ou use o form (`nfsc.21.form.php`) disponivel:

- URL para emissao de modelo 21 `<SERVER>:<PORT>/nfsc.21.query.select.data.php?di=2019-02-01&df=2019-02-07&nf=0&ri=1&da=1902&de=20190207&mo=21&tu=4&csv=0`
- URL para emissao de modelo 22 `<SERVER>:<PORT>/nfsc.21.query.select.data.php?di=2019-02-01&df=2019-02-07&nf=0&ri=1&da=1902&de=20190207&mo=22&tu=1&csv=1`

Onde os parametros passados na URL são:
* di = data inicial                          (formato AAAA-MM-DD)
* df = data final                            (formato AAAA-MM-DD)
* nf = numeração inicial da NF               (obrigatoriamente precisa ser iniciado em ZERO(0))
* ri = referência do item do registro fiscal (obrigatoriamente precisa ser iniciado em UM(1))
* da = data da apuração                      (formato AAMM)
* de = data da emissão                       (formato AAAAMMDD)
* mo = modelo (21 ou 22) modelo 06 - Energia Eletrica ainda nao foi implementado.
* tu = tipo utilizacao (mod. 21: internet(4) parametro opicional | mod. 22: telefonia(1), tv assinatura(3), outros(6) parametro requerido)
* csv = exportar dados para arquivo CSV (0 = omitir, 1 = gerar).

**Nota de Uso:** O programa de validação pede um arquivo com a extensao .INI, vai ser necessário 
cadastrar esses dados inicialmente e então o programa da nota fiscal deve gerar esse arquivo 
.INI (guarde ele para próximo uso), no cadastro preencha as informações que o programa pede com 
os mesmos dados de empresa que estiver registrado aqui 
-> https://github.com/deepcell/Nfsc21/blob/master/nfsc.21.array.php (A partir da linha 38: coloque 
os dados da sua empresa nesse script e repita no cadastro do programa).


### PERMISSÕES
* Set o diretório raiz com as permissões:                  chmod -R 756 NFSC21/
* Set o diretorio /001 com as seguintes permissoes:        chmod -R 756 001/
* Set permissão para o grupo poder servir o arquivo:       chmod g+s NFSC21/
* Set permissão para o grupo poder escrever o arquivo:     chmod g+w NFSC21/


## BANCO DE DADOS - TABELAS
Executando o script abaixo, as seguintes tabelas serão criadas no banco de dados do contribuinte:

- `tb_municipios_ibge` (Incluir no INSERT todas as cidades que a empresa possui clientes.)
- `Nfsc_21_Mestre`     (Arquivo tipo MESTRE DE DOCUMENTO FISCAL)
- `Nfsc_21_Item`       (Arquivo tipo ITEM DE DOCUMENTO FISCAL)
- `Nfsc_21_Cadastro`   (DADOS CADASTRAIS DO DESTINATÁRIO DO DOCUMENTO FISCAL)

Rodar o script `script_db_update_0.2.sql` localizado no diretorio /db.

`user@hostname:/$ mysql -u <username> -p  databasename  < /tmp/script_db_update_0.2.sql`

### Informacoes adicionais sobre a estrutura de cada tabela da Nota Fiscal de Serviço de Comunicação, modelo 21.


    TABELA MESTRE
    +----+---------------------------------------------------------+------+--------+-----+---------+-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
    | Nº | CONTEUDO                                                | TAM. | INICIO | FIM | FORMATO | OBS
    +----+---------------------------------------------------------+------+--------+-----+---------+-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
    | 01 | CNPJ ou CPF                                             | 14   | 1      | 14  | N       | pessoa não obrigada à inscrição no CNPJ ou CPF, preencher o campo com zeros;
    | 02 | IE                                                      | 14   | 15     | 28  | X       | pessoa não obrigada à inscrição estadual, preencher o campo com a expressão "ISENTO";
    | 03 | Razão Social ou nome do cliente                         | 35   | 29     | 63  | X       |
    | 04 | UF                                                      | 2    | 64     | 65  | X       | UF da localização do consumidor - Para o exterior preencher o campo com EX
    | 05 | Classe de Consumo                                       | 1    | 66     | 66  | N       | IMPORTANTE: preencher com zeros para NF de comunicacao;
    | 06 | Fase ou Tipo de Utilização                              | 1    | 67     | 67  | N       | tipo de utilização, conforme tabela 1;
    | 07 | Grupo de Tensão                                         | 2    | 68     | 69  | N       | informar apenas para conta de energia eletrica, Nos demais caso deverá ser preenchido com 00;
    | 08 | Código de Identificação do consumidor ou assinante      | 12   | 70     | 81  | X       | ID do cliente no sistema de processamento de dados da empresa.
    | 09 | Data de emissão                                         | 8    | 82     | 89  | N       | data de emissão do documento fiscal no formato AAAAMMDD;
    | 10 | Modelo                                                  | 2    | 90     | 91  | N       | Conta de Energia Elétrica, modelo 6, Serviço de Telecomunicações, modelo 22  ou  Serviço de Comunicação, modelo 21
    | 11 | Série                                                   | 3    | 92     | 94  | X       | no mínimo, uma letra não acentuada, ou um algarismo de 1 a 9 e ter seu preenchimento iniciado a partir da esquerda (exemplo: "A ", e não " A") Hifen e espacos em branco sao aceitos. utilizar a letra "U" para indicar a série única.
    | 12 | Número                                                  | 9    | 95     | 103 | N       | número seqüencial atribuído pelo sistema eletrônico de processamento de dados ao documento fiscal (ver item 1). O campo deverá ser alinhado à direita com as posições não significativas preenchidas com zeros;
    | 13 | Código de Autenticação Digital do documento fiscal      | 32   | 104    | 135 | X       | Criar um hash MD5() na cadeia de caracteres formada pelos campos 01, 12, 14, 15, 16, 09 e 27, nessa ordem, respeitando o tamanho previsto do campo, assim como os brancos e zeros de preenchimento.
    | 14 | Valor Total (com 2 decimais)                            | 12   | 136    | 147 | N       | Informar o Valor Total do documento fiscal, com 2 decimais;
    | 15 | BC ICMS (com 2 decimais)                                | 12   | 148    | 159 | N       | Informar a Base de Cálculo do ICMS destacado no documento fiscal, com 2 decimais;
    | 16 | ICMS destacado (com 2 decimais)                         | 12   | 160    | 171 | N       | Informar o valor do ICMS destacado no documento fiscal, com 2 decimais;
    | 17 | Operações isentas ou não tributadas (com 2 decimais)    | 12   | 172    | 183 | N       | Informar o valor das operações ou serviços isentos ou não tributados pelo ICMS, com 2 decimais;
    | 18 | Outros valores (com 2 decimais)                         | 12   | 184    | 195 | N       | ver item 2.
    | 19 | Situação do documento                                   | 1    | 196    | 196 | X       | ver item 3.
    | 20 | Ano e Mês de referência de apuração                     | 4    | 197    | 200 | N       | Informar o ano e mês de referência de apuração do ICMS do documento fiscal, utilizando o formato "AAMM";
    | 21 | Referência ao item da NF                                | 9    | 201    | 209 | N       | Informar o número do registro do arquivo ITEM DO DOCUMENTO FISCAL, onde se encontra o primeiro item do documento fiscal;
    | 22 | Número do terminal telefônico ou da unidade consumidora | 12   | 210    | 221 | X       | ver item 4 (Nao esta claro qual numero telefonico precisa ser)
    | 23 | Indicação do tipo de informação contida no campo 1      | 1    | 222    | 222 | N       | ver item 5.
    | 24 | Tipo de cliente                                         | 2    | 223    | 224 | N       | ver tabela 2.
    | 25 | Subclasse de consumo                                    | 2    | 225    | 226 | N       | Em se tratando de Nota Fiscal de Serviço de Comunicação, modelo 21, ou Nota Fiscal de Serviço de Telecomunicação, modelo 22, preencher com zeros.
    | 26 | Número do terminal telefônico principal                 | 12   | 227    | 238 | X       | Para planos individuais e nota fiscal modelo 6, o campo deve ser preenchido com brancos. (Nao esta claro qual numero telefonico precisa ser)
    | 27 | CNPJ do emitente                                        | 14   | 239    | 252 | N       | CNPJ da empresa que emite o documento fiscal.
    | 28 | Número ou código da fatura comercial                    | 20   | 253    | 272 | X       | atribuído pelo sistema de faturamento do emitente.
    | 29 | Valor total da fatura comercial                         | 12   | 273    | 284 | N       | 2 decimais.
    | 30 | Data de leitura anterior                                | 8    | 285    | 292 | N       | Em se tratando de nota fiscal modelo 6, informar a data da leitura anterior, no formato AAAAMMDD. Nos demais casos, preencher com zeros;
    | 31 | Data de leitura atual                                   | 8    | 293    | 300 | N       | Em se tratando de nota fiscal modelo 6, informar a data de leitura atual, no formato AAAAMMDD. Nos demais casos, preencher com zeros;
    | 32 | Brancos - reservado para uso futuro                     | 50   | 301    | 350 | X       | Informar a chave de acesso do documento fiscal eletrônico (CV115-e). Nas unidades federadas em que tal documento não tiver sido implementado, preencher com brancos;
    | 33 | Brancos - reservado para uso futuro                     | 8    | 351    | 358 | N       | Informar a data da autorização de emissão do documento fiscal eletrônico (CV115-e),  no  formato  AAAAMMDD.  Nas  unidades  federadas  em  que  tal  documento  não  tiver  sido implementado, preencher com zeros;
    | 34 | Informações adicionais                                  | 30   | 359    | 388 | X       | ver item 6.
    | 35 | Brancos - reservado para uso futuro                     | 5    | 389    | 393 | X       | Preencher com espaços em branco;
    | 36 | Código de Autenticação Digital do registro              | 32   | 394    | 425 | X       | Criar um hash MD5() na cadeia de caracteres formada pelos campos 01 a 35;
    +----+---------------------------------------------------------+------+--------+-----+---------+-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
    |    | TOTAL                                                   | 425  |        |     |
    +----+---------------------------------------------------------+------+--------+-----+---------+-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+



    Tabela 2 - Nfsc_21_Item

    TABELA ITEM
    +----+---------------------------------------------------------+------+--------+-----+---------+-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
    |  Nº | CONTEUDO                                                | TAM. | INICIO | FIM | FORMATO | OBS
    +----+---------------------------------------------------------+------+--------+-----+---------+-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
    | 01 | CNPJ ou CPF                                             | 14   | 1      | 14  | N       | |
    | 02 | UF                                                      | 2    | 15     | 16  | X       | |
    | 03 | Classe do Consumo                                       | 1    | 17     | 17  | N       | |
    | 04 | Fase ou Tipo de Utilização                              | 1    | 18     | 18  | N       | |
    | 05 | Grupo de Tensão                                         | 2    | 19     | 20  | N       | |
    | 06 | Data de Emissão                                         | 8    | 21     | 28  | N       | |
    | 07 | Modelo                                                  | 2    | 29     | 30  | N       | |
    | 08 | Série                                                   | 3    | 31     | 33  | X       | |
    | 09 | Número                                                  | 9    | 34     | 42  | N       | |
    | 10 | CFOP                                                    | 4    | 43     | 46  | N       | |
    | 11 | Nº de ordem do Item                                     | 3    | 47     | 49  | N       | |
    | 12 | Código do item                                          | 10   | 50     | 59  | X       | |
    | 13 | Descrição do item                                       | 40   | 60     | 99  | X       | |
    | 14 | Código de classificação do item                         | 4    | 100    | 103 | N       | |
    | 15 | Unidade                                                 | 6    | 104    | 109 | X       | |
    | 16 | Quantidade contratada (com 3 decimais)                  | 12   | 110    | 121 | N       | |
    | 17 | Quantidade medida (com 3 decimais)                      | 12   | 122    | 133 | N       | |
    | 18 | Total (com 2 decimais)                                  | 11   | 134    | 144 | N       | |
    | 19 | Desconto / Redutores (com 2 decimais)                   | 11   | 145    | 155 | N       | |
    | 20 | Acréscimos e Despesas Acessórias (com 2 decimais)       | 11   | 156    | 166 | N       | |
    | 21 | BC ICMS (com 2 decimais)                                | 11   | 167    | 177 | N       | |
    | 22 | ICMS (com 2 decimais)                                   | 11   | 178    | 188 | N       | |
    | 23 | Operações Isentas ou não tributadas (com 2 decimais)    | 11   | 189    | 199 | N       | |
    | 24 | Outros valores (com 2 decimais)                         | 11   | 200    | 210 | N       | |
    | 25 | Alíquota do ICMS (com 2 decimais)                       | 4    | 211    | 214 | N       | |
    | 26 | Situação                                                | 1    | 215    | 215 | X       | |
    | 27 | Ano e Mês de referência de apuração                     | 4    | 216    | 219 | X       | |
    | 28 | Número do Contrato                                      | 15   | 220    | 234 | X       | |
    | 29 | Quantidade faturada (com 3 decimais)                    | 12   | 235    | 246 | N       | |
    | 30 | Tarifa Aplicada / Preço Médio Efetivo (com 6 decimais)  | 11   | 247    | 257 | N       | |
    | 31 | Alíquota PIS/PASEP (com 4 decimais)                     | 6    | 258    | 263 | N       | |
    | 32 | PIS/PASEP (com 2 decimais)                              | 11   | 264    | 274 | N       | |
    | 33 | Alíquota COFINS (com 4 decimais)                        | 6    | 275    | 280 | N       | |
    | 34 | COFINS (com 2 decimais)                                 | 11   | 281    | 291 | N       | |
    | 35 | Indicador de Desconto Judicial                          | 1    | 292    | 292 | X       | |
    | 36 | Tipo de Isenção/Redução de Base de Cálculo              | 2    | 293    | 294 | N       | Em se tratando de Nota Fiscal de Serviço de Comunicação, modelo 21, ou Nota Fiscal de Serviço de Telecomunicação, modelo 22, preencher conforme tabela 11.10. Se não houver isenção ou redução de base de cálculo, preencher com zeros. |
    | 37 | Brancos - reservado para uso futuro                     | 5    | 295    | 299 | X       | |
    | 38 | Código de Autenticação Digital do registro              | 32   | 300    | 331 | X       | |
    +----+---------------------------------------------------------+------+--------+-----+---------+-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
    |    | TOTAL                                                   | 331  |        |     |         |
    +----+---------------------------------------------------------+------+--------+-----+---------+-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+



    Tabela 3 - Nfsc_21_Cadastro

    TABELA CADASTRO
    +----+---------------------------------------------------------+------+--------+-----+---------+-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
    | Nº | CONTEUDO                                                | TAM. | INICIO | FIM | FORMATO | OBS                                                                                                                                                                                                                                     |
    +----+---------------------------------------------------------+------+--------+-----+---------+-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
    | 01 | CNPJ ou CPF                                             | 14   | 1      | 14  | N       | Informar  o  CNPJ  ou  CPF.  SE  pessoa  não  obrigada  à inscrição no CNPJ ou CPF, preencher o campo com zeros;       |
    | 02 | IE                                                      | 14   | 15     | 28  | X       | Informar a Inscrição Estadua. SE pessoa não obrigada à inscrição estadual, preencher o campo com a expressão "ISENTO"; |
    | 03 | Razão Social                                            | 35   | 29     | 63  | X       | Informar a razão social, denominação ou nome; |
    | 04 | Logradouro                                              | 45   | 64     | 108 | X       | Informar o Logradouro do endereco. |
    | 05 | Número                                                  | 5    | 109    | 113 | N       | Informar o Número do endereço; |
    | 06 | Complemento                                             | 15   | 114    | 128 | X       | Informar o Complemento do endereço; |
    | 07 | CEP                                                     | 8    | 129    | 136 | N       | Informar o CEP do endereço; |
    | 08 | Bairro                                                  | 15   | 137    | 151 | X       | Informar o Bairro do endereço;|
    | 09 | Município                                               | 30   | 152    | 181 | X       | Informar o nome do Município do endereço, de acordo com a tabela de municípios elaborada pelo Instituto Brasileiro de Geografia e Estatística - IBGE. |
    | 10 | UF                                                      | 2    | 182    | 183 | X       | Informar a sigla da UF do endereço. Para exterior, preencher o campo com a expressão "EX";|
    | 11 | Telefone de contato                                     | 12   | 184    | 195 | X       | Informar a localidade de registro e o número do telefone de contato no formato "LLNNNNNNNN". No caso do telefone conter 9 (nove) dígitos, usar o formato "LLNNNNNNNNN". |
    | 12 | Código de identificação do consumidor ou assinante      | 12   | 196    | 207 | X       | Informar o código de identificação do consumidor ou assinante utilizado pelo contribuinte. |
    | 13 | Número do terminal telefônico ou da unidade consumidora | 12   | 208    | 219 | X       | ver item 4 (Nao esta claro qual numero telefonico precisa ser) |
    | 14 | UF de habilitação do terminal telefônico                | 2    | 220    | 221 | X       | Informar a sigla da UF de habilitação do terminal/aparelho telefônico, deixando em branco nos demais casos; |
    | 15 | Data de emissão                                         | 8    | 222    | 229 | N       | Informar a data de emissão do documento fiscal no formato AAAAMMDD; |
    | 16 | Modelo                                                  | 2    | 230    | 231 | N       | Conta de Energia Elétrica, modelo 6, Serviço de Telecomunicações, modelo 22  ou  Serviço de Comunicação, modelo 21. |
    | 17 | Série                                                   | 3    | 232    | 234 | X       | no mínimo, uma letra não acentuada, ou um algarismo de 1 a 9 e ter seu preenchimento iniciado a partir da esquerda (exemplo: "A ", e não " A") Hifen e espacos em branco sao aceitos. utilizar a letra "U" para indicar a série única. |
    | 18 | Número                                                  | 9    | 235    | 243 | N       | número seqüencial atribuído pelo sistema eletrônico de processamento de dados ao documento fiscal (ver item 1). O campo deverá ser alinhado à direita com as posições não significativas preenchidas com zeros; |
    | 19 | Código do Município                                     | 7    | 244    | 250 | N       | Informar o código do município de acordo com a tabela de municípios elaborada pelo Instituto Brasileiro de Geografia e Estatística - IBGE; |
    | 20 | Brancos - reservado para uso futuro                     | 5    | 251    | 255 | X       | Preencher com espaços em branco; |
    | 21 | Código de Autenticação Digital do registro              | 32   | 256    | 287 | X       | gerar hash MD5() na cadeia de caracteres formada pelos campos 01 a 20. |
    +----+---------------------------------------------------------+------+--------+-----+---------+-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
    |    | TOTAL                                                   | 287  |        |     |
    +----+---------------------------------------------------------+------+--------+-----+---------+-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+ */

## TODO LIST

- [x] passar dados via query SQL.
- [x] passar dados via array PHP.
- [ ] passar dados via JSON.
- [ ] passar dados via XML.
- [ ] permitir o cancelamento de uma nota fiscal.


:star2: Marque com uma estrela este repositório para acompanhar o seu desenvolvimento e torná-lo popular.
