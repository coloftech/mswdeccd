<?php 
defined('BASEPATH') or exit('No direct script access allowed');


/**
  * 
  */
 class Assessment extends MY_Controller
 {
 	

  private $worker_id;
  function __construct()
  {
    // code...
    parent::__construct();
   
    if (!$this->aauth->is_loggedin()) {
      // code...
      redirect('login?url='.current_url());
    }
    $this->worker_id = $this->session->userdata('workersId');
    /*
    if (!$this->aauth->is_admin()) {
      // code...
      redirect('permission/deny');
    }
    */
    $this->load->model('centers/centers_model');
    $this->load->model('settings/settings_model');
    $this->load->model('workers_model');
    $this->load->model('students/students_model','student');
    $this->load->model('assessment_model','assessment');
  }

  public function index($worker=0)
  {
    // code...
    $worker_id = $this->worker_id;
    if ($worker !== 0) {
      // code...
      $worker_id = $worker;
    }

    $data = new stdClass();
    $data->gross_motor = $this->assessment->list('gross_motor');
    $data->fine_motor = $this->assessment->list('fine_motor');
    $data->self_help = $this->assessment->list('self_help');
    $data->expressive_lang = $this->assessment->list('expressive_lang');
    $data->receiptive_lang = $this->assessment->list('receiptive_lang');
    $data->cognitive = $this->assessment->list('cognitive');
    $data->social_emotion = $this->assessment->list('social_emotion');

    $data->myschoolyear = $this->workers_model->listmyschoolyear($worker_id);
    $data->worker_id = $worker_id;

    $data->content = 'assessment/students';
    $this->template->dashboard($data);


  }
  public function list_by_worker($worker=0,$year_id=0,$type=0)
  {
    // code...

    if ($worker > 0 && $year_id > 0 && $type > 0) {
      // code...

      $result = $this->assessment->get_sum_scaled_score($worker,$year_id,$type);
      if (!empty($result)) {
        // code...
        $data = array();
        foreach ($result as $key => $value) {
          // code...
          //$data[$key] = $value;
          //$data[$key]->standard_score = get_standard_score($value->sum_scaled_score);
          $data[] =  array(
            '<a href="'.site_url('students/profile/').$value->student_id.'">'.$this->student->get_name($value->student_id).'</a>',
            tomdy($value->date_assessment),
            $value->sum_scaled_score,
            get_standard_score($value->sum_scaled_score)

          );
        }
        echo json_encode(array('data'=>$data));
      exit;

      }

    }
      echo json_encode(array('data'=>false));
      exit;


  }
  public function export($value='')
  {
    // code...
  }
  public function get_checked($value='')
  {
    // code...
    $student_id = $this->input->post('student_id');
    $type = $this->input->post('type');
    $result = $this->assessment->get_checked($student_id,$type);

    if (!empty($result)) {
      // code...
      $data = array();

      foreach ($result as $key => $value) {
        // code...
        //var_dump($value);
        $data[$value->domain] = json_decode($value->answer);
      }
      echo json_encode(array('status'=>true,'data'=>$data));
    }else{
      echo json_encode(array('status'=>false));
    }
  }
  public function setscore($value='')
  {
    // code...
    if ($this->input->post()) {

      $checked = $this->input->post('checked');
      $unchecked = $this->input->post('unchecked');

      $total_checked = (!empty($checked) ? count($checked) : 0);
      $total_unchecked = (!empty($unchecked) ? count($unchecked) : 0);

      $total = $total_checked + $total_unchecked;
        $raw_score = $total - $total_unchecked;
        $domain = $this->input->post('domain');
        $type = $this->input->post('type');
        $student_id = $this->input->post('student_id');
        $date_assessment = $this->input->post('date_assessment');
        $answer = json_encode($checked);
        $scaled_score = $this->get_score($domain,$raw_score,$student_id,$date_assessment);
        
      if ($total_checked <= 0 || $student_id <= 0 || empty($date_assessment)) {
        // code...
        echo json_encode(array('status'=>false,'msg'=>'Nothing to save here. Please check your form.'));
        exit();
      }

              $postdata = new stdClass();
              $postdata->student_id = $student_id;
              $postdata->type = $type;
              $postdata->domain = $domain;
              $postdata->raw_score = $raw_score;
              $postdata->answer = $answer;
              $postdata->scaled_score = $scaled_score;
              $postdata->date_assessment = $date_assessment;

              if ($result = $this->assessment->save_score($postdata)) {
                  // code...
                echo json_encode(array('status'=>true,'msg'=>'Assessment successfully save.'));

                }else{
                echo json_encode(array('status'=>false,'msg'=>'Assessment unsuccessful.'));

                }
 
      exit;
    }

  }
 	private function get_score($domain,$raw_score,$student_id,$date_assessment)
 	{
 		// code...
 		$this->load->library('scaled_score');
 		$score = new Scaled_score();
 		$this->load->model('students/students_model');
 		$info = $this->students_model->info($this->input->post('student_id'));
 		$birthday = getAge($info->birthDate,$date_assessment);
 		$age = $birthday->y.'.'.$birthday->m;
 		$score = $score::_get($domain,$raw_score,$age);
 		return $score;
    //	$score = $score::_get('fine_motor',10,3.10);
 	//	echo json_encode(array('status'=>true,'msg'=>'Get Score.','score'=>$score));


 	}

  public function domain_questions($form='')
  {
    // code...
    if (!empty($form)) {
      // code...
      switch ($form) {
        case 'add':
          // code...
        $this->add_question($this->input->post());
          break;
        
        case 'edit':
          // code...
        $this->edit_question();
          break;
          case 'update_position':
        $this->update_position($this->input->post());

          break;
        case 'remove':
        $id = $this->input->post('id');
        $result = $this->assessment->remove($id);
        break;

        default:
          // code...
        echo json_encode(noinput());
          break;
      }
      exit();
    }

    $data = new stdClass();
    $data->gross_motor = $this->assessment->list('gross_motor');
    $data->fine_motor = $this->assessment->list('fine_motor');
    $data->self_help = $this->assessment->list('self_help');
    $data->expressive_lang = $this->assessment->list('expressive_lang');
    $data->receiptive_lang = $this->assessment->list('receiptive_lang');
    $data->cognitive = $this->assessment->list('cognitive');
    $data->social_emotion = $this->assessment->list('social_emotion');
    $data->content = 'assessment/questions';
    $this->template->dashboard($data);
  }
  private function add_question($data)
  {
    // code...
    if (!empty($data)) {
      // code...
      //echo json_encode($data);
      $result = $this->assessment->save($data);
      echo json_encode($result);
      
    }
    else{
      echo json_encode(noinput());
    }

  }
  private function update_position($data)
  {
    // code...
    $result = $this->assessment->save($data['domain'],0,true);
    echo json_encode(array('status'=>$result));

  }
  public function get_date_assessment($student_id=0,$type=0)
  {
    // code...
    if($row = $this->assessment->get_date_assessment($student_id,$type))
      echo tomdy($row->date_assessment);
    else
      echo null;
  }

  public function get_assessment_age($student_id=0,$type=0)
  {
    // code...
    if($row = $this->assessment->get_date_assessment($student_id,$type)){
      $this->load->model('students/students_model');
      $info = $this->students_model->info($student_id);
      $age = getAge($info->birthDate,$row->date_assessment);
      echo "$age->y.$age->m";
    }
    else{
      echo null;
    }
  }
  public function remove_student_score()
  {
    // code...
    if ($this->input->post()) {
      // code...
      $result = $this->assessment->remove_student_score($this->input->post());
      echo json_encode($result);
    }
  }



 } // end class 
?>