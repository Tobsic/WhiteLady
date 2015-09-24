<?php
include("settings/conf.php");
class MySQL_CRUD_API extends REST_CRUD_API {

	protected $queries = array(
		'reflect_table'=>'SELECT "TABLE_NAME" FROM "INFORMATION_SCHEMA"."TABLES" WHERE "TABLE_NAME" LIKE ? AND "TABLE_SCHEMA" = ?',
		'reflect_pk'=>'SELECT "COLUMN_NAME" FROM "INFORMATION_SCHEMA"."COLUMNS" WHERE "COLUMN_KEY" = \'PRI\' AND "TABLE_NAME" = ? AND "TABLE_SCHEMA" = ?',
		'reflect_belongs_to'=>'SELECT
				"TABLE_NAME","COLUMN_NAME",
				"REFERENCED_TABLE_NAME","REFERENCED_COLUMN_NAME"
			FROM
				"INFORMATION_SCHEMA"."KEY_COLUMN_USAGE"
			WHERE
				"TABLE_NAME" = ? AND
				"REFERENCED_TABLE_NAME" IN ? AND
				"TABLE_SCHEMA" = ? AND
				"REFERENCED_TABLE_SCHEMA" = ?',
		'reflect_has_many'=>'SELECT
				"TABLE_NAME","COLUMN_NAME",
				"REFERENCED_TABLE_NAME","REFERENCED_COLUMN_NAME"
			FROM
				"INFORMATION_SCHEMA"."KEY_COLUMN_USAGE"
			WHERE
				"TABLE_NAME" IN ? AND
				"REFERENCED_TABLE_NAME" = ? AND
				"TABLE_SCHEMA" = ? AND
				"REFERENCED_TABLE_SCHEMA" = ?',
		'reflect_habtm'=>'SELECT
				k1."TABLE_NAME", k1."COLUMN_NAME",
				k1."REFERENCED_TABLE_NAME", k1."REFERENCED_COLUMN_NAME",
				k2."TABLE_NAME", k2."COLUMN_NAME",
				k2."REFERENCED_TABLE_NAME", k2."REFERENCED_COLUMN_NAME"
			FROM
				"INFORMATION_SCHEMA"."KEY_COLUMN_USAGE" k1, "INFORMATION_SCHEMA"."KEY_COLUMN_USAGE" k2
			WHERE
				k1."TABLE_SCHEMA" = ? AND
				k2."TABLE_SCHEMA" = ? AND
				k1."REFERENCED_TABLE_SCHEMA" = ? AND
				k2."REFERENCED_TABLE_SCHEMA" = ? AND
				k1."TABLE_NAME" = k2."TABLE_NAME" AND
				k1."REFERENCED_TABLE_NAME" = ? AND
				k2."REFERENCED_TABLE_NAME" IN ?'
	);

	protected function connectDatabase($hostname,$username,$password,$database,$port,$socket,$charset) {
		$db = mysqli_connect($hostname,$username,$password,$database,$port,$socket);
		if (mysqli_connect_errno()) {
			throw new \Exception('Connect failed. '.mysqli_connect_error());
		}
		if (!mysqli_set_charset($db,$charset)) {
			throw new \Exception('Error setting charset. '.mysqli_error($db));
		}
		if (!mysqli_query($db,'SET SESSION sql_mode = \'ANSI_QUOTES\';')) {
			throw new \Exception('Error setting ANSI quotes. '.mysqli_error($db));
		}
		return $db;
	}

	protected function query($db,$sql,$params) {
		$sql = preg_replace_callback('/\!|\?/', function ($matches) use (&$db,&$params) {
			$param = array_shift($params);
			if ($matches[0]=='!') return preg_replace('/[^a-zA-Z0-9\-_=<>]/','',$param);
			if (is_array($param)) return '('.implode(',',array_map(function($v) use (&$db) {
				return "'".mysqli_real_escape_string($db,$v)."'";
			},$param)).')';
			if (is_object($param) && $param->type=='base64') {
				return "x'".bin2hex(base64_decode($param->data))."'";
			}
			if ($param===null) return 'NULL';
			return "'".mysqli_real_escape_string($db,$param)."'";
		}, $sql);
		//echo "\n$sql\n";
		return mysqli_query($db,$sql);
	}

	protected function fetch_assoc($result) {
		return mysqli_fetch_assoc($result);
	}

	protected function fetch_row($result) {
		return mysqli_fetch_row($result);
	}

	protected function insert_id($db,$result) {
		return mysqli_insert_id($db);
	}

	protected function affected_rows($db,$result) {
		return mysqli_affected_rows($db);
	}

	protected function close($result) {
		return mysqli_free_result($result);
	}

	protected function fetch_fields($result) {
		return mysqli_fetch_fields($result);
	}

	protected function add_limit_to_sql($sql,$limit,$offset) {
		return "$sql LIMIT $limit OFFSET $offset";
	}

	protected function likeEscape($string) {
		return addcslashes($string,'%_');
	}

	protected function is_binary_type($field) {
		//echo "$field->name: $field->type ($field->flags)\n";
		return (($field->flags & 128) && ($field->type==252));
	}

}

class PgSQL_CRUD_API extends REST_CRUD_API {

	protected $queries = array(
		'reflect_table'=>'SELECT "TABLE_NAME" FROM "INFORMATION_SCHEMA"."TABLES" WHERE "TABLE_NAME" LIKE ? AND "TABLE_SCHEMA" = ?',
		'reflect_pk'=>'SELECT "COLUMN_NAME" FROM "INFORMATION_SCHEMA"."COLUMNS" WHERE "COLUMN_KEY" = \'PRI\' AND "TABLE_NAME" = ? AND "TABLE_SCHEMA" = ?',
		'reflect_belongs_to'=>'SELECT
				"TABLE_NAME","COLUMN_NAME",
				"REFERENCED_TABLE_NAME","REFERENCED_COLUMN_NAME"
			FROM
				"INFORMATION_SCHEMA"."KEY_COLUMN_USAGE"
			WHERE
				"TABLE_NAME" = ? AND
				"REFERENCED_TABLE_NAME" IN ? AND
				"TABLE_SCHEMA" = ? AND
				"REFERENCED_TABLE_SCHEMA" = ?',
		'reflect_has_many'=>'SELECT
				"TABLE_NAME","COLUMN_NAME",
				"REFERENCED_TABLE_NAME","REFERENCED_COLUMN_NAME"
			FROM
				"INFORMATION_SCHEMA"."KEY_COLUMN_USAGE"
			WHERE
				"TABLE_NAME" IN ? AND
				"REFERENCED_TABLE_NAME" = ? AND
				"TABLE_SCHEMA" = ? AND
				"REFERENCED_TABLE_SCHEMA" = ?',
		'reflect_habtm'=>'SELECT
				k1."TABLE_NAME", k1."COLUMN_NAME",
				k1."REFERENCED_TABLE_NAME", k1."REFERENCED_COLUMN_NAME",
				k2."TABLE_NAME", k2."COLUMN_NAME",
				k2."REFERENCED_TABLE_NAME", k2."REFERENCED_COLUMN_NAME"
			FROM
				"INFORMATION_SCHEMA"."KEY_COLUMN_USAGE" k1, "INFORMATION_SCHEMA"."KEY_COLUMN_USAGE" k2
			WHERE
				k1."TABLE_SCHEMA" = ? AND
				k2."TABLE_SCHEMA" = ? AND
				k1."REFERENCED_TABLE_SCHEMA" = ? AND
				k2."REFERENCED_TABLE_SCHEMA" = ? AND
				k1."TABLE_NAME" = k2."TABLE_NAME" AND
				k1."REFERENCED_TABLE_NAME" = ? AND
				k2."REFERENCED_TABLE_NAME" IN ?'
	);

	protected function connectDatabase($hostname,$username,$password,$database,$port,$socket,$charset) {
		$e = function ($v) { return str_replace(array('\'','\\'),array('\\\'','\\\\'),$v); };
		$conn_string = "host='".$e($hostname)."' port=5432 dbname=test user=lamb password=bar options='--client_encoding=UTF8'";
		$db = pg_connect($conn_string);



		return $db;
	}

	protected function query($db,$sql,$params) {
		$sql = preg_replace_callback('/\!|\?/', function ($matches) use (&$db,&$params) {
			$param = array_shift($params);
			if ($matches[0]=='!') return preg_replace('/[^a-zA-Z0-9\-_=<>]/','',$param);
			if (is_array($param)) return '('.implode(',',array_map(function($v) use (&$db) {
				return "'".mysqli_real_escape_string($db,$v)."'";
			},$param)).')';
			if (is_object($param) && $param->type=='base64') {
				return "x'".bin2hex(base64_decode($param->data))."'";
			}
			if ($param===null) return 'NULL';
			return "'".mysqli_real_escape_string($db,$param)."'";
		}, $sql);
		//echo "\n$sql\n";
		return mysqli_query($db,$sql);
	}

	protected function fetch_assoc($result) {
		return mysqli_fetch_assoc($result);
	}

	protected function fetch_row($result) {
		return mysqli_fetch_row($result);
	}

	protected function insert_id($db,$result) {
		return mysqli_insert_id($db);
	}

	protected function affected_rows($db,$result) {
		return mysqli_affected_rows($db);
	}

	protected function close($result) {
		return mysqli_free_result($result);
	}

	protected function fetch_fields($result) {
		return mysqli_fetch_fields($result);
	}

	protected function add_limit_to_sql($sql,$limit,$offset) {
		return "$sql LIMIT $limit OFFSET $offset";
	}

	protected function likeEscape($string) {
		return addcslashes($string,'%_');
	}

	protected function is_binary_type($field) {
		//echo "$field->name: $field->type ($field->flags)\n";
		return (($field->flags & 128) && ($field->type==252));
	}

}

class MsSQL_CRUD_API extends REST_CRUD_API {

	protected $queries = array(
		'reflect_table'=>'SELECT "TABLE_NAME" FROM "INFORMATION_SCHEMA"."TABLES" WHERE "TABLE_NAME" LIKE ? AND "TABLE_CATALOG" = ?',
		'reflect_pk'=>'SELECT
				"COLUMN_NAME"
			FROM
				"INFORMATION_SCHEMA"."TABLE_CONSTRAINTS" tc, "INFORMATION_SCHEMA"."KEY_COLUMN_USAGE" ku
			WHERE
				tc."CONSTRAINT_TYPE" = \'PRIMARY KEY\' AND
				tc."CONSTRAINT_NAME" = ku."CONSTRAINT_NAME" AND
				ku."TABLE_NAME" = ? AND
				ku."TABLE_CATALOG" = ?',
		'reflect_belongs_to'=>'SELECT
				cu1."TABLE_NAME",cu1."COLUMN_NAME",
				cu2."TABLE_NAME",cu2."COLUMN_NAME"
			FROM
				"INFORMATION_SCHEMA".REFERENTIAL_CONSTRAINTS rc,
				"INFORMATION_SCHEMA".CONSTRAINT_COLUMN_USAGE cu1,
				"INFORMATION_SCHEMA".CONSTRAINT_COLUMN_USAGE cu2
			WHERE
				cu1."CONSTRAINT_NAME" = rc."CONSTRAINT_NAME" AND
				cu2."CONSTRAINT_NAME" = rc."UNIQUE_CONSTRAINT_NAME" AND
				cu1."TABLE_NAME" = ? AND
				cu2."TABLE_NAME" IN ? AND
				cu1."TABLE_CATALOG" = ? AND
				cu2."TABLE_CATALOG" = ?',
		'reflect_has_many'=>'SELECT
				cu1."TABLE_NAME",cu1."COLUMN_NAME",
				cu2."TABLE_NAME",cu2."COLUMN_NAME"
			FROM
				"INFORMATION_SCHEMA".REFERENTIAL_CONSTRAINTS rc,
				"INFORMATION_SCHEMA".CONSTRAINT_COLUMN_USAGE cu1,
				"INFORMATION_SCHEMA".CONSTRAINT_COLUMN_USAGE cu2
			WHERE
				cu1."CONSTRAINT_NAME" = rc."CONSTRAINT_NAME" AND
				cu2."CONSTRAINT_NAME" = rc."UNIQUE_CONSTRAINT_NAME" AND
				cu1."TABLE_NAME" IN ? AND
				cu2."TABLE_NAME" = ? AND
				cu1."TABLE_CATALOG" = ? AND
				cu2."TABLE_CATALOG" = ?',
		'reflect_habtm'=>'SELECT
				cua1."TABLE_NAME",cua1."COLUMN_NAME",
				cua2."TABLE_NAME",cua2."COLUMN_NAME",
				cub1."TABLE_NAME",cub1."COLUMN_NAME",
				cub2."TABLE_NAME",cub2."COLUMN_NAME"
			FROM
				"INFORMATION_SCHEMA".REFERENTIAL_CONSTRAINTS rca,
				"INFORMATION_SCHEMA".REFERENTIAL_CONSTRAINTS rcb,
				"INFORMATION_SCHEMA".CONSTRAINT_COLUMN_USAGE cua1,
				"INFORMATION_SCHEMA".CONSTRAINT_COLUMN_USAGE cua2,
				"INFORMATION_SCHEMA".CONSTRAINT_COLUMN_USAGE cub1,
				"INFORMATION_SCHEMA".CONSTRAINT_COLUMN_USAGE cub2
			WHERE
				cua1."CONSTRAINT_NAME" = rca."CONSTRAINT_NAME" AND
				cua2."CONSTRAINT_NAME" = rca."UNIQUE_CONSTRAINT_NAME" AND
				cub1."CONSTRAINT_NAME" = rcb."CONSTRAINT_NAME" AND
				cub2."CONSTRAINT_NAME" = rcb."UNIQUE_CONSTRAINT_NAME" AND
				cua1."TABLE_CATALOG" = ? AND
				cub1."TABLE_CATALOG" = ? AND
				cua2."TABLE_CATALOG" = ? AND
				cub2."TABLE_CATALOG" = ? AND
				cua1."TABLE_NAME" = cub1."TABLE_NAME" AND
				cua2."TABLE_NAME" = ? AND
				cub2."TABLE_NAME" IN ?'
	);

	protected function connectDatabase($hostname,$username,$password,$database,$port,$socket,$charset) {
		$connectionInfo = array();
		if ($port) $hostname.=','.$port;
		if ($username) $connectionInfo['UID']=$username;
		if ($password) $connectionInfo['PWD']=$password;
		if ($database) $connectionInfo['Database']=$database;
		if ($charset) $connectionInfo['CharacterSet']=$charset;
		$connectionInfo['QuotedId']=1;
		$connectionInfo['ReturnDatesAsStrings']=1;

		$db = sqlsrv_connect($hostname, $connectionInfo);
		if (!$db) {
			throw new \Exception('Connect failed. '.print_r( sqlsrv_errors(), true));
		}
		if ($socket) {
			throw new \Exception('Socket connection is not supported.');
		}
		return $db;
	}

	protected function query($db,$sql,$params) {
		$args = array();
		$sql = preg_replace_callback('/\!|\?/', function ($matches) use (&$db,&$params,&$args) {
			static $i=-1;
			$i++;
			$param = $params[$i];
			if ($matches[0]=='!') {
				return preg_replace('/[^a-zA-Z0-9\-_=<>]/','',$param);
			}
			// This is workaround because SQLSRV cannot accept NULL in a param
			if ($matches[0]=='?' && is_null($param)) {
				return 'NULL';
			}
			if (is_array($param)) {
				$args = array_merge($args,$param);
				return '('.implode(',',str_split(str_repeat('?',count($param)))).')';
			}
			if (is_object($param)) {
				switch($param->type) {
					case 'base64':
						$args[] = bin2hex(base64_decode($param->data));
						return 'CONVERT(VARBINARY(MAX),?,2)';
				}
			}
			$args[] = $param;
			return '?';
		}, $sql);
		//var_dump($params);
		//echo "\n$sql\n";
		//var_dump($args);
		if (strtoupper(substr($sql,0,6))=='INSERT') {
			$sql .= ';SELECT SCOPE_IDENTITY()';
		}
		$result = sqlsrv_query($db,$sql,$args);
		if ($result===false) {
			$errors = sqlsrv_errors();
			$this->exitWith422(compact('sql','args','errors'));
		}
		return $result;
	}

	protected function fetch_assoc($result) {
		$values = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
		if ($values) $values = array_map(function($v){ return is_null($v)?null:(string)$v; },$values);
		return $values;
	}

	protected function fetch_row($result) {
		$values = sqlsrv_fetch_array($result, SQLSRV_FETCH_NUMERIC);
		if ($values) $values = array_map(function($v){ return is_null($v)?null:(string)$v; },$values);
		return $values;
	}

	protected function insert_id($db,$result) {
		sqlsrv_next_result($result);
		sqlsrv_fetch($result);
		return (int)sqlsrv_get_field($result, 0);
	}

	protected function affected_rows($db,$result) {
		return sqlsrv_rows_affected($result);
	}

	protected function close($result) {
		return sqlsrv_free_stmt($result);
	}

	protected function fetch_fields($result) {
		//var_dump(sqlsrv_field_metadata($result));
		return array_map(function($a){
			$p = array();
			foreach ($a as $k=>$v) {
				$p[strtolower($k)] = $v;
			}
			return (object)$p;
		},sqlsrv_field_metadata($result));
	}

	protected function add_limit_to_sql($sql,$limit,$offset) {
		return "$sql OFFSET $offset ROWS FETCH NEXT $limit ROWS ONLY";
	}

	protected function likeEscape($string) {
		return str_replace(array('%','_'),array('[%]','[_]'),$string);
	}

	protected function is_binary_type($field) {
		return ($field->type>=-4 && $field->type<=-2);
	}

}

class REST_CRUD_API {

	protected $config;

	protected function mapMethodToAction($method,$key) {
		switch ($method) {
			case 'GET': return $key?'read':'list';
			case 'PUT': return 'update';
			case 'POST': return 'create';
			case 'DELETE': return 'delete';
			default: $this->exitWith404('method');
		}
	}

	protected function parseRequestParameter(&$request,$characters,$default) {
		if (!count($request)) return $default;
		$value = array_shift($request);
		return $characters?preg_replace("/[^$characters]/",'',$value):$value;
	}

	protected function parseGetParameter($get,$name,$characters,$default) {
		$value = isset($get[$name])?$get[$name]:$default;
		return $characters?preg_replace("/[^$characters]/",'',$value):$value;
	}

	protected function parseGetParameterArray($get,$name,$characters,$default) {
		$values = isset($get[$name])?$get[$name]:$default;
		if (!is_array($values)) $values = array($values);
		if ($characters) {
			foreach ($values as &$value) {
				$value = preg_replace("/[^$characters]/",'',$value);
			}
		}
		return $values;
	}

	protected function applyPermissions($database, $tables, $action, $permissions, $multidb) {
		if (in_array(strtolower($database), array('information_schema','mysql','sys'))) return array();
		$results = array();
		$permissions = array_change_key_case($permissions,CASE_LOWER);
		foreach ($tables as $table) {
			$result = false;
			$options = $multidb?array("*.*","$database.*","$database.$table"):array("*","$table");
			$options = array_map('strtolower', $options);
			foreach ($options as $option) {
				if (isset($permissions[$option])) {
					$result = strpos($permissions[$option],$action[0])!==false;
				}
			}
			if ($result) $results[] = $table;
		}
		return $results;
	}

	protected function processTableParameter($database,$table,$db) {
		$tablelist = explode(',',$table);
		$tables = array();
		foreach ($tablelist as $table) {
			$table = str_replace('*','%',$table);
			if ($result = $this->query($db,$this->queries['reflect_table'],array($table,$database))) {
				while ($row = $this->fetch_row($result)) $tables[] = $row[0];
				$this->close($result);
			}
		}
		return $tables;
	}

	protected function findSinglePrimaryKey($table,$database,$db) {
		$keys = array();
		if ($result = $this->query($db,$this->queries['reflect_pk'],array($table[0],$database))) {
			while ($row = $this->fetch_row($result)) $keys[] = $row[0];
			$this->close($result);
		}
		return count($keys)==1?$keys[0]:false;
	}

	protected function exitWith404($type) {
		if (isset($_SERVER['REQUEST_METHOD'])) {
			header('Content-Type:',true,404);
			die("Not found ($type)");
		} else {
			throw new \Exception("Not found ($type)");
		}
	}

	protected function exitWith422($object) {
		if (isset($_SERVER['REQUEST_METHOD'])) {
			header('Content-Type:',true,422);
			die('Unprocessable Entity');
		} else {
			throw new \Exception(json_encode($object));
		}
	}

	protected function startOutput($callback) {
		if (isset($_SERVER['REQUEST_METHOD'])) {
			header('Access-Control-Allow-Origin: *');
			if ($callback) {
				header('Content-Type: application/javascript');
				echo $callback.'(';
			} else {
				header('Content-Type: application/json');
			}
		}
	}

	protected function endOutput($callback) {
		if ($callback) {
			echo ');';
		}
	}

	protected function processKeyParameter($key,$table,$database,$db) {
		if ($key) {
			$key = array($key,$this->findSinglePrimaryKey($table,$database,$db));
			if ($key[1]===false) $this->exitWith404('1pk');
		}
		return $key;
	}

	protected function processOrderParameter($order) {
		if ($order) {
			$order = explode(',',$order,2);
			if (count($order)<2) $order[1]='ASC';
			$order[1] = strtoupper($order[1])=='DESC'?'DESC':'ASC';
		}
		return $order;
	}

	protected function processFilterParameter($filter,$db) {
		if ($filter) {
			$filter = explode(',',$filter,3);
			if (count($filter)==3) {
				$match = $filter[1];
				$filter[1] = 'LIKE';
				if ($match=='cs') $filter[2] = '%'.$this->likeEscape($filter[2]).'%';
				if ($match=='sw') $filter[2] = $this->likeEscape($filter[2]).'%';
				if ($match=='ew') $filter[2] = '%'.$this->likeEscape($filter[2]);
				if ($match=='eq') $filter[1] = '=';
				if ($match=='ne') $filter[1] = '<>';
				if ($match=='lt') $filter[1] = '<';
				if ($match=='le') $filter[1] = '<=';
				if ($match=='ge') $filter[1] = '>=';
				if ($match=='gt') $filter[1] = '>';
				if ($match=='in') {
					$filter[1] = 'IN';
					$filter[2] = explode(',',$filter[2]);

				}
			} else {
				$filter = false;
			}
		}
		return $filter;
	}

	protected function processPageParameter($page) {
		if ($page) {
			$page = explode(',',$page,2);
			if (count($page)<2) $page[1]=20;
			$page[0] = ($page[0]-1)*$page[1];
		}
		return $page;
	}

	protected function retrieveObject($key,$table,$db) {
		if (!$key) return false;

		if ($result = $this->query($db,'SELECT * FROM "!" WHERE "!" = ?',array($table[0],$key[1],$key[0]))) {
			$object = $this->fetch_assoc($result);
			foreach ($this->fetch_fields($result) as $field) {
				if ($this->is_binary_type($field) && $object[$field->name]) {
					$object[$field->name] = base64_encode($object[$field->name]);
				}
			}
			$this->close($result);
		}
		return $object;
	}

	protected function hashPassword($password){
			return password_hash($password, PASSWORD_DEFAULT);
	}

  protected function checkLogin($db, $username, $password, $CRUDtype, $table, $key){
    // TODO: do the checking, password hashing,
		$sql = "SELECT `location_id`, `acl_password`,`acl_isAdmin`  FROM `Acl` WHERE `acl_username` = '".$username."'";

		$result = $db->query($sql);
		if ($result->num_rows > 0) {
		  // output data of each row
		  while($row = $result->fetch_assoc()) {
					$success = password_verify($password, $row["acl_password"]);
					if($success === true){
						if($row["acl_isAdmin"] === "1"){
							return true;
						}

						//creating records is fine, as long it is noch POI
						if($CRUDtype === "create"){
							if($table[0] === "Poi"){
								if($row["location_id"] === $key[0]){
									return true;
								}
								return false;
							} else {
								return true;
							}
						}

						// POI can be handled the same way
						$sql_locId = "SELECT `location_id` FROM `Poi` WHERE `".$key[1]."` = ".$key[0].";";
						$inner_result = $db->query($sql_locId);
						if ($inner_result->num_rows > 0) {
						  // output data of each row
						  while($inner_row = $inner_result->fetch_assoc()) {
								if($inner_row["location_id"] === $row["location_id"])
									return true;
							}
						}
						return false;
					}
		  }
		}

    return false;
  }


	  protected function checkAdmin($db, $username, $password){
	  	$sql = "SELECT `acl_password`,`acl_isAdmin`  FROM `Acl` WHERE `acl_username` = '".$username."'";

			$result = $db->query($sql);
			if ($result->num_rows > 0) {
			  // output data of each row
			  while($row = $result->fetch_assoc()) {
						$success = password_verify($password, $row["acl_password"]);

						if($success === true){
								return $row["acl_isAdmin"] == "1";
						}
			  }
			}
	    return false;
	  }


	protected function createObject($input,$table,$db) {
		if (!$input) return false;
		$input = (array)$input;
		$keys = implode('","',str_split(str_repeat('!', count($input))));
		$values = implode(',',str_split(str_repeat('?', count($input))));
		$params = array_merge(array_keys($input),array_values($input));
		array_unshift($params, $table[0]);
    $username = "";
    $password = "";
		$key = NULL;
    $count = count($params) - 1;
    for($i = 1; $i < $count; $i++){
      $secondpos = $i + $count / 2;
      if($params[$i] == 'username'){
          $username = $params[$secondpos];
          unset($params[$i]);
          unset($params[$secondpos]);
          $keys = substr($keys,4);
          $values = substr($values,2);
      }
      if($params[$i] == 'password'){
          $password = $params[$secondpos];
          unset($params[$i]);
          unset($params[$secondpos]);
          $keys = substr($keys,4);
          $values = substr($values,2);
      }
			if($params[$i] == 'acl_password'){
					$passwordToSave = $params[$secondpos];
					$params[$secondpos] = $this->hashPassword($passwordToSave);
			}
			if($table[0] == "Poi"){
				if($params[$i] == "location_id"){
					$key[0] =	$params[$secondpos];
					$key[1] = $params[$i];
				}
			}
    }

    $loginSuccess = $this->checkLogin($db, $username, $password, "create", $table, $key);


    if($loginSuccess === true){
  		$result = $this->query($db,'INSERT INTO "!" ("'.$keys.'") VALUES ('.$values.')',$params);

			if (!$result) return null;
  		return $this->insert_id($db,$result);
    } else {
      //TODO
      return -1;
    }
	}

	protected function updateObject($key,$input,$table,$db) {
		if (!$input) return false;
		$input = (array)$input;
		$params = array();
		$sql = 'UPDATE "!" SET ';
		$params[] = $table[0];

    $username = $input["username"];
    $password = $input["password"];
    unset($input["username"]);
    unset($input["password"]);

		if($table[0] === "Acl"){
			$nothashedPassword = $input["acl_password"];
			$input["acl_password"] = $this->hashPassword($nothashedPassword);
		}

    $loginSuccess = $this->checkLogin($db, $username, $password, "update", $table, $key);

		if($loginSuccess === true){
  		foreach (array_keys($input) as $i=>$k) {
  			if ($i) $sql .= ',';
  			$v = $input[$k];
  			$sql .= '"!"=?';
  			$params[] = $k;
  			$params[] = $v;
  		}
  		$sql .= ' WHERE "!"=?';
  		$params[] = $key[1];
  		$params[] = $key[0];
  		$result = $this->query($db,$sql,$params);

  		return $this->affected_rows($db, $result);
    }else{
      //TODO
      return -1;
    }
	}

	protected function deleteObject($key,$table,$db,$input) {
    $input = (array)$input;

    $username = $input["username"];
    $password = $input["password"];
    unset($input["username"]);
    unset($input["password"]);

    $loginSuccess = $this->checkLogin($db, $username, $password, "delete", $table, $key);

    if($loginSuccess === true){
      $result = $this->query($db,'DELETE FROM "!" WHERE "!" = ?',array($table[0],$key[1],$key[0]));
  		return $this->affected_rows($db, $result);
    } else {
      //TODO
      return -1;
    }
	}

	protected function findRelations($tables,$database,$db) {
		$collect = array();
		$select = array();
		if (count($tables)>1) {
			$table0 = array_shift($tables);

			$result = $this->query($db,$this->queries['reflect_belongs_to'],array($table0,$tables,$database,$database));
			while ($row = $this->fetch_row($result)) {
				$collect[$row[0]][$row[1]]=array();
				$select[$row[2]][$row[3]]=array($row[0],$row[1]);
			}
			$result = $this->query($db,$this->queries['reflect_has_many'],array($tables,$table0,$database,$database));
			while ($row = $this->fetch_row($result)) {
				$collect[$row[2]][$row[3]]=array();
				$select[$row[0]][$row[1]]=array($row[2],$row[3]);
			}
			$result = $this->query($db,$this->queries['reflect_habtm'],array($database,$database,$database,$database,$table0,$tables));
			while ($row = $this->fetch_row($result)) {
				$collect[$row[2]][$row[3]]=array();
				$select[$row[0]][$row[1]]=array($row[2],$row[3]);
				$collect[$row[4]][$row[5]]=array();
				$select[$row[6]][$row[7]]=array($row[4],$row[5]);
			}
		}
		return array($collect,$select);
	}

	protected function retrieveInput($post) {
		$input = (object)array();
		$data = trim(file_get_contents($post));
		if (strlen($data)>0) {
			if ($data[0]=='{' || $data[0]=='[') {
				$input = json_decode($data);
			} else {
				parse_str($data, $input);
				foreach ($input as $key => $value) {
					if (substr($key,-9)=='__is_null') {
						$input[substr($key,0,-9)] = null;
						unset($input[$key]);
					}
				}
				$input = (object)$input;
			}
		}
		return $input;
	}

	protected function convertBinary($input,$tables,$db) {
		if (is_array($input)) {
			foreach (array_keys($input) as $i) {
				$input[$i] = $this->convertBinary($input[$i],$tables,$db);
			}
			return $input;
		}
		$result = $this->query($db,'SELECT * FROM "!" WHERE 1=2;',array($tables[0]));
		foreach ($this->fetch_fields($result) as $field) {
			$key = $field->name;
			if (isset($input->$key) && $input->$key && $this->is_binary_type($field)) {
				$data = $input->$key;
				$data = str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT);
				$input->$key = (object)array('type'=>'base64','data'=>$data);
			}
		}
		return $input;
	}

	protected function getParameters($config) {
		extract($config);
		$table     = $this->parseRequestParameter($request, 'a-zA-Z0-9\-_*,', false);
		$key       = $this->parseRequestParameter($request, 'a-zA-Z0-9\-,', false); // auto-increment or uuid
		$action    = $this->mapMethodToAction($method,$key);
		$callback  = $this->parseGetParameter($get, 'callback', 'a-zA-Z0-9\-_', false);
		$page      = $this->parseGetParameter($get, 'page', '0-9,', false);
		$filters   = $this->parseGetParameterArray($get, 'filter', false, false);
		$satisfy   = $this->parseGetParameter($get, 'satisfy', 'a-z', 'all');
		$columns   = $this->parseGetParameter($get, 'columns', 'a-zA-Z0-9\-_,', false);
		$order     = $this->parseGetParameter($get, 'order', 'a-zA-Z0-9\-_*,', false);
		$transform = $this->parseGetParameter($get, 'transform', '1', false);

		$table    = $this->processTableParameter($database,$table,$db);
		$key      = $this->processKeyParameter($key,$table,$database,$db);
		foreach ($filters as &$filter) $filter = $this->processFilterParameter($filter,$db);
		if ($columns) $columns = explode(',',$columns);
		$page     = $this->processPageParameter($page);
		$order    = $this->processOrderParameter($order);

		$table  = $this->applyPermissions($database,$table,$action,$permissions,$multidb);
		if (empty($table)) $this->exitWith404('entity');

		$object = $this->retrieveObject($key,$table,$db);

		$input = $this->retrieveInput($post);
		if (!empty($input)) $input = $this->convertBinary($input,$table,$db);

		list($collect,$select) = $this->findRelations($table,$database,$db);

		return compact('action','database','table','key','callback','page','filters','satisfy','columns','order','transform','db','object','input','collect','select');
	}

	protected function listCommand($parameters) {
		extract($parameters);
		$this->startOutput($callback);
		echo '{';
		$tables = $table;
		$table = array_shift($tables);
		if($table === "Acl"){
			$input = (array)$parameters["input"];
			$db = $parameters["db"];
			$username = $input["username"];
			$password = $input["password"];

			$isadmin = $this->checkAdmin($db,$username,$password);

			if(!($isadmin === true)){
				echo "\"message\":\"Missing rights to view the structure of this table\", \"errorCode\":-1}";
				$this->endOutput($callback);
				return;
			}
		}
		// first table
		$count = false;
		echo '"'.$table.'":{';
		if (is_array($order) && is_array($page)) {
			$params = array();
			$sql = 'SELECT COUNT(*) FROM "!"';
			$params[] = $table;
			foreach ($filters as $i=>$filter) {
				if (is_array($filter)) {
					$sql .= $i==0?' WHERE ':($satisfy=='all'?' AND ':' OR ');
					$sql .= '"!" ! ?';
					$params[] = $filter[0];
					$params[] = $filter[1];
					$params[] = $filter[2];
				}
			}
			if ($result = $this->query($db,$sql,$params)) {
				while ($pages = $this->fetch_row($result)) {
					$count = $pages[0];
				}
			}
		}
		$params = array();
		$sql = 'SELECT ';
		if (is_array($columns)) {
			$sql .= '"'.implode('","',$columns).'"';
		} else {
			$sql .= '*';
		}
		$sql .= ' FROM "!"';
		$params[] = $table;
		foreach ($filters as $i=>$filter) {
			if (is_array($filter)) {
				$sql .= $i==0?' WHERE ':($satisfy=='all'?' AND ':' OR ');
				$sql .= '"!" ! ?';
				$params[] = $filter[0];
				$params[] = $filter[1];
				$params[] = $filter[2];
			}
		}
		if (is_array($order)) {
			$sql .= ' ORDER BY "!" !';
			$params[] = $order[0];
			$params[] = $order[1];
		}
		if (is_array($order) && is_array($page)) {
			$sql = $this->add_limit_to_sql($sql,$page[1],$page[0]);
		}
		if ($result = $this->query($db,$sql,$params)) {
			echo '"columns":';
			$fields = array();
			$base64 = array();
			foreach ($this->fetch_fields($result) as $field) {
				$base64[] = $this->is_binary_type($field);
				$fields[] = $field->name;
			}
			echo json_encode($fields);
			$fields = array_flip($fields);
			echo ',"records":[';
			$first_row = true;
			while ($row = $this->fetch_row($result)) {
				if ($first_row) $first_row = false;
				else echo ',';
				if (isset($collect[$table])) {
					foreach (array_keys($collect[$table]) as $field) {
						$collect[$table][$field][] = $row[$fields[$field]];
					}
				}
				foreach ($base64 as $k=>$v) {
					if ($v && $row[$k]) {
						$row[$k] = base64_encode($row[$k]);
					}
				}
				echo json_encode($row);
			}
			$this->close($result);
			echo ']';
			if ($count) echo ',';
		}
		if ($count) echo '"results":'.$count;
		echo '}';
		// prepare for other tables
		foreach (array_keys($collect) as $t) {
			if ($t!=$table && !in_array($t,$tables)) {
				array_unshift($tables,$t);
			}
		}
		// other tables
		foreach ($tables as $t=>$table) {
			echo ',';
			echo '"'.$table.'":{';
			$params = array();
			$sql = 'SELECT * FROM "!"';
			$params[] = $table;
			if (isset($select[$table])) {
				$first_row = true;
				echo '"relations":{';
				foreach ($select[$table] as $field => $path) {
					$values = $collect[$path[0]][$path[1]];
					$sql .= $first_row?' WHERE ':' OR ';
					$sql .= '"!" IN ?';
					$params[] = $field;
					$params[] = $values;
					if ($first_row) $first_row = false;
					else echo ',';
					echo '"'.$field.'":"'.implode('.',$path).'"';
				}
				echo '}';
			}
			if ($result = $this->query($db,$sql,$params)) {
				if (isset($select[$table])) echo ',';
				echo '"columns":';
				$fields = array();
				$base64 = array();
				foreach ($this->fetch_fields($result) as $field) {
					$base64[] = $this->is_binary_type($field);
					$fields[] = $field->name;
				}
				echo json_encode($fields);
				$fields = array_flip($fields);
				echo ',"records":[';
				$first_row = true;
				while ($row = $this->fetch_row($result)) {
					if ($first_row) $first_row = false;
					else echo ',';
					if (isset($collect[$table])) {
						foreach (array_keys($collect[$table]) as $field) {
							$collect[$table][$field][]=$row[$fields[$field]];
						}
					}
					foreach ($base64 as $k=>$v) {
						if ($v && $row[$k]) {
							$row[$k] = base64_encode($row[$k]);
						}
					}
					echo json_encode($row);
				}
				$this->close($result);
				echo ']';
			}
			echo '}';
		}
		echo '}';
		$this->endOutput($callback);
	}

	protected function readCommand($parameters) {
		extract($parameters);
		if($parameters["table"][0] === "Acl"){
			$input = (array)$parameters["input"];
			$db = $parameters["db"];
			$username = $input["username"];
			$password = $input["password"];

			$isadmin = $this->checkAdmin($db,$username,$password);

			if(!($isadmin === true)){
				echo "{\"message\":\"Missing rights to view the structure of this table\", \"errorCode\":-1}";
				$this->endOutput($callback);
				return;
			}
		}
		if (!$object) $this->exitWith404('object');
		$this->startOutput($callback);
		echo json_encode($object);
		$this->endOutput($callback);
	}

	protected function createCommand($parameters) {
		extract($parameters);
		if (!$input) $this->exitWith404('input');
		$this->startOutput($callback);
		if (!is_array($input)) {
			echo json_encode($this->createObject($input,$table,$db));
		} else {
			$result = array();
			foreach ($input as $i) {
				$result[] = $this->createObject($i,$table,$db);
			}
			echo json_encode($result);
		}
		$this->endOutput($callback);
	}

	protected function updateCommand($parameters) {
		extract($parameters);
		if (!$input) $this->exitWith404('subject');
		$this->startOutput($callback);
		echo json_encode($this->updateObject($key,$input,$table,$db));
		$this->endOutput($callback);
	}

	protected function deleteCommand($parameters) {
		extract($parameters);
		$this->startOutput($callback);
		echo json_encode($this->deleteObject($key,$table,$db,$input));
		$this->endOutput($callback);
	}

	protected function listCommandTransform($parameters) {
		if ($parameters['transform']) {
			ob_start();
		}
		$this->listCommand($parameters);
    $tablename = $parameters[table][0];
		if($tablename === "Acl"){
			return;
		}
		if ($parameters['transform']) {
			$content = ob_get_contents();
			ob_end_clean();
			$data = json_decode($content,true);
			echo json_encode(self::mysql_crud_api_transform($data));
		}
	}

	public function __construct($config) {
		extract($config);

		$hostname = isset($hostname)?$hostname:null;
		$username = isset($username)?$username:'root';
		$password = isset($password)?$password:null;
		$database = isset($database)?$database:false;
		$port = isset($port)?$port:null;
		$socket = isset($socket)?$socket:null;
		$charset = isset($charset)?$charset:'utf8';

		$permissions = isset($permissions)?$permissions:array('*'=>'crudl');

		$db = isset($db)?$db:null;
		$method = isset($method)?$method:$_SERVER['REQUEST_METHOD'];
		$request = isset($request)?$request:(isset($_SERVER['PATH_INFO'])?$_SERVER['PATH_INFO']:'');
		$get = isset($get)?$get:$_GET;
		$post = isset($post)?$post:'php://input';

		$request = explode('/', trim($request,'/'));

		$multidb   = !$database;
		if ($multidb) {
			$database  = $this->parseRequestParameter($request, 'a-zA-Z0-9\-_,', false);
		}
		if (!$db) {
			$db = $this->connectDatabase($hostname,$username,$password,$database,$port,$socket,$charset);
		}

		$this->config = compact('method', 'request', 'get', 'post', 'multidb', 'database', 'permissions', 'db');
	}

	public static function mysql_crud_api_transform(&$tables) {
		$get_objects = function (&$tables,$table_name,$where_index=false,$match_value=false) use (&$get_objects) {
			$objects = array();
			foreach ($tables[$table_name]['records'] as $record) {
				if ($where_index===false || $record[$where_index]==$match_value) {
					$object = array();
					foreach ($tables[$table_name]['columns'] as $index=>$column) {
						$object[$column] = $record[$index];
						foreach ($tables as $relation=>$reltable) {
							if (isset($reltable['relations'])) {
								foreach ($reltable['relations'] as $key=>$target) {
									if ($target == "$table_name.$column") {
										$column_indices = array_flip($reltable['columns']);
										$object[$relation] = $get_objects($tables,$relation,$column_indices[$key],$record[$index]);
									}
								}
							}
						}
					}
					$objects[] = $object;
				}
			}
			return $objects;
		};
		$tree = array();
		foreach ($tables as $name=>$table) {
			if (!isset($table['relations'])) {
				$tree[$name] = $get_objects($tables,$name);
				if (isset($table['results'])) {
					$tree['_results'] = $table['results'];
				}
			}
		}
		return $tree;
	}

	public function executeCommand() {
		$parameters = $this->getParameters($this->config);
		switch($parameters['action']){
			case 'list': $this->listCommandTransform($parameters); break;
			case 'read': $this->readCommand($parameters); break;
			case 'create': $this->createCommand($parameters); break;
			case 'update': $this->updateCommand($parameters); break;
			case 'delete': $this->deleteCommand($parameters); break;
		}
	}

}

// uncomment the lines below when running in stand-alone mode:

// header('Access-Control-Allow-Origin: *');

 $api = new MySQL_CRUD_API(array(
 	'hostname'=>$host,
	'username'=>$user,
	'password'=>$password,
	'database'=>$database,
 	'charset'=>'utf8'
 ));
 $api->executeCommand();

// For Microsoft SQL Server use:

// $api = new MsSQL_CRUD_API(array(
// 	'hostname'=>'(local)',
// 	'username'=>'',
// 	'password'=>'',
// 	'database'=>'xxx',
// 	'charset'=>'UTF-8'
// ));
// $api->executeCommand();
