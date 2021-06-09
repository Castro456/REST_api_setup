<?php 	// this each fn is same as $_SERVER["REQUEST_METHOD"]
require APPPATH.'libraries/REST_Controller.php';

class Student extends REST_Controller {
		// echo "This is POST Method";
	public function __construct() {
		parent::__construct();
		$this->load->model("api/Stu_query");
	}

	public function index_post() {
		$data=json_decode(file_get_contents("php://input"));

		$name=isset($data->name) ? $data->name : " ";
		$email=isset($data->email) ? $data->email : " ";
		$mobile=isset($data->mobile) ? $data->mobile : " ";

		if ( !empty($name) && !empty($email) && !empty($mobile)) {
			$student=array(
				"name"=> $name,
				"email"=> $email,
				"mobile"=> $mobile);

			if ($this->Stu_query->insert_data($student)) {
				$this->response(array( //this response method is from rest_controller file
						"status"=> "1",
						"message"=> "Students Added"
					), REST_Controller::HTTP_OK);
			}

			else {
				$this->response(array("status"=> "0",
						"message"=> "Students didn't Added"
					), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
			}
		}

		else {
			$this->response(array( //this response method is from rest_controller file
					"status"=> "0",
					"message"=> "All Fields are needed"
				), REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function index_put() {
		echo "This is PUT Method";
	}

	public function index_delete() {
		echo "This is DELETE Method";
	}

	public function index_get() {
		// echo "This is GET Method";
		$this->load->model(array("api/Stu_query"));
		$disp=$this->Stu_query->get_data();
		// print_r($disp);
		if (count($disp) > 0) {
			$this->response(array( //this response method is from rest_controller file
					"status"=> "1",
					"message"=> "Students Found",
					"data"=> $disp), REST_Controller::HTTP_OK); //http ok is 200, which is in static var, so using scope res operator  
		}

		else {
			$this->response(array( //this response method is from rest_controller file
					"status"=> "0",
					"message"=> "No Students Found",
					"data"=> $disp), REST_Controller::HTTP_NOT_FOUND); //http ok is 200, which is in static var, so using scope res operator
		}
	}
}
