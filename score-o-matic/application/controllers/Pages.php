<?php
class Pages extends CI_Controller {
        public function __construct()
        {
                parent::__construct();
                $this->load->model('Geek_model');
                $this->load->helper('url_helper');
        }



public function view($page = 'home')
{
        if ( ! file_exists(APPPATH.'views/pages/'.$page.'.php'))
        {
                // Whoops, we don't have a page for that!
                show_404();
        }

        $data['title'] = ucfirst($page); // Capitalize the first letter

	//Display
        $this->load->view('templates/header', $data);
        $this->load->view('pages/'.$page, $data);
        $this->load->view('templates/footer', $data);
}
public function setevent() {
	//converting array into a string
	$this->load->library('form_validation');
	$data['title'] = 'Set the current event';
	
	$this->form_validation->set_rules('yevent','Event','required');
	
	$data['dropdown_single'] = $this->input->post('yevent');

	if ($this->form_validation->run() === FALSE)
	    {
	    	$data['options'] = $this->Geek_model->read_event();
	    	$data['selected'] = $this->Geek_model->read_event_sel();
	        $this->load->view('templates/header', $data);
	        $this->load->view('pages/setevent', $data);
	        $this->load->view('templates/footer');

	    }
	    else
	    {
	    	$this->Geek_model->update_event($data);
		$data['options'] = $this->Geek_model->read_event();
	    	$data['selected'] = $this->Geek_model->read_event_sel();
		$data['result'] = '1';
	        $this->load->view('templates/header', $data);
	        $this->load->view('pages/setevent', $data);
	        $this->load->view('templates/footer');
	    }

}

public function media_1() {
	//converting array into a string
	$this->load->library('form_validation');
	$data['title'] = 'Round 1';
	
	$this->form_validation->set_rules('question_no','Question','required');
	
	$data['question_nbr'] = $this->input->post('question_no');

	if ($this->form_validation->run() === FALSE)
	    {
		$data['rtn'] = 'Not submitted';
	    }
	    else
	    	{
    		$data['rtn'] = $this->Geek_model->update_roundj('1');
		}

		$data['options'] = $this->Geek_model->read_round(1);
	    	$data['event'] = $this->Geek_model->read_event_sel();
		$data['teams'] = $this->Geek_model->read_team_all();
		$data['scores'] = $this->Geek_model->show_scoresum('1','N');
	        $this->load->view('templates/header', $data);
	        $this->load->view('pages/media_1', $data);
	        $this->load->view('templates/footer');
}

public function media_2() {
	//converting array into a string
	$this->load->library('form_validation');
	$data['title'] = 'Round 2';
	
	$this->form_validation->set_rules('question_no','Question','required');
	
	$data['question_nbr'] = $this->input->post('question_no');

	if ($this->form_validation->run() === FALSE)
	    {
		$data['rtn'] = 'Not submitted';
	    }
	    else
	    {
		$data['rtn'] = $this->Geek_model->update_round('2');
	    }

	$data['options'] = $this->Geek_model->read_round('2');
	$data['event'] = $this->Geek_model->read_event_sel();
	$data['teams'] = $this->Geek_model->read_teams('1', '10', '0');
	$data['scores'] = $this->Geek_model->show_scoresum('2','N');
	$this->load->view('templates/header', $data);
	$this->load->view('pages/media_2', $data);
	$this->load->view('templates/footer');
}

public function media_3() {
	//converting array into a string
	$this->load->library('form_validation');
	$data['title'] = 'Round 3';

	$this->form_validation->set_rules('question_no','Question','required');
	//not sure if this works with a blank value, may have to move to model
	
	if ($this->form_validation->run() === FALSE)
	    {
		$data['rtn'] = 'Not submitted';
	    }
	    else
	    {
    		$data['rtn'] = $this->Geek_model->update_round3('3');
	    }

	$data['questions'] = $this->Geek_model->read_question('3');
	$data['event'] = $this->Geek_model->read_event_sel();
	$data['teams'] = $this->Geek_model->read_teams('2', '3', '1');
	$data['scores'] = $this->Geek_model->show_scoresum('3','N');
	$this->load->view('templates/header', $data);
	$this->load->view('pages/media_3', $data);
	$this->load->view('templates/footer');
}

public function media_4() {
	//converting array into a string
	$this->load->library('form_validation');
	$data['title'] = 'Round 4';

	$this->form_validation->set_rules('teamdrop','Team No','required');
	//not sure if this works with a blank value, may have to move to model
	
	if ($this->form_validation->run() === FALSE)
	    {
		$data['rtn'] = 'Not submitted';
	    }
	    else
	    {
    		$data['rtn'] = $this->Geek_model->update_roundp('4');
	    }

	$data['questions'] = $this->Geek_model->read_question('4', 'asc');
	$data['event'] = $this->Geek_model->read_event_sel();
	$data['teams'] = $this->Geek_model->read_teams('3', '2', '0');
	$data['scores'] = $this->Geek_model->show_scoresum('4','N');
	$this->load->view('templates/header', $data);
	$this->load->view('pages/media_4', $data);
	$this->load->view('templates/footer');
}

public function emp_1() {
	//converting array into a string
	$this->load->library('form_validation');
	$data['title'] = 'Round 1';
	
	$this->form_validation->set_rules('question_no','Question','required');
	
	$data['question_nbr'] = $this->input->post('question_no');

	if ($this->form_validation->run() === FALSE)
	    {
		$data['rtn'] = 'Not submitted';
	    }
	    else
	    {
		$data['rtn'] = $this->Geek_model->update_round('1');
	    }

	$data['options'] = $this->Geek_model->read_round('1');
	$data['event'] = $this->Geek_model->read_event_sel();
	$data['teams'] = $this->Geek_model->read_team_all();
	$data['scores'] = $this->Geek_model->show_scoresum('1','N');
	$this->load->view('templates/header', $data);
	$this->load->view('pages/emp_1', $data);
	$this->load->view('templates/footer');
}

public function emp_2() {
	//converting array into a string
	$this->load->library('form_validation');
	$data['title'] = 'Pyramid';

	$this->form_validation->set_rules('teamdrop','Team No','required');
	//not sure if this works with a blank value, may have to move to model
	
	if ($this->form_validation->run() === FALSE)
	    {
		$data['rtn'] = 'Not submitted';
	    }
	    else
	    {
    		$data['rtn'] = $this->Geek_model->update_roundp('2');
	    }

	$data['questions'] = $this->Geek_model->read_question('2', 'asc');
	$data['event'] = $this->Geek_model->read_event_sel();
	$data['teams'] = $this->Geek_model->read_teams('1', '6', '0');
	$data['scores'] = $this->Geek_model->show_scoresum('2','N');
	$this->load->view('templates/header', $data);
	$this->load->view('pages/emp_2', $data);
	$this->load->view('templates/footer');
}

public function emp_3() {
	//converting array into a string
	$this->load->library('form_validation');
	$data['title'] = 'Jeopardy';

	$this->form_validation->set_rules('question_no','Question','required');
	//not sure if this works with a blank value, may have to move to model
	
	if ($this->form_validation->run() === FALSE)
	    {
		$data['rtn'] = 'Not submitted';
	    }
	    else
	    {
    		$data['rtn'] = $this->Geek_model->update_round3('3');
	    }

	$data['questions'] = $this->Geek_model->read_question('3');
	$data['event'] = $this->Geek_model->read_event_sel();
	$data['teams'] = $this->Geek_model->read_teams('2', '3', '1');
	$data['scores'] = $this->Geek_model->show_scoresum('3','N');
	$this->load->view('templates/header', $data);
	$this->load->view('pages/emp_3', $data);
	$this->load->view('templates/footer');
}

public function emp_4() {
	//converting array into a string
	$data['title'] = 'Final Jeopardy';
	
	$data['question_nbr'] = $this->input->post('question_no');

	if ($this->input->post('question_no'))
	    {
    		$data['rtn'] = $this->Geek_model->update_roundj('3');
	    }
	    else
	    	{
		$data['rtn'] = 'Not submitted';
		}

	    	$data['event'] = $this->Geek_model->read_event_sel();
		$data['teams'] = $this->Geek_model->read_teams('2','3','1');
		$data['scores'] = $this->Geek_model->show_scorebd_234('3','3');
	        $this->load->view('templates/header', $data);
	        $this->load->view('pages/emp_4', $data);
	        $this->load->view('templates/footer');
}

public function finalize_round() {
	//finalize round 1 and write to roundresult table
        if($this->input->post()){
		$round = $this->input->post('round');
		$data['title'] = 'Round '.$round . ' - Finalized';
	
		$data['rtn'] = $this->Geek_model->finalize_round($round);
		$data['event'] = $this->input->post('event');
		$data['next_page'] = $this->input->post('next_page');
		$data['scores'] = $this->Geek_model->show_scoresum($round, 'Y');
		$this->load->view('templates/header', $data);
		$this->load->view('pages/finalize_round', $data);
		$this->load->view('templates/footer');          
            }
	    else
	    {
                // send them back to the form
                redirect('index.php');
            }
        }

public function score_1() {
	$data['title'] = '';
	$data['scorebd'] = $this->Geek_model->scorebd_round();
	$data['rnd'] = $data['scorebd']['round_no'];
	$this->load->view('templates/header_s');

	if ($data['rnd'] == 1) {
		$data['scores'] = $this->Geek_model->show_scorebd_1($data['rnd']);
		$this->load->view('pages/score_1', $data);
	} 
	else {
		if ($data['rnd'] == 2) {
			$data['scores'] = $this->Geek_model->show_scorebd_234($data['rnd'],'6');
			$this->load->view('pages/score_2', $data);
		}
		 else {
			$data['scores'] = $this->Geek_model->show_scorebd_234($data['rnd'],'3');
			$this->load->view('pages/score_3', $data);
		}
	}
	$this->load->view('templates/footer_s');
	}


public function team_update() {
	//converting array into a string
	$data['title'] = 'Team Update';
	
	if ($this->input->post('team_no'))
	    {
    		$data['rtn'] = $this->Geek_model->team_upd();
	    }
	    else
	    	{
		$data['rtn'] = 'Not submitted';
		}

	    	$data['event'] = $this->Geek_model->read_event_sel();
		$data['teams'] = $this->Geek_model->read_team_update();
	        $this->load->view('templates/header', $data);
	        $this->load->view('pages/team_update', $data);
	        $this->load->view('templates/footer');
	}

public function final_result() {
	//converting array into a string
	$data['title'] = 'Final Results';
	$data['event'] = $this->Geek_model->read_event_sel();
	$data['teams'] = $this->Geek_model->final_page();
	$this->load->view('templates/header_s');
	$this->load->view('pages/final_result', $data);
	$this->load->view('templates/footer_s');
	}

public function team_info() {
	$data['title'] = 'Team Information';
	$data['event'] = $this->Geek_model->read_event_sel();
	$data['teams'] = $this->Geek_model->read_team_update();
	$this->load->view('templates/header_s');
	$this->load->view('pages/team_info', $data);
	$this->load->view('templates/footer_s');
	}


public function refresh_scoreboard(){
        $data['round'] = $this->input->post('round');

        if ($this->input->post('round'))
            {
                $data['rtn'] = $this->Geek_model->refresh_board($data['round']);
            }
            else
            {
                $data['rtn'] = 'Not submitted';
            }

	echo json_encode($data);
	}

}
