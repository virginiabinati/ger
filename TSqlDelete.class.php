<?php
/*
 * classe TSqlDelete
 *  Esta classe prov� meios
 *  para manipula��o de uma instru��o
 *  de DELETE no banco de dados
 */
final class TSqlDelete extends TSqlInstruction
{
    /*
     * m�todo getInstruction()
     *  retorna a instru��o de DELETE
     *  em forma de string.
     */
    public function getInstruction()
    {
        // monta a string de DELETE
        $this->sql  = "DELETE FROM {$this->entity}";
        
        // retorna a cl�usula WHERE do objeto $this->criteria
        if ($this->criteria)
        {
            $expression = $this->criteria->dump();
            if ($expression)
            {
                $this->sql .= ' WHERE ' . $expression;
            }
        }
        return $this->sql;
    }
}
?>