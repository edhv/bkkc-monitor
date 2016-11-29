<?php



class cab_functions
{



	function __construct()
	{
		//echo $this->get_owner_by_organisation_id(12);
		        add_action('save_post', array($this, 'remove_caches'),1);

	}


	function remove_caches($post_id) {

            if ( 'cab_organisation' != get_post_type( $post_id )) {
                 return;
             }

  			// delete the cached organisation
            delete_transient( 'organisation_'.$post_id );

            // delete the cached organisations export
            delete_transient( 'get_bloementuin_organisations_export' );

           // // Delete specific post transient
           // delete_transient( 'edhv_portfolio_get_data_'.$post_id );
           // delete_transient( 'edhv_portfolio_get_item_'.$post_id );

	}


	function convertArrayToCsvRow($array) {

		$csv_string = '';

		foreach ($array as $key => $value) {
			if (!is_array($value)) {
				$csv_string .= '"'.$value.'";';

			}
		}
		return rtrim($csv_string, ";").chr(13);
	}


	function get_all_periods($cleanName = true) {

		$periods = get_field_object('field_51e7d0d4776b7');

		// remove beschikt and begroot from the names
		if ($cleanName) {
			foreach ($periods['choices'] as $key => $period) {
				$periods['choices'][$key] = substr($periods['choices'][$key], 0,4);
			}
		}


		if (!isset($periods['choices'])) {
			return false;
		} else {
			return $periods['choices'];
		}

	}

	function get_geo_coordinates_by_id($id) {

		$address = $this->get_organisation_address($id);

		$url_address = urlencode($address['street']." ".$address['nr'].", ".$address['city']);
    	$json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=$url_address&sensor=false");

    	$return = json_decode($json);
		//http://maps.googleapis.com/maps/api/geocode/json?address=Citadellaan+7,+'s-hertogenbosch&sensor=false

    	if ($return->status != "ZERO_RESULTS") {
    		return array("lat"=>$return->results[0]->geometry->location->lat, "lng"=>$return->results[0]->geometry->location->lng);
    	} else {
    		return false;
    	}



	}

	function get_organisation_address($id, $type = false) {

		$organisation_data = $this->get_organisation_data($id);

		$visitor_address_array['city'] = $organisation_data['general']['visit_address_city'];
		$visitor_address_array['zipcode'] = $organisation_data['general']['visit_address_zipcode'];
		$visitor_address_array['street'] = $organisation_data['general']['visit_address_street'];
		$visitor_address_array['nr'] = $organisation_data['general']['visit_address_nr'];

		$post_address_array['city'] = $organisation_data['general']['post_address_city'];
		$post_address_array['zipcode'] = $organisation_data['general']['post_address_zipcode'];
		$post_address_array['street'] = $organisation_data['general']['post_address_street'];
		$post_address_array['nr'] = $organisation_data['general']['post_address_nr'];


		// Convert arrays to string
		$post_address_string = implode('', $post_address_array);
		$visitor_address_string = implode('', $visitor_address_array);

		// Clean up strings
		$post_address_string = trim($post_address_string);
		$visitor_address_string = trim($visitor_address_string);

		// If the visitor address is not available return post address
		if ($visitor_address_string != '') {
			return $visitor_address_array;
		} else if ($post_address_string != '') {
			return $post_address_array;
		} else {
			return false;
		}

	}

	//
	function get_gform_form_data($form_id) {

		global $wpdb;

		//print_r(GFAPI::get_form($form_id));
		// $sql = "SELECT display_meta FROM ".$wpdb->prefix."rg_form_meta WHERE form_id = ".$form_id;
		// $results = $wpdb->get_row( $sql, OBJECT );

		return GFAPI::get_form($form_id);

	}


	function get_vragenlijst_title($vragenlijst_id) {
		$vragenlijst_categories = $this->get_vragenlijst_categories();
		return $vragenlijst_categories[$vragenlijst_id];
	}


	// Gets the status categories from the advanced custom
	function get_status_categories() {
		    $categories = get_metadata('post', 626, 'field_520c97610f395');
			return $categories[0]['choices'];
	}

	function get_vragenlijst_categories() {
		    $vragenlijst_categorie_labels = get_metadata('post', 28, 'field_51e3f440c250c');
			return $vragenlijst_categorie_labels[0]['choices'];
	}


	function show_array_as_table($array) {

		$header_fields = array_keys($array[0]);
		//print_r($header_fields);

		$html = "<table>";

		// Header
		$html .= "<tr>";
		foreach ($header_fields as $key => $value) {
			$html .= "<td>".$value."</td>";
		}
		$html .= "</tr>";

		// Values

		foreach ($array as $row) {
			$html .= "<tr>";

			foreach ($row as $value) {
				$html .= "<td>".$value."</td>";
			}

			$html .= "</tr>";
		}


		$html .= "</table>";

		echo $html;


	}

	function get_gform_id() {
		return 1;
	}

	// get a specific gravity forms field by id or array of id's
	function get_gform_field_data($form_id, $field_id) {
		$form_data = $this->get_gform_form_data($form_id);

		// If the field id is an array return an array
		if (is_array($field_id)) {
			$return_array = array();
			foreach ($form_data['fields'] as $form_field) {
				if (in_array($form_field['id'], $field_id)) {
					array_push($return_array, $form_field);
				}
			}

			return $return_array;

		} else {

			foreach ($form_data['fields'] as $form_field) {
				if ($form_field['id'] == $field_id) {
					return $form_field;
				}
			}

		}

	}

	function get_gform_checkbox_values($field_id, $entry) {

		$return_array = array();
		//echo $field_id;
		//print_r($entry);
		// Find all the fields which contain the field id
		// i.e. field[10.1], field[20.2]
		foreach ($entry as $key => $value) {
			if (strstr($key, $field_id.".")) {
				if ($value) {
					array_push($return_array, $value);
				}
			}
		}

		return $return_array;
	}



	function get_bloementuin_organisations_export($output = "xml") {


		// Check if there is a transient
		$export = get_transient( 'get_bloementuin_organisations_export' );



        // If there is no cache available rerun the funtion
        if ( false === $export ) {


				// Prepare data
				$organisations_list = $this->get_all_bloementuin_organisations_list();
				//print_r($this->get_all_organisations_data($organisations_list));
				$organisations_data = $this->get_all_organisations_data($organisations_list);
				//print_r($this->get_all_bloementuin_organisations_list());
				$period_id = 3;

				$export = "";

				switch ($output) {
					case 'xml':

						$export .= "<datastructure>\n\t<instellingen>\n";
						$export .= "";

						foreach ($organisations_data as $key => $organisation) {


							//print_r($organisation);
							//print_r($organisation['periodical'][$period_id]);
							// If organisation fits all requirements
							$export .= "\t\t<instelling id=\"".$organisation['general']['id']."\">\n";

							$export .= "\t\t\t<label><![CDATA[".$organisation['general']['name']."]]></label>\n";
							//$export .= "\t\t\t<contact_persoon><![CDATA[".$organisation['general']['director_name']."]]></contact_persoon>\n";
							$export .= "\t\t\t<adres><![CDATA[".$organisation['general']['visit_address_street']." ".$organisation['general']['visit_address_nr']."]]></adres>\n";
							$export .= "\t\t\t<postcode><![CDATA[".$organisation['general']['visit_address_zipcode']."]]></postcode>\n";
							$export .= "\t\t\t<plaats><![CDATA[".$organisation['general']['visit_address_city']."]]></plaats>\n";
							$export .= "\t\t\t<telefoonnummer><![CDATA[".$organisation['general']['telephone']."]]></telefoonnummer>\n";
							$export .= "\t\t\t<website><![CDATA[http://".$organisation['general']['website']."]]></website>\n";
							$export .= "\t\t\t<facebook><![CDATA[http://www.facebook.com/".$organisation['general']['facebook']."]]></facebook>\n";
							$export .= "\t\t\t<twitter><![CDATA[http://www.twitter.com/".$organisation['general']['twitter']."]]></twitter>\n";
							$export .= "\t\t\t<email><![CDATA[".$organisation['general']['email']."]]></email>\n";


							if ($organisation['general']['geo'] != '') {
								$organisation_geo =  explode(",", $organisation['general']['geo']);
							} else {
								$organisation_geo =  array("51.441642","5.469722");
							}

					   		$export .= "\t\t\t<gps long=\"".$organisation_geo[1]."\" lat=\"".$organisation_geo[0]."\"/>\n";
							//$export .= "\t\t\t<instelling_categorie>".."</instelling_categorie>\n";
							$export .= "\t\t\t<regio>".$organisation['general']['regio']."</regio>\n";

							// $discipline = implode(",", $organisation['general']['kernactiviteiten_discipline']);
							// $type = implode(",", $organisation['general']['kernactiviteiten_type']);

							if (is_array($organisation['general']['kernactiviteiten_keten'])) {
							 	$keten =  implode(",", $organisation['general']['kernactiviteiten_keten']);
							}

							if (is_array($organisation['general']['kernactiviteiten_discipline'])) {
							 	$discipline =  implode(",", $organisation['general']['kernactiviteiten_discipline']);
							}

							if (is_array($organisation['general']['kernactiviteiten_type'])) {
							 	$type =  implode(",", $organisation['general']['kernactiviteiten_type']);
							}

							$export .= "\t\t\t<groepen>".$type."</groepen>\n"; //Categorien
			     			$export .= "\t\t\t<sectoren>".$discipline."</sectoren>\n";
			    			$export .= "\t\t\t<ketens>".$keten."</ketens>\n";
							$export .= "\n";

							// If period data exists
							// if (isset($organisation['periodical'][$period_id])) {



							// 	$export .= "\t\t\t<groepen>".$categories."</groepen>\n"; //Categorien
			    // 				$export .= "\t\t\t<sectoren>".$sectors."</sectoren>\n";
			    // 				$export .= "\t\t\t<ketens>".$chain."</ketens>\n";


							// 	$export .= "\n";

							// 	# code...
			    // 				$export .= "\t\t\t<view_opties></view_opties>\n";
			    // 				$export .= "\t\t\t<specificaties></specificaties>\n\n";

							// 	$export .= "\t\t\t<!-- Landelijke Fondsen -->\n";
							// 	$export .= "\t\t\t<landelijke_fondsen></landelijke_fondsen>\n\n";


							// 	$export .= "\t\t\t<!-- relaties -->\n";
							// 	$export .= "\t\t\t<relaties></relaties>\n\n";

							// 	$export .= "\t\t\t<!-- Talent Ontwikkeling -->\n";
							// 	$export .= "\t\t\t<talentontwikkeling></talentontwikkeling>\n\n";

							 	$export .= "\t\t\t<!-- Teksten -->\n";
							 	$export .= "\t\t\t<teksten>\n";


							 	$rows = array();
							 	$rows[] = $organisation['general']['director_name'];
							 	$rows[] = $organisation['general']['director_function'];
							 	$rows[] = $organisation['general']['telephone'];
							 	$rows[] = $organisation['general']['director_email'];
							 	$rows[] = $organisation['general']['email'];
								$rows = array_filter($rows); // remove empty values

							 	// Contact
							 	$export .= "\t\t\t\t<tekst>\n";
							 	$export .= "\t\t\t\t\t<titel ><![CDATA[Contact]]></titel>\n";
							 	$export .= "\t\t\t\t\t<content><![CDATA[".implode("<br/>", $rows)."<br/>]]></content>\n";
							 	$export .= "\t\t\t\t</tekst>\n";


							 	// Postadres
							 	$rows = array();
							 	$rows[] = $organisation['general']['post_address_street']." ".$organisation['general']['post_address_nr'];
							 	$rows[] = $organisation['general']['post_address_zipcode']."  ".$organisation['general']['post_address_city'];
								$rows = array_filter($rows); // remove e
								foreach ($rows as $key => $row) {
									if ($row == '  ') {
										unset($rows[$key]);
									}
								}

								$value = implode("<br/>", $rows);
								if ($value != '' && $value != ' ') {

							 	$export .= "\t\t\t\t<tekst>\n";
							 	$export .= "\t\t\t\t\t<titel ><![CDATA[Adresgegevens]]></titel>\n";
							 	$export .= "\t\t\t\t\t<content><![CDATA[".$value."<br/>]]></content>\n";
							 	$export .= "\t\t\t\t</tekst>\n";
							 }





							 	/// Bezoekadres
							 	$rows = array();
							 	$rows[] = $organisation['general']['visit_address_street']." ".$organisation['general']['visit_address_nr'];
							 	$rows[] = $organisation['general']['visit_address_zipcode']."  ".$organisation['general']['visit_address_city'];
								$rows = array_filter($rows); // remove e

								foreach ($rows as $key => $row) {
									if ($row == '  ') {
										unset($rows[$key]);
									}
								}

								$value = implode("<br/>", $rows);
								if ($value != '' && $value != ' ') {
								 	$export .= "\t\t\t\t<tekst>\n";
								 	$export .= "\t\t\t\t\t<titel ><![CDATA[Bezoekadres]]></titel>\n";
								 	$export .= "\t\t\t\t\t<content><![CDATA[".$value."<br/>]]></content>\n";
								 	$export .= "\t\t\t\t</tekst>\n";
							 	}



							 	$export .= "\t\t\t</teksten>\n\n";

							// 	$export .= "\t\t\t<!-- Projecten -->\n";
							// 	$export .= "\t\t\t<projecten></projecten>\n\n";


		    	// 			} else {
		    	// 				$export .= "\t\t\t<groepen>1</groepen>\n"; //Categorien
			    // 				$export .= "\t\t\t<sectoren>1</sectoren>\n";
			    // 				$export .= "\t\t\t<ketens>1</ketens>\n";

		    	// 			}

							$export .= "\t\t</instelling>\n";
							/*
							Array
		(
		    [general] => Array
		        (
		            [id] => 556
		            [name] => Muziekgebouw Eindhoven
		            [email] => Welkom@MuziekgebouwEindhoven.nl
		            [telephone] => 040-26555664
		            [geo] =>
		            [website] => www.muziekgebouwEindhoven.nl
		            [twitter] => MuziekgebouwEHV
		            [facebook] => nl-nl.facebook.com/MuziekgebouwEindhoven
		            [director_name] => Wim Vringer
		            [director_function] => directeur
		            [director_email] => Wim.vringer@muziekgebouwEindhoven.nl
		            [administrator_name] => Marcia van den Wildenberg
		            [administrator_email] => Marcia@MuziekgebouwEindhoven.nl
		            [visit_address_city] => Eindhoven
		            [visit_address_zipcode] => 5611 DK
		            [visit_address_street] => Heuvel Galerie
		            [visit_address_nr] =>  140
		            [post_address_city] => Eindhoven
		            [post_address_zipcode] => 5600 AX
		            [post_address_street] => Postbus
		            [post_address_nr] =>  930
		        )*/
						}

						$export .= "\t</instellingen>\n";
						$export .= "</datastructure>";
						# code...
					break;

				}


				set_transient( 'get_bloementuin_organisations_export', $export, 12 * HOUR_IN_SECONDS );

		}

		return $export;
	}

	// Get all organisation id's
	function get_all_organisations($orderby = "post_date", $order = "DESC") {

		$args = array(
			'posts_per_page' => -1,
     		'post_type' => 'Cab_Organisation',
    		'post_status' => array('publish','pending'),
    		'orderby' => $orderby,
    		'order' => $order
        );

		return get_posts( $args );
	}

	// gets all the organisations which have the 'export data' checkbox checked
	function get_all_exportable_organisations() {

		$organisations = $this->get_all_organisations();
		$vragenlijst_organisations = array();

		foreach ($organisations as $organisation) {

			if ($this->is_application_allowed($organisation->ID, "data export")) {
				array_push($vragenlijst_organisations, $organisation);
			}
			# code...
		}


		return $vragenlijst_organisations;
	}

	function get_all_vragenlijst_organisations() {

		$organisations = $this->get_all_organisations();
		$vragenlijst_organisations = array();

		foreach ($organisations as $organisation) {

			if ($this->is_application_allowed($organisation->ID, "vragenlijst")) {
				array_push($vragenlijst_organisations, $organisation);
			}
			# code...
		}


		return $vragenlijst_organisations;
	}


	//
	function get_all_organisations_data($organisation_list) {
		//print_r($organisation_list);
		$organisations_array = array();

		foreach ($organisation_list as $key => $organisation_list_item) {

			$organisations_array[$organisation_list_item['id']] = $this->get_organisation_data($organisation_list_item['id']);
			# code...
		}

		return $organisations_array;

	}


	// Gets all the data of an organisation
	function get_organisation_data($organisation_id) {

		$cache = get_transient( 'organisation_'.$organisation_id );

		if ($cache) {
			return $cache;
		}




		global $wpdb;

		$periods = $this->get_available_forms($organisation_id, false);

		$aanvullende_vragenlijsten = get_field('vragenlijst_categorie', $organisation_id);
		$regio = get_field('regio', $organisation_id);
		if (is_array($regio)) {
			$regio = $regio[0];
		}
		$organisation_general_data = get_post($organisation_id);


		// Get organisation general data
		$owner_id = $this->get_user_id_by_organisation_id($organisation_id);
		$user_meta = get_user_meta($owner_id);

		if (isset($user_meta['user-cab_geo'][0])) {
			$geo = $user_meta['user-cab_geo'][0];
		} else {
			$geo = "";
		}

		$general_data = array(
			"id" => $organisation_id,
			"name" => $user_meta['user-cab_organisatie-naam'][0],
			"email" => $user_meta['user-cab_organisatie-email'][0],
			"telephone" => $user_meta['user-cab_organisatie-telefoon'][0],

			"start_date" => get_field('organisatie_begin_datum', $organisation_id),
			"end_date" => get_field('organisatie_eind_datum', $organisation_id),
			//"geo" => $user_meta['user-cab_bezoekadres-gps'][0],
			"geo"=> $geo,
			"regio" => $regio,
			"website" =>  $user_meta['user-cab_organisatie-website'][0],
			"twitter" => $user_meta['user-cab_organisatie-twitter'][0],
			"facebook" => $user_meta['user-cab_organisatie-facebook'][0],

			"director_name" => $user_meta['user-cab_organisatie-directeur-naam'][0],
			"director_function" => $user_meta['user-cab_organisatie-directeur-functie'][0],
			"director_email" => $user_meta['user-cab_organisatie-directeur-email'][0],

			"administrator_name" => $user_meta['user-cab_organisatie-administratief-naam'][0],
			"administrator_email" => $user_meta['user-cab_organisatie-administratief-email'][0],

			"visit_address_city" => $user_meta['user-cab_bezoekadres-plaats'][0],
			"visit_address_zipcode" => $user_meta['user-cab_bezoekadres-postcode'][0],
			"visit_address_street" => $user_meta['user-cab_bezoekadres-straat'][0],
			"visit_address_nr" => $user_meta['user-cab_bezoekadres-huisnummer'][0],

			"post_address_city" => $user_meta['user-cab_postadres-plaats'][0],
			"post_address_zipcode" => $user_meta['user-cab_postadres-postcode'][0],
			"post_address_street" => $user_meta['user-cab_postadres-straat'][0],
			"post_address_nr" => $user_meta['user-cab_postadres-huisnummer'][0],

			"kernactiviteiten_keten" => get_field('kernactiviteiten_keten', $organisation_id),
			"kernactiviteiten_discipline" => get_field('kernactiviteiten_discipline', $organisation_id),
			"kernactiviteiten_type" => get_field('kernactiviteiten_type', $organisation_id)
		);




		$organisation_data = array();

		$tables_to_get = array( // (table_name, has_aanv_vragenlijst_id)
			array("cab_activiteiten",true),
			array("cab_bezoekers",true),
			array("cab_eigen_inkomsten",false),
			array("cab_marketing",false),
			array("cab_media",false),
			array("cab_nevenactiviteiten",true),
			array("cab_omzet",false),
			array("cab_organisatie",false),
			array("cab_scholing",false),
			array("cab_spreiding",true),
			array("cab_subsidy",false),
			array("cab_vertoningen",true)
		);

		$organisation_data["general"] = $general_data;
		$organisation_data["periodical"] = array();


		foreach ($periods as $key => $period) {
			# code...
			$organisation_data["periodical"][$period->term_id]["period"] = $period->name;

			// Prepare discipline data
			$organisation_discipline_array = array();
			$organisation_discipline_results = $wpdb->get_results( "SELECT discipline_id FROM ".$wpdb->prefix."cab_organisatie_discipline WHERE organisation_id = ".$organisation_id." AND period_id = ".$period->term_id, ARRAY_A );
			foreach ($organisation_discipline_results as $discipline) {
				array_push($organisation_discipline_array, $discipline['discipline_id']);
			}

			// Prepare type data
			$organisation_type_array = array();
			$organisation_type_results = $wpdb->get_results( "SELECT type_id FROM ".$wpdb->prefix."cab_organisatie_type WHERE organisation_id = ".$organisation_id." AND period_id = ".$period->term_id, ARRAY_A );
			foreach ($organisation_type_results as $type) {
				array_push($organisation_type_array, $type['type_id']);
			}

			// Prepare keten data
			$organisation_keten_array = array();
			$organisation_keten_results = $wpdb->get_results( "SELECT keten_id FROM ".$wpdb->prefix."cab_organisatie_keten WHERE organisation_id = ".$organisation_id." AND period_id = ".$period->term_id, ARRAY_A );
			foreach ($organisation_keten_results as $keten) {
				array_push($organisation_keten_array, $keten['keten_id']);
			}



			// Add organisation data to array
			$organisation_data["periodical"][$period->term_id]["data"]["kernactiviteiten"]["functie"] = $organisation_keten_array;
			$organisation_data["periodical"][$period->term_id]["data"]["kernactiviteiten"]["type"] = $organisation_type_array;
			$organisation_data["periodical"][$period->term_id]["data"]["kernactiviteiten"]["sector"] = $organisation_discipline_array;


			$sql_where = '';

			foreach ($tables_to_get as $table) {

				if ($table[0] === "cab_activiteiten") {
					$sql = "SELECT id, period_id, organisation_id, aanv_vragenlijst_id, (IF(IFNULL(aantal, 0) <= 0, (IFNULL(in_opdracht, 0)+IFNULL(eigen_werk, 0)+IFNULL(premieres, 0)+IFNULL(reprises, 0)) ,aantal)) AS aantal, in_opdracht, eigen_werk, premieres, reprises FROM ".$wpdb->prefix.$table[0];
				} else {
					$sql = "SELECT * FROM ".$wpdb->prefix.$table[0];
				}


				$sql = "SELECT * FROM ".$wpdb->prefix.$table[0];

				$organisation_data["periodical"][$period->term_id]["data"][$table[0]] = array();
				$sql_where = " WHERE ";
				$sql_where .= " organisation_id = ".$organisation_id." ";
				$sql_where .= " AND period_id = ".$period->term_id." ";

				if (count($aanvullende_vragenlijsten) > 0 && $table[1] == true) {
					$sql_where .= " AND ( ";
					foreach ($aanvullende_vragenlijsten as $aanv) {

						$sql_where .= " aanv_vragenlijst_id = ".$aanv." OR";
					}
					// Remove last OR
					$sql_where = substr_replace($sql_where ,"",-2);
					$sql_where .= " ) ";
				}


				$rows = $wpdb->get_results( $sql.$sql_where, ARRAY_A );


				// parse results

				foreach ($rows as $row) {

					// parse row to rename null to NULL etc
					foreach ($row as $key => $value) {
						if ($value == '') {
							$row[$key] = 'null';
						}
					}

					array_push($organisation_data["periodical"][$period->term_id]["data"][$table[0]], $row);
				}

				//  $sql = "SELECT * FROM ".$wpdb->prefix.$table." WHERE organisation_id = ".$organisation_id." AND period_id = $period";

				# code...
			}

		}

		// set cache
		set_transient( 'organisation_'.$organisation_id, $organisation_data );

		return $organisation_data;

 		/*

		organisation

			"created"
			"title"
			"details" => array(
				"general" => array(

				),
				"periodical" => array(
					"period" => 2012,
					"data" => array (
						"kernactiviteiten" => array(
							"discipline" => array(
								0 => 4,
								1 => 6,
								2 => 8
							)
						)
						"activiteiten" => array(
							"aanv-6" => array (
								"id" => 792
								"totaal" => 0
								"in_opdracht" => 0
								"eigen_werk" => 0
								"premieres" => 0
							)
						),
						"bezoekers"
						"eigen_inkomsten"
						"marketing"
						"media"
						"nevenactiviteiten"
						"omzet"
						"organisatie"
						"kerndiscipline"
						"scholing"
						"spreiding"
						"subsidie"
						"vertoningen"
									"cab_activiteiten",
			"cab_bezoekers",
			"cab_eigen_inkomsten",
			"cab_marketing",
			"cab_media",
			"cab_nevenactiviteiten",
			"cab_omzet",
			"cab_organisatie",
			"cab_organisatie_discipline",
			"cab_organisatie_keten",
			"cab_organisatie_type",
			"cab_scholing",
			"cab_spreiding",
			"cab_subsidy",
			"cab_vertoningen"
					)
				)

			)



 		*/

	}

	function remove_custom_tables($organisation_id) {
		global $wpdb;
		$tables_array = array(
			"cab_activiteiten",
			"cab_bezoekers",
			"cab_eigen_inkomsten",
			"cab_marketing",
			"cab_media",
			"cab_nevenactiviteiten",
			"cab_omzet",
			"cab_organisatie",
			"cab_organisatie_discipline",
			"cab_organisatie_keten",
			"cab_organisatie_type",
			"cab_scholing",
			"cab_spreiding",
			"cab_subsidy",
			"cab_vertoningen"
		);

		foreach ($tables_array as $table_name) {
			$wpdb->delete(
				$wpdb->prefix.$table_name,
				array( 'organisation_id' => $organisation_id )
			);
		}
	}

	function get_current_organisationd_id() {
		global $post;

		return $post->ID;
	}

	function get_user_id_by_organisation_id($organisation_id) {
		return get_post_meta( $organisation_id, 'gekoppelde_gebruiker', true );
	}


	// Returns an array with organisation id's by kernactiviteit type
	function get_all_organisations_by_kernactiviteit_type_list() {

		global $wpdb;

		$type_array[1] = "Theater";
		$type_array[2] = "Concertzaal";
		$type_array[3] = "Filmtheater";
		$type_array[4] = "Festival";
		$type_array[5] = "Museum";
		$type_array[6] = "Bibliotheek";
		$type_array[7] = "Cbk";
		$type_array[8] = "Gezelschap";
		$type_array[9] = "Werkplaats";
		$type_array[10] = "Opleiding";
		$type_array[11] = "Steunfunctie";

		$table_name = $wpdb->prefix . "cab_organisatie_type";

		$organisations_by_type = array();

		// Walk throught the types and get corresponding organisations
		foreach ($type_array as $key => $type) {

			if (!array_key_exists($key, $organisations_by_type)) {
				$organisations_by_type[$key] = array('label'=>$type_array[$key],'organisations'=>array());
			}

			// Get all organisations of a certain type
			$sql = "SELECT DISTINCT organisation_id FROM ".$table_name." WHERE type_id = ".$key;
			$organisations = $wpdb->get_results($sql, 'ARRAY_N');

			foreach ($organisations as $organisation) {

				if ($this->is_organisation_active($organisation[0])) {
					array_push($organisations_by_type[$key]['organisations'], $organisation[0]);
				}
			}

		}

		return $organisations_by_type;

	}


	// Returns an array with organisation id's by type
	function get_all_organisations_by_type_list() {

		global $wpdb;

		$type_array[1] = "Festivals";
		$type_array[2] = "BKV/AV";
		$type_array[3] = "Film(producenten)";
		$type_array[4] = "Musea";
		$type_array[5] = "Podia";
		$type_array[6] = "Producenten podiumkunsten";
		$type_array[7] = "Podia Theater";

		$vragenlijst_organisations = $this->get_all_vragenlijst_organisations_list();

		$organisations_by_type = array();

		// Walk throught the types and get corresponding organisations
		foreach ($type_array as $key => $type) {

			if (!array_key_exists($key, $organisations_by_type)) {
				$organisations_by_type[$key] = array('label'=>$type_array[$key],'amount'=>0,'organisations'=>array());
			}


			// Walk through organisations and compare to type_array
			foreach ($vragenlijst_organisations as $organisation) {

				$categories = get_field('vragenlijst_categorie',$organisation['id']);

				if (in_array($key, $categories)) {
					array_push($organisations_by_type[$key]['organisations'], $organisation['id']);
				}

			}

			$organisations_by_type[$key]['amount'] = count($organisations_by_type[$key]['organisations']);

		}

		$organisations_by_type['amount'] = count($vragenlijst_organisations);

		return $organisations_by_type;

	}



	// Returns an array with organisation id's by type
	// UNDER CONSTRUCTION
	function get_all_organisations_by_city_list() {

		global $wpdb;

		// $type_array[1] = "Festivals";
		// $type_array[2] = "BKV/AV";
		// $type_array[3] = "Film(producenten)";
		// $type_array[4] = "Musea";
		// $type_array[5] = "Podia";
		// $type_array[6] = "Producenten podiumkunsten";
		// $type_array[7] = "Podia Theater";

		$vragenlijst_organisations = $this->get_all_vragenlijst_organisations_list();

		$organisation_city_array = array();

		foreach ($vragenlijst_organisations as $key => $value) {
			# code...
			//print_r($value);
			$organisation_id = $value['id'];
			$owner_id = $this->get_user_id_by_organisation_id($organisation_id);
			$user_meta = get_user_meta($owner_id);

			$array_key = $user_meta['user-cab_postadres-plaats'][0];

			if (!isset($organisation_city_array[$array_key])) {


				$organisation_city_array[$array_key] = array();

				//$organisation_city_array[$array_key][] = "a";
				//array_push($organisation_city_array[$user_meta['user-cab_bezoekadres-plaats'][0]], $organisation_id);
			}

			$organisation_city_array[$array_key][] = $organisation_id;
			//$user_meta['cab_postadres'];
		}
		print_r($organisation_city_array);
		// $organisations_by_type = array();

		// // Walk throught the types and get corresponding organisations
		// foreach ($type_array as $key => $type) {

		// 	if (!array_key_exists($key, $organisations_by_type)) {
		// 		$organisations_by_type[$key] = array('label'=>$type_array[$key],'organisations'=>array());
		// 	}


		// 	// Walk through organisations and compare to type_array
		// 	foreach ($vragenlijst_organisations as $organisation) {

		// 		$categories = get_field('vragenlijst_categorie',$organisation['id']);

		// 		if (in_array($key, $categories)) {
		// 			array_push($organisations_by_type[$key]['organisations'], $organisation['id']);
		// 		}

		// 	}

		// }

		// return $organisations_by_type;

	}


	// Checks if an organisation is part of the vragenlijst and if the organisation is active
	function is_organisation_active($organisation_id, $application_allowed = false) {

		$organisation = get_post( $organisation_id, "OBJECT");

		if ($organisation->post_status != 'trash' && $this->is_application_allowed($organisation_id, "vragenlijst") ) {
			return true;
		} else {
			return false;
		}

	}


	// Returns a list of all organisations that are allowed in the bloementuin
	// The requirements are a published state and existence of the latest period available in the system
	function get_all_bloementuin_organisations_list($period = 2) {

		$args = array(
			'posts_per_page' => -1,
     		'post_type' => 'Cab_Organisation',
    		'post_status' => array('publish')
        );

		$all_organisations = get_posts( $args );

		$organisation_list = array();

		// Walk through all organisations to check if the organisation needs to be shown
		// in "bloementuin"
		foreach ($all_organisations as $key => $organisation) {
			if ($this->is_application_allowed($organisation->ID, "bloementuin")) {

				$owner_id = $this->get_user_id_by_organisation_id($organisation->ID);

				$organisation_name = get_user_meta($owner_id, 'user-cab_organisatie-naam');

				array_push($organisation_list, array("id"=>$organisation->ID,"name"=>$organisation_name[0]));
			}
		}

		return $organisation_list;
	}


	// Generate a list of ID and Organisation Name
	function get_all_organisations_list($orderby = "post_date", $order = "DESC") {




		$organisations = $this->get_all_organisations($orderby, $order);
		$organisation_list = array();

		foreach ($organisations as $key => $organisation) {
			$owner_id = $this->get_user_id_by_organisation_id($organisation->ID);

			$organisation_name = get_user_meta($owner_id, 'user-cab_organisatie-naam');

			array_push($organisation_list, array("id"=>$organisation->ID,"name"=>$organisation_name[0]));

		}

 		return $organisation_list;
	}


	// Gets a list of all organisations that have open forms
	function get_all_vragenlijst_organisations_open_forms_list($orderby = "post_date", $order = "DESC") {

		$args = array(
			'posts_per_page' => -1,
     		'post_type' => 'Cab_Organisation',
    		'post_status' => array('publish','pending'),
    		'orderby' => $orderby,
    		'order' => $order
        );

		$all_organisations = get_posts( $args );

		$organisation_list = array();

		foreach ($all_organisations as $key => $organisation) {



			if ($this->is_application_allowed($organisation->ID, "vragenlijst")) {

					$available_forms = $this->get_available_forms($organisation->ID);

				if (count($available_forms) > 0) {
				 $owner_id = $this->get_user_id_by_organisation_id($organisation->ID);
				 $organisation_name = get_user_meta($owner_id, 'user-cab_organisatie-naam');

				array_push($organisation_list, array("id"=>$organisation->ID,"name"=>$organisation_name[0]));


				// if (is_array($available_forms)) {
				// 	echo 'jaa';
				// } else {
				// 	echo 'nee';
				// }

				 }
				// $owner_id = $this->get_user_id_by_organisation_id($organisation->ID);
				// $organisation_name = get_user_meta($owner_id, 'user-cab_organisatie-naam');



				// }

			}
		}
		return $organisation_list;



	}








	function get_all_vragenlijst_organisations_list($orderby = "post_date", $order = "DESC") {

		$args = array(
			'posts_per_page' => -1,
     		'post_type' => 'Cab_Organisation',
    		'post_status' => array('publish','pending'),
    		'orderby' => $orderby,
    		'order' => $order
        );

		$all_organisations = get_posts( $args );

		$organisation_list = array();

		foreach ($all_organisations as $key => $organisation) {



			if ($this->is_application_allowed($organisation->ID, "vragenlijst")) {
				$owner_id = $this->get_user_id_by_organisation_id($organisation->ID);
				$organisation_name = get_user_meta($owner_id, 'user-cab_organisatie-naam');
				array_push($organisation_list, array("id"=>$organisation->ID,"name"=>$organisation_name[0]));

			}
		}
		return $organisation_list;

	}

	//function get_all_vragenlijst_organisations_by_city_list()


	// Converts the vragenlijst organisations list to id list
	function convert_organisation_list_to_id_list($organisation_list) {
		$return_array = array();

		foreach ($organisation_list as $value) {
			$return_array[] = $value['id'];
		}
		return $return_array;
	}



	function get_organisation_by_id($id) {

		return array(get_post($id));
	}

	// Returns a human readable string with the status of the period
	function get_form_period_status($organisation_id, $period_id) {


		//if ($this->is_application_allowed($organisation_id,'vragenlijst') && $this->is_period_available($period_id, $organisation_id, true)) {
		if ($this->is_application_allowed($organisation_id,'vragenlijst') && $this->is_period_available($period_id, $organisation_id)) {
			$form_activity = $this->form_activity_get_data($organisation_id, $period_id);

		//	print_r($form_activity['last_access_date']);
			$status = "";

			if ($form_activity['last_access_date']) {
				$status = "In process";
			} else {
				$status = "Open";
			}

      if ($form_activity['form_finish_date'] != '') {
        $status = "Sent";
      }

			if ($form_activity['is_finished']) {
				$status = "Finished";
			}

			return $status;
		}  else {
			return "";
		}
	}

	function split_street_number($string) {
		$pos = strrpos($string, " ");
		$number = substr($string, $pos);
		$street = substr($string, 0, $pos);
		return array($street, $number);
	}


	//
	function get_organisation_id_by_user_id($user_id) {

		$args = array(
    		'post_type' => 'Cab_Organisation',
    		'post_status' => array('publish','pending'),
    		'meta_query' => array(
        		array(
            		'key' => 'gekoppelde_gebruiker',
            		'value'=> $user_id
            	)
            )
        );

		$result = get_posts( $args );

		if (count($result) > 0) {
			return $result[0]->ID;
		} else {
			return false;
		}
	}

	function comma_to_dot($value) {
		return str_replace(',', '.', $value);
	}

	// Run through all the periods connected to the organisation to check
	// if the given period is allowed/active for the organisation
	function is_period_available($period_id, $organisation_id) {

		$periods = get_field('vragenlijst_periodes', $organisation_id);


		// foreach ($fields as $key => $term_id) {

		// 	$term_object = get_term_by('id', $term_id, 'post_tag', 'OBJECT');
		// 	$periods[$term_id] = $term_object;

		// }


		foreach ($periods as $key => $period) {

			//$form_activity = $this->form_activity_get_data( $organisation_id, $period_id );
			if (intval($period) === intval($period_id)) {
				return true;
			}
		}
		return false;


		/*

		array

		form_activity => period_id = 2
						 auto_save = serialized by field id
						 completed = true
						 entry_started
						 entry_completed
		*/

	}







	/* FORM ACTIVITY */
	function form_activity_start($organisation_id, $period_id) {
		 $form_activity = array();

		 $form_activity["period_id"] = $period_id;
		 $form_activity["entry_id"] = '';
		 $form_activity["auto_save"] =  '';
		 $form_activity["is_finished"] =  '';
		 $form_activity["form_finish_date"] =  '';
		 $form_activity["last_access_date"] = date('Y-m-d H:i:s');

		$this->form_activity_save_data(array($period_id => $form_activity), $organisation_id);

		return $form_activity;

	}




	function form_activity_update_entry($organisation_id, $period_id) {
		// Get current form activity
		$form_activity = $this->form_activity_get_data($organisation_id, $period_id);

		// Update activity
		$form_activity['last_access_date'] = date('Y-m-d H:i:s');
		// Save the activity
		$this->form_activity_save_data(array($period_id => $form_activity), $organisation_id);

	}

	// Makes the form available again after locking
	function form_activity_release($organisation_id, $period_id) {
		$form_activity = $this->form_activity_get_data($organisation_id, $period_id);
		$form_activity['entry_id'] = '';
		$form_activity['is_finished'] = '';
		$form_activity['form_finish_date'] = '';
		$this->form_activity_save_data(array($period_id => $form_activity), $organisation_id);

	}

	function form_activity_lock($organisation_id, $period_id) {
		$form_activity = $this->form_activity_get_data($organisation_id, $period_id);
		$form_activity['is_finished'] = true;
		$form_activity['form_finish_date'] = date('Y-m-d H:i:s');
		$this->form_activity_save_data(array($period_id => $form_activity), $organisation_id);

	}

	function form_activity_finished($organisation_id, $period_id, $entry) {
		// Get current form activity
		$form_activity = $this->form_activity_get_data($organisation_id, $period_id);

		$form_activity['entry_id'] = $entry['id'];
		$form_activity['is_finished'] = false;
		$form_activity['auto_save'] = '';
		$form_activity['form_finish_date'] = date('Y-m-d H:i:s');
		// Save the activity
		$this->form_activity_save_data(array($period_id => $form_activity), $organisation_id);

	}


	function form_activity_get_data($organisation_id, $period_id) {
		$form_activity = $this->form_activity_get_all($organisation_id);


		if ($form_activity && array_key_exists($period_id, $form_activity)) {
			return $form_activity[$period_id];

		} else {
			$form_activity = $this->form_activity_start($organisation_id, $period_id);
			return $form_activity;
		}
		// if (!$form_activity || !array_key_exists($period_id, $form_activity)) {
		// 	$form_activity = $this->form_activity_start($organisation_id, $period_id);
		// 	return $form_activity;
		// } else {
		// 	return $form_activity[$period_id];
		// }

	}

	function form_activity_get_all($organisation_id) {
		$form_activity = get_post_meta( $organisation_id, "cab_form_activity", true);
		//$form_activity = preg_replace('!s:(\d+):"(.*?)";!e', "'s:'.strlen('$2').':\"$2\";'", $form_activity);

		return $form_activity;


	}


	function form_activity_get_auto_save($organisation_id, $period_id)  {
		$form_activity = $this->form_activity_get_data($organisation_id, $period_id);

		if ( count($form_activity['auto_save']) > 0) {
			return $form_activity['auto_save'];
		} else {
			return false;
		}

	}


	function form_activity_remove_autosave($organisation_id, $period_id) {
		// Get current form activity
		$form_activity = $this->form_activity_get_data($organisation_id, $period_id);

		// // Update activity
		 $form_activity['auto_save'] = '';
		// // Save the activity
		return $this->form_activity_save_data(array($period_id => $form_activity), $organisation_id);
	}

	function form_activity_auto_save($organisation_id, $period_id, $data) {
		// Get current form activity
		$form_activity = $this->form_activity_get_data($organisation_id, $period_id);

		// // Update activity
		 $form_activity['auto_save'] = $data;
		// // Save the activity
		$this->form_activity_save_data(array($period_id => $form_activity), $organisation_id);

	}



	function form_activity_save_data($form_activity, $organisation_id) {

		$form_activity_updated = $this->form_activity_get_all($organisation_id);

		foreach ($form_activity as $key => $value) {

			$form_activity_updated[$key] = $value;
		}
		return update_post_meta($organisation_id, "cab_form_activity", $form_activity_updated);
	}


	function auto_save_form($organisation_id, $period_id, $fields) {

	}







	function get_current_period() {
		if (isset($_GET['period_id'])) {
			$period_id = $_GET['period_id'];
			return $period_id;
		} else {
			return false;
		}

	}

	function get_owner_by_organisation_id($organisation_id) {
		return get_post_meta( $organisation_id, 'gekoppelde_gebruiker', true);
	}




	// Checks the period tab of the organisation
	// Returns array of form periods
	function get_available_forms($organisation_id, $hide_finished = true) {


		$periodsField = get_field_object('field_51e7d0d4776b7');

		if (!isset($periodsField['choices'])) {
			return false;
		}


		$fields = get_field('vragenlijst_periodes', $organisation_id);
		$forms = array();
		if (is_array($fields)) {
			foreach ($fields as $key => $period_id) {

				$periodName = substr($periodsField['choices'][$period_id], 0,4);

				$object = new stdClass();
				$object->term_id = $period_id;
				$object->name = $periodName;
				$object->slug = $periodName."_".$period_id;
				//$term_object = get_term_by('id', $term_id, 'post_tag', 'OBJECT');
				//if ($term_object != '') {
					$forms[$period_id] = $object;
				//}


			}
		}


		// Get filled forms data
		$form_activity = $this->form_activity_get_all($organisation_id);

		if ($hide_finished && $form_activity) {

			foreach ($forms as $key => $form) {
				// Check if there is form activity anyway
				if (array_key_exists($form->term_id, $form_activity)) {
					// If the forms is already finished dont show it
					if ($form_activity[$form->term_id]['is_finished']) {
						 unset($forms[$key]);
					}
				}
			}
		}

		//$form_activity =
		return $forms;

	}

	function delete_row($table, $columns) {
		global $wpdb;

		return $wpdb->delete( $wpdb->prefix . $table, $columns);


	}

	function add_data($table, $organisation_id, $period, $columns) {

	    global $wpdb;

	    $columns = array_merge($columns, array("organisation_id"=>$organisation_id,"period_id"=>$period));

	    // Add a filter to replace the 'NULL' string with NULL because wordpres doesn't support null
	    add_filter( 'query', 'wp_db_null_value' );

	    // replace empty values with null
	    foreach ($columns as $key => $value) {
	    	if ($columns[$key] == '') {
	    		$columns[$key] = 'null';
	    	}
	    }


		  $wpdb->insert(
		        $wpdb->prefix.$table,
		        $columns
		        );


		  // Remove the filter again:
		  remove_filter( 'query', 'wp_db_null_value' );
	}

	/*

	$table = name of table
	$values = array of values grouped by column

	*/
	function set_habtm_table_data($table, $values, $organisation_id, $period_id) {
		global $wpdb;

    	$table_name = $wpdb->prefix . $table;

  		// First delete the current values
		$this->delete_row($table, array('period_id' => $period_id, 'organisation_id' => $organisation_id));

		// Save the data

			foreach ($values as $key => $value) {

				foreach ($value as $sub_key => $sub_value) {
					$this->add_data($table, $organisation_id, $period_id,array($key => $sub_value));
				}
			}

	}

	// Use by kernactiviteiten populate
	function get_habtm_table_data($table, $column, $organisation_id, $period) {
		global $wpdb;

    	$table_name = $wpdb->prefix . $table;
    	$sql = "SELECT * FROM ".$wpdb->prefix.$table." WHERE organisation_id = $organisation_id AND period_id = $period";
    	$result = $wpdb->get_results($sql, 'ARRAY_A');

    	if ($result) {

			// Create a new array which stores the multivalue in a better manner
	    	$type_id_array = array();
	    	foreach ($result as $key => $value) {
	    		array_push($type_id_array, $value[$column]);
	    	}
	    	$return_array = array('period_id'=>$result[0]['period_id'],'organisation_id'=>$result[0]['organisation_id'],'values'=>$type_id_array);

	 		return $return_array;

    	} else {
    		return false;
    	}

	}

	// Gets a row from the specified table and returns associative array
	function get_table_data($table, $organisation_id, $period, $aanv_vragenlijst_id = false) {
    	global $wpdb;
    	$table_name = $wpdb->prefix . $table;

		$sql = "SELECT * FROM ".$wpdb->prefix.$table." WHERE organisation_id = $organisation_id AND period_id = $period";
    	if ($aanv_vragenlijst_id) {
    		$sql .= " AND aanv_vragenlijst_id = $aanv_vragenlijst_id";
    	}
    	return $wpdb->get_row($sql, 'ARRAY_A');
	}



	// This function splits an array in 2, so it seperates system fields from data fields
	function system_split_array($array) {

	    $system_array = array();
	    $data_array = array();

	    foreach ($array as $key => $value) {
	        if (substr( $key, 0, 1 ) == "_" ) {
	            $system_array[$key] = $value;
	        } else {
	            $data_array[$key] = $value;
	        }
	    }

	    return array('system' => $system_array, 'data' => $data_array);
	}



	function get_entry_row_id($entry_array, $structure_array) {
	    return $entry_array[$structure_array['_id']['gform-field-id']];
	}



	function entry_has_row_id($entry_array, $structure_array) {
	    $row_id = $this->get_entry_row_id($entry_array, $structure_array);
	    if ($row_id) {
	        return true;
	    } else {
	        return false;
	    }
	}


	// Converts the entry to db format
	function convert_entry_to_db($structure_array, $entry_array) {
	    $columns = array();
	    foreach ($structure_array as $key => $value) {
	    	if (!isset($entry_array[$value['gform-field-id']])) {
	    		continue;
	    	}
	        $columns[$key] = $entry_array[$value['gform-field-id']];
	        if ($columns[$key] == '' && $key != 'aanv_vragenlijst_id' && $key != 'id') {
	        	$columns[$key] = 'null';
	        }
	    }
	    return $columns;
	}




	function is_application_allowed($post_id, $application) {

		/* bloementuin:1 */

		/* vragenlijst:2 */
		$connected_applications = get_field('gekoppeld', $post_id);

		if (is_array($connected_applications)) {
			switch ($application) {
				case 'bloementuin':
					if (in_array(1, $connected_applications)) {
						return true;
					}
				break;

				case 'vragenlijst':
					if (in_array(2, $connected_applications)) {
						return true;
					}
				break;

				case 'data export':
					if (in_array(3, $connected_applications)) {
						return true;
					}
				break;
			}
		}


		return false;

	}


	function show_table_organisation_data($name, $data) {
		$html = "
		<table>
		<thead>
		<tr><th colspan='2'>".$name."</th></tr>
		</thead>
		<tbody>";

		foreach ($data[0] as $field_name => $value) {
			$html .= "<tr><td>".$field_name."</td><td>".$value."</td></tr>";
		}

		$html .= "
		</tbody>
		</table>
		";

		echo $html;
	}

	function show_table_organisation_radio_data($name, $data) {
		$html = "
		<table>
		<thead>
		<tr><th colspan='2'>".$name."</th></tr>
		</thead>
		<tbody>";

		foreach ($data as $field_name => $value) {
			$html .= "<tr><td>".$field_name."</td><td>".$value."</td></tr>";
		}

		$html .= "
		</tbody>
		</table>
		";

		echo $html;
	}

		function show_table_organisation_radio_data_by_type($name, $data) {
		$html = "
		<table>
		<thead>
		<tr><th colspan='2'>".$name."</th></tr>
		</thead>
		<tbody>";

		foreach ($data as $type) {

			// If the item has only 1 value show it right behind the type
			if (count($type['data']['totals']) > 1) {

				$html .= "<tr><td><strong>".$type['label']."</strong></td><td></td></tr>";
				foreach ($type['data']['totals'] as $field_name => $value) {
					$html .= "<tr><td>".$field_name."</td><td>".$value."</td></tr>";
				}
				$html .= "<tr><td colspan='2'></td></tr>";

			} else {
				$html .= "<tr><td><strong>".$type['label']."</strong></td><td>".reset($type['data']['totals'])."</td></tr>";

			}
		}


		$html .= "
		</tbody>
		</table>
		";

		echo $html;
	}

	function show_table_organisation_data_by_type($name, $data) {
		$html = "
		<table>
		<thead>
		<tr><th colspan='2'>".$name."</th></tr>
		</thead>
		<tbody>";

		foreach ($data as $type) {

			// If the item has only 1 value show it right behind the type
			if (count($type['data']['totals']) > 1) {

				$html .= "<tr><td><strong>".$type['label']."</strong></td><td></td></tr>";
				foreach ($type['data']['totals'] as $field_name => $value) {
					$html .= "<tr><td>".$field_name."</td><td>".$value."</td></tr>";
				}
				$html .= "<tr><td colspan='2'></td></tr>";

			} else {
				$html .= "<tr><td><strong>".$type['label']."</strong></td><td>".reset($type['data']['totals'])."</td></tr>";

			}
		}


		$html .= "
		</tbody>
		</table>
		";

		echo $html;
	}

	// Returns the sum/total of the given organisations
	// get_organisation_data_sum(array, string, array)
	function get_organisation_data_sum($organisations_list, $table, $fields, $period = false) {

    	global $wpdb;
    	$table_name = $wpdb->prefix . $table;

    	// Generate select part
    	$query_select = "";
    	foreach ($fields['value_fields'] as $field) {
    		$query_select .= "SUM(".$field.") as ".$field.",";
    	}

    	// Generate the combine part if combine values are given
    	if (isset($fields['combine_fields'])) {

    		foreach ($fields['combine_fields'] as $combine_key => $combine_group) {
	    		$combine_fields = "";
	    		// Generate the combine sql
	    		foreach ($combine_group['fields'] as $field) {
	    			$combine_fields .= "SUM(".$field.") + ";
	    		}
	    		$combine_fields = rtrim($combine_fields, " + ");

				$query_select .= "(".$combine_fields.") as ".$combine_group['label'].",";
    		}


    	}

    	// Sanitize select part
    	$query_select = rtrim($query_select, ",");

    	// Compose Query
		$sql = "SELECT ".$query_select." FROM ".$table_name;
		$sql .= " WHERE ";

		// Organisations
		$sql .= " organisation_id IN (".implode(',',$organisations_list).") ";
		if ($period) {
			$sql .= " AND period_id = ".$period;
		}


    	return $wpdb->get_results($sql, 'ARRAY_A');

	}




	function get_organisation_data_sum_by_type($organisation_list_by_type, $table, $fields, $period = false) {


		$data_type_array = array();

		foreach ($organisation_list_by_type as $key => $type) {

			$data_type_array[$key]['label'] = $type['label'];

			if (count($type['organisations']) > 0) {
				$totals = $this->get_organisation_data_sum(
					$type['organisations'],
					$table,
					$fields,
					$period
				);

				$data_type_array[$key]['data']['totals'] = $totals[0];
			}

		}

		return $data_type_array;

	}


	// Returns the sum/total of the given organisations
	// get_organisation_data_sum(array, string, array)
	function get_organisation_data_radio($organisations_list, $table, $fields, $radio, $period = false) {


		global $wpdb;
    	$table_name = $wpdb->prefix . $table;



// Generate select part
    	$query_select = "";
    	foreach ($fields['value_fields'] as $field) {
    		$query_select .= $field." as ".$field.",";
    	}

    	// Sanitize select part
    	$query_select = rtrim($query_select, ",");

    	// Compose Query
		$sql = "SELECT ".$query_select." FROM ".$table_name;
		$sql .= " WHERE ";

		// Organisations
		$sql .= " organisation_id IN (".implode(',',$organisations_list).") ";
		if ($period) {
			$sql .= " AND period_id = ".$period;
		}

    	$media_aandacht = $wpdb->get_results($sql, 'ARRAY_A');


    	$return_array = array();
    	//print_r($radio);

    	foreach ($radio as $value) {

    		# code...
    		$return_array[$value] = "";
    	}

    	foreach ($media_aandacht as $key => $value) {

    		if ($value['aandacht'] != 0) {

	    		if (!isset($return_array[$radio[$value['aandacht']]])) {
	    			$return_array[$radio[$value['aandacht']]] = 1;
	    		} else {
	    			$return_array[$radio[$value['aandacht']]] += 1;
	    		}

    		}
    		# code...
    	}

    	return $return_array;

	}


		function get_organisation_data_radio_by_type($organisation_list_by_type, $table, $fields, $radio, $period = false) {

		 $data_type_array = array();

		 foreach ($organisation_list_by_type as $key => $type) {
		 	$data_type_array[$key]['label'] = $type['label'];

			if (count($type['organisations']) > 0) {
		 		$totals = $this->get_organisation_data_radio(
		 			$type['organisations'],
		 			$table,
		 			$fields,
		 			$radio,
		 			$period
		 		);
		 		//print_r($totals);
		 		$data_type_array[$key]['data']['totals'] = $totals;
		 	}

		 }

		return $data_type_array;

	}



	// For use in report //
function combine_organisation_lists($main_array, $additional_array) {


	$merged_array = $main_array;

	foreach ($additional_array as $key => $value) {

		if (array_key_exists( $key, $main_array )) {

			foreach ($additional_array[$key] as $key_city => $city) {

				if (array_key_exists( $key_city, $main_array[$key] )) {

					foreach ($additional_array[$key][$key_city] as $key_type => $type) {

						if (array_key_exists( $key_type, $main_array[$key][$key_city] )) {
							$merge = array_merge($main_array[$key][$key_city][$key_type], $additional_array[$key][$key_city][$key_type]);

							$merged_array[$key][$key_city][$key_type] = $merge;
						}
					}

				}
			}

		}

		# code...
	}

	return $merged_array;
}

function organisation_list_to_id_list($organisation_array, $sort_by = "all") {

	$result_array = array();

	// all



	switch ($sort_by) {


		case 'all':
			foreach ($organisation_array as $city_label => $city) {
				foreach ($city as $type_key => $type_value) {
					# code...

					 $result_array = array_merge($result_array, $type_value);
				}
				# code...
			}
		break;



		case 'type':
			$result_array_2 = array();

			foreach ($organisation_array as $city_label => $city) {
				foreach ($city as $type_key => $type_value) {

					if (!isset($result_array_2[$type_key])) {
						$result_array_2[$type_key] = array();
					}
					$result_array_2[$type_key] = array_merge($result_array_2[$type_key], $type_value);

				}
			}

			foreach ($result_array_2 as $key => $value) {
				# code...

				$result_array[] = array(
						"label"=>$key,
						"amount"=>count($value),
						"organisations" => $value
					);


			}
		break;

	}

	// by type

	return $result_array;
}

/*    [1] => Array
        (
            [label] => Festivals
            [amount] => 17
            [organisations] => Array
                (
                    [0] => 510
                    [1] => 508
                    [2] => 428
                    [3] => 545
                    [4] => 544
                    [5] => 509
                    [6] => 506
                    [7] => 467
                    [8] => 549
                    [9] => 507
                    [10] => 547
                    [11] => 548
                    [12] => 543
                    [13] => 546
                    [14] => 425
                    [15] => 426
                    [16] => 427
                )

        )
*/

}


$cab_functions = new cab_functions();






add_action( 'wp_login_failed', 'custom_login_failed' );
function custom_login_failed( $username )
{
	global $custom_error;
	$custom_error = new WP_Error('sdfsdfsd', 'sdfdsfsdf');
    $referrer = wp_get_referer();

    if ( $referrer && ! strstr($referrer, 'wp-login') && ! strstr($referrer,'wp-admin') )
    {
        wp_redirect( add_query_arg('login', 'failed', $referrer) );
        exit;
    }
}

add_filter( 'authenticate', 'custom_authenticate_username_password', 30, 3);
function custom_authenticate_username_password( $user, $username, $password )
{
    if ( is_a($user, 'WP_User') ) { return $user; }

    if ( empty($username) || empty($password) )
    {
        $error = new WP_Error();
        $user  = new WP_Error('authentication_failed', __('<strong>ERROR</strong>: Invalid username or incorrect password.'));

        return $error;
    }
}


function ref_stripslashes(&$value,$key) {
    $value = stripslashes($value);
}

function cab_user_is_allowed() {
	global $post, $userdata;

	if (isset($userdata) && isset($post)) {
		$organisation_owner = get_post_meta( $post->ID, 'gekoppelde_gebruiker', true);
		$current_user_id = $userdata->data->ID;

		if ($current_user_id == $organisation_owner || current_user_can( 'manage_options' )) {
		return true;
	} else {
		return false;
	}

	} else {
		return false;
	}


}


function cab_show_menu() {

		global $post;
		global $cab_functions;

		$period_id = $cab_functions->get_current_period();


		echo '<div class="row-fluid header-row">';
			echo '<span class="span7"><h1>Culturele Atlas Brabant Monitor</h1></span>';
			echo '<span class="span5">';
			echo '<ul class="tool_menu">';
			echo '<li><a id="" class="menu-button menu-button" href="'.home_url().'">home</a></li>';
			echo '<li><div id="menu_save" class="menu-button menu-button-save menu-button" href="#" onclick="auto_save_data('.$post->ID.','.$period_id.');">tijdelijk opslaan</div></li>';
			echo '<li><span id="menu_logout" class="menu-button menu-button-logout"><a href="'.wp_logout_url(home_url()).'" title="Logout">uitloggen</a></span></li>';
			echo '</ul>';
			echo '</span>';
		echo '</div>';

}

/**
* Replace the 'NULL' string with NULL
*
* @param  string $query
* @return string $query
*/

function wp_db_null_value( $query )
{
  return str_ireplace( "'NULL'", "NULL", $query );
}




?>
