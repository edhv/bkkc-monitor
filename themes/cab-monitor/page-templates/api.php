<?php
/*
Template Name: Cab-Api
*/
global $wpdb;
global $cab_functions;




//function

switch ($_GET['action']) {



	case 'get_all_organisations_naw':

		$target_organisations = $cab_functions->get_all_organisations();

		$data_array = array();

		foreach ($target_organisations as $target_organisation) {

			$organisation_data = $cab_functions->get_organisation_data($target_organisation->ID);


			array_push($data_array, array(
				'name' => $organisation_data['general']['name'],
	            'email' => $organisation_data['general']['email'],
	            'telephone' => $organisation_data['general']['telephone'],
	            // 'geo' => $organisation_data['general']['geo'],
	            // 'regio' => $organisation_data['general']['regio'],
	            'website' => $organisation_data['general']['website'],
	            'twitter' => $organisation_data['general']['twitter'],
	            'facebook' => $organisation_data['general']['facebook'],
	            'director_name' => $organisation_data['general']['director_name'],
	            'director_function' => $organisation_data['general']['director_function'],
	            'director_email' => $organisation_data['general']['director_email'],
	            'administrator_name' => $organisation_data['general']['administrator_name'],
	            'administrator_email' => $organisation_data['general']['administrator_email'],
	            'visit_address_city' => $organisation_data['general']['visit_address_city'],
	            'visit_address_zipcode'  => $organisation_data['general']['visit_address_zipcode'],
	            'visit_address_street' => $organisation_data['general']['visit_address_street'],
	            'visit_address_nr' => $organisation_data['general']['visit_address_nr'],
	            'post_address_city' => $organisation_data['general']['post_address_city'],
	            'post_address_zipcode' => $organisation_data['general']['post_address_zipcode'],
	            'post_address_street' => $organisation_data['general']['post_address_street'],
	            'post_address_nr' => $organisation_data['general']['post_address_nr']
			));

		}

				$cab_functions->show_array_as_table($data_array);


		//print_r($target_organisations);
	break;

	case 'get_discipline_table':


		$categorieen = '';
		$sectoren = '';
		$ketens = '';

		// Create table array
		$target_organisations = $cab_functions->get_all_organisations();

		$discipline_array = array();

		foreach ($target_organisations as $organisation) {



			$organisation_data = $cab_functions->get_organisation_data($organisation->ID);
			# code...
			//print_r($organisation_data['general']['name']);


			// Check which period is available
			if (isset($organisation_data['periodical'])) {

				foreach ($organisation_data['periodical'] as $key => $period) {
					//print_r($period);
					array_push($discipline_array, array(
						"id" => $organisation->ID,
						"name" => $organisation_data['general']['name'],
						"period" => $period['period'],
						"soort_instelling" => implode(",", $period['data']['kernactiviteiten']['type']),
						"disciplines" => implode(",", $period['data']['kernactiviteiten']['discipline']),
						"ketens" => implode(",", $period['data']['kernactiviteiten']['keten'])
					));
				}
					# code...
			} else {
				array_push($discipline_array, array(
					"id" => $organisation->ID,
					"name" => $organisation_data['general']['name'],
					"period" => '',
					"soort_instelling" => '',
					"disciplines" => '',
					"ketens" => ''
				));
			}

				// if (isset($organisation_data['periodical'][3])) {

				// 	$period = 3;

				// } else if (isset($organisation_data['periodical'][2])) {
				// 	$period = 2;
				// } else {
				// 	$period = false;
				// }
		}

			// if ($period) {
			// 	print_r($organisation_data['periodical'][$period]);
			// 	$categorieen = 'a';
			// $sectoren = 'a';
			// $ketens = 'a';
			// }


		$cab_functions->show_array_as_table($discipline_array);
		//print_r($discipline_array);

	break;

	case 'get_geo_coords':

	/*
	<?php
$conn = mysql_connect("localhost", "user", "passwd");
if (!$conn) {
  die("Could not connect: ". mysql_error());
}
if (!mysql_select_db("database", $conn)) {
  die("Could select db: ". mysql_error());
}

// YOUR DOMAIN API KEY
$api_key = "ABCDEFGHIJK";

$query = "select * from buildings where building_latitude is null and building_longitude is null order by building_id";
$result = mysql_query($query);

while ($row = mysql_fetch_array($result)) {

  // SET ADDRESS
  $address = urlencode($row["building_street"]." ".$row["building_street_nr"]." ".$row["building_city"]." Czech republic");

  // URL TO HTTP REQUEST
  $link = "http://maps.google.com/maps/geo?q=".$address."&key=".$api_key."&sensor=false&output=csv&oe=utf8";

  // WE GET FILE CONTENT
  $page = file_get_contents($link);

  // WE OBTAIN DATA FROM GIVEN CSV
  list($status, $accuracy, $latitude, $longitude) = explode(",", $page);

  // IF EVERYTHING OK AND ACCURANCY GREATER THEN 3 WE SAVE COORDINATES
  if (($status == 200) and ($accuracy>=4)) {
    $query_edit = "update buildings set building_latitude = '".$latitude."',
    building_longitude = '".$longitude."'
    where building_id = '".$row["building_id"]."'";
    $result_edit = mysql_query($query_edit);
    echo $row["building_id"]." - OK<br />";
  } else {
    echo $row["building_id"]." - ERROR<br />";
  }

  // TIMER BECAUSE GOOGLE DOESN'T LIKE TO BE QUERIED IN SHORT TIME
  sleep(3);
}

mysql_close($conn);
?>*/

	break;


	case 'set_acf_field':

		// echo "Aa";
		// $field = get_field_object("field_52739cf1da013");
		// print_r($field);

		//$target_organisation = 416;
		$acf_fields = array(
			"field_52739cf1da013", //field_52739cf1da013
			"field_52739d74da014", //field_52739d74da014
			"field_52739e00a4ee1" //field_52739e00a4ee1
		);



		// get all organisation
		$target_organisations = $cab_functions->get_all_organisations();


		foreach ($target_organisations as $target_organisation) {


				$organisation_data = $cab_functions->get_organisation_data($target_organisation->ID);

				echo $target_organisation->ID." ";

				// If the organisation has kernactiviteiten data
				if (isset($organisation_data['periodical'])) {


					if (isset($organisation_data['periodical'][3])) {

						$period = 3;

					} else if (isset($organisation_data['periodical'][2])) {
						$period = 2;
					} else {
						$period = false;
					}

					if ($period) {

						if (isset($organisation_data['periodical'][$period]['data']['kernactiviteiten'])) {
							// Walk through each field to check if the acf is already set
							foreach ($acf_fields as $field) {
								$acf_object = get_field_object($field, $target_organisation->ID);

								$field_type = explode("_", $acf_object['name']);
								$field_type = $field_type[1];

								if (!is_array($acf_object['value'])) {

									//print_r($organisation_data['periodical'][3]['data']['kernactiviteiten'][$field_type]);
									print_r($organisation_data['periodical'][$period]['data']['kernactiviteiten'][$field_type]);
									update_field( $field, $organisation_data['periodical'][$period]['data']['kernactiviteiten'][$field_type] , $target_organisation->ID );
									// $value = $acf_object['value'];
									// print_r($value);

									echo "acf-updated";

								} else {
									echo "acf already set";
								}

								# code...
							}
						} else {
							echo "no period ".$period;
						}
					} else {
						echo "no periods";
					}
				} else {
					echo "no core activities";
				}

				echo "\n";



		}


			// $acf_object = get_field_object("field_52739cf1da013", $target_organisation);
			// 		print_r($acf_object);





	break;

	case 'get_cab_organisations':
header ("Content-Type:text/xml");
		//print_r($cab_functions->get_all_bloementuin_organisations_list());
		print_r($cab_functions->get_bloementuin_organisations_export( "xml"));
	//print_r($cab_functions->get_organisation_data(527));



	break;


	case 'get_periods':
		$return = array("periods");
		foreach ($cab_api->get_all_periods() as $key => $period) {
			$return['periods'][$key] = array(
		 			"id" => $key,
		 			"label" => substr($period, 0,4)
		// 			"description" => $period->description
				);
		}
		echo json_encode($return);
	break;


	case 'get_auto_save':
		$period_id = $_GET['period_id'];
		$organisation_id = $_GET['organisation_id'];

		$auto_save = $cab_api->get_auto_save($_GET['organisation_id'], $_GET['period_id']);
		echo json_encode($auto_save['auto_save']);

	break;


	case 'test_mail':

		$cab_api->test_mail();


	break;


	case 'get_organisation_password':

		$cab_api->get_organisation_passwords();

	break;

	case 'remove_autosave':
		$period_id = $_GET['period_id'];
		$organisation_id = $_GET['organisation_id'];
		return $cab_api->remove_autosave($_GET['organisation_id'], $_GET['period_id']);
	break;

	case 'unlock_form':
		$period_id = $_GET['period_id'];
		$organisation_id = $_GET['organisation_id'];
		return $cab_api->unlock_form($_GET['organisation_id'], $_GET['period_id']);
	break;

	case 'lock_form':
		$period_id = $_GET['period_id'];
		$organisation_id = $_GET['organisation_id'];
		return $cab_api->lock_form($_GET['organisation_id'], $_GET['period_id']);
	break;

	case 'set_auto_save':
	    array_walk_recursive($_POST,'ref_stripslashes');

		$cab_api->form_auto_save($_POST['auto_save']['organisation_id'],$_POST['auto_save']['period_id'],$_POST['auto_save']['data']);


	// print_r($_POST);
	//     print_r($_POST);

	    //$data = json_decode($_POST['auto_save']['data'], true);
	    //$serialized = serialize($data);
	  	//print_r(unserialize($serialized));
	    //print_r($serialized);
		//print_r($_POST['auto_save']['data']);
		//print_r(json_decode($_POST['auto_save']['data']));
		//return $cab_api->form_auto_save($_POST['auto_save']['organisation_id'],$_POST['auto_save']['period_id'],$_POST['auto_save']['data']);
		//return $cab_functions->form_activity_auto_save($_POST['auto_save']['organisation_id'],$_POST['auto_save']['period_id'],$_POST['auto_save']['data']);


	break;



	case 'report_global_subsidy':
		$cab_api->report_global_subsidy();
	break;

	case 'report_global_omzet':
		$cab_api->report_global_omzet();
	break;

	case 'report_global_organisatie':
		$cab_api->report_global_organisatie();
	break;

	case 'report_global_scholing':
		$cab_api->report_global_scholing();
	break;

	case 'report_global_marketing':
		$cab_api->report_global_marketing();
	break;

	case 'report_global_eigen_inkomsten':
		$cab_api->report_global_eigen_inkomsten();
	break;

	case 'report_global_geldstromen':
		$cab_api->report_global_geldstromen();
	break;


	case 'report_global_media':
		$cab_api->report_global_media();
	break;


	case 'report_global_activiteiten':
		$cab_api->report_global_activiteiten();
	break;

	case 'report_global_nevenactiviteiten':
		$cab_api->report_global_nevenactiviteiten();
	break;

	case 'report_global_spreiding_activiteiten':
		$cab_api->report_global_spreiding_activiteiten();
	break;

	case 'report_global_vertoningen':
		$cab_api->report_global_vertoningen();
	break;

	case 'report_global_bezoekers':
		$cab_api->report_global_bezoekers();
	break;

	case 'export_all_vragenlijst_organisations':
		$cab_api->export_all_vragenlijst_organisations();
	break;


	case 'export_all_organisations':
		$cab_api->export_all_organisations();
	break;

	case 'export_all_organisations_csv':

			// // Setup csv file
			// header('Content-Type: text/csv; charset=utf-8');
			// header( 'Content-Description: File Transfer' );

			// header( 'Content-Disposition: attachment; filename='.$filename.'.csv' );

	  //   	echo chr(255) . chr(254) . $result;

			// //
			// die();

		echo $cab_api->export_all_organisations_csv($_GET['period']);
		//die();
	break;

  case 'export_selected_organisations_csv':

    echo $cab_api->export_selected_organisations_csv($_GET['period'], $_GET['open'], $_GET['inBehandeling'], $_GET['afgerond'], $_GET['geblokkeerd']);

  break;

	case 'organisations_by_type':
		$cab_api->organisations_by_type();
	break;

		case 'count_export_organisations':
			$cab_api->count_export_organisations();

	break;

	// // Outputs an array with omzet
	// case 'report_global_omzet':
	// //echo "aa";
	// 		print_r($cab_api->report_global_omzet());

	// break;

	// // Outputs an array with income
	// case 'report_global_income':
	// //echo "aa";
	// 		print_r($cab_api->report_global_income());

	// break;



	default:

	break;
}





?>
