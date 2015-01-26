<?php

use Illuminate\Console\Command;
use SICV\Contracts\Contract;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class SicvFix
 * Not needed just for a specific case
 */
class SicvFix extends Command {

	protected $name = 'sicv:update';
	protected $description = 'Actualiza las fechas de contrato que quedaron mal';
	protected $connection;

	public function __construct()
	{
		parent::__construct();
	}

	public function fire()
	{
		$this->createDBConfig();
		echo "Creating the new connection to the old database...\n";
		$this->connection = DB::connection('olddb');
		echo "Performing migrations...\n";
		$contracts = $contracts = $this->connection->table('contrato')->get();
		foreach($contracts as $contract){
			Contract::find($contract['idcontrato'])->update([
				'created_at' => $contract['fechaingreso']
			]);
			echo "{$contract['idcontrato']} ";
		}
		echo "\n";

		echo "PROCESS TERMINATED\nPlease check for annulations\n";

	}

	public function createDBConfig() {
		// Create the old db connection
		Config::set(
			'database.connections.olddb',
			[
				'driver' => 'mysql',
				'host' => 'localhost',
				'database' => $this->argument('old_db'),
				'username' => 'root',
				'password' => $this->option('root_pass'),
				'collation' => 'utf8_general_ci',
				'charset' => 'utf8'
			]
		);
	}

	protected function getArguments()
	{
		return array(
			array('old_db', InputArgument::REQUIRED, 'Nombre de la base de datos vieja.'),
		);
	}

	protected function getOptions()
	{
		return array(
			array('new_db', null, InputOption::VALUE_OPTIONAL, 'Nombre de la base de datos nueva', 'sicv'),
			array('root_pass', null, InputOption::VALUE_OPTIONAL, 'Contraseña de root', 'root'),
		);
	}

}
