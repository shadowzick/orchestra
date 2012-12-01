<?php

use Orchestra\Controller,
	Orchestra\Core,
	Orchestra\Extension\Publisher,
	Orchestra\Messages;

class Orchestra_Publisher_Controller extends Controller {

	/**
	 * Use restful verb.
	 *
	 * @var  string
	 */
	public $restful = true;

	/**
	 * Repository Publisher instance.
	 *
	 * @var Orchestra\Extension\Publisher\Driver
	 */
	private $driver = null;

	/**
	 * Load dependencies during __construct
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Load publisher based on service.
	 *
	 * @access public
	 * @return Response
	 */
	public function get_index()
	{
		$driver = Core::memory()->get('orchestra.publisher.driver', 'ftp');

		return $this->{"get_{$driver}"}();
	}
	

	/**
	 * Show FTP configuration form or run the queue.
	 *
	 * @access public
	 * @return Response
	 */
	public function get_ftp()
	{
		if (Publisher::connected()) 
		{
			return Publisher::execute();
		}
	
		return View::make('orchestra::publisher.ftp');
	}

	public function post_ftp()
	{
		$input = Input::only(array('host', 'user', 'password'));
		$m     = new Messages;

		// Make an attempt to connect to service first before
		try
		{
			Publisher::connect($input);
			Session::put('orchestra.ftp', $input);
		}
		catch(Hybrid\RuntimeException $e)
		{
			Session::forget('orchestra.ftp');

			$m->add('error', $e->getMessage());

			return Redirect::to(handles('orchestra::publisher/ftp'))
				->with('message', $m->serialize());
		}

		if (Publisher::connected())
		{
			return Publisher::execute();
		}
	}
}