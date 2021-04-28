<?php
date_default_timezone_set("America/Sao_Paulo");
/**
 * @title: Sintegra.
 * @description: Sistema Integrado de Informações sobre Operações 
 * Interestaduais com Mercadorias e Serviços (Sintegra).
 * 
 * 
 * X = String alinhado a esquerda e restante do tamanho do campo preenche com vazios.
 * N = Inteiro alinhado a direita e restante do tamanho do campo preenche com zeros a esquerda.
 * 
 * 
 * REGISTRO 10 - MESTRE ESTABELECIMENTO
 * +----+-----------------------+-----------------------------+----------+-----------+---------+
 * | N  | CAMPO                 | CONTEUDO                    | TAMANHO  | POSICAO   | FORMATO |
 * +----+-----------------------+-----------------------------+----------+-----------+---------+
 * | 01 | Tipo                  | 10                          | 2        |  1  | 2   | N       |
 * +----+-----------------------+-----------------------------+----------+-----------+---------+
 * | 02 | CNPJ                  | CNPJ estabelecimento        | 14       |  3  | 16  | N       |
 * |    |                       | informante                  |          |     |     |         |
 * +----+-----------------------+-----------------------------+----------+-----------+---------+
 * | 03 | Insc. UF              | Insc. UF estabelecimento    | 14       | 17  | 30  | X       |
 * |    |                       | informante                  |          |     |     |         |
 * +----+-----------------------+-----------------------------+----------+-----------+---------+
 * | 04 | Nome contribuinte     | Razao social                | 35       | 31  | 65  | X       |
 * +----+-----------------------+-----------------------------+----------+-----------+---------+
 * | 05 | Municipio             | Municipio domicilio         | 30       | 66  | 95  | X       |
 * |    |                       | estabelecimento informante  |          |     |     |         |
 * +----+-----------------------+-----------------------------+----------+-----------+---------+
 * | 06 | Unidade federacao     | UF municipio                | 2        | 96  | 97  | X       |
 * +----+-----------------------+-----------------------------+----------+-----------+---------+
 * | 07 | FAX                   | FAX estabelecimento         | 10       | 98  | 107 | X       |
 * +----+-----------------------+-----------------------------+----------+-----------+---------+
 * | 08 | Data inicial          | Inicio periodo ref. as      | 8        | 108 | 115 | N       |
 * |    |                       | informacoes prestadas       |          |     |     |         |
 * +----+-----------------------+-----------------------------+----------+-----------+---------+
 * | 09 | Data final            | Fim periodo ref. as         | 8        | 116 | 123 | N       |
 * |    |                       | informacoes prestadas       |          |     |     |         |
 * +----+-----------------------+-----------------------------+----------+-----------+---------+
 * | 10 | Cod. indentificacao   | Ver tabela 1                | 1        | 124 | 124 | X       |
 * |    | da estrutura arquivo  |                             |          |     |     |         |
 * |    | magnetico entregue    |                             |          |     |     |         |
 * +----+-----------------------+-----------------------------+----------+-----------+---------+
 * | 11 | Cod. identificacao da | Ver tabela 2                | 1        | 125 | 125 | X       |
 * |    | natureza operacoes    |                             |          |     |     |         |
 * |    | informadas            |                             |          |     |     |         |
 * +----+-----------------------+-----------------------------+----------+-----------+---------+
 * | 12 | Cod. finalidade do    | Ver tabela 3                | 1        | 126 | 126 | X       |
 * |    | arquivo magnetico     |                             |          |     |     |         |
 * +----+-----------------------+-----------------------------+----------+-----------+---------+
 * 
 * TABELA 1
 * +--------+----------------------------------------------------------------------------------+
 * | CODIGO | DESCRICAO COD. DA IDENTIFICACAO DA ESTRUTURA DO ARQUIVO                          |
 * +--------+----------------------------------------------------------------------------------+
 * | 1      | Estrutura conforme Convênio ICMS 57/95 na versão do Convênio ICMS 31/99.         |
 * +--------+----------------------------------------------------------------------------------+
 * | 2      | Estrutura conforme Convênio ICMS 57/95 na versão atual.                          |
 * +--------+----------------------------------------------------------------------------------+
 *
 * TABELA 2
 * +--------+----------------------------------------------------------------------------------+
 * | CODIGO | DESCRICAO COD. NATUREZA OPERACOES                                                |
 * +--------+----------------------------------------------------------------------------------+
 * | 1      | Interestaduais somente operações sujeitas ao regime de substituição tributária.  |
 * +--------+----------------------------------------------------------------------------------+
 * | 2      | Interestaduais - operações com ou sem substituição tributária                    |
 * +--------+----------------------------------------------------------------------------------+
 * | 3      | Totalidade das operações do informantes.                                         |
 * +--------+----------------------------------------------------------------------------------+
 * 
 * TABELA 3
 * +--------+----------------------------------------------------------------------------------+
 * | CODIGO | DESCRICAO DA FINALIDADE                                                          |
 * +--------+----------------------------------------------------------------------------------+
 * | 1      | Normal                                                                           |
 * +--------+----------------------------------------------------------------------------------+
 * | 2      | Retificação total de arquivo: substituição total de informações prestadas pelo   |
 * |        | contribuinte referentes a este período.                                          |
 * +--------+----------------------------------------------------------------------------------+
 * | 3      | Retificação aditiva de arquivo: acréscimo de informação não incluída em arquivos |
 * |        | já apresentados.                                                                 |
 * +--------+----------------------------------------------------------------------------------+
 * | 5      | Desfazimento: arquivo de informação referente a operações/prestações não         |
 * |        | efetivadas. Neste caso, o arquivo deverá conter, além dos registros tipo 10 e    |
 * |        | tipo 90, apenas os registros referentes as operações/prestações não efetivadas.  |
 * +--------+----------------------------------------------------------------------------------+
 * 
 * TABELA CFOP
 * +---------------+---------------------------------------------------------------------------+
 * | CFOP          | DESCRICAO                                                                 |
 * +---------------+---------------------------------------------------------------------------+
 * | 5.301 / 6.301 | EMITIR PARA EMPRESAS DE TELEFONIA                                         |
 * +---------------+---------------------------------------------------------------------------+
 * | 5.302 / 6.302 | EMITIR PARA INDÚSTRIAS                                                    |
 * +---------------+---------------------------------------------------------------------------+
 * | 5.303 / 6.303 | EMITIR PARA COMÉRCIOS                                                     |
 * +---------------+---------------------------------------------------------------------------+
 * | 5.304 / 6.304 | EMITIR PARA TRANSPORTADORAS                                               |
 * +---------------+---------------------------------------------------------------------------+
 * | 5.305 / 6.305 | EMITIR PARA DISTRIBUIDORAS DE ENERGIA                                     |
 * +---------------+---------------------------------------------------------------------------+
 * | 5.306 / 6.306 | EMITIR PARA PRODUTORES RURAIS                                             |
 * +---------------+---------------------------------------------------------------------------+
 * | 5.307 / 6.307 | EMITIR PARA PESSOA FÍSICA E PARA PESSOA JURÍDICA SEM INSCRIÇÃO ESTADUAL   |
 * +---------------+---------------------------------------------------------------------------+
 * 
 * 
 * 
 * REGISTRO 11 - DADOS COMPLEMENTARES DO INFORMANTE
 * +----+-----------------------+-----------------------------+----------+-----------+---------+
 * | N  | CAMPO                 | CONTEUDO                    | TAMANHO  | POSICAO   | FORMATO |
 * +----+-----------------------+-----------------------------+----------+-----------+---------+
 * 
 * 
 * 
 * REGISTRO 75 - CODIGO DE PRODUTOS OU SERVICOS
 * +----+-----------------------+-----------------------------+----------+-----------+---------+
 * | N  | CAMPO                 | CONTEUDO                    | TAMANHO  | POSICAO   | FORMATO |
 * +----+-----------------------+-----------------------------+----------+-----------+---------+
 * 
 * 
 * 
 * REGISTRO 76 - NOTA FISCAL DE SERVICO DE COMUNICACAO E TELECOMUNICACAO
 * +----+-----------------------+-----------------------------+----------+-----------+---------+
 * | N  | CAMPO                 | CONTEUDO                    | TAMANHO  | POSICAO   | FORMATO |
 * +----+-----------------------+-----------------------------+----------+-----------+---------+
 * 
 * 
 * 
 * REGISTRO 77 - SERVICO DE COMUNICACAO E TELECOMUNICACAO
 * +----+-----------------------+-----------------------------+----------+-----------+---------+
 * | N  | CAMPO                 | CONTEUDO                    | TAMANHO  | POSICAO   | FORMATO |
 * +----+-----------------------+-----------------------------+----------+-----------+---------+
 * 
 * 
 * 
 * REGISTRO 90 - TOTALIZACAO DO ARQUIVO
 * +----+-----------------------+-----------------------------+----------+-----------+---------+
 * | N  | CAMPO                 | CONTEUDO                    | TAMANHO  | POSICAO   | FORMATO |
 * +----+-----------------------+-----------------------------+----------+-----------+---------+
 * | 01 | Tipo                  | 90                          | 2        |  1 | 2    | N       |
 * +----+-----------------------+-----------------------------+----------+-----------+---------+
 * | 02 | CNPJ                  | CNPJ informante             | 14       |  3 | 16   | N       |
 * +----+-----------------------+-----------------------------+----------+-----------+---------+
 * | 03 | Inscricao Estadual    | Insc. UF informante         | 14       | 17 | 30   | X       |
 * +----+-----------------------+-----------------------------+----------+-----------+---------+
 * | 04 | Tipo a ser totalizado | Tipo registro totalizado    | 2        | 31 | 32   | N       |
 * |    |                       | pelo prox. campo            |          |           |         |
 * +----+-----------------------+-----------------------------+----------+-----------+---------+
 * | 05 | Total de registros    | Total registros do tipo     | 8        | 33 | 40   | N       |
 * |    |                       | informado no campo anterior |          |           |         |
 * +----+-----------------------+-----------------------------+----------+-----------+---------+
 * | "  | "                     | "                           | "        | "         | N       |
 * +----+-----------------------+-----------------------------+----------+-----------+---------+
 * |    | Total geral           | Total registros no arquivo  | 8        |           | N       |
 * |    |                       | (todos os tipos)            |          |           |         |
 * +----+-----------------------+-----------------------------+----------+-----------+---------+
 * |    | Num. Registro tipo 90 |                             | 1        | 126 | 126 | N       |
 * +----+-----------------------+-----------------------------+----------+-----------+---------+
 * 
 * 
 * @update: 2019 MAY 26
 * @author: <deepcell@gmail.com>
 * @observation: 
 *      ifdef PHP_WIN32 PHP_EOL = "\r\n"
 *      else PHP_EOL "\n" (all unix system)  >>> programas da receita federal nao reconhecem.
 * 
 */


class Sintegra {

    /**
     * Declaracao das propriedades
     */

    # propriedades genericas
    public $data_inicial = '';                      // tamanho[8] formato[N]
    public $data_final = '';                        // tamanho[8] formato[N]
    public $bc_icms = '';                           // tamanho[13] formato[N] info[com 2 decimais]
    public $cliente_documento = '';                 // tamanho[14] formato[N] info[CPF/CNPJ tomador do servico]
    public $nf_modelo = '';                         // tamanho[2] formato[N]
    public $nf_serie = '';                          // tamanho[2] formato[X]
    public $nf_subserie = '';                       // tamanho[2] formato[X]
    public $nf_numero = '';                         // tamanho[10] formato[N]
    public $nf_cfop = '';                           // tamanho[4] formato[N]
    public $nf_tipo_receita = 1;                    // tamanho[1] formato[N] info[]
    public $aliquota_icms_inteiro = '';             // tamanho[2] formato[N] info[valor inteiro]
    public $empresa_cnpj = '';                      // tamanho[14] formato[N]
    public $empresa_insc_uf = '';                   // tamanho[14] formato[X]
    public $file_sintegra = '';
    public $layout_sintegra = '';
    public $blanks = '';                            // preenche com branco/vazio


    # propriedades do registro 10
    public $tipo10 = 10;                            // #01 tamanho[02] formato[N]
                                                    // #02 $empresa_cnpj usa propriedade generica
                                                    // #03 $empresa_insc_uf usa proprieddade generica
    public $empresa_razao_social = '';              // #04 tamanho[35] formato[X]
    public $empresa_municipio = '';                 // #05 tamanho[30] formato[X]
    public $empresa_uf = '';                        // #06 tamanho[2] formato[X]
    public $empresa_fax = '';                       // #07 tamanho[10] formato[N]
                                                    // #08 $data_inicial e #09 $data_final usam propriedade genericas
    public $cod_arquivo_magnetico_entregue = '3';   // #10 tamanho[1] formato[X]  valor 2 ou 3 ?
    public $cod_natureza_op_informada = '3';        // #11 tamanho[1] formato[X]
    public $cod_finalidade_arquivo_magnetico = '1'; // #12 tamanho[1] formato[X]
    public $tipo10total = 0;                        // total de registros do tipo 10


    # propriedades do registro 11
    public $tipo11 = 11;                      // #01 tamanho[02] formato[N]
    public $empresa_logradouro = '';          // #02 tamanho[34] formato[X]
    public $empresa_numero = '';              // #03 tamanho[5] formato[N]
    public $empresa_complemento = '';         // #04 tamanho[22] formato[X]
    public $empresa_bairro = '';              // #05 tamanho[15] formato[X]
    public $empresa_cep = '';                 // #06 tamanho[8] formato[N]
    public $empresa_nome_contato = '';        // #07 tamanho[28] formato[X]
    public $empresa_telefone = '';            // #08 tamanho[12] formato[N]
    public $tipo11total = 0;                  // total de registros do tipo 11


    # propriedades do registro 75
    public $tipo75 = 75;                      // #01 tamanho[2] formato[N]
                                              // #02 $data_inicial e #03 $data_final usam propriedade genericas
    public $cod_servico_contribuinte = '';    // #04 tamanho[14] formato[X]
    public $cod_ncm = '';                     // #05 tamanho[8] formato[X] info[cod. nomenclatura comum do mercosul]
    public $desc_servico = '';                // #06 tamanho[53] formato[X]
    public $unid_medida = 'mbps';             // #07 tamanho[6] formato[X] info[ex.: kg, kWh, kbps, mbps, ..]
    public $aliquota_ipi = '00000';           // #08 tamanho[5] formato[N] info[com 2 decimais]
    public $aliquota_icms = '0000';           // #09 tamanho[4] formato[N] info[com 2 decimais]
    public $reducao_bc_icms = '00000';        // #10 tamanho[5] formato[N] info[com 2 decimais]
                                              // #11 $bc_icms usa propriedade generica
    public $tipo75total = 0;                  // total de registros do tipo 75


    # propriedades do registro 76
    public $tipo76 = 76;                      // #01 tamanho[2] formato[N]
                                              // #02 $cliente_documento usa propriedade generica
    public $cliente_insc_uf = '';             // #03 tamanho[14] formato[X]
                                              // #04 $nf_modelo usa propriedade generica
                                              // #05 $nf_serie usa propriedade generica
                                              // #06 $nf_subserie usa propriedade generica
                                              // #07 $nf_numero usa propriedade generica
                                              // #08 $nf_cfop usa propriedade generica
                                              // #09 $nf_tipo_receita usa propriedade generica
    public $nf_data_recebimento_emissao = ''; // #10 tamanho[8] formato[N]
    public $nf_uf = '';                       // #11 tamanho[2] formato[X]
    public $valor_total = '';                 // #12 tamanho[13] formato[N] info[com 2 decimais]
                                              // #13 $bc_icms usa propriedade generica
    public $valor_icms = '';                  // #14 tamanho[12] formato[N] info[com 2 decimais]
    public $isenta_nao_tributada = '';        // #15 tamanho[12] formato[N] info[com 2 decimais]
    public $outras = '';                      // #16 tamanho[12] formato[N] info[valor que nao confira debito/credito do icms - com 2 decimais] 
                                              // #17 $aliquota_icms_inteiro usa propriedade generica
    public $nf_situacao = 'N';                // #18 tamanho[1] formato[X]
    public $tipo76total = 0;                  // total de registros do tipo 76


    # propriedades do registro 77
    public $tipo77 = 77;                      // #01 tamanho[2] formato[N]
                                              // #02 $cliente_documento usa propriedadee generica
                                              // #03 $nf_modelo usa propriedade generica
                                              // #04 $nf_serie usa propriedade generica
                                              // #05 $nf_subserie usa propriedade generica
                                              // #06 $nf_numero usa propriedade generica
                                              // #07 $nf_cfop usa propriedade generica
                                              // #08 $nf_tipo_receita usa propriedade generica
    public $numero_item = '001';              // #09 tamanho[3] formato[N] info[padra fica sendo 001]
    public $cod_servico_informante = '';      // #10 tamanho[11] formato[X]
    public $qtde_servico_informante = '';     // #11 tamanho[13] formato[N] info[com 3 decimais]
    public $valor_servico = '';               // #12 tamanho[12] formato[N] info[valor bruto unitario x quantidade com 2 decimais]
    public $valor_desconto = '';              // #13 tamanho[12] formato[N] info[com 2 decimais]
                                              // #14 $bc_icms usa propriedade generica
                                              // #15 $aliquota_icms_inteiro usa propriedade generica
    public $cnpj_mf = '';                     // #16 tamanho[14] formato[N] info[CNPJ/MF operadora destino]
    public $cod_terminal = '';                // #17 tamanho[10] formato[N] info[numero terminal]
    public $tipo77total = 0;                  // total de registros do tipo 77


    # propriedades do registro 90
    public $tipo90 = 90;                      // #01 tamanho[2] formato[N]
                                              // #02 $empresa_cnpj usa propriedade generica
                                              // #03 $empresa_insc_uf usa propriedade generica
    public $tipo = '';                        // #04 tamanho[2] formato[N] info[usar variavel $tipoXX para cada inicio de posicao]
    public $num_registro_tipo90 = '1';        // -- tamanho[1] formato[N]
    public $tipo90total = 0;                  // total de registros do tipo 90



    /**
     * Gerar arquivo sintegra.
     * 
     * Exemplo validacao dos campos
     * substr(string, start, length)
     * $valor = substr($valor, 0, $campo->tamanho);
     * string - str_pad(string, pad_length, pad_string, pad_type)
     * $valor = str_pad($valor, $campo->tamanho, ' ', $campo->pad_type); // STR_PAD_RIGHT (default), STR_PAD_LEFT, or STR_PAD_BOTH
     * big integer - str_pad(string, pad_length, pad_string, pad_type)
     * $valor = str_pad($valor, $campo->tamanho, '0', $campo->pad_type); // STR_PAD_RIGHT (default), STR_PAD_LEFT, or STR_PAD_BOTH
     *
     */
    public function gerarArquivo(
        $arrayMESTRE, $arrayITEM, $dtini, $dtfim, 
        $data_apuracao, $data_emissao, $dados_empresa, 
        $modelo, $tipo_utilizacao, $database) 
    {
        /**
         * Gerar header sintegra
         * composto pelo registro 10 e registro 11
         * >>> Dados da empresa <<<
         */
        # tratar data inicial e data final
        $expdi = explode("-",$dtini);
        $this->data_inicial = $expdi['0'].$expdi['1'].$expdi['2'];
        $expdf = explode("-",$dtfim);
        $this->data_final = $expdf['0'].$expdf['1'].$expdf['2'];

    	# 02 tratar cnpj empresa
        $cnpj = str_replace('.', '', str_replace('/', '', str_replace('-', '', $dados_empresa['0']['cnpj'])));
        if (strlen($cnpj) != 14)
            throw new Exception('O CNPJ da empresa precisa conter exatos 14 caracteres, corrija essa informação no cadastro de empresa no sistema. O arquivo SINTEGRA n&atilde;o pode ser escrito!');

        $this->empresa_cnpj = str_pad($cnpj, 14, '0', STR_PAD_LEFT); // N

        # 03 tratar insc. estadual
        $ie = str_replace('.', '', str_replace('/', '', str_replace('-', '', $dados_empresa['0']['ie'])));
        if ($ie == '00000000000000' or empty($ie))
            $ie = 'ISENTO';

        $this->empresa_insc_uf = str_pad($ie, 14, ' ', STR_PAD_RIGHT); // X

        # 04 tratar nome contribuinte
        $razao_social = str_replace('.', '', str_replace('/', '', str_replace('-', '', $dados_empresa['0']['razao_social'])));
        $this->empresa_razao_social = str_pad($razao_social, 35, ' ', STR_PAD_RIGHT); // X
        
        # 05 tratar municipio
        $cidade = str_replace('.', '', str_replace('/', '', str_replace('-', '', $dados_empresa['0']['cidade'])));
        $this->empresa_municipio = str_pad($cidade, 30, ' ', STR_PAD_RIGHT); // X

        # 06 tratar UF
        $estado = str_replace('.', '', str_replace('/', '', str_replace('-', '', $dados_empresa['0']['estado'])));
        $this->empresa_uf = str_pad($estado, 2, ' ', STR_PAD_RIGHT); // X

        # 07 tratar fax
        $fax = str_replace(' ', '', str_replace('(', '', str_replace(')', '', str_replace('.', '', str_replace('/', '', str_replace('-', '', $dados_empresa['0']['fax']))))));
        $this->empresa_fax = str_pad($fax, 10, '0', STR_PAD_LEFT); // N

        # 08 tratar data inicial
        $this->data_inicial = str_replace('.', '', str_replace('/', '', str_replace('-', '', $this->data_inicial)));
        $this->data_inicial = str_pad($this->data_inicial, 8, '0', STR_PAD_LEFT); // N

        # 09 tratar data final
        $this->data_final = str_replace('.', '', str_replace('/', '', str_replace('-', '', $this->data_final)));
        $this->data_final = str_pad($this->data_final, 8, '0', STR_PAD_LEFT); // N

        # 10 tratar cod. identificacao convenio ou cod. identificacao arquivo magnetico entregue
        $this->cod_arquivo_magnetico_entregue = str_pad($dados_empresa['0']['cod_id_convenio'], 1, ' ', STR_PAD_RIGHT); // X

        # 11 tratar cod. identificacao natureza op. informada
        $this->cod_natureza_op_informada = str_pad($dados_empresa['0']['cod_natureza_op_informada'], 1, ' ', STR_PAD_RIGHT); // X

        # 12 tratar cod. finalidade do arquivo magnetico
        $this->cod_finalidade_arquivo_magnetico = str_pad($dados_empresa['0']['cod_finalidade_arquivo_magnetico'], 1, ' ', STR_PAD_RIGHT); // X

        # soma total do registro
        $this->tipo10total += 1;

        # Gerar linha para registro 10
        $this->layout_sintegra = $this->tipo10 . $this->empresa_cnpj . $this->empresa_insc_uf . $this->empresa_razao_social . 
            $this->empresa_municipio . $this->empresa_uf . $this->empresa_fax . $this->data_inicial . $this->data_final . 
            $this->cod_arquivo_magnetico_entregue . $this->cod_natureza_op_informada . $this->cod_finalidade_arquivo_magnetico . 
            "\r\n"; // essa propriedade recebe dados dos tipos de registros. >>> PHP_EOL nao funciona no windows (programa sintegra) <<<


        # tratar dados complementares da empresa para o registro 11
        # 02 logradouro
        $this->empresa_logradouro = str_pad($dados_empresa['0']['logradouro'], 34, ' ', STR_PAD_RIGHT); // X

        # 03 numero
        if (!is_numeric($dados_empresa['0']['numero']))
            throw new Exception('O numero do logradouro da empresa precisa ser numerico, corrija essa informação no cadastro de empresa no sistema. O arquivo SINTEGRA n&atilde;o pode ser escrito!');

        $this->empresa_numero = str_pad($dados_empresa['0']['numero'], 5, '0', STR_PAD_LEFT); // N

        # 04 complemento
        $this->empresa_complemento = str_pad($dados_empresa['0']['complemento'], 22, ' ', STR_PAD_RIGHT); // X

        # 05 bairro
        $this->empresa_bairro = str_pad($dados_empresa['0']['bairro'], 15, ' ', STR_PAD_RIGHT); // X

        # 06 cep
        $empresa_cep = str_replace('-', '', $dados_empresa['0']['cep']);
        $this->empresa_cep = str_pad($empresa_cep, 8, '0', STR_PAD_LEFT); // N

        # 07 nome do contato
        $this->empresa_nome_contato = str_pad($dados_empresa['0']['nome_contato'], 28, ' ', STR_PAD_RIGHT); // X

        # 08 telefone
        $empresa_telefone = str_replace(' ', '', str_replace('(', '', str_replace(')', '', str_replace('.', '', str_replace('/', '', str_replace('-', '', $dados_empresa['0']['empresa_telefone']))))));
        $this->empresa_telefone = str_pad($empresa_telefone, 12, '0', STR_PAD_LEFT); // N

        # soma total do registro
        $this->tipo11total += 1;

        # Gerar linha para registro 11
        $this->layout_sintegra .= $this->tipo11 . $this->empresa_logradouro . $this->empresa_numero . $this->empresa_complemento . 
            $this->empresa_bairro . $this->empresa_cep . $this->empresa_nome_contato . $this->empresa_telefone . 
            "\r\n"; // essa propriedade recebe dados dos tipos de registros. >>> PHP_EOL nao funciona no windows (programa sintegra) <<<





        /**
         * Gerar registro 75
         */
        // exemplo validacao dos campos
        // X = String alinhado a esquerda e restante do tamanho do campo preenche com vazios.
        $valor1 = str_pad($this->empresa_cnpj, 14, ' ', STR_PAD_RIGHT); // STR_PAD_RIGHT (default), STR_PAD_LEFT, or STR_PAD_BOTH
        // N = Inteiro alinhado a direita e restante do tamanho do campo preenche com zeros a esquerda.
        $valor2 = str_pad($this->empresa_cnpj, 14, '0', STR_PAD_LEFT); // STR_PAD_RIGHT (default), STR_PAD_LEFT, or STR_PAD_BOTH

        //print '<pre>'; print_r($arrayITEM); print '</pre>';
        foreach((array) $arrayITEM as $valueITEM)
        {
            # tratar propriedades
            # 01, 02 e 03 ja tratados no inicio da classe

            # 04 cod. produto ou servico
            //$this->cod_servico_contribuinte = str_pad($valueITEM['@PlanID'], 14, ' ', STR_PAD_RIGHT); // X
            $this->cod_servico_contribuinte = str_pad($valueITEM['@ClientID'], 14, ' ', STR_PAD_RIGHT); // X  info[planID nao esta validando - programa sintegra retorna: registro informado em duplicidade]

            # 05 codigo NCM
            $this->cod_ncm = str_pad($this->cod_ncm, 8, ' ', STR_PAD_RIGHT); // X

            # 06 descricao produto ou servico
            $this->desc_servico = str_pad($valueITEM['@PlanTitle'], 53, ' ', STR_PAD_RIGHT); // X

            # 07 un. de medida (ex. mbps, kbps,..)
            $this->unid_medida = str_pad($this->unid_medida, 6, ' ', STR_PAD_RIGHT); // X

            # 08 aliquota IPI (2 decimais)
            $this->aliquota_ipi = str_pad($this->aliquota_ipi, 5, '0', STR_PAD_LEFT); // N

            # 09 aliquota do ICMS
            $this->aliquota_icms = str_pad($this->aliquota_icms, 4, '0', STR_PAD_LEFT); // N

            # 10 reducao BC do ICMS
            $this->reducao_bc_icms = str_pad($this->reducao_bc_icms, 5, '0', STR_PAD_LEFT); // N

            # 11 BC do ICMS
            $this->bc_icms = str_pad($this->bc_icms, 13, '0', STR_PAD_LEFT); // N

            # 3 - gerar linhas no loop(foreach) para registro 75
            $this->layout_sintegra .= $this->tipo75 . $this->data_inicial . $this->data_final . $this->cod_servico_contribuinte . 
                $this->cod_ncm . $this->desc_servico . $this->unid_medida . $this->aliquota_ipi . $this->aliquota_icms . 
                $this->reducao_bc_icms . $this->bc_icms . 
                "\r\n"; // essa propriedade recebe dados dos tipos de registros. >>> PHP_EOL nao funciona no windows (programa sintegra) <<<

            # incremento - total de registros do tipo 75
            $this->tipo75total += 1;
        }




        /**
         * Gerar registro 76
         */
        //print '<pre>'; print_r($arrayMESTRE); print '</pre>';
        foreach((array) $arrayMESTRE as $valueMESTRE)
        {
            // tratar propriedades

            # 01 tipo do registro

            # 02 tratar documento (cpf/cnpj) do tomador do servico - info[cfop depende do tipo do cliente, se PJ usar 5303, se PF 5307]
            if (!empty($valueMESTRE['@ClientCPF'])) {
                $cliente_documento = str_replace('.', '', str_replace('/', '', str_replace('-', '', $valueMESTRE['@ClientCPF'])));

                if (strlen($cliente_documento) != 11)
                    throw new Exception('O CPF do cliente precisa conter exatos 11 caracteres, corrija essa informação no cadastro de empresa no sistema. O arquivo SINTEGRA n&atilde;o pode ser escrito!');

                $this->cliente_documento = str_pad($cliente_documento, 14, '0', STR_PAD_LEFT); // N
                $tipo_cliente = 'pf';
            }
            else {
                $cliente_documento = $valueMESTRE['@ClientCNPJ'];
                $cliente_documento = str_replace('.', '', str_replace('/', '', str_replace('-', '', $valueMESTRE['@ClientCNPJ'])));

                if (strlen($cliente_documento) != 14)
                    throw new Exception('O CNPJ do cliente precisa conter exatos 14 caracteres, corrija essa informação no cadastro de empresa no sistema. O arquivo SINTEGRA n&atilde;o pode ser escrito!');

                $this->cliente_documento = str_pad($cliente_documento, 14, '0', STR_PAD_LEFT); // N
                $tipo_cliente = 'pj';
            }

            # 03 tratar insc. estadual do tomador do servico
            $cliente_insc_uf = str_replace('.', '', str_replace('/', '', str_replace('-', '', $valueMESTRE['@ClientIE'])));
            if ($cliente_insc_uf == '00000000000000' or empty($cliente_insc_uf))
                $cliente_insc_uf = 'ISENTO';

            $this->cliente_insc_uf = str_pad($cliente_insc_uf, 14, ' ', STR_PAD_RIGHT); // X
            //$this->cliente_insc_uf = str_pad($cliente_insc_uf, 14, ' ', STR_PAD_LEFT); // N  info[assim nao funciona]

            # 04
            $this->nf_modelo = str_pad($modelo, 2, '0', STR_PAD_LEFT); // N

            # 05
            $this->nf_serie = str_pad($this->nf_serie, 2, ' ', STR_PAD_RIGHT); // X

            # 06
            $this->nf_subserie = str_pad($this->nf_subserie, 2, ' ', STR_PAD_RIGHT); // X

            # 07
            # consultar NF do cliente no periodo
            # "SELECT `num_nf` FROM `Nfsc_21_Notas` WHERE `cliente_id`=5 and `periodo_apuracao`='1904'"
            $get_nf_numero = $database->select("Nfsc_21_Notas", "num_nf", [
                "AND" => [ 
                    "cliente_id[=]" => $valueMESTRE['@ClientID'], 
                    "periodo_apuracao[=]" => $data_apuracao
                ]
            ]);
            $num_nf = $get_nf_numero['0'];
            $this->nf_numero = str_pad($num_nf, 10, '0', STR_PAD_LEFT); // N

            # 08 - info[cfop depende do tipo do cliente, se PJ usar 5303, se PF 5307]
            if ($tipo_cliente == 'pf') {
                $this->nf_cfop = str_pad('5307', 4, '0', STR_PAD_LEFT); // N  $valueMESTRE['@CfopCode']
            } else {
                // para PJ
                $this->nf_cfop = str_pad('5303', 4, '0', STR_PAD_LEFT); // N  $valueMESTRE['@CfopCode']
            }
            
            # 09
            $this->nf_tipo_receita = str_pad($this->nf_tipo_receita, 1, '0', STR_PAD_LEFT); // N

            # 10
            $invoice_paid_date = str_replace('-', '', $valueMESTRE['@InvoicePaidDate']);
            $this->nf_data_recebimento_emissao = str_pad($invoice_paid_date, 8, '0', STR_PAD_LEFT); // N

            # 11
            $this->nf_uf = str_pad($dados_empresa['0']['estado'], 2, ' ', STR_PAD_RIGHT); // X

            # 12 
            $invoice_total = str_replace('.', '', $valueMESTRE['@PlanAmount']);  // foi trocado de @InvoiceTotal para @PlanAmount
            $this->valor_total = str_pad($invoice_total, 13, '0', STR_PAD_LEFT); // N

            # 13
            $this->bc_icms = str_pad($this->bc_icms, 13, '0', STR_PAD_LEFT); // N

            # 14
            $this->valor_icms = str_pad($this->valor_icms, 12, '0', STR_PAD_LEFT); // N

            # 15
            $this->isenta_nao_tributada = str_pad($this->isenta_nao_tributada, 12, '0', STR_PAD_LEFT); // N

            # 16
            $this->outras = str_pad($invoice_total, 12, '0', STR_PAD_LEFT); // N

            # 17
            $this->aliquota_icms_inteiro = str_pad($this->aliquota_icms_inteiro, 2, '0', STR_PAD_LEFT); // N

            # 18
            $this->nf_situacao = str_pad($this->nf_situacao, 1, ' ', STR_PAD_RIGHT); // X

            # 4 - gerar linhas no loop(foreach) para registro 76
            $this->layout_sintegra .= $this->tipo76 . $this->cliente_documento . $this->cliente_insc_uf . $this->nf_modelo . 
                $this->nf_serie . $this->nf_subserie . $this->nf_numero . $this->nf_cfop . $this->nf_tipo_receita . 
                $this->nf_data_recebimento_emissao . $this->nf_uf . $this->valor_total . $this->bc_icms . $this->valor_icms . 
                $this->isenta_nao_tributada . $this->outras . $this->aliquota_icms_inteiro . $this->nf_situacao . 
                "\r\n"; // essa propriedade recebe dados dos tipos de registros. >>> PHP_EOL nao funciona no windows (programa sintegra) <<<

            # incremento - total de registros do tipo 76
            $this->tipo76total += 1;
        }





        /**
         * Gerar registro 77
         */
        foreach((array) $arrayMESTRE as $valueMESTRE)
        {
            // tratar propriedades
            # 01 tipo do registro

            # 02 tratar documento (cpf/cnpj) do tomador do servico
            if (!empty($valueMESTRE['@ClientCPF'])) {
                $cliente_documento = str_replace('.', '', str_replace('/', '', str_replace('-', '', $valueMESTRE['@ClientCPF'])));

                if (strlen($cliente_documento) != 11)
                    throw new Exception('O CPF do cliente precisa conter exatos 11 caracteres, corrija essa informação no cadastro de empresa no sistema. O arquivo SINTEGRA n&atilde;o pode ser escrito!');

                $this->cliente_documento = str_pad($cliente_documento, 14, '0', STR_PAD_LEFT); // N
                $tipo_cliente = 'pf';
            }
            else {
                $cliente_documento = $valueMESTRE['@ClientCNPJ'];
                $cliente_documento = str_replace('.', '', str_replace('/', '', str_replace('-', '', $valueMESTRE['@ClientCNPJ'])));

                if (strlen($cliente_documento) != 14)
                    throw new Exception('O CNPJ do cliente precisa conter exatos 14 caracteres, corrija essa informação no cadastro de empresa no sistema. O arquivo SINTEGRA n&atilde;o pode ser escrito!');

                $this->cliente_documento = str_pad($cliente_documento, 14, '0', STR_PAD_LEFT); // N
                $tipo_cliente = 'pj';
            }

            # 03
            $this->nf_modelo = str_pad($modelo, 2, '0', STR_PAD_LEFT); // N

            # 04
            $this->nf_serie = str_pad($this->nf_serie, 2, ' ', STR_PAD_RIGHT); // X

            # 05
            $this->nf_subserie = str_pad($this->nf_subserie, 2, ' ', STR_PAD_RIGHT); // X

            # 06
            # consultar NF do cliente no periodo
            # "SELECT `num_nf` FROM `Nfsc_21_Notas` WHERE `cliente_id`=5 and `periodo_apuracao`='1904'"
            $get_nf_numero = $database->select("Nfsc_21_Notas", "num_nf", [
                "AND" => [ 
                    "cliente_id[=]" => $valueMESTRE['@ClientID'], 
                    "periodo_apuracao[=]" => $data_apuracao
                ]
            ]);
            $num_nf = $get_nf_numero['0'];
            $num_nf_arr[] = $get_nf_numero['0']; // usado para gravar status da nota apos o arquivo sintegra ja ter sido gerado.
            $this->nf_numero = str_pad($num_nf, 10, '0', STR_PAD_LEFT); // N

            # 07
            //$this->nf_cfop = str_pad($valueMESTRE['@CfopCode'], 4, '0', STR_PAD_LEFT); // N
            # 07 - info[cfop depende do tipo do cliente, se PJ usar 5303, se PF 5307]
            if ($tipo_cliente == 'pf') {
                $this->nf_cfop = str_pad('5307', 4, '0', STR_PAD_LEFT); // N  $valueMESTRE['@CfopCode']
            } else {
                // para PJ
                $this->nf_cfop = str_pad('5303', 4, '0', STR_PAD_LEFT); // N  $valueMESTRE['@CfopCode']
            }
            
            # 08
            $this->nf_tipo_receita = str_pad($this->nf_tipo_receita, 1, '0', STR_PAD_LEFT); // N

            # 09 info[se houver mais que 1 item na NF, vai precisar iterar esse item nesse ponto. ex.: 001, 002, ..]
            $this->numero_item = str_pad($this->numero_item, 3, '0', STR_PAD_LEFT); // N

            # 10 cod. do servico info[arquivo ITEM]. 
            # IMPORTANTE: Precisa ser o mesmo informado no cod. do item no REGISTRO 75.
            $get_item_data = $database->select("Nfsc_21_Item", [
                "codigo_item", 
                "quantidade_contratada", 
                "total", 
                "descontos", 
                "bc_icms"
                ], [
                "AND" => [ 
                    "numero[=]" => $this->nf_numero, 
                    "ano_mes_ref_apuracao[=]" => $data_apuracao
                ]
            ]);
            $cod_item = $get_item_data['0']['codigo_item'];
            // $this->cod_servico_informante = str_pad($cod_item, 11, ' ', STR_PAD_RIGHT); // X
            $this->cod_servico_informante = str_pad($valueMESTRE['@ClientID'], 11, ' ', STR_PAD_RIGHT); // X
            

            # 11
            $quantidade_contratada = $get_item_data['0']['quantidade_contratada'];
            $this->qtde_servico_informante = str_pad($quantidade_contratada, 13, '0', STR_PAD_LEFT); // N
            
            # 12
            $total_valor_servico = $get_item_data['0']['total'];
            $this->valor_servico = str_pad($total_valor_servico, 12, '0', STR_PAD_LEFT); // N
            
            # 13
            $descontos = $get_item_data['0']['descontos'];
            $this->valor_desconto = str_pad($descontos, 12, '0', STR_PAD_LEFT); // N

            # 14 bc icms
            $base_calc_icms = $get_item_data['0']['bc_icms'];
            $this->bc_icms = str_pad($base_calc_icms, 12, '0', STR_PAD_LEFT); // N
            
            # 15 info[nao temos o dado - no exemplo sintegra consta como zero]
            $this->aliquota_icms_inteiro = str_pad($this->aliquota_icms_inteiro, 2, '0', STR_PAD_LEFT); // N
            
            # 16 info[nao temos o dado - no exemplo sintegra consta como zero]
            $this->cnpj_mf = str_pad($this->cnpj_mf, 14, '0', STR_PAD_LEFT); // N
            
            # 17
            $this->cod_terminal = str_pad($this->cod_terminal, 10, '0', STR_PAD_LEFT); // N

            // 5 - gerar linhas no loop(foreach) para registro 77
            $this->layout_sintegra .= $this->tipo77 . $this->cliente_documento . $this->nf_modelo . $this->nf_serie . 
                $this->nf_subserie . $this->nf_numero . $this->nf_cfop . $this->nf_tipo_receita . $this->numero_item . 
                $this->cod_servico_informante . $this->qtde_servico_informante . $this->valor_servico . $this->valor_desconto . 
                $this->bc_icms . $this->aliquota_icms_inteiro . $this->cnpj_mf . $this->cod_terminal . 
                "\r\n"; // essa propriedade recebe dados dos tipos de registros. >>> PHP_EOL nao funciona no windows (programa sintegra) <<<

            # incremento - total de registros do tipo 77
            $this->tipo77total += 1;
        }



        /**
         * Gerar footer sintegra
         * composto pelo regstro 90
         */
    	# 02 tratar cnpj empresa (cnpj do informante)
        $cnpj = str_replace('.', '', str_replace('/', '', str_replace('-', '', $dados_empresa['0']['cnpj'])));
        if (strlen($cnpj) != 14)
            throw new Exception('O CNPJ da empresa precisa conter exatos 14 caracteres, corrija essa informação no cadastro de empresa no sistema. O arquivo SINTEGRA n&atilde;o pode ser escrito!');

        $this->empresa_cnpj = str_pad($cnpj, 14, '0', STR_PAD_LEFT); // N

        # 03 tratar insc. estadual
        $ie = str_replace('.', '', str_replace('/', '', str_replace('-', '', $dados_empresa['0']['ie'])));
        if ($ie == '00000000000000' or empty($ie))
            $ie = 'ISENTO';

        $this->empresa_insc_uf = str_pad($ie, 14, ' ', STR_PAD_RIGHT); // X


        # 04 tipo a ser totalizado
        $this->tipo75 = str_pad($this->tipo75, 2, '0', STR_PAD_LEFT); // N

        # 05 total de registro do tipo informado no campo anterior
        $soma_total_registros = $this->tipo75total;
        $this->tipo75total = str_pad($this->tipo75total, 8, '0', STR_PAD_LEFT); // N

        # 06 tipo a ser totalizado
        $this->tipo76 = str_pad($this->tipo76, 2, '0', STR_PAD_LEFT); // N

        # 07 total de registro do tipo informado no campo anterior
        $soma_total_registros += $this->tipo76total;
        $this->tipo76total = str_pad($this->tipo76total, 8, '0', STR_PAD_LEFT); // N

        # 08 tipo a ser totalizado
        $this->tipo77 = str_pad($this->tipo77, 2, '0', STR_PAD_LEFT); // N

        # 09 total de registro do tipo informado no campo anterior
        $soma_total_registros += $this->tipo77total;
        $this->tipo77total = str_pad($this->tipo77total, 8, '0', STR_PAD_LEFT); // N


        # 10 total de registros no arquivo
        # soma total do registro 90
        $this->tipo90total += 1;
        $soma_total_registros += ($this->tipo10total + $this->tipo10total + $this->tipo90total); // soma total dos registros 10, 11 e 90
        $this->total_registros = str_pad($soma_total_registros, 8, '0', STR_PAD_LEFT); // N
        $this->total_registros = '99' . $this->total_registros;

        # preenche com vazio o final da linha
        $this->blanks = str_pad($this->blanks, 55, ' ', STR_PAD_RIGHT); // X

        # numero de registros tipo 90
        $this->num_registro_tipo90 = str_pad($this->num_registro_tipo90, 1, ' ', STR_PAD_RIGHT); // X

        # 6 - gerar linha para registro 90
        $this->layout_sintegra .= $this->tipo90 . $this->empresa_cnpj . $this->empresa_insc_uf . 
            $this->tipo75 . $this->tipo75total . 
            $this->tipo76 . $this->tipo76total . 
            $this->tipo77 . $this->tipo77total . 
            $this->total_registros . $this->blanks . $this->num_registro_tipo90;
            // "\r\n"; // essa propriedade recebe dados dos tipos de registros. >>> PHP_EOL nao funciona no windows (programa sintegra) <<<br





        /**
         * Gravar arquivo sintegra
         */
        $horario_gravado = date("YmdHis");
        echo "</br>$horario_gravado</br>";
        $this->file_sintegra = $this->empresa_cnpj . '-' . $this->nf_data_recebimento_emissao . date('YmdHis') . '-Sintegra.txt';
        $this->layout_sintegra .= '' . "\r\n"; // essa propriedade recebe dados dos tipos de registros.
      /*  echo "</br>";
        echo "/Files/Sintegra/" . $this->file_sintegra;
        echo "</br>";
        echo $this->layout_sintegra;
        echo "</br>";
        echo LOCK_EX;*/
	    if (!@file_put_contents('Files/Sintegra/'.$this->file_sintegra, $this->layout_sintegra, LOCK_EX))
	        throw new Exception('O arquivo '.$this->file_sintegra.' n&atilde;o pode ser escrito!');
	    else 
        {
            /**
             * grava nome e data do arquivo que foi gerado na tabela: `Nfsc_21_NF_Regencia`
             * caso array contenha dados.
             */
            if (!empty($this->layout_sintegra)) 
            {
                $setNfsSintegraRegencia = $database->insert("Nfsc_21_NF_Regencia", [
                    "data_gerado" => date('Y-m-d H:i:s'), 
                    "arquivo" => $this->file_sintegra
                ]);
            }

            /**
             * atualizar o status da nota para `gerada`
             * UPDATE `Nfsc_21_Notas` SET `status` = 'gerada' WHERE `Nfsc_21_Notas`.`id` = $_GET['tblnotasid'];
             */
            
            foreach ($num_nf_arr as $nfk => $nfv) {
                $database->update("Nfsc_21_Notas", [
                    "status" => "gerada",
                    "gerada" => 1
                ], [
                    
                    "id[=]" => $nfv
                ]);
               // echo $nfv;
            }

            /**
             * Mostrar mensagens
             */
            echo '<pre>O arquivo <b>`'.$this->file_sintegra.'`</b> foi escrito com sucesso!</pre>';
        }


    } // end method



    

}

// auto test
//$sintegra = new Sintegra;
//$sintegra->gerarArquivo();
