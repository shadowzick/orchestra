<?php namespace Orchestra\Support;

use \Closure,
	\Config,
	\Input,
	\Lang,
	Orchestra\View;

class Table extends Decorator {

	/**
	 * Create a new Table instance.
	 * 			
	 * @access public
	 * @param  Closure      $callback
	 * @return void	 
	 */
	public function __construct(Closure $callback)
	{
		// Initiate Table\Grid, this wrapper emulate table designer
		// script to create the table.
		$this->grid = new Table\Grid(Config::get('orchestra::support.table', array()));
		
		$this->extend($callback);	
	}

	/**
	 * Render the table
	 *
	 * @access  public
	 * @return  string
	 */
	public function render()
	{
		// localize Table\Grid object
		$grid  = $this->grid;
		
		// Add paginate value for current listing while appending query string
		$input = Input::query();

		// we shouldn't append ?page
		if (isset($input['page'])) unset($input['page']);

		$paginate = (true === $grid->paginate ? $grid->model->appends($input)->links() : '');

		$empty_message = $grid->empty_message;

		if ( ! ($empty_message instanceof Lang))
		{
			$empty_message = Lang::line($empty_message);
		}

		$data = array(
			'table_markup'  => $grid->markup,
			'row_markup'    => $grid->rows->markup,
			'empty_message' => $empty_message,
			'columns'       => $grid->columns(),
			'rows'          => $grid->rows(),
			'pagination'    => $paginate,
		);

		// Build the view and render it.
		return View::make($grid->view)->with($data)->render();
	}
}