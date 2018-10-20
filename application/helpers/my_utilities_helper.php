<?php

defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('verificaSessao')) {

    function verificaSessao() {
        $ci = & get_instance();
        if ($ci->config->item('maintenance_mode') == TRUE) {
            redirect('manutencao');
        }
        $ci->load->model('Adm/Usuario/Usuario');
        if ($ci->session->userdata('logado') == FALSE) {
            if ($ci->input->post_get("isAjax")) {
                die("login");
            }
            $_SESSION['url'] = uri_string();
            redirect('login');
        }

        $status = $ci->Usuario->verificarStatus($ci->session->userdata('id_usuario'));

        if ($status[0]['status'] == 0) {
            $ci->session->sess_destroy();
            $_SESSION['url'] = uri_string();
            redirect('login');
        }
        if ($status[0]['lembrar_senha'] == "t") {
            $_SESSION['url'] = uri_string();
            redirect("alterarsenha");
        }
    }

}

if (!function_exists('concatObject')) {

    function concatObject(&$object, $data) {
        foreach ($data as $key => $value) {
            $object->{$key} = $value;
        }
    }

}

if (!function_exists('stop')) {

    function stop($var) {
        die(var_dump($var));
    }

}

if (!function_exists('last_query')) {

    function last_query() {
        $ci = & get_instance();
        die($ci->db->last_query());
    }

}

if (!function_exists('last_query_sqlserver')) {

    function lastQuerySQL() {
        $ci = & get_instance();
        die($ci->db2->last_query());
    }

}

if (!function_exists('getFeriados')) {

    function getFeriados($ano) {
        $dia = 86400;
        $datas = [];
        $datas['pascoa'] = easter_date($ano);
        $datas['sexta_santa'] = $datas['pascoa'] - (2 * $dia);
        $datas['carnaval'] = $datas['pascoa'] - (47 * $dia);
        $datas['carnaval2'] = $datas['pascoa'] - (48 * $dia);
        $datas['corpus_cristi'] = $datas['pascoa'] + (60 * $dia);
        $feriado = [
            '01/01',
            date('m/d', $datas['carnaval2']),
            date('m/d', $datas['carnaval']),
            date('m/d', $datas['sexta_santa']),
            '05/01',
            date('m/d', $datas['corpus_cristi']),
            '09/07',
            '10/12',
            '11/02',
            '11/15',
            '12/25'
        ];

        if (!empty($local)) {
            array_push($feriado, '10/04');
            array_push($feriado, '15/09');
        }
        return $feriado;
    }

}

if (!function_exists('getNivel')) {

    function getNivel() {
        $ci = & get_instance();
        $ci->load->model('Sistema/Sistema');
        return (int) $ci->Sistema->getNivel();
    }

}

if (!function_exists('verificaNivelUsuarioEditar')) {

    function verificaNivelUsuarioEditar($id_usuario) {
        $ci = & get_instance();
        $ci->load->model('Sistema/Sistema');
        $nivel = (int) $ci->Sistema->getNivel();

        if ($nivel == 0) {
            return;
        }

        $nivelUsuario = (int) $ci->Sistema->getNivel($id_usuario);

        if ($nivel == 1 && $nivelUsuario >= $nivel) {
            return;
        }

        if ($nivelUsuario > $nivel || $ci->session->userdata('id_usuario') === $id_usuario) {
            return;
        }
        $ci->session->set_userdata("mensagem", [
            "form_erros" => "Peça permissão para acessar a área correspondente",
            "status" => "danger",
            "msg" => "Permissão negada",
            "sign" => "exclamation"
        ]);
        redirect('sistemas');
    }

}

if (!function_exists('verificaNivelAcessso')) {

    function verificaNivelAcessso() {
        $nivel = getNivel();

        if ($nivel < 3) {
            return;
        } else {
            $ci = & get_instance();
            $ci->session->set_userdata("mensagem", [
                "form_erros" => "Peça permissão para acessar a área correspondente",
                "status" => "danger",
                "msg" => "Permissão negada",
                "sign" => "exclamation"
            ]);
            redirect('sistemas');
        }
    }

}
if (!function_exists('verificaPermissoes')) {

    /**
     * verificaPermissoes
     * 
     * @param Boolean $oculto Em caso de menu no 4 segmento da url deve ser true
     * @return NULL
     */
    function verificaPermissoes($oculto = FALSE, $url = '', $isAjax = FALSE) {
        $ci = & get_instance();
        $ci->load->model('Sistema/Sistema');
        $dados = $ci->Sistema->verificaPermissoes($oculto, $url);
        if (($dados['consultar'] == 't') || ($url != '')) {
            return $dados;
        }
        if ($isAjax) {
            return [
                "consultar" => "f",
                "alterar" => "f",
                "criar" => "f",
                "imprimir" => "f",
                "emitir" => "f",
                "excluir" => "f"
            ];
        }
        $ci->session->set_userdata("mensagem", [
            "form_erros" => "Peça permissão para acessar a área correspondente",
            "status" => "danger",
            "msg" => "Permissão negada",
            "sign" => "exclamation"
        ]);
        redirect('sistemas');
    }

}

if (!function_exists('verificaPermissoesEspec')) {

    function verificaPermissoesEspec($acao, $oculto = FALSE, $url = '') {
        $ci = & get_instance();

        $ci->load->model('Sistema/Sistema');
        $dados = $ci->Sistema->verificaPermissoesEspec($acao, $oculto, $url);

        if (!empty($dados[$acao]) && $dados[$acao] == 't' && !empty($dados['consultar']) && $dados['consultar'] == 't') {
            return;
        }
        if ((int) $ci->uri->segment(2) > 0) {
            $_SESSION["mensagem"]['form_erros'] = 'Permissão negada!';
            $_SESSION["mensagem"]['status'] = 'danger';
            $_SESSION["mensagem"]['msg'] = "Erro";
            $_SESSION["mensagem"]['sign'] = "exclamation";
            redirect("{$ci->uri->segment(1)}/{$ci->uri->segment(2)}/{$ci->uri->segment(3)}/lista/0");
        } else {
            header("Window: " . base_url('sistemas/0'));
        }
    }

}

if (!function_exists('verificaPermissoesEspecAjax')) {

    /**
     * verificaPermissoesEspecAjax
     * 
     * @param String $acao Qual acao deseja-se verificar
     * @return NULL
     */
    function verificaPermissoesEspecAjax($acao, $url = "") {
        $ci = & get_instance();

        $ci->load->model('Sistema/Sistema');
        $dados = $ci->Sistema->verificaPermissoesEspec($acao, FALSE, $url);

        if (((isset($dados[$acao])) && ($dados[$acao] == 't')) && ((isset($dados['consultar'])) && ($dados['consultar'] == 't'))) {
            return TRUE;
        }
        $dados = [
            'msg' => 'Erro',
            'status' => 'danger',
            'form_erros' => '<p style="font-size:14px;"><strong>Permissão negada!</strong></p>',
            'sign' => "exclamation"
        ];
        return $dados;
    }

}

if (!function_exists('log_exclusao')) {

    function log_exclusao($array) {
        $ci = & get_instance();

        $ci->db->insert('log_exclusao', $array);
    }

}

if (!function_exists('log_crud')) {

    function log_crud($id, $valor, $tabela, $acao) {
        $ci = & get_instance();
        $id_usuario = (int) $ci->session->userdata('id_usuario');
        $value = convert_strtoupper(str_replace(array("'", '"'), '', $valor));
        $action = convert_strtolowerS(str_replace(array("'", '"'), '', $acao));

        $id_table = $id ? $id : 'vazio';
        $value_table3 = (!empty($value) ? $value : 'vazio');
        $value_table2 = str_replace([",", ";", "*", "|", "&"], ".", $value_table3);
        $value_table = str_replace(["/", "\\", "(", ")", "[", "]", "{", "}"], "-", $value_table2);

        execInBackground('log_crud ' . $id_table . ' "' . $value_table . '" ' . $tabela . ' "' . $action . '" ' . $id_usuario);
    }

}

if (!function_exists('log_crud_batch')) {

    function log_crud_batch($id, $acao) {
        $ci = & get_instance();
        $id_usuario = (int) $ci->session->userdata('id_usuario');
        $id_table = urlencode(json_encode($id));
        $action = convert_strtolowerS(str_replace(array("'", '"'), '', $acao));
//        if ($acao == _EXCLUIR) {
//            stop('log_crud_batch ' . $id_table . ' "' . $action . '" ' . $id_usuario);
//        }
        execInBackground('log_crud_batch ' . $id_table . ' "' . $action . '" ' . $id_usuario);
    }

}

if (!function_exists('setLogHash')) {

    function setLogHash($dados, $campo_id, $valor_id, $tabela) {
        $ci = & get_instance();
        $ci->load->model('Sistema/Background/Background');
        return $ci->Background->setLogHash($dados, $campo_id, $valor_id, $tabela);
    }

}

if (!function_exists('deleteLogHash')) {

    function deleteLogHash($id_hash_log) {
        $ci = & get_instance();
        $ci->load->model('Sistema/Background/Background');
        $ci->Background->deleteLogHash($id_hash_log);
    }

}

if (!function_exists('log_atualizacao')) {

    function log_atualizacao($id_hash_log) {
        execInBackground("log_atualizacao {$id_hash_log}");
    }

}

if (!function_exists('template')) {

    function template($id_usuario, $template, $view, $data = NULL, $id_modulo = NULL) {
        $ci = & get_instance();
        $ci->load->model('Sistema/Sistema');
        $ci->load->library('user_agent');
        //$view = 1;
        if ($template !== 'index.nomenu.tpl') {
            $nivel = getNivel();
            $data['md'] = $ci->Sistema->mdmnsmsc('modulo', NULL, $id_usuario, $nivel);

            if (!empty($id_modulo)) {
                $data['mn'] = $ci->Sistema->mdmnsmsc('menu', 'modulo', $id_usuario, $nivel);
                $data['sm'] = $ci->Sistema->mdmnsmsc('sub_menu', 'menu', $id_usuario, $nivel);
                $data['sc'] = $ci->Sistema->mdmnsmsc('sub_camada', 'sub_menu', $id_usuario, $nivel);

                $data['pathUrl'] = $ci->Sistema->rotasMenus($id_modulo);
                $data['pathUrl'] = $ci->Sistema->assignRoutes($data['pathUrl'], $id_modulo);
            } else {
                $data['pathUrl'] = $ci->Sistema->rotasModulos();
            }
        }
        if (!empty($id_usuario)) {
            $data['usuariocia'] = $ci->Sistema->usuarioCias($id_usuario);

            $data['infotemplate'] = $ci->Sistema->informacoesTemplate();
        }
        $data["preferencias"] = $ci->Sistema->getPreferenciasLayout();

        $data['mobile'] = $ci->agent->is_mobile();
        $data['id_md'] = $id_modulo;
        $ci->template->load("sistemas/templates/{$template}.php", $view, $data);
    }

}

if (!function_exists('catchIp')) {

    function catchIp() {
        return (empty($_SESSION["ip"]) ? '' : $_SESSION["ip"]);
    }

}

if (!function_exists('convert_strtoupper')) {

    function convert_strtoupper($str) {
        $palavra = str_replace("[^a-zA-Z0-9_]", "", $str);
        $de = array("'", '"', ",", "Á", "À", "Ã", "Â", "È", "É", "Ê", "Í", "Ò", "Ó", "Ô", "Õ", "Ú", "Ü", "Ç", "á", "à", "ã", "â", "è", "é", "ê", "í", "ò", "ó", "ô", "õ", "ú", "ü", "ç", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
        $para = array("", '', ",", "A", "A", "A", "A", "E", "E", "E", "I", "O", "O", "O", "O", "U", "U", "C", "A", "A", "A", "A", "E", "E", "E", "I", "O", "O", "O", "O", "U", "U", "C", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
        $palavra = str_replace($de, $para, $str);

        return $palavra;
    }

}

if (!function_exists('convert_strtolower')) {

    function convert_strtolower($str) {
        $palavra = str_replace("[^a-zA-Z0-9_]", "", $str);
        $para = array("'", '"', "á", "à", "ã", "â", "è", "é", "ê", "í", "ò", "ó", "ô", "õ", "ú", "ü", "ç", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
        $de = array("", '', "Á", "À", "Ã", "Â", "È", "É", "Ê", "Í", "Ò", "Ó", "Ô", "Õ", "Ú", "Ü", "Ç", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
        $palavra = str_replace($de, $para, $str);
        return $palavra;
    }

}

if (!function_exists('convert_strtolowerS')) {

    function convert_strtolowerS($str) {
        $palavra = str_replace("[^a-zA-Z0-9_]", "", $str);
        $para = array("", '', "c", "a", "a", "a", "a", "e", "e", "e", "i", "o", "o", "o", "o", "u", "u", "a", "a", "a", "a", "e", "e", "e", "i", "o", "o", "o", "o", "u", "u", "c", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
        $de = array("'", '"', "Ç", "á", "à", "ã", "â", "è", "é", "ê", "í", "ò", "ó", "ô", "õ", "ú", "ü", "Á", "À", "Ã", "Â", "È", "É", "Ê", "Í", "À", "Ó", "Ô", "Õ", "Ú", "Ü", "C", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
        $palavra = str_replace($de, $para, $str);
        return $palavra;
    }

}

if (!function_exists('convert_ucfirst_strtolower')) {

    function convert_ucfirst_strtolower($str) {
        $palavra = str_replace("[^a-zA-Z0-9_]", "", $str);
        $para = array("", '', "ç", "á", "à", "ã", "â", "è", "é", "ê", "í", "ò", "ó", "ô", "õ", "ú", "ü", "c", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
        $de = array("'", '"', "Ç", "Á", "À", "Ã", "Â", "È", "É", "Ê", "Í", "À", "Ó", "Ô", "Õ", "Ú", "Ü", "C", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
        $palavra = str_replace($de, $para, $str);
        return ucfirst($palavra);
    }

}

if (!function_exists('removeFormatting')) {

    function removeFormatting($str, $numeric = FALSE) {
        if ($numeric) {
            return preg_replace("/([^A-Za-z0-9.,-])+/", "", $str);
        }
        return preg_replace("/([^A-Za-z0-9])+/", "", $str);
    }

}

if (!function_exists('save_aba')) {

    function save_aba($id_usuario, $id_modulo, $url, $nome) {
        $ci = & get_instance();
        $ci->load->model('Sistema/Sistema');
        $ci->Sistema->save_aba($id_usuario, $id_modulo, $url, $nome);
        //var_dump($ci->db->last_query());die;
    }

}

if (!function_exists('aba')) {

    function aba($id_modulo) {
        $ci = & get_instance();
        $ci->load->model('Sistema/Sistema');
        return $ci->Sistema->aba($id_modulo);
    }

}

if (!function_exists('excluirPendencia')) {

    function excluirPendencia($id_usuario = NULL) {
        if ($id_usuario) {
            execInBackground('excluirPendencia ' . $id_usuario);
        }
    }

}

if (!function_exists('isPendencias')) {

    function isPendencias($id, $campo, $table, $rota = NULL) {
        $ci = & get_instance();
        $ci->load->model('Sistema/Pendencia/Pendencia');
        $route = empty($rota) ? ($ci->uri->segment(1) . '/' . $ci->uri->segment(2) . '/' . $ci->uri->segment(3) . '/editar/' . $id) : $rota;
        $ci->Pendencia->isPendencia($id, $campo, $table, $route);
    }

}

if (!function_exists('havePendencias')) {

    function havePendencias($id, $campo, $table, $rota = NULL) {
        $ci = & get_instance();
        $ci->load->model('Sistema/Pendencia/Pendencia');
        $route = empty($rota) ? ($ci->uri->segment(1) . '/' . $ci->uri->segment(2) . '/' . $ci->uri->segment(3) . '/editar/' . $id) : $rota;
        $ci->Pendencia->havePendencia($id, $campo, $table, $route);
        //var_dump($ci->db->last_query());
    }

}

if (!function_exists('excluirPendencias')) {

    function excluirPendencias($id, $table) {
        $ci = & get_instance();
        $ci->load->model('Sistema/Pendencia/Pendencia');
        $ci->Pendencia->excluirPendencias($id, $table);
    }

}

if (!function_exists('execInBackground')) {

    function execInBackground($method) {
        if (substr(php_uname(), 0, 7) == "Windows") {
            $cmd = "start /B php.exe index.php Sistemas/Backgrounds/Backgrounds {$method}";
//            die($cmd);
            exec($cmd);
        } else {
            $command = "nohup php index.php Sistemas/Backgrounds/Backgrounds {$method} > /dev/null &";
            exec($command);
        }
    }

}

if (!function_exists('getCiaUsuario')) {

    /**
     * 
     * @param int $id_usuario id do usuário, caso não seja definido ou definido como NULL será atribuido o id_usuario salvo na sessão
     * @param bool $checked Cias checadas pelo usuario
     * @return type
     */
    function getCiaUsuario($id_usuario = NULL, $checked = NULL) {
        $ci = & get_instance();
        $ci->load->model('Adm/Usuario/Usuario');
        $data = $ci->Usuario->buscaCia($id_usuario, $checked);
        return $data;
    }

}

if (!function_exists('dadosCia')) {

    function dadosCia($id_cia = NULL, $rowObject = TRUE, $onlyIdRelacionamento = FALSE) {
        if (empty($id_cia)) {
            $cia = getCiaUsuario(NULL, TRUE);
            if (empty($cia[0]->id_cia)) {
                return NULL;
            }
            $id_cia = $cia[0]->id_cia;
        }
        return getPessoaCodigo($id_cia, 6, 1, $rowObject, $onlyIdRelacionamento);
    }

}

if (!function_exists('getPessoaRelacionamento')) {

    function getPessoaRelacionamento($id_relacionamento, $status = null, $rowObject = FALSE) {
        $ci = & get_instance();
        $ci->load->model('Cadastro/Pessoa/Relacionamento');

        return $dados = $ci->Relacionamento->getPessoaRelacionamento($id_relacionamento, $status, $rowObject);
    }

}

if (!function_exists('getPessoaCodigo')) {

    function getPessoaCodigo($codigo, $tipo = null, $status = null, $rowObject = FALSE, $onlyIdRelacionamento) {
        $ci = & get_instance();
        $ci->load->model('Cadastro/Pessoa/Relacionamento');

        return $dados = $ci->Relacionamento->getPessoaCodigo($codigo, $tipo, $status, $rowObject, $onlyIdRelacionamento);
    }

}

if (!function_exists('getPessoa')) {

    function getPessoa($id, $conta = NULL, $status = null) {
        $ci = & get_instance();
        $ci->load->model('Cadastro/Pessoa/Relacionamento');
        //stop($status);
        return $dados = $ci->Relacionamento->getPessoa($id, $conta, $status);
    }

}

if (!function_exists('managerTask')) {

    function managerTask($nome, $campos, $rota, $id, $campo_id, $tabela, $descricao) {
        $ci = & get_instance();
        $ci->load->model('Sistema/Tarefa/Tarefa');
        $ci->Tarefa->managerTask($nome, $campos, $rota, $id, $campo_id, $tabela, $descricao);
    }

}

if (!function_exists('virgulaPonto')) {

    function virgulaPonto($string) {
        if ($string == '') {
            return NULL;
        }
        return str_replace(",", ".", (str_replace(".", "", (str_replace(" ", "", $string)))));
    }

}

if (!function_exists('aux_nArredonda')) {

//str_replace(" ", "", $string)
    function aux_nArredonda($valor) {
        if (strstr($valor, ",") && strstr($valor, '.')) {
            $aux = str_replace(",", ".", str_replace(".", "", str_replace(" ", "", ($valor))));
        } else if (strstr($valor, ",")) {
            $aux = str_replace(",", ".", str_replace(" ", "", ($valor)));
        } else {
            $aux = str_replace(" ", "", ($valor));
        }

        return $aux;
    }

}

if (!function_exists('nArredonda')) {

    function nArredonda($valor = null, $zero = null, $format = false) {

        $valor = empty($valor) ? 0 : $valor;
        $x = explode('.', aux_nArredonda($valor));
        $x1 = $x[0] . (empty($x[1]) ? '' : '.' . substr($x[1], 0, $zero));
//        stop($x1);
//         $valor = str_replace(',', '', $x1);
        $elev = pow(10, $zero);
        $aux = (float) ($x1 * $elev) / $elev;
        //stop($aux);
        if ($format) {
            return number_format($aux, $zero, ',', '.');
        }
        return $aux;
    }

}

if (!function_exists('validNumAndFormat')) {

    function validNumAndFormat($valor = null, $zero = 0, $format = false) {
        if (strval($valor) == "" || empty($valor)) {
            return NULL;
        }
        return nArredonda($valor, $zero, $format);
    }

}

if (!function_exists('mask')) {

    function mask($val, $mask) {
        $maskared = '';
        $k = 0;
        for ($i = 0; $i <= strlen($mask) - 1; $i++) {
            if ($mask[$i] == '#') {
                if (isset($val[$k]))
                    $maskared .= $val[$k++];
            }
            else {
                if (isset($mask[$i]))
                    $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }

}

if (!function_exists('removeSujeira')) {

    function removeSujeira($string) {
        $string = convert_strtolower($string);
        $string = str_replace("&nbsp;", " ", $string);
        $string = str_replace(["</div>", "</DIV>"], "<br>", $string);
        $string = html_entity_decode($string);
        $string = convert_strtoupper($string);
        return $string;
    }

}

if (!function_exists('sendEmail')) {

    function sendEmail($to, $subject, $body, $from_email = "sistema@asf.com.br", $from_nome = "ERP Andrade Sun Farms") {
        $ci = & get_instance();
        $ci->load->library('email');

        $ci->email->from($from_email, $from_nome);

        $ci->email->to($to);
        $ci->email->bcc('alexis.barboza@asf.com.br,alexandre.barboza@asf.com.br');
        $ci->email->subject($subject);
        $html_email = $ci->load->view("sistemas/email_assinatura.php", ["body" => $body], TRUE);
        $ci->email->message($html_email);
        if ($ci->email->send()) {
            return TRUE;
        }
        return $ci->email->print_debugger();
    }

}
