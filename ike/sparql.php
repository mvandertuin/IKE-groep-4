<?
require_once( "sparqllib.php" );
 
$db = sparql_connect( "http://dbpedia.org/sparql" );
if( !$db ) { print $db->errno() . ": " . $db->error(). "\n"; exit; }
//$db->ns( "foaf","http://xmlns.com/foaf/0.1/" );
 
$sparql = "SELECT * WHERE {?x dbpedia-owl:musicSubgenre ?y} LIMIT 5";
$result = $db->query( $sparql ); 
if( !$result ) { print $db->errno() . ": " . $db->error(). "\n"; exit; }
 
$fields = $result->field_array( $result );
 
print "<p>Number of rows: ".$result->num_rows( $result )." results.</p>";
print "<table border='1'>";
print "<tr>";
foreach( $fields as $field )
{
	print "<th>$field</th>";
}
print "</tr>";
while( $row = $result->fetch_array( $result ) )
{
	print "<tr>";
	foreach( $fields as $field )
	{
		print "<td>$row[$field]</td>";
	}
	print "</tr>";
}
print "</table>";