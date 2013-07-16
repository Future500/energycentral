<?PHP
class Stats
{
	public function __construct() 
	{
		// ...
	}

    public function fetchDay(Silex\Application $app)
    {
		$statement = $app['db']->executeQuery("SELECT * FROM daydata");
		$retn = $statement->fetchAll();

		foreach ($retn as $row) {
			echo '----------------------<br>';
			echo ' DateTime: ' . $row['datetime'] . '<br>';
			echo ' kWh: ' . $row['kWh']. '<br>';
			echo ' kW: ' . $row['kW']. '<br>';
		}
	}
}
?>