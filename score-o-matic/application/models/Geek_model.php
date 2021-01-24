<?php
class Geek_model extends CI_Model {

        public function __construct()
        {
                $this->load->database();
        }


public function read_event()
	{
	$query = $this->db->get('event_name');
	$return = array();
	if($query->num_rows() > 0) 
		{
		foreach($query->result_array() as $row) 
			{
			$return[$row['yevent']] = $row['event_name'];
			}
		}

		return $return;
	}

public function refresh_board($round)
	{
		$querystr = "notify scoreboard, '" . $round . "'";
		$ins = array($round);
        	return $this->db->query($querystr, $ins);
	}

public function read_event_sel()
	{
	$this->db->select('yevent');
	$this->db->from('event_name');
	$this->db->where("sel_event = 1");
	$query = $this->db->get();
	$return = array();
	if($query->num_rows() > 0) 
		{
		foreach($query->result_array() as $row) 
			{
			$return = ($row['yevent']);
			}
		}

		return $return;
	}

public function update_event($yevent) 
	{
	//delete current selection
	$this->db->update('event_name', array('sel_event' => '0'));
	//update row with returned result
	$this->db->update('event_name', array('sel_event' => '1'), array('yevent' => $yevent['dropdown_single']));

}

//reads question numbers for round and returns as two dimensional array for dropdown
public function read_round($round)
	{
		$querystr = "select distinct question_no from scoreposs where round_no = '" . $round;
		$querystr .= "' and yevent in (select yevent from event_name where sel_event = 1) ";
		$querystr .= "order by question_no asc";
		$query = $this->db->query($querystr);
		$return = array();
		if($query->num_rows() > 0) 
		{
			foreach($query->result_array() as $row) 
				{
					$return[$row['question_no']] = $row['question_no'];
				}
		}
		return $return;
	}

//use for Jeopardy and Pyramid. Returns object
public function read_question($round, $sort = 'asc')
	{
		$querystr = "select question_no, ptsposs from scoreposs where round_no = '" . $round .= "' and ptsposs <> 0 and yevent in (select yevent from event_name where sel_event = 1) order by question_no " . $sort;
		return $this->db->query($querystr);
	}

//use for round 1 and dollar amount entry
public function read_team_all()
	{
		$querystr = "select team_no, teamname, dollarraised from teamreference where ";
		$querystr .= "yevent in (select yevent from event_name where sel_event = 1) ";
		$querystr .= "order by team_no asc";
		return $this->db->query($querystr);
	}
	
//use for team list update
public function read_team_update()
	{
		$querystr = "select team_no, teamname, dollarraised, member1, member2, workgroup1, workgroup2 from teamreference where ";
		$querystr .= "yevent in (select yevent from event_name where sel_event = 1) ";
		$querystr .= "order by team_no asc";
		$query = $this->db->query($querystr);
		return $query->result_array();
	}

//use for other than round 1. Returns object
public function read_teams($round, $limit, $order = '0')
	{
		$querystr = "select tr.team_no, tr.teamname, rr.rnk from teamreference tr, 
		roundresult rr where tr.team_no = rr.team_no and tr.yevent = rr.yevent and tr.yevent in 		(select yevent from event_name where sel_event = 1) and rr.round_no = '" . $round . "'
		and rr.rnk <= " . $limit;
		if ($order == '0') {
			$querystr .= " order by tr.team_no asc";
		} else {
			$querystr .= " order by rr.rnk, tr.team_no asc";
		}
		return $this->db->query($querystr);
	}

//use for media day round 1 or final jeopardy
public function update_roundj($round)
	{
		$question_no = $this->input->post('question_no');
		$yevent = $this->input->post('event');
		
		for ($i=1; $i<=45; $i++)
		{
			if ($this->input->post('t'. $i))
			{
				$date = date('Y-m-d H:i:s');
				$cols = array('yevent','team_no','round_no','question_no','finaljep','updatetime');
				$ins = array($yevent, $i, $round, $question_no, $this->input->post('t' . $i),
					$date, $this->input->post('t' . $i), $date
				);
				//upsert used for RI and if we need to change a score
				$querystr = "INSERT INTO scoring (" . implode(', ',$cols) . ") values 
					(?, ?, ?, ?, ?, ?) ON CONFLICT(yevent, team_no, round_no, question_no)
				       	DO UPDATE SET finaljep = ?, updatetime = ?";
				$query = $this->db->query($querystr, $ins);
			}
		}
		return 1;
	}

//use for round 1 emp, round 2 media. Not Pyramid or Jep.
public function update_round($round)
	{
		$question_no = $this->input->post('question_no');
		$yevent = $this->input->post('event');
		
		for ($i=1; $i<=45; $i++)
		{
			if ($this->input->post('t'. $i)) //&& ($this->input->post('t'. $i) != ''))
			{
				$date = date('Y-m-d H:i:s');
				$cols = array('yevent','team_no','round_no','question_no','point_amt','updatetime');
				$ins = array($yevent, $i, $round, $question_no, 1,
					$date, 1, $date
				);
				//upsert used for RI and if we need to change a score
				$querystr = "INSERT INTO scoring (" . implode(', ',$cols) . ") values 
					(?, ?, ?, ?, ?, ?) ON CONFLICT(yevent, team_no, round_no, question_no)
				       	DO UPDATE SET point_amt = ?, updatetime = ?";
				$query = $this->db->query($querystr, $ins);
			}
		}
		return $ins;
	}

//use for pyramid. Similar but slightly different from round 1/2 above.
public function update_roundp($round)
		{
			$team_no = $this->input->post('teamdrop');
			$yevent = $this->input->post('event');
		
			$querystr = "select question_no from scoreposs where round_no = '$round' and yevent in (select yevent from event_name where sel_event = 1) order by question_no asc ";
			$question =  $this->db->query($querystr);
			foreach($question->result() as $qno)
			{
				if ($this->input->post('q'. $qno->question_no)) 
				{
					$date = date('Y-m-d H:i:s');
					$cols = array('yevent','team_no','round_no','question_no','point_amt','updatetime');
					$ins = array($yevent, $team_no, $round, $qno->question_no, 1, $date, 1, $date);
					//upsert used for RI and if we need to change a score
					$sql = "INSERT INTO scoring (" . implode(', ',$cols) . ") values 
					(?, ?, ?, ?, ?, ?) ON CONFLICT(yevent, team_no, round_no, question_no)
				       		DO UPDATE SET point_amt = ?, updatetime = ?";
					$query = $this->db->query($sql, $ins);
				}
			}
			return 1;
		}

//use for round 3 - standard Jeopardy
public function update_round3($round)
	{
		$question_no = $this->input->post('question_no');
		$yevent = $this->input->post('event');
		$return="";
		//process daily double first then team numbers. note team are variable
		if ($this->input->post('dailydblteam') != '') {
			$date = date('Y-m-d H:i:s');
			if (is_numeric($this->input->post('dailydblwager'))) {
				$dailydblwager = $this->input->post('dailydblwager');
			} else {
				$dailydblwager = 'null';
			}
			$cols = array('yevent','team_no','round_no','question_no','finaljep','updatetime');
			$ins = array($yevent, $this->input->post('dailydblteam'), $round, $question_no, $dailydblwager, 
				$date, $dailydblwager, $date);
			//upsert used for RI and if we need to change a score
			$querystr = "INSERT INTO scoring (" . implode(', ',$cols) . ") values 
				(?, ?, ?, ?, ?, ?) ON CONFLICT(yevent, team_no, round_no, question_no)
			       	DO UPDATE SET finaljep = ?, updatetime = ?";
			$query = $this->db->query($querystr, $ins);
		}
		else 
		{
			$colname = array('round3neg','point_amt','round3neg');
			for ($i=0; $i<3; $i++)
			{
				if ($this->input->post('t'. $i)) //&& ($this->input->post('t'. $i) != ''))
				{
					$date = date('Y-m-d H:i:s');
					$team_no = $this->input->post('tr' . $i);
					$col_no = $this->input->post('t' . $i);
					
					//decode the point value to column name
					$cols = array('yevent','team_no','round_no','question_no',$colname[$col_no],'updatetime');
					$ins = array($yevent, $team_no, $round, $question_no, 1, $date, $date);
					//upsert used for RI and if we need to change a score
					$querystr = "INSERT INTO scoring (" . implode(', ',$cols) . ") values 
						(?, ?, ?, ?, ?, ?) ON CONFLICT(yevent, team_no, round_no, question_no)
			       			DO UPDATE SET $colname[$col_no] = 1, updatetime = ?";
					$query = $this->db->query($querystr, $ins);
					$return .= $colname[$col_no] . "/";
				}
			}
		}
		return $return;
	}
		
//use this one for regular round 1 or media round 2
public function finalize_round($round)
	{
		$querystr = "WITH res AS (SELECT ts.yevent, ts.round_no, ts.team_no, ts.ptswithbonus, 
		rank() over (ORDER BY ts.ptswithbonus DESC ) AS rnk FROM 
		totalscore ts, event_name en WHERE ts.round_no =  $round  AND ts.yevent 
		 = en.yevent AND en.sel_event = 1) INSERT INTO roundresult
		SELECT res.yevent, res.round_no, res.team_no, res.ptswithbonus, res.rnk
		FROM res ON CONFLICT (yevent, round_no, team_no) DO UPDATE SET
		ptswithbonus = excluded.ptswithbonus, rnk = excluded.rnk";
		$query = $this->db->query($querystr);
		return 1;	
	}

public function show_scoresum($round, $final)
	{
		if ($final == 'Y') {
			$querystr = "select team_no, ptswithbonus, rnk from roundresult where ";
			$querystr .= "yevent in (select yevent from event_name where sel_event = 1) ";
			$querystr .= "and round_no = '" . $round . "' order by rnk asc, team_no asc";
		}
		else
		{
			$querystr = "select team_no, ptswithbonus, null as rnk from totalscore where ";
			$querystr .= "yevent in (select yevent from event_name where sel_event = 1) ";
			$querystr .= "and round_no = '" . $round . "' order by ptswithbonus desc, team_no asc";
		}
		return $this->db->query($querystr);
	}

//use for rounds 2-4 scoreboards only, not round 1. requires final
	public function show_scorebd_234($round, $limit)
		{
			$querystr = "select tr.team_no, tr.teamname, ts.ptswithbonus, rr.rnk as rnk from teamreference tr inner join roundresult rr on tr.team_no = rr.team_no and tr.yevent = rr.yevent and rr.round_no = (". $round . "-1) left outer join totalscore ts on rr.team_no = ts.team_no and rr.yevent = ts.yevent and rr.round_no = (ts.round_no-1) where tr.yevent in (select yevent from event_name where sel_event = 1) and rr.rnk <= " . $limit . " order by rr.rnk asc, ts.team_no asc";
			return $this->db->query($querystr);
		}

//returns row count of scores. for dynamic update 

//returns current round 
	public function scorebd_round()
		{
			$querystr = "select max(round_no) round_no from (select coalesce(rr.round_no, 1) round_no from roundresult rr where rr.yevent in (select yevent from event_name where sel_event = 1) union all select coalesce(ts.round_no, 1) round_no from totalscore ts where ts.yevent in (select yevent from event_name where sel_event = 1)) a; ";
			$query = $this->db->query($querystr);
			return $query->row_array();
		}

//use for round 1. designed to support multiples if needed but query is hard coded for now
	public function show_scorebd_1($round)
		{
			$querystr = "select ts.team_no, tr.teamname, min(case when sc.question_no = 1 then coalesce(sc.point_amt,0) * sp.ptsposs else null end) q1, min(case when sc.question_no = 2 then coalesce(sc.point_amt,0) * sp.ptsposs else null end) q2,
 min(case when sc.question_no = 3 then coalesce(sc.point_amt,0) * sp.ptsposs else null end) q3, min(case when sc.question_no = 4 then coalesce(sc.point_amt,0) * sp.ptsposs else null end) q4,
 min(case when sc.question_no = 5 then coalesce(sc.point_amt,0) * sp.ptsposs else null end) q5,
 min(case when sc.question_no = 6 then coalesce(sc.point_amt,0) * sp.ptsposs else null end) q6,
 min(case when sc.question_no = 7 then coalesce(sc.point_amt,0) * sp.ptsposs else null end) q7,
 min(case when sc.question_no = 8 then coalesce(sc.point_amt,0) * sp.ptsposs else null end) q8,
 min(case when sc.question_no = 9 then coalesce(sc.point_amt,0) * sp.ptsposs else null end) q9,
 min(case when sc.question_no = 10 then coalesce(sc.point_amt,0) * sp.ptsposs else null end) q10,
 min(case when sc.question_no = 11 then coalesce(sc.point_amt,0) * sp.ptsposs else null end) q11,
 min(case when sc.question_no = 12 then coalesce(sc.point_amt,0) * sp.ptsposs else null end) q12,
 min(case when sc.question_no = 13 then coalesce(sc.point_amt,0) * sp.ptsposs else null end) q13,
 min(case when sc.question_no = 14 then coalesce(sc.point_amt,0) * sp.ptsposs else null end) q14,
 min(case when sc.question_no = 15 then coalesce(sc.point_amt,0) * sp.ptsposs else null end) q15,
 min(case when sc.question_no = 16 then coalesce(sc.point_amt,0) * sp.ptsposs else null end) q16,
 min(case when sc.question_no = 17 then coalesce(sc.point_amt,0) * sp.ptsposs else null end) q17,
 min(ts.bonus) bonus,
 min(cast(ts.ptswithbonus as integer)) ptswithbonus 
from totalscore ts
inner join teamreference tr
on ts.yevent = tr.yevent
and ts.team_no = tr.team_no
left outer join scoring sc
on ts.yevent = sc.yevent
and ts.team_no = sc.team_no
and ts.round_no = sc.round_no
left outer join scoreposs sp
on sc.yevent = sp.yevent
and sc.round_no = sp.round_no
and sc.question_no = sp.question_no
where ts.yevent in (select yevent from event_name where sel_event = 1)
group by ts.team_no, tr.teamname
order by ts.team_no, tr.teamname;";
			return $this->db->query($querystr);
		}
		
	public function team_upd() {
		
		$yevent = $this->input->post('event');
		$team_no[] = $this->input->post('team_no');
		$teamname[] = $this->input->post('teamname');
		$member1[] = $this->input->post('member1');
		$member2[] = $this->input->post('member2');
		$dollarraised[] = $this->input->post('dollarraised');
		$workgroup1[] = $this->input->post('workgroup1');
		$workgroup2[] = $this->input->post('workgroup2');
		$dr = set_value('dollarraised[]');
		$m1 = set_value('member1[]');
		$m2 = set_value('member2[]');
		$w1 = set_value('workgroup1[]');
		$w2 = set_value('workgroup2[]');
		$tm = set_value('teamname[]');
			
		$j = 0;
		foreach($tm as $team) {
			if ($team <> '') {
				$ins = array( $yevent, $j + 1, $team, $m1[$j], $m2[$j], $dr[$j], $w1[$j], $w2[$j], 
					$team, $dr[$j], $m1[$j], $m2[$j], $w1[$j], $w2[$j]);
				//upsert used for RI and if we need to change a dollar amt
				//Must be manually built now
				$querystr = "INSERT INTO teamreference values (?, ?, ?, ?, ?, ?, ?, ?) ON CONFLICT
					(yevent,team_no) DO UPDATE SET teamname = ?, dollarraised = ?, member1 = 
					?, member2 = ?, workgroup1 = ?, workgroup2 = ?";
				//$this->db->on_duplicate('teamreference', $data, array('yevent','team_no'));
				$query=$this->db->query($querystr, $ins);	
				$j++;
			}
			else
			{
				$this->db->where('team_no', $j);
				$this->db->where('yevent', $yevent);
				$this->db->delete('teamreference');
				$j++;
			}	
		}
		
		//delete remaining rows if we shrink
		$delstate = "team_no > " . $j . " and yevent = " . $this->db->escape($yevent) ;
		$this->db->delete('teamreference', $delstate);
		
		return $j;
		
	}
	public function final_page() {
		$querystr = "select rr.rnk, rr.round_no, rr.team_no, tr.teamname, tr.member1, tr.member2 
from roundresult rr inner join teamreference tr on rr.yevent = tr.yevent and rr.team_no = tr.team_no 
where rr.round_no = 3 and rr.yevent in (select yevent from event_name where sel_event = 1)
union all 
select rr.rnk, rr.round_no, rr.team_no, tr.teamname, tr.member1, tr.member2 
from roundresult rr inner join teamreference tr on rr.yevent = tr.yevent and rr.team_no = tr.team_no 
where rr.round_no = 2 and rnk > 3
and rr.yevent in (select yevent from event_name where sel_event = 1)
union all
select rr.rnk, rr.round_no, rr.team_no, tr.teamname, tr.member1, tr.member2 
from roundresult rr inner join teamreference tr on rr.yevent = tr.yevent and rr.team_no = tr.team_no 
where rr.round_no = 1 and rnk > 6
and rr.yevent in (select yevent from event_name where sel_event = 1)
order by rnk;";
			return $this->db->query($querystr);
	}

}
