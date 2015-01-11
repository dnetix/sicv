<?php

use SICV\Core\Commander\CommandBus;

class BaseController extends Controller {

	public $commandBus;

	function __construct(CommandBus $commandBus) {
		$this->commandBus = $commandBus;
	}

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}

		View::share('currentUser', Auth::user());
	}

	public function execute($command){
		return $this->commandBus->execute($command);
	}

}
