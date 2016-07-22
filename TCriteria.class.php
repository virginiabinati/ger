<?php
/*
 * classe TCriteria
 *  Esta classe prov� uma interface
 *  utilizada para defini��o de crit�rios
 */
class TCriteria extends TExpression
{
    private $expressions;  // armazena a lista de express�es
    private $operators;    // armazena a lista de operadores
    private $properties;   // propriedades do crit�rio
    
    /*
     * m�todo add()
     *  adiciona uma express�o ao crit�rio
     * @param  $expression = express�o (objeto TExpression)
     * @param  $operator   = operador l�gico de compara��o
     */
    public function add(TExpression $expression, $operator = self::AND_OPERATOR)
    {
        // na primeira vez, n�o precisamos de operador l�gico para concatenar
        if (empty($this->expressions))
        {
            unset($operator);
        }
        
        // agrega o resultado da express�o � lista de express�es
        $this->expressions[] = $expression;
        $this->operators[]   = $operator;
    }
    
    /*
     * m�todo dump()
     *  retorna a express�o final
     */
    public function dump()
    {
        // concatena a lista de express�es
        if (is_array($this->expressions))
        {
            foreach ($this->expressions as $i=> $expression)
            {
                $operator = $this->operators[$i];
                // concatena o operador com a respectiva express�o
                $result .=  $operator. $expression->dump() . ' ';
            }
            $result = trim($result);
            return "({$result})";
        }
    }
    
    /*
     * m�todo setProperty()
     *  define o valor de uma propriedade
     * @param  $property = propriedade
     * @param  $value    = valor
     */
    public function setProperty($property, $value)
    {
        $this->properties[$property] = $value;
    }
    
    /*
     * m�todo getProperty()
     *  retorna o valor de uma propriedade
     * @param  $property = propriedade
     */
    public function getProperty($property)
    {
        return $this->properties[$property];
    }
}
?>