<?php
/*
 * classe TCriteria
 *  Esta classe provъ uma interface
 *  utilizada para definiчуo de critщrios
 */
class TCriteria extends TExpression
{
    private $expressions;  // armazena a lista de expressѕes
    private $operators;    // armazena a lista de operadores
    private $properties;   // propriedades do critщrio
    
    /*
     * mщtodo add()
     *  adiciona uma expressуo ao critщrio
     * @param  $expression = expressуo (objeto TExpression)
     * @param  $operator   = operador lѓgico de comparaчуo
     */
    public function add(TExpression $expression, $operator = self::AND_OPERATOR)
    {
        // na primeira vez, nуo precisamos de operador lѓgico para concatenar
        if (empty($this->expressions))
        {
            unset($operator);
        }
        
        // agrega o resultado da expressуo р lista de expressѕes
        $this->expressions[] = $expression;
        $this->operators[]   = $operator;
    }
    
    /*
     * mщtodo dump()
     *  retorna a expressуo final
     */
    public function dump()
    {
        // concatena a lista de expressѕes
        if (is_array($this->expressions))
        {
            foreach ($this->expressions as $i=> $expression)
            {
                $operator = $this->operators[$i];
                // concatena o operador com a respectiva expressуo
                $result .=  $operator. $expression->dump() . ' ';
            }
            $result = trim($result);
            return "({$result})";
        }
    }
    
    /*
     * mщtodo setProperty()
     *  define o valor de uma propriedade
     * @param  $property = propriedade
     * @param  $value    = valor
     */
    public function setProperty($property, $value)
    {
        $this->properties[$property] = $value;
    }
    
    /*
     * mщtodo getProperty()
     *  retorna o valor de uma propriedade
     * @param  $property = propriedade
     */
    public function getProperty($property)
    {
        return $this->properties[$property];
    }
}
?>