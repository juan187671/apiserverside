<?php
class ErrorController extends Zend_Controller_Action {
	
	public function errorAction() {
		$errors = $this->getParam ( "error_handler" );
		$exception = $errors->exception;
		exit ( $exception->getMessage () );
	}
	
}