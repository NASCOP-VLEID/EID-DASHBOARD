<?php
defined("BASEPATH") or exit("No direct script access allowed");

/**
* 
*/
class Trends_model extends MY_Model
{
	
	function __construct()
	{
		parent:: __construct();;
	}

	function yearly_trends($county=NULL){

		if($county == NULL || $county == 48){
			$county = 0;
		}

		if ($county == 0) {
			$sql = "CALL `proc_get_eid_national_yearly_tests`();";
		} else {
			$sql = "CALL `proc_get_eid_yearly_tests`(" . $county . ");";
		}
		
		$result = $this->db->query($sql)->result_array();
		
		$year;
		$i = 0;
		$b = true;

		$data;

		$cur_year = date('Y');

		foreach ($result as $key => $value) {

			if((int) $value['year'] > $cur_year || (int) $value['year'] < 2008){

			}
			else{
				if($b){
					$b = false;
					$year = (int) $value['year'];
				}

				$y = (int) $value['year'];
				if($value['year'] != $year){
					$i++;
					$year--;
				}

				$month = (int) $value['month'];
				$month--;


				$data['test_trends'][$i]['name'] = $value['year'];
				$data['test_trends'][$i]['data'][$month] = (int) $value['tests'];

				$data['rejected_trends'][$i]['name'] = $value['year'];

				if($value['tests'] == 0){
					$data['rejected_trends'][$i]['data'][$month] = 0;
				}else{
					$data['rejected_trends'][$i]['data'][$month] = (int)
					($value['rejected'] / $value['tests'] * 100);
				}

				$data['positivity_trends'][$i]['name'] = $value['year'];

				if ($value['positive'] == 0){
					$data['positivity_trends'][$i]['data'][$month] = 0;
				}else{
					$data['positivity_trends'][$i]['data'][$month] = (int) 
					($value['positive'] / ($value['positive'] + $value['negative']) * 100 );
				}

				$data['infant_trends'][$i]['name'] = $value['year'];
				$data['infant_trends'][$i]['data'][$month] = (int) $value['infants'];

				$data['tat4_trends'][$i]['name'] = $value['year'];
				$data['tat4_trends'][$i]['data'][$month] = (int) $value['tat4'];
			}

		}
		

		return $data;
	}

	function yearly_summary($county=NULL){

		if($county == NULL || $county == 48){
			$county = 0;
		}
		
		if($county==0){
			$sql = "CALL `proc_get_eid_national_yearly_summary`();";
		} else {
			$sql = "CALL `proc_get_eid_yearly_summary`(" . $county . ");";
		}
		// echo "<pre>";print_r($sql);die();
		
		$result = $this->db->query($sql)->result_array();
		// echo "<pre>";print_r($result);die();
		$year = date("Y");
		$i = 0;

		$data['outcomes'][0]['name'] = "Redraws";
		$data['outcomes'][1]['name'] = "Positive";
		$data['outcomes'][2]['name'] = "Negative";
		$data['outcomes'][3]['name'] = "Positivity";

		$data['outcomes'][0]['color'] = '#52B3D9';
		$data['outcomes'][1]['color'] = '#E26A6A';
		$data['outcomes'][2]['color'] = '#257766';
		$data['outcomes'][3]['color'] = '#913D88';

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "column";
		$data['outcomes'][2]['type'] = "column";
		$data['outcomes'][3]['type'] = "spline";

		$data['outcomes'][0]['yAxis'] = 1;
		$data['outcomes'][1]['yAxis'] = 1;
		$data['outcomes'][2]['yAxis'] = 1;

		foreach ($result as $key => $value) {
			if($value['year'] != 2007){
				$data['categories'][$i] = $value['year'];
			
				$data['outcomes'][0]['data'][$i] = (int) $value['redraws'];
				$data['outcomes'][1]['data'][$i] = (int) $value['positive'];
				$data['outcomes'][2]['data'][$i] = (int) $value['negative'];
				$data['outcomes'][3]['data'][$i] = round(((int) $value['positive']*100)/((int) $value['negative']+(int) $value['positive']+(int) $value['redraws']),1);
				$i++;
			}
			
		}
		$data['outcomes'][0]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][1]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][2]['tooltip'] = array("valueSuffix" => ' ');
		$data['outcomes'][3]['tooltip'] = array("valueSuffix" => ' %');

		$data['title'] = "Outcomes (Initial PCR)";

		return $data;
	}

	function quarterly_trends($county=NULL){

		if($county == NULL || $county == 48){
			$county = 0;
		}

		if ($county == 0) {
			$sql = "CALL `proc_get_eid_national_yearly_tests`();";
		} else {
			$sql = "CALL `proc_get_eid_yearly_tests`(" . $county . ");";
		}
		
		$result = $this->db->query($sql)->result_array();
		
		$year;
		$i = 0;
		$b = true;
		$limit = 0;
		$quarter = 1;
		$month;

		$data;

		foreach ($result as $key => $value) {

			if($b){
				$b = false;
				$year = (int) $value['year'];
			}

			$y = (int) $value['year'];
			$name = $y . ' Q' . $quarter;
			if($value['year'] != $year){
				$year--;
				if($month != 2){
					$i++;
				}
			}

			$m = (int) $value['month'];
			$modulo = ($m % 3);

			$month = $modulo-1;

			if($modulo == 0){
				$month = 2;
			}			

			$data['test_trends'][$i]['name'] = $name;
			$data['test_trends'][$i]['data'][$month] = (int) $value['tests'];

			$data['rejected_trends'][$i]['name'] = $name;

			if($value['tests'] == 0){
				$data['rejected_trends'][$i]['data'][$month] = 0;
			}else{
				$data['rejected_trends'][$i]['data'][$month] = (int)
				($value['rejected'] / $value['tests'] * 100);
			}

			$data['positivity_trends'][$i]['name'] = $name;

			if ($value['positive'] == 0){
				$data['positivity_trends'][$i]['data'][$month] = 0;
			}else{
				$data['positivity_trends'][$i]['data'][$month] = (int) 
				($value['positive'] / ($value['positive'] + $value['negative']) * 100 );
			}

			$data['infant_trends'][$i]['name'] = $name;
			$data['infant_trends'][$i]['data'][$month] = (int) $value['infants'];

			$data['tat4_trends'][$i]['name'] = $name;
			$data['tat4_trends'][$i]['data'][$month] = (int) $value['tat4'];

			if($modulo == 0){
				$i++;
				$quarter++;
				$limit++;
			}
			if($quarter == 5){
				$quarter = 1;
			}
			if ($limit == 8) {
				break;
			}



		}
		

		return $data;
	}

	function quarterly_outcomes($county=NULL){

		if($county == NULL || $county == 48){
			$county = 0;
		}

		if ($county == 0) {
			$sql = "CALL `proc_get_eid_national_yearly_tests`();";
		} else {
			$sql = "CALL `proc_get_eid_yearly_tests`(" . $county . ");";
		}
		
		$result = $this->db->query($sql)->result_array();
		
		$year;
		$i = 4;
		$b = true;
		$limit = 0;
		$quarter = 1;

		$data;

		$data['outcomes'][0]['name'] = "Redraws";
		$data['outcomes'][1]['name'] = "Positive";
		$data['outcomes'][2]['name'] = "Negative";
		$data['outcomes'][3]['name'] = "Positivity";

		$data['outcomes'][0]['color'] = '#52B3D9';
		$data['outcomes'][1]['color'] = '#E26A6A';
		$data['outcomes'][2]['color'] = '#257766';
		$data['outcomes'][3]['color'] = '#913D88';

		$data['outcomes'][0]['type'] = "column";
		$data['outcomes'][1]['type'] = "column";
		$data['outcomes'][2]['type'] = "column";
		$data['outcomes'][3]['type'] = "spline";

		$data['outcomes'][0]['yAxis'] = 1;
		$data['outcomes'][1]['yAxis'] = 1;
		$data['outcomes'][2]['yAxis'] = 1;

		$data['title'] = "Outcomes";

		$data['categories'] = array_fill(0, 8, "Null");
		$data['outcomes'][0]['data'] = array_fill(0, 8, 0);
		$data['outcomes'][1]['data'] = array_fill(0, 8, 0);
		$data['outcomes'][2]['data'] = array_fill(0, 8, 0);
		$data['outcomes'][3]['data'] = array_fill(0, 8, 0);


		foreach ($result as $key => $value) {

			if($b){
				$b = false;
				$year = (int) $value['year'];
			}

			$y = (int) $value['year'];
			$name = $y . ' Q' . $quarter;
			if($value['year'] != $year){
				$year--;
			}

			$month = (int) $value['month'];
			$modulo = ($month % 3);

			$data['categories'][$i] = $name;

			$data['outcomes'][0]['data'][$i] += (int) $value['redraw'];
			$data['outcomes'][1]['data'][$i] += (int) $value['positive'];
			$data['outcomes'][2]['data'][$i] += (int) $value['negative'];
			$data['outcomes'][3]['data'][$i] += round(@(( (int) $value['positive']*100)/((int) $value['negative']+(int) $value['positive']+(int) $value['redraw'])),1);
			

			if($modulo == 0){
				$data['outcomes'][3]['data'][$i] /= 3;
				$i++;
				$quarter++;
				$limit++;	

				if ($limit == 8) {
					break;
				}

			}
			if($quarter == 5){
				$quarter = 1;
				$i = 0;
			}


		}

		return $data;

	}


}