<?PHP
class Stats
{
	public function __construct() 
	{
		// ...
	}

    public function fetchDay(Silex\Application $app, $date = '')
    {
    	if($date == '') {
    		$date = date('Y-m-d');
    	}
    	$q = '%' . $date . '%';
		$statement = $app['db']->executeQuery("SELECT * FROM daydata WHERE datetime LIKE :q AND NOT kW='0.000'", array('q' => $q));
		$retn = $statement->fetchAll();

		// loop through all the data
		$numberArray = array();

		foreach ($retn as $row) {
			array_push($numberArray, $row['datetime'] . ',' . floatval($row['kW']));
		}

		return json_encode($numberArray); // encode for jquery
	}
}
?>