<?php
	#+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	#  SELECT FIELDS FROM TABLE WHERE FIELD CONDITION GROUP BY FIELD1 ORDER BY FIELD2 LIMIT NUM1,NUM2;   
	#  INSERT INTO TABLE (FIELDS) VALUES(VALUES); 
	#  UPDATE TABLE SET FIELD = VALUE WHERE  FIELD CONDITION
	#  DELETE FROM TABLE WHERE FIELD CONDITION 		
	#+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/**
	* @param $data    arr            $data['field'] = array('gt',456);
	* @param $or      arr            $or=array(field1,field2); 
	* @param $group   arr/str        $group = array(field1,field2); $group = 'field1,field2';
	* @param $order   arr/str        $order = array(field1,field2); $order = 'field1,field2';
	* @param $type    int            $type=1;=>'DESC'
	* @param $limit   str            $limit = '0,15';
	*/
	#$row = $obj->where($data,$or)->group($group)->order($order,$type)->limit('0,10')->select();
	#$or = array(fields);
class XM{
	private $table = '';
	private $fields= '';
	private $where = 1;
	/**
	* @author xsn
	* @brief 
	*/
	public function __construct($name){
		$this->table = $name;
		$this->get_table_fields();
	}
	
	public function get_table_fields(){
		$fields_msg = mysql_query("SHOW FULL COLUMNS FROM ".$this->table);
		while($row = mysql_fetch_assoc(%fields_msg)){
			$this->fields[] = array(
						'fields_name'=>$row['Field'],		
						'type'       =>$row['Type'],
						'default'    =>$row['Default'],
						'collation'  =>$row['Collation'],
			);
		}
		return $this->fields;
	}
	
	public function select(){
		$sql = "SELECT ".$this->fields." FROM ".$this->$table." WHERE ".$this->where.$this->group.$this->order.$this->limit;
		$res = mysql_query($sql);
		while($row = mysql_fetch_assoc($res))
		{
			$data[]=$row;
		}
		return $data;
	}
	
	
	public function add($data)
	{
		$fields_str = '';
		$value_str  = '';
		foreach($this->fields as $k => $v)
		{
			$fields_str .= "`".$v['fields_name']."` ,";
			$value_str  .= "'".($data[$v['fields_name']] ? $data[$v['fields_name']] : '')."' ,";  
		}
		$sql = "INSERT INTO   ";
		$sql.= $this->table;
		$sql.= " ( ";
		$sql.= trim($fields_str,',');
		$sql.= " ) VALUES(";
		$sql.= trim($value_str,',');
		$sql.= " ) ";
		if(mysql_query( $sql ))
		{
			return mysql_insert_id();
		}else
		{
			return $sql;
		}
		
	}
	
	public function edit($data)
	{
		$data_str = '';
		foreach($data as $k=>$v)
		{
			$data_str .= '`'.$k."` = '".$v."' ,";
		}
		$sql = "update ".$this->table." set ".trim($data_str,",")." where".$this->where;
		if($result = mysql_query($sql)or die(mysql_error()))
		{
			return mysql_affected_rows();
		}else{
			return $sql;
		}
	}
	
	public function del()
	{
		$sql = "DELETE FROM ".$this->table." WHERE ".$this->where;
		if($result = mysql_query($sql)or die(mysql_error()))
		{
			return mysql_affected_rows();
		}else{
			return $sql;
		}
	}
	
	public function getSeleteSql(){
		$sql = "SELECT ".$this->fields." FROM ".$this->$table." WHERE ".$this->where.$this->group.$this->order.$this->limit;
		echo $sql;
	}
	
	public function getUpdateSql($data){
		$data_str = '';
		foreach($data as $k=>$v)
		{
			$data_str .= '`'.$k."` = '".$v."' ,";
		}
		$sql = "update ".$this->table." set ".trim($data_str,",")." where".$this->where;
		echo $sql;
	}
	
	public function getDeleteSql(){
		$sql = "DELETE FROM ".$this->table." WHERE ".$this->where;
		echo $sql;
	}
	
	public function getInsertSql($data){
		$fields_str = '';
		$value_str  = '';
		foreach($this->fields as $k => $v)
		{
			$fields_str .= "`".$v['fields_name']."` ,";
			$value_str  .= "'".($data[$v['fields_name']] ? $data[$v['fields_name']] : '')."' ,";  
		}
		$sql = "INSERT INTO   ";
		$sql.= $this->table;
		$sql.= " ( ";
		$sql.= trim($fields_str,',');
		$sql.= " ) VALUES(";
		$sql.= trim($value_str,',');
		$sql.= " ) ";
		echo $sql;
	}
	
	public function field($data='*'){
		$this->fields = $data;
	}
	
	public function where($where = 1,$or = array();){
		if(is_array($where) && !empty($where)){
			foreach($where as $k=>$v){
				$where_type = in_array($k,$or)?' OR ':' AND ';
				$vsg = switch_func($v);
				$where .= $where_type.$k.$vsg;
			}
			$where = trim($where,' OR');
			$this->where = trim($wherw,' AND'); 	
		}else{
			$this->where = $where;
		}
	}
	
	public function group($data=''){
		if(is_array($data) && !empty($date)){
			foreach($data as $k=>$v){
				fields .= ','.$v;
			}
			$this->group = ' GROUP BY '.trim(fielfs,',');
		}else{
			$this->group = ' GROUP BY '.$date;
		}
	}
	
	public function order($data='',$type=0){
		$o = $type ? ' DESC ' : '';
		if(is_array($data) && !empty($date)){
			foreach($data as $k=>$v){
				fields .= ','.$v;
			}
			$this->order = ' ORDER BY '.trim(fielfs,',').$o;
		}else{
			$this->order = ' ORDER BY '.$date.$o;
		}
	}
	
	public function limit($data){
		$this->limit = ' LIMIT '.$data;
	}
	

	public function switch_func($v){
		switch(strtolower($v[0])){
			case 'eq':
				$vsg = '='.$v[1];
				break;
			case 'neq':
				$vsg = '!='.$v[1];
				break;
			case 'gt':
				$vsg = '>'.$v[1];
				break;
			case 'egt':
				$vsg = '>='.$v[1];
				break;
			case 'lt':
				$vsg = '<'.$v[1];
				break;
			case 'elt':
				$vsg = '<='.$v[1];
				break;
			case 'like':
				$vsg = ' like "%'.$v[1].'%"';
				break;
			case 'between':
				$vsg = ' between '.$v[1].' and '.$v[2];
				break;
			case 'in':
				$vsg = ' in('.$v[1].') ';
				break;
		}	
		return $vsg;
	}

}
