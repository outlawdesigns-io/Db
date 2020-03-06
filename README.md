# Db

Db (database) is a utility class that is intended to facilitate communications with a MySQL server.

You can chain together Db's methods to build and execute queries for you, eliminating long query strings in your code.

## Requirements

Db uses global constants HOST, USER & PASSWORD to connect to MySQL server.

These constants are defined in credentials.php and should be updated or overloaded before use.

## Methods

### connect()

Connect to database server. Throws exception on failure. Predominantly used for testing as get() and put() will call connect() for you when executing a query.

### database($database:string)

Set the target database.

### createDatabase($dbName:string)

Create a Database.

```
$db->createDatabase('MyDb')->put();
```

### createTable($tableArr:AssociativeArray)

Create a table on the current database.

```
$table = array(
  "name"=>"Test",
  "columns"=>array(
    "UID"=>array('int','not null','auto_increment'),
    "modelId"=>array('int'),
    "ipAddress"=>array('varchar(50)'),
    "createdDate"=>array('DATETIME')
  ),
  "primaryKey"=>"UID"
);

$db->database('MyDb')->createTable($table)->put();
```

### drop($name:string,$table:bool = true)

Drop a database or table.

```
$db->database('MyDb')->drop('Test')->put();
$db->database('MyDb')->drop('MyDb',false)->put();
```

### truncate()

Truncate the selected table.

```
$db->database('MyDb')->table('Test')->truncate()->put();
```

### select($select:string)

Set the fields you wish to select with your query.

### table($table:string)

Set the table you wish to query against.

### where($where,$conditional,$condition)

Add a WHERE clause to your query.

```
$results = $db->database('MyDb')->table('Test')->select('UID')->where('modelId','>=',2)->get();
```

### andWhere($where:string,$conditional:string,$condition:string)

Add an AND clause to your query. This can be repeated as many times as desired.

```
$results = $db->database('MyDb')->table('Test')->select('UID')->where('modelId','>=',2)->andWhere('created_date','>','2019-11-17')->andWhere('ipAddress','=','127.0.0.1')->get();
```

### orWhere($where:string,$conditional:string,$condition:string)

Add an OR clause to your query. This can be repeated as many times as desired.

```
$results = $db->database('MyDb')->table('Test')->select('UID')->where('modelId','>=',2)->orWhere('created_date','>','2019-11-17')->orWhere('ipAddress','=','127.0.0.1')->get();
```

### orderBy($condition:string)

Add an ORDER BY clause to your query.

```
$results = $db->database('MyDb')->table('Test')->select('UID')->where('modelId','>=',2)->orderBy('created_date')->get();
```

### groupBy($condition:string)

Add a GROUP BY clause to your query.

```
$results = $db->database('MyDb')->table('Test')->select('UID')->where('modelId','>=',2)->groupBy('ipAddress')->get();
```

### join($table:string,$condition1:string,$conditional:string,$condition2:string)

Add a basic LEFT OUTER JOIN to your query. This can be repeated as many times as desired.

### having($where:string,$conditional:string,$condition:string)

Add a HAVING clause to your query. This should only be used in conjunction with groupBy()

### delete()

Delete a specific record from the selected database and table. Note, if not combined with a where clause, this method will essentially truncate your table.

```
$db->database('MyDb')->table('Test')->delete()->where("modelId","=",3)->put();
```

### insert($data:AssociativeArray)

Insert a new record into the selected database and table.

```
$data = array("field1"=>"val1","field2"=>"val2");

$db->database($db)->table($table)->insert($data)->put();
```

### update($data:AssociativeArray)

Update a specific record from the selected database and table. Note, if not combined with a where clause, this method will update all records in your table.

```
$data = array("field1"=>"val1","field2"=>"val2");

$db->database($db)->table($table)->update($data)->where("id","=",1)->put();
```

### put()

Execute a query from which you do not expect results.

### get()

Execute a query from which you do expect results.


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
