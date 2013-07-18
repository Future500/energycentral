<?PHP
class Stats
{
	public function __construct() 
	{
		// ...
	}

	public function checkDate($date) 
	{
		 if(strlen($date) > 8) { // need atleast the year and month before we continue
			list($yy, $mm, $dd) = explode("-", $date); // save explode result to variables

			if (is_numeric($yy) && is_numeric($mm) && is_numeric($dd)) { // check if only numbers have been used
				return checkdate($mm, $dd, $yy);  // check if valid
			}
		}
	    return false;            
	}

	private function encode_highcharts(array $input)
	{
		$numberArray = array();
		
		if(count($input)) { // If there is any data then we loop through it, if there isn't then the graph will be empty
			// We start with 0 kW so we take the first index of our input (which is the first time energy is received) and subtract 5 minutes
			$lastTime = strtotime($input[0]['datetime'] . 'UTC') * 1000;
			array_push($numberArray, array($lastTime - 300000, 0.0)); // We start with 0 kW 5 minutes before the first reading

			// Save time and energy production to array
			foreach ($input as $row) {
				array_push($numberArray, array(strtotime($row['datetime'] . 'UTC') * 1000, floatval($row['kW'])));
			}

			// We end with 0 kW so we take the last index of our input (which is the last time energy is received) and add 5 minutes
			$lastIndex = count($input) - 1; // Amount of elements in array
			$lastTime = strtotime($input[$lastIndex]['datetime'] . 'UTC') * 1000;
			array_push($numberArray, array($lastTime + 300000, 0.0)); // We end with 0 kW 5 minutes after the last reading
		}
		return json_encode($numberArray); // encode for jquery
	}

    public function fetchDay(Silex\Application $app, $date = '', $highcharts = true)
    {
    	// Set current date if none is set yet
    	if(empty($date)) {
    		$date = date('Y-m-d');
    	}

    	// Check if valid date otherwise we can skip the next part of code
    	if($this->checkDate($date)) {
    		// Fetch the first and last reading
    		$beginGraph = $app['db']->executeQuery("SELECT datetime FROM daydata WHERE DATE(datetime) = :q AND kW > 0.000 ORDER BY datetime ASC LIMIT 1", array('q' => $date))->fetchColumn();
    		$endGraph = $app['db']->executeQuery("SELECT datetime FROM daydata WHERE DATE(datetime) = :q AND kW > 0.000 ORDER BY datetime DESC LIMIT 1", array('q' => $date))->fetchColumn();
   	 	}

    	// Fetch all data between the two times
    	$retn = array();

    	if(!empty($beginGraph) && !empty($endGraph)) {
			$statement = $app['db']->executeQuery("SELECT * FROM daydata WHERE datetime >= :first AND datetime <= :last", array('first' => $beginGraph, 'last' => $endGraph));
			$retn = $statement->fetchAll();
		}
		return ($highcharts ? $this->encode_highcharts($retn) : $retn);
	}
}
?>