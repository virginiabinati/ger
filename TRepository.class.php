<?php
/*
 * classe TRepository
 *  Esta classe provê os métodos
 *  necessários para manipular coleções de objetos.
 */
final class TRepository
{
    private $class; // nome da classe manipulada pelo repositório
    
    /* método __construct()
     *  instancia um Repositório de objetos
     *  @param $class = Classe dos Objetos
     */
    function __construct($class)
    {
        $this->class = $class;
    }
    
    /*
     * método load()
     *  Recuperar um conjunto de objetos (collection) da base de dados
     *  através de um critério  de seleção, e instanciá-los em memória
     *  @param $criteria = objeto do tipo TCriteria
     */
    function load(TCriteria $criteria, array $columns = NULL)
    {
        // instancia a instrução de SELECT
        $sql = new TSqlSelect;
        if($columns)
        	$sql->addColumns($columns);
        else
        	$sql->addColumn('*');
        $sql->setEntity($this->class);
        // atribui o critério passado como parâmetro
        $sql->setCriteria($criteria);
        
        // inicia transação
        if ($conn = TTransaction::get())
        {
			
            // registra mensagem de log
            TTransaction::log($sql->getInstruction());
          foreach ($conn->Query($sql->getInstruction()) as $row) {
    	  $results[]=$row;
 		  }
            
            	          
            return $results;
        }
        else
        {
            // se não tiver transação, retorna uma exceção
            throw new Exception('Não há transação ativa !!');
        }
    }
    /*
     * método delete()
     *  Excluir um conjunto de objetos (collection) da base de dados
     *  através de um critério de seleção.
     *  @param $criteria = objeto do tipo TCriteria
     */
    function delete(TCriteria $criteria)
    {
        // instancia instrução de DELETE
        $sql = new TSqlDelete;
        $sql->setEntity($this->class);
        // atribui o critério passado como parâmetro
        $sql->setCriteria($criteria);
        
        // inicia transação
        if ($conn = TTransaction::get())
        {
            // registra mensagem de log
            TTransaction::log($sql->getInstruction());
            // executa instrução de DELETE
            $result = $conn->exec($sql->getInstruction());
            return $result;
        }
        else
        {
            // se não tiver transação, retorna uma exceção
            throw new Exception('Não há transação ativa !!');
        }
    }
    
    /*
     * método count()
     *  Retorna a quantidade de objetos da base de dados
     *  que satisfazem um determinado critério de seleção.
     *  @param $criteria = objeto do tipo TCriteria
     */
    function count(TCriteria $criteria)
    {
        // instancia instrução de SELECT
        $sql = new TSqlSelect;
        $sql->addColumn('count(*)');
        $sql->setEntity($this->class);
        // atribui o critério passado como parâmetro
        $sql->setCriteria($criteria);
        
        // inicia transação
        if ($conn = TTransaction::get())
        {
            // registra mensagem de log
            TTransaction::log($sql->getInstruction());
            // executa instrução de SELECT
            $result= $conn->Query($sql->getInstruction());
            if ($result)
            {
                $row = $result->fetch();
            }
            // retorna o resultado
            return $row[0];
        }
        else
        {
            // se não tiver transação, retorna uma exceção
            throw new Exception('Não há transação ativa !!');
        }
    }
}
?>