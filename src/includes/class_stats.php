<?PHP
class Stats
{
	public function __construct() 
	{
		// ...
	}

    public function fetchDay(Silex\Application $app)
    {
		$statement = $app['db']->executeQuery("SELECT * FROM daydata WHERE datetime LIKE '%2013-04-19%' AND NOT kW='0.000'" );
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