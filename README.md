# db_php

## Usage

```
$db = new Db();

//get records
$results = $db->database($db)->table($table)->select('*')->where('id','>',10)->get();

while($row = mysqli_fetch_assoc($results)){
  print_r($row);
}

//insert record
$data = array("field1"=>"val1","field2"=>"val2");

$results = $db->database($db)->table($table)->insert($data)->put();

//update record
$results = $db->database($db)->table($table)->update($data)->where("id","=",1)->put();

```
