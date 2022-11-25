<?php

class Sintatico
{
    public $linhas_processadas = [];
    public $pilha = [];
    public $regras_sintatico = [];
    public $characters_atuais = [];
    public $ids = [];

    public function __construct()
    {
        include 'regras_sintatico.php';

        $this->regras_sintatico = $regras_sintatico;
        $chars_lexico =  preg_split('/\r\n|\r|\n/', file_get_contents('lexico.txt'));

        foreach ($chars_lexico as $indice => $linedata) {
            $linha = !empty($linedata) ? explode(' ', $linedata) : '';

            if (is_array($linha)) {
                $num_linha = $linha[0];
                unset($linha[0]);

                $linha = array_values($linha);

                $this->linhas_processadas[$num_linha][] = $linha;
            }
        }

        $this->index();
    }

    public function finaliza($finaliza, $mensagem)
    {
        $_SESSION['retorno'] = $finaliza ? '<p style="color: green; font-size: 1.5rem;">'.$mensagem.'</p>' : '<p style="color: red; font-size: 1.5rem;">'.$mensagem.'</p>';

        return $finaliza;
    }

    public function testaSemantico()
    {
        foreach ($this->linhas_processadas as $linha => $data) {
            foreach ($data as $index => $characters) {
                if (in_array($characters[0], $this->regras_sintatico['MODIF']) and $data[$index+1][0] != 'int') {
                    return $this->finaliza(false, 'Erro semântico na linha '.$linha.', o valor precisa ser inteiro para usar um modificador');
                }

                if (in_array($characters[0], $this->regras_sintatico['TIPOS'])) {
                    if (isset($data[$index+1][0]) and $data[$index+1][0] == 'id' and isset($this->ids[$data[$index+1][1]])) {
                        return $this->finaliza(false, 'Erro semântico na linha '.$linha.', variável já foi declarada anteriormente');
                    } else {
                        $this->ids[$data[$index+1][1]] = ['tipo' => isset($data[$index][0]) ? $data[$index][0] : 'int', 'valor' => isset($data[$index+3][1]) ? $data[$index+3][1]+0 : 0];
                        continue;
                    }
                }

                if (isset($data[$index][0]) and $data[$index][0] == 'id' and isset($this->ids[$data[$index][1]]) and
                $this->ids[$data[$index][1]]['tipo'] == 'int' and !is_int($data[$index+2][1]+0)) {
                    return $this->finaliza(false, 'Erro semântico na linha '.$linha.', tentando atribuir valor de tipo diferente em variável já declarada');
                }

                if (isset($data[$index][0]) and $data[$index][0] == 'id' and isset($this->ids[$data[$index][1]]) and
                $this->ids[$data[$index][1]]['tipo'] == 'float' and !is_float($data[$index+2][1]+0)) {
                    return $this->finaliza(false, 'Erro semântico na linha '.$linha.', tentando atribuir valor de tipo diferente em variável já declarada');
                }

                if ($data[$index][0] == 'id' and !in_array($data[$index-1][0] ?? 666, $this->regras_sintatico['TIPOS'])
                and !isset($this->ids[$data[$index][1]])) {
                    return $this->finaliza(false, 'Erro semântico na linha '.$linha.', realizando operação com variável não declarada');
                }

                //fazer uma que verifique operadores entre dados de tipo diferente
                /* if ($data[$index][0] == 'id' and !in_array($data[$index-1][0] ?? 666, $this->regras_sintatico['TIPOS'])
                and !isset($this->ids[$data[$index][1]])) {
                    return $this->finaliza(false, 'Erro semântico na linha '.$linha.', realizando operação com variável não declarada');
                } */
            }
        }

        return true;
    }

    public function index()
    {
        $linhaerro = -1;

        foreach ($this->linhas_processadas as $linha => $data) {
            if (count($data) > $linhaerro) {
                $linhaerro = $linha;
            }


            foreach ($data as $index => $characters) {
                //pega primeiro caractere, por enquanto desconsidera valores de variaveis e numeros
                $this->characters_atuais['chars'] = $data;
                $this->characters_atuais['index'] = $index;

                $retorno = $this->shift($characters[0]);
            }
            $this->reduce();
        }

        //sintático passou, agora vamos testar o semantico
        if ($this->pilha == [] or (count($this->pilha) == 1 and $this->pilha[0] == 'EXP')) {
            if ($this->testaSemantico()) {
                return $this->finaliza(true, 'Código válido');
            }
        } else {
            return $this->finaliza(false, 'Erro sintático na linha '.$linhaerro.' - '.json_encode($this->pilha));
        }
    }

    public function regraId($terminal)
    {
        /* if ($terminal == 'COMANDO') {
            var_dump($this->pilha);
            exit;
        } */
        if ($terminal == 'EXP' and $this->characters_atuais['chars'][$this->characters_atuais['index']][0] == 'id' and isset($this->characters_atuais['chars'][$this->characters_atuais['index']+1][0])
         and $this->characters_atuais['chars'][$this->characters_atuais['index']+1][0] == '=') {
            return false;
        }

        return true;
    }

    public function validateAtri($terminal)
    {
        if ($terminal == 'ATRI' and $this->pilha[count($this->pilha)-2] != 'TIPOS') {
            return false;
        }

        return true;
    }

    public function reduce($loop = false)
    {
        $flaglast = true;



        foreach ($this->regras_sintatico as $terminal => $regras) {
            for ($i = 0; $i<count($regras); $i++) {
                //regra composta de um ou mais lexemas
                if (is_array($regras[$i])) {
                    //percorre pilha de cima pra baixo e ve se existe regra a partir da direita das regras
                    $match = 0;
                    $size_regra_copy = count($regras[$i])-1;
                    $pilhasize = count($this->pilha)-1;

                    if ($pilhasize > 0 and $this->pilha[$pilhasize] == $regras[$i][$size_regra_copy] and $terminal != 'TIPOS' and $this->validateAtri($regras[$i][$size_regra_copy])) {
                        $flaglast = false;
                    }
                }
            }
        }

        foreach ($this->regras_sintatico as $terminal => $regras) {
            for ($i = count($regras)-1; $i>=0; $i--) {
                if (!is_array($regras[$i])) {
                    $pilhasize = count($this->pilha)-1;

                    if ($pilhasize >= 0 and $regras[$i] == $this->pilha[$pilhasize]  and $flaglast) {
                        if ($this->regraId($terminal)) {
                            $this->pilha[$pilhasize] = $terminal;
                        }
                    }
                }
            }
        }

        foreach ($this->regras_sintatico as $terminal => $regras) {
            for ($i = 0; $i<count($regras); $i++) {
                //regra composta de um ou mais lexemas
                if (is_array($regras[$i])) {
                    //percorre pilha de cima pra baixo e ve se existe regra a partir da direita das regras
                    $match = 0;
                    $size_regra_copy = count($regras[$i])-1;

                    for ($p = count($this->pilha)-1; $p>=0; $p--) {
                        if ($this->pilha[$p] == $regras[$i][$size_regra_copy]) {
                            $match++;
                            if ($size_regra_copy != 0) {
                                $size_regra_copy--;
                            }
                            continue;
                        }

                        break;
                    }

                    //verificar aqui, não tá entrando e reduzindo
                    //encontrou regra composta que pode ser reduzida
                    if (count($regras[$i]) == $match) {
                        //remove da pilha os chars que vao reduzir

                        for ($k = 0; $k<$match; $k++) {
                            array_pop($this->pilha);
                        }

                        //adiciona o terminal que encontrou na redução
                        $this->pilha[] = $terminal;
                    }
                }
            }
        }

        if (!$loop) {
            $this->reduce(true);
        }

        if (count($this->pilha) == 1 and $this->pilha[0] == 'RECUR') {
            $this->pilha = [];
        }

        return true;
    }

    public function shift($char = '')
    {
        if (!empty($char)) {
            $this->pilha[] = $char;
        }

        $retorno = $this->reduce();

        return $retorno;
    }
}

new Sintatico();
