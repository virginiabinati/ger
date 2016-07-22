<?php
/*
 * classe TFilter
 *  Esta classe prov� uma interface
 *  para defini��o de filtros de sele��o
 */
class TFilter extends TExpression
{
    private $variable;  // vari�vel
    private $operator;  // operador
    private $value;     // valor
    
    /*
     * m�todo __construct()
     *  instancia um novo filtro
     * @param  $variable = vari�vel
     * @param  $operator = operador (>,<)
     * @param  $value    = valor a ser comparado
     */
    public function __construct($variable, $operator, $value)
    {
        // armazena as propriedades
        $this->variable = $variable;
        $this->operator = $operator;
        // transforma o valor de acordo com certas regras
        // antes de atribuir � propriedade $this->value
        $this->value    = $this->transform($value);
    }
    
    /*
     * m�todo transform()
     *  recebe um valor e faz as modifica��es necess�rias
     *  para ele ser interpretado pelo banco de dados
     *  podendo ser um integer/string/boolean ou array.
     * @param $value = valor a ser transformado
     */
    private function transform($value)
    {
        // caso seja um array
        if (is_array($value))
        {
            // percorre os valores
            foreach ($value as $x)
            {
                // se for um inteiro
                if (is_integer($x))
                {
                    $foo[]= $x;
                }
                else if (is_string($x))
                {
                    // se for string, adiciona aspas
                    $foo[]= "'$x'";
                }
            }
            // converte o array em string separada por ","
            $result = '(' . implode(',', $foo) . ')';
        }
        // caso seja uma string
        else if (is_string($value))
        {
            // adiciona aspas
            $result = "'$value'";
        }
        // caso seja valor nullo
        else if (is_null($value))
        {
            // armazena NULL
            $result = 'NULL';
        }
        // caso seja booleano
        else if (is_bool($value))
        {
            // armazena NULL
            $result = $value ? 'TRUE' : 'FALSE';
        }
        else
        {
            $result = $value;
        }
        // retorna o valor
        return $result;
    }

    /*
     * m�todo dump()
     *  retorna o filtro em forma de express�o
     */
    public function dump()
    {
        // concatena a express�o
        return "{$this->variable} {$this->operator} {$this->value}";
    }
}
?>