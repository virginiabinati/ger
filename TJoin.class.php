<?php
/*
 * classe TJoin
 * Esta classe abstrata
 * para gerencias Joins 
 * 
 */
 class TJoin
{
	// TIPO DE JOIN
    const INNER_OPERATOR 	= 'INNER ';
    const LEFT_OPERATOR  	= 'LEFT ';
    const RIGHT_OPERATOR 	= 'RIGHT ';
    
	private $types; //lista de INNER, LEFT, RIGHT
	private $leftEntities; //lista de tabelas que vem a esquerda
	private $rightEntities; // lista de tabelas que vem a direita
	private $filters; // lista de filtros ON nesse relacionamento
	
	/* método __construct()
     *  chama o metodo initAdd()
     *  
     *  @param [$id] = ID do objeto
     */
    public function __construct($leftEntity, $rightEntity, $filter, $type = self::INNER_OPERATOR)
    {
        if ($leftEntity) // se o ID for informado
        {
        	$this->initAdd($leftEntity, $rightEntity, $filter, $type);
        }
    }
	
	/*
	 * método initAdd()
	 * adiciona um join completo, sendo este o primeiro a ser adicionado
	 * @param $leftEntity  = nome da entidade a esquesda;
	 * @param $rightEntity = nome da entidade a direita;
	 * @param $filter	   = objeto Tfilter
	 * @param $type		   = tipo de join
	 */
	private function initAdd($leftEntity, $rightEntity, $filter, $type = self::INNER_OPERATOR)
	{
		$this->leftEntities[] 	= $leftEntity;
		$this->rightEntities[] 	= $rightEntity;
		$this->filters[] 		= $filter;
		$this->types[] 			= $type;
	}
	/*
	 * método add()
	 * adiciona um join
	 * @param $leftEntity  = nome da entidade a esquesda;
	 * @param $rightEntity = nome da entidade a direita;
	 * @param $filter	   = objeto Tfilter
	 * @param $type		   = tipo de join
	 */
	public function add($rightEntity, $filter, $type = self::INNER_OPERATOR)
	{
		//$this->leftEntities[] 	= $leftEntity;
		$this->rightEntities[] 	= $rightEntity;
		$this->filters[] 		= $filter;
		$this->types[] 			= $type;
	}
	
	public function dump()
	{
		// concatena a lista de expressões
        if (is_array($this->leftEntities)&&is_array($this->rightEntities))
        {
            foreach ($this->leftEntities as $i=> $leftEntity)
            {
                $type = $this->types[$i];
                $filter = $this->filters[$i];
                // concatena as entidades, tipo e filtro ON
                if($result)
	                $result .=  $type. 'JOIN ' .
	                			$this->rightEntities[$i] . ' ON ' . 
	            				$filter->dump() . ' '; 
	            else
	            	$result = 	$this->leftEntities[$i] . ' ' . 
	            				$type . ' JOIN ' . 
	            				$this->rightEntities[$i] . ' ON ' . 
	            				$filter->dump() . ' '; 
            }
            $result = trim($result);
            return "({$result})";
        }
	}
}
?>