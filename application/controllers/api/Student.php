<?php 	
require APPPATH.'libraries/REST_Controller.php';

class Student extends REST_Controller {
		// this each fn is same as $_SERVER["REQUEST_METHOD"]
	public function __construct() {
		parent::__construct();
		$this->load->model("api/Stu_query");
		$this->load->library("form_validation");
		$this->load->helper("security");
	}

	public function index_post() {
		// $data=json_decode(file_get_contents("php://input"));
		// $name=isset($data->name) ? $data->name : " ";         (*this is for post body methos)
		// $email=isset($data->email) ? $data->email : " ";
		// $mobile=isset($data->mobile) ? $data->mobile : " ";
		$name= $this->security->xss_clean($this->input->post("name"));
		$email= $this->security->xss_clean($this->input->post("email"));
		$mobile= $this->security->xss_clean($this->input->post("mobile"));

		$this->form_validation->set_rules("name", "Name" , "required");
		$this->form_validation->set_rules("email", "Email" , "required|valid_email");
		$this->form_validation->set_rules("mobile", "Mobile" , "required");

		if($this->form_validation->run() === FALSE){
			$this->response(array( 
					"status"=> "0",
					"message"=> "All fields are required"
				), REST_Controller::HTTP_NOT_FOUND);
		}
		else {
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
						$this->response(array(
							"status"=> "0",
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
	}

	public function index_put() {
		// echo "This is PUT Method";
		$data = json_decode(file_get_contents("php://input"));
		if (isset($data->id) && isset($data->name) && isset($data->email) && isset($data->mobile)) {
			$stu_id = $data->id;
			$stu_info = array(
				"name" => $data->name,
				"email" => $data->email,
				"mobile" => $data->mobile
			);
			if ($this->Stu_query->update_stu($stu_id,$stu_info)) {
				$this->response(array(
				"status" => "1",
				"message" => "Student Changed"
			),REST_Controller::HTTP_OK);
			}else {
				$this->response(array(
				"status" => "0",
				"message" => "Student didn't change"
			),REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
			}
		}else {
			$this->response(array(
				"status" => "0",
				"message" => "Student failed to change"
			),REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function index_delete() {
		// echo "This is DELETE Method";
		$data = json_decode(file_get_contents("php://input"));
		$stu_id = $this->security->xss_clean($data->stu_id);
		if ($this->Stu_query->delete_stu($stu_id)) {
			$this->response(array(
				"status" => "1",
				"message" => "Student Deleted"
			),REST_Controller::HTTP_OK);
		}
		else {
			$this->response(array(
				"status" => "0",
				"message" => "Student failed to Deleted"
			),REST_Controller::HTTP_NOT_FOUND);
		}
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
