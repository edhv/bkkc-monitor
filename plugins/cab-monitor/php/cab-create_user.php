<?php

class cab_user
{
	
	var $settings;
	
	function __construct()
	{
		global $cab_functions;
		$this->cab_functions = $cab_functions;
		add_action('admin_menu', array($this, 'register_submenu_page'));
	}

	function register_submenu_page() {
		//add_submenu_page('edit.php?post_type=cab_organisation', 'Create user', 'Create user', 'manage_options', 'cab_create_user', array($this,'show_create_user'));
		//add_submenu_page( 'tools.php', 'My Custom Submenu Page', 'My Custom Submenu Page', 'manage_options', 'my-custom-submenu-page', 'my_custom_submenu_page_callback' ); 
	}



	function show_create_user() {
		
	}


	// Converts a csv to associative array
	function convert_csv_to_ass_array($file, $delimiter = ',', 	$enclosure = '"') {



		$content_str = file_get_contents($file);

		$file_lines = preg_split("/\r\n|\n|\r/", $content_str);
	    $csv_array = array();

	    // Array with the column names of the csv
	    $column_names = str_getcsv($file_lines[0], ',', '"');

	    foreach ($file_lines as $key => $row) {
	    		
	    	$csv_line = str_getcsv($row, ',', '"');
	    	$csv_line_ass = array();
	    	
	    	foreach ($csv_line as $key => $column) {
	    		$csv_line_ass[$column_names[$key]] = $column;
	    	}

	    	array_push($csv_array, $csv_line_ass);
	    }
	    unset($csv_array[0]);

		return $csv_array;
	}


	function import_organisation($file) {


		$organisation_users = $this->convert_csv_to_ass_array($file['tmp_name']);

	    	foreach ($organisation_users as $key => $new_user) {

	    		$user_name = $new_user['organisatie-email'];
	    		$user_email = $new_user['organisatie-email'];
				$user_id = username_exists( $new_user['organisatie-email'] );
				
				if ( !$user_id and email_exists($new_user['organisatie-email']) == false ) {
					
					$random_password = wp_generate_password( $length=6, $include_standard_special_chars=false );
					$user_id = wp_insert_user( array (
						'user_login' => $user_name, 
						'user_pass' => $random_password, 
						'user_email' => $user_email,
						'role' => 'cab_organisation'
						)
					);


					// Update user meta
					update_user_meta( $user_id, 'user-cab_organisatie-password', $random_password);
					update_user_meta( $user_id, 'user-cab_organisatie-naam', $new_user['organisatie-naam']);
					update_user_meta( $user_id, 'user-cab_organisatie-email', $new_user['organisatie-email']);
					update_user_meta( $user_id, 'user-cab_organisatie-administratief-naam', $new_user['organisatie-administratief-naam']);
					update_user_meta( $user_id, 'user-cab_organisatie-administratief-email', $new_user['organisatie-administratief-email']);
					update_user_meta( $user_id, 'user-cab_organisatie-directeur-naam', $new_user['organisatie-directeur-naam']);
					update_user_meta( $user_id, 'user-cab_organisatie-directeur-email', $new_user['organisatie-directeur-email']);
					update_user_meta( $user_id, 'user-cab_organisatie-directeur-functie', $new_user['organisatie-directeur-functie']);

					// Split huisnummer from street
					$adres = $this->cab_functions->split_street_number($new_user['postadres-straat']);
					update_user_meta( $user_id, 'user-cab_postadres-straat', $adres[0]);
					update_user_meta( $user_id, 'user-cab_postadres-huisnummer', $adres[1]);
					update_user_meta( $user_id, 'user-cab_postadres-postcode', $new_user['postadres-postcode']);
					update_user_meta( $user_id, 'user-cab_postadres-plaats', $new_user['postadres-plaats']);
					update_user_meta( $user_id, 'user-cab_postadres-gps', $new_user['postadres-gps']);

					// Split huisnummer from street
					$adres = $this->cab_functions->split_street_number($new_user['bezoekadres-straat']);
					update_user_meta( $user_id, 'user-cab_bezoekadres-straat', $adres[0]);
					update_user_meta( $user_id, 'user-cab_bezoekadres-huisnummer', $adres[1]);
					update_user_meta( $user_id, 'user-cab_bezoekadres-postcode', $new_user['bezoekadres-postcode']);
					update_user_meta( $user_id, 'user-cab_bezoekadres-plaats', $new_user['bezoekadres-plaats']);
					update_user_meta( $user_id, 'user-cab_bezoekadres-gps', $new_user['bezoekadres-gps']);
					update_user_meta( $user_id, 'user-cab_organisatie-telefoon', $new_user['organisatie-telefoon']);
					update_user_meta( $user_id, 'user-cab_organisatie-website', $new_user['organisatie-website']);
					update_user_meta( $user_id, 'user-cab_organisatie-facebook', $new_user['organisatie-facebook']);
					update_user_meta( $user_id, 'user-cab_organisatie-twitter', $new_user['organisatie-twitter']);



					// Create Organisation record
					$post_args = array(
					  'post_title'    => $new_user['organisatie-naam'],
					  'post_type'  => 'cab_organisation',
					  'post_status'   => 'pending',
					  'post_author' => $user_id
					);


					$post_id = wp_insert_post( $post_args );

					update_post_meta($post_id, 'gekoppelde_gebruiker', $user_id);

					$vragenlijst_categorie = explode(",", $new_user['vragenlijst_categorie']);
					update_post_meta($post_id, 'vragenlijst_categorie', serialize($vragenlijst_categorie));

					$gekoppeld = "";
					if ($new_user['bloementuin'] == 1) { $gekoppeld .= "1,"; }
					if ($new_user['vragenlijst'] == 1) { $gekoppeld .= "2,"; }
					$gekoppeld = rtrim($gekoppeld, ',');


					update_post_meta($post_id, 'gekoppeld', serialize(explode(",", $gekoppeld)));
					update_post_meta($post_id, 'vragenlijst_periodes', serialize(explode(",", $new_user['vragenlijst_periodes'])));
					update_post_meta($post_id, 'vragenlijst_notities', $new_user['vragenlijst_notitie']);




				} else {

					if (!isset($wp_error)) {
						$wp_error = new WP_Error();
					}
					$wp_error->add('user_exists', "User ".$user_name." already exists..");
					//array_push($errors, new WP_Error('error', "User ".$user_name." already exists.."));

				}
			}

		if (isset($wp_error)) {
		return $wp_error;
		}
		
	}












	function import_data($file) {

		$organisation_data = $this->convert_csv_to_ass_array($file['tmp_name']);


	    foreach ($organisation_data as $key => $organisation) {
	    
	    	// Check if organisation exists
	    	if (get_post($organisation['instelling_id'])) {

	    		$organisation_id = $organisation['instelling_id'];
	    		$period_id = $organisation['data_periode'];


	    		// Organisation categories
		    	$this->cab_functions->set_habtm_table_data(
		    		'cab_organisatie_keten', 
		    		array("keten_id" => explode(",", $organisation['keten'])), 
		    		$organisation_id, $period_id
		    	);

		    	$this->cab_functions->set_habtm_table_data(
		    		'cab_organisatie_type', 
		    		array("type_id" => explode(",", $organisation['type'])), 
		    		$organisation_id, $period_id
		    	);

		    	$this->cab_functions->set_habtm_table_data(
		    		'cab_organisatie_discipline', 
		    		array("discipline_id" => explode(",", $organisation['discipline'])), 
		    		$organisation_id, $period_id
		    	);


		    	// Subsidy
		    	$columns = array(
				  'totaal' => $organisation['subsidie_totaal'],
				  'gemeente' => $organisation['subsidie_gemeente'],
				  'prov_nb' => $organisation['subsidie_provincie'],
				  'rijk' => $organisation['subsidie_rijk'],
				  'fonds_podiumkunsten' => $organisation['subsidie_fonds_podiumkunsten'],
				  'mondriaan_stichting' => $organisation['subsidie_mondriaanstichting'],
				  'fonds_bkvb' => $organisation['subsidie_fonds_bkvb'],
				  'mediafonds' => $organisation['subsidie_mediafonds'],
				  'nl_filmfonds' => $organisation['subsidie_nl_film_fonds'],
				  'fonds_creatieve_industrie' => $organisation['subsidie_stim_fonds_ci'],
				  'letterenfonds' => $organisation['subsidie_letterenfonds'],
				  'overig' => $organisation['subsidie_overige_fondsen'],
				  'mondriaan_fonds' => $organisation['subsidie_mondriaanfonds'],
				  'gemeente_meerjarig' => $organisation['subsidie_gemeente_meerjarig'],
				  'prov_nb_meerjarig' => $organisation['subsidie_provincie_meerjarig'],
				  'rijk_meerjarig' => $organisation['subsidie_rijk_meerjarig'],
				  'fonds_podiumkunsten_meerjarig' => $organisation['subsidie_fonds_podiumkunsten_meerjarig'],
				  'mondriaan_stichting_meerjarig' => $organisation['subsidie_mondriaanstichting_meerjarig'],
				  'mondriaan_fonds_meerjarig' => $organisation[''],
				  'fonds_bkvb_meerjarig' => $organisation['subsidie_fonds_bkvb_meerjarig'],
				  'mediafonds_meerjarig' => $organisation['subsidie_mediafonds_meerjarig'],
				  'nl_filmfonds_meerjarig' => $organisation['subsidie_nl_film_fonds_meerjarig'],
				  'fonds_creatieve_industrie_meerjarig' => $organisation['subsidie_stim_fonds_ci_meerjarig'],
				  'letterenfonds_meerjarig' => $organisation['subsidie_letterenfonds_meerjarig'],
				  'overig_toelichting' => $organisation['namen_overige_fondsen']
		    	);

		    	$this->cab_functions->add_data("cab_subsidy", $organisation_id, $period_id,$columns); 

		    	// Eigen inkomsten
		    	$columns = array(
		    		'totaal' => $organisation['eigen_inkomsten_totaal'],
		    		'publieksinkomsten' => $organisation['eigen_inkomsten_publiek'],
  					'sponsoring' => $organisation['eigen_inkomsten_sponsoring'],
  					'private_fondsen' => $organisation['eigen_inkomsten_private_fondsen'],
  					'overig' => $organisation['eigen_inkomsten_overig']
  				);
		    	$this->cab_functions->add_data("cab_eigen_inkomsten", $organisation_id, $period_id,$columns); 
 
		    	// Omzet
		    	$columns = array(
		    		'totaal' => $organisation['omzet_totaal']
  				);
		    	$this->cab_functions->add_data("cab_omzet", $organisation_id, $period_id,$columns); 

		    	// Organisatie
		    	$columns = array(
		    		'fte' => $this->cab_functions->comma_to_dot($organisation['organisatie_fte']),
		    		'freelancers' => $organisation['organisatie_freelancers'],
  					'vrijwilligers' => $organisation['organisatie_vrijwilligers'],
  					'stagiaires' => $organisation['organisatie_stagiaires']
  				);
		    	$this->cab_functions->add_data("cab_organisatie", $organisation_id, $period_id,$columns); 

		    	// Scholing
		    	$columns = array(
		    		'uitgaven' => $organisation['scholing_uitgaven']
  				);
		    	$this->cab_functions->add_data("cab_scholing", $organisation_id, $period_id,$columns); 

		    	// Media
		    	$columns = array(
		    		'aandacht' => $organisation['media_aandacht']
  				);
		    	$this->cab_functions->add_data("cab_media", $organisation_id, $period_id,$columns); 

		    	// Marketing
		    	$columns = array(
		    		'uitgaven' => $organisation['marketing_uitgaven']
  				);
		    	$this->cab_functions->add_data("cab_marketing", $organisation_id, $period_id,$columns); 



		    	// Aanvullende vragen lijsten
		    	// Festivals

		    	// Activiteiten
				$columns = array(
					'totaal' => $organisation['1_programmaonderdelen_totaal'],
					'in_opdracht' => '',
					'eigen_werk' => '',
					'premieres' => '',
					'aanv_vragenlijst_id' => 1
  				);
		    	$this->cab_functions->add_data("cab_activiteiten", $organisation_id, $period_id,$columns); 

		    	// Nevenactiviteiten
				$columns = array(
					'totaal' => $organisation['1_nevenactiviteiten_totaal'],
					'educatief' => $organisation['1_educatieve_activiteiten'],
					'overig' => $organisation['1_overige_activiteiten'],
					'overig_toelichting' => $organisation['1_nevenactiviteiten_over_text'],
					'aanv_vragenlijst_id' => 1
  				);
		    	$this->cab_functions->add_data("cab_nevenactiviteiten", $organisation_id, $period_id,$columns); 

 		    	// Bezoekers
				$columns = array(
					'totaal' => $organisation['1_bezoekers_totaal'],
					'standplaats' => $organisation['1_bezoekers_standplaats'],
					'provincie' => $organisation['1_bezoekers_noord_brabant'],
					'nederland' => $organisation['1_bezoekers_nederland'],
					'buitenland' => $organisation['1_bezoekers_buitenland'],
					'podium' => '',
					'festivals' => '',
					'overig' => '',					
					'aanv_vragenlijst_id' => 1
  				);
		    	$this->cab_functions->add_data("cab_bezoekers", $organisation_id, $period_id,$columns); 



		    	// BKV/AV
				$columns = array(
					'totaal' => $organisation['2_activiteiten_totaal'],
					'in_opdracht' => '',
					'eigen_werk' => '',
					'premieres' => '',
					'aanv_vragenlijst_id' => 2
  				);
		    	$this->cab_functions->add_data("cab_activiteiten", $organisation_id, $period_id,$columns); 

		    	// Nevenactiviteiten
				$columns = array(
					'totaal' => $organisation['2_nevenactiviteiten_totaal'],
					'educatief' => $organisation['2_nevenactiviteiten_educatief'],
					'overig' => $organisation['2_nevenactiviteiten_overig'],
					'overig_toelichting' => $organisation['2_nevenactiviteiten_over_text'],
					'aanv_vragenlijst_id' => 2
  				);
		    	$this->cab_functions->add_data("cab_nevenactiviteiten", $organisation_id, $period_id,$columns); 

 		    	// Bezoekers
				$columns = array(
					'totaal' => $organisation['2_bezoekers_totaal'],
					'standplaats' => $organisation['2_bezoekers_standplaats'],
					'provincie' => $organisation['2_bezoekers_noord_brabant'],
					'nederland' => $organisation['2_bezoekers_nederland'],
					'buitenland' => $organisation['2_bezoekers_buitenland'],
					'podium' => '',
					'festivals' => '',
					'overig' => '',					
					'aanv_vragenlijst_id' => 2
  				);
		    	$this->cab_functions->add_data("cab_bezoekers", $organisation_id, $period_id,$columns); 


		    	// FILM
				$columns = array(
					'totaal' => $organisation['3_producties_totaal'],
					'in_opdracht' => $organisation['3_waarvan_opdracht'],
					'eigen_werk' => $organisation['3_waarvan_eigen_werk'],
					'premieres' => '',
					'aanv_vragenlijst_id' => 3
  				);
		    	$this->cab_functions->add_data("cab_activiteiten", $organisation_id, $period_id,$columns); 

		    	// vertoningen
				$columns = array(
					'totaal' => $organisation['3_vertoningen_totaal'],
					'standplaats' => $organisation['3_vertoningen_standplaats'],
					'provincie' => $organisation['3_vertoningen_noord_brabant'],
					'nederland' => $organisation['3_vertoningen_nederland'],
					'buitenland' => $organisation['3_vertoningen_buitenland'],
					'bioscoop' => $organisation['3_vertoningen_bioscoop'],
					'filmhuis' => $organisation['3_vertoningen_filmhuis'],
					'festival' => $organisation['3_vertoningen_festival'],
					'omroep' => $organisation['3_vertoningen_omroep'],
					'internet' => $organisation['3_vertoningen_internet'],
					'internet_toelichting' => $organisation['3_vertoningen_internet_text'],
					'aanv_vragenlijst_id' => 3
  				);
		    	$this->cab_functions->add_data("cab_vertoningen", $organisation_id, $period_id,$columns); 


		    	// Musea
		    	$columns = array(
					'totaal' => $organisation['4_activiteiten_totaal'],
					'in_opdracht' => '',
					'eigen_werk' => '',
					'premieres' => '',
					'aanv_vragenlijst_id' => 4
  				);
		    	$this->cab_functions->add_data("cab_activiteiten", $organisation_id, $period_id,$columns); 

		    	// Nevenactiviteiten
				$columns = array(
					'totaal' => $organisation['4_nevenactiviteiten_totaal'],
					'educatief' => $organisation['4_nevenactiviteiten_educatief'],
					'overig' => $organisation['4_nevenactiviteiten_overig'],
					'overig_toelichting' => $organisation['4_nevenactiviteiten_over_text'],
					'aanv_vragenlijst_id' => 4
  				);
		    	$this->cab_functions->add_data("cab_nevenactiviteiten", $organisation_id, $period_id,$columns); 

 		    	// Bezoekers
				$columns = array(
					'totaal' => $organisation['4_bezoekers_totaal'],
					'standplaats' => $organisation['4_bezoekers_standplaats'],
					'provincie' => $organisation['4_bezoekers_noord_brabant'],
					'nederland' => $organisation['4_bezoekers_nederland'],
					'buitenland' => $organisation['4_bezoekers_buitenland'],
					'podium' => '',
					'festivals' => '',
					'overig' => '',					
					'aanv_vragenlijst_id' => 4
  				);
		    	$this->cab_functions->add_data("cab_bezoekers", $organisation_id, $period_id,$columns); 



		    	// PODIA
		    	$columns = array(
					'totaal' => $organisation['5_activiteiten_totaal'],
					'in_opdracht' => '',
					'eigen_werk' => '',
					'premieres' => '',
					'aanv_vragenlijst_id' => 5
  				);
		    	$this->cab_functions->add_data("cab_activiteiten", $organisation_id, $period_id,$columns); 

		    	// Nevenactiviteiten
				$columns = array(
					'totaal' => $organisation['5_nevenactiviteiten_totaal'],
					'educatief' => $organisation['5_nevenactiviteiten_educatief'],
					'overig' => $organisation['5_nevenactiviteiten_overig'],
					'overig_toelichting' => $organisation['5_nevenactiviteiten_over_text'],
					'aanv_vragenlijst_id' => 5
  				);
		    	$this->cab_functions->add_data("cab_nevenactiviteiten", $organisation_id, $period_id,$columns); 


 		    	// Bezoekers
				$columns = array(
					'totaal' => $organisation['5_bezoekers_totaal'],
					'standplaats' => $organisation['5_bezoekers_standplaats'],
					'provincie' => $organisation['5_bezoekers_noord_brabant'],
					'nederland' => $organisation['5_bezoekers_nederland'],
					'buitenland' => $organisation['5_bezoekers_buitenland'],
					'podium' => '',
					'festivals' => '',
					'overig' => '',					
					'aanv_vragenlijst_id' => 5
  				);
		    	$this->cab_functions->add_data("cab_bezoekers", $organisation_id, $period_id,$columns); 



// PODIA 2
		    	$columns = array(
					'totaal' => $organisation['7_activiteiten_totaal'],
					'in_opdracht' => '',
					'eigen_werk' => '',
					'premieres' => '',
					'aanv_vragenlijst_id' => 7
  				);
		    	$this->cab_functions->add_data("cab_activiteiten", $organisation_id, $period_id,$columns); 

		    	// Nevenactiviteiten
				$columns = array(
					'totaal' => $organisation['7_nevenactiviteiten_totaal'],
					'educatief' => $organisation['7_nevenactiviteiten_educatief'],
					'overig' => $organisation['7_nevenactiviteiten_overig'],
					'overig_toelichting' => $organisation['7_nevenactiviteiten_over_text'],
					'aanv_vragenlijst_id' => 7
  				);
		    	$this->cab_functions->add_data("cab_nevenactiviteiten", $organisation_id, $period_id,$columns); 


 		    	// Bezoekers
				$columns = array(
					'totaal' => $organisation['7_bezoekers_totaal'],
					'standplaats' => $organisation['7_bezoekers_standplaats'],
					'provincie' => $organisation['7_bezoekers_noord_brabant'],
					'nederland' => $organisation['7_bezoekers_nederland'],
					'buitenland' => $organisation['7_bezoekers_buitenland'],
					'podium' => '',
					'festivals' => '',
					'overig' => '',					
					'aanv_vragenlijst_id' => 7
  				);
		    	$this->cab_functions->add_data("cab_bezoekers", $organisation_id, $period_id,$columns); 



		    	// PODIUMKUNSTEN
		    	$columns = array(
					'totaal' => $organisation['6_activiteiten_totaal'],
					'in_opdracht' => '',
					'eigen_werk' => '',
					'premieres' => $organisation['6_activiteiten_premieres'],
					'aanv_vragenlijst_id' => 6
  				);
		    	$this->cab_functions->add_data("cab_activiteiten", $organisation_id, $period_id,$columns); 

 		    	// Spreiding
				$columns = array(
					'standplaats' => $organisation['6_uitvoeringen_standplaats'],
					'provincie' => $organisation['6_uitvoeringen_noord_brabant'],
					'nederland' => $organisation['6_uitvoeringen_nederland'],
					'buitenland' => $organisation['6_uitvoeringen_buitenland'],
					'podium' => $organisation['6_uitvoeringen_podiumcircuit'],
					'festivals' => $organisation['6_uitvoeringen_festivals'],
					'scholen' => $organisation['6_uitvoeringen_scholen'],
					'overig' => $organisation['6_uitvoeringen_overig'],				
					'aanv_vragenlijst_id' => 6
  				);
		    	$this->cab_functions->add_data("cab_spreiding", $organisation_id, $period_id,$columns); 

		    	// Nevenactiviteiten
				$columns = array(
					'totaal' => $organisation['6_nevenactiviteiten_totaal'],
					'educatief' => $organisation['6_waarvan_educatief'],
					'overig' => $organisation['6_waarvan_overig'],
					'overig_toelichting' => $organisation['6_waarvan_overig_tekst'],
					'aanv_vragenlijst_id' => 6
  				);
		    	$this->cab_functions->add_data("cab_nevenactiviteiten", $organisation_id, $period_id,$columns); 

 		    	// Bezoekers
				$columns = array(
					'totaal' => $organisation['6_bezoekers_totaal'],
					'standplaats' => $organisation['6_bezoekers_standplaats'],
					'provincie' => $organisation['6_bezoekers_brabant'],
					'nederland' => $organisation['6_bezoekers_nederland'],
					'buitenland' => $organisation['6_bezoekers_buitenland'],
					'podium' => $organisation['6_bezoekers_podiumcircuit'],
					'festivals' => $organisation['6_bezoekers_festivals'],
					'scholen' => $organisation['6_bezoekers_scholen'],
					'overig' => $organisation['6_bezoekers_overig'],				
					'aanv_vragenlijst_id' => 6
  				);
		    	$this->cab_functions->add_data("cab_bezoekers", $organisation_id, $period_id,$columns); 





	    	} else {
	    					if (!isset($wp_error)) {
						$wp_error = new WP_Error();
					}
	    		// Organisation does not exist
	    		$wp_error->add('organisation', "Organisation with id:".$organisation['instelling_id']." does not exist.");
	    	}



	    }

	    		if (isset($wp_error)) {
		return $wp_error;
		} 

		//print_r($organisation_data);
		/*
		            [instelling_id] => 68
            [type] => 8
            [discipline] => 1
            [keten] => 2,1
            [] => 
            [data_periode] => 2
            [subsidie_totaal] => 3507959
            [subsidie_gemeente] => 545953
            [subsidie_gemeente_meerjarig] => 0
            [subsidie_provincie] => 318785
            [subsidie_provincie_meerjarig] => 1
            [subsidie_rijk] => 2643221
            [subsidie_rijk_meerjarig] => 1
            [subsidie_fonds_podiumkunsten] => 
            [subsidie_fonds_podiumkunsten_meerjarig] => 
            [subsidie_mondriaanstichting] => 
            [subsidie_mondriaanstichting_meerjarig] => 
            [subsidie_mondriaanfonds] => 
            [subsidie_fonds_bkvb] => 
            [subsidie_fonds_bkvb_meerjarig] => 
            [subsidie_mediafonds] => 
            [subsidie_mediafonds_meerjarig] => 
            [subsidie_nl_film_fonds] => 
            [subsidie_nl_film_fonds_meerjarig] => 
            [subsidie_stim_fonds_ci] => 
            [subsidie_stim_fonds_ci_meerjarig] => 
            [subsidie_letterenfonds] => 
            [subsidie_letterenfonds_meerjarig] => 
            [subsidie_overige_fondsen] => 
            [namen_overige_fondsen] => 
            [eigen_inkomsten_totaal] => 542768
            [eigen_inkomsten_publiek] => 370833
            [eigen_inkomsten_sponsoring] => 
            [eigen_inkomsten_private_fondsen] => 15600
            [eigen_inkomsten_overig] => 156335
            [omzet_totaal] => 4050727
            [organisatie_fte] => 24.11
            [organisatie_freelancers] => 
            [organisatie_vrijwilligers] => 
            [organisatie_stagiaires] => 
            [scholing_uitgaven] => 4588
            [marketing_uitgaven] => 98829
            [media_aandacht] => 
            [1_programmaonderdelen_totaal] => 
            [1_nevenactiviteiten_totaal] => 
            [1_educatieve_activiteiten] => 
            [1_overige_activiteiten] => 
            [1_nevenactiviteiten_over_text] => 
            [1_bezoekers_totaal] => 
            [1_bezoekers_standplaats] => 
            [1_bezoekers_noord_brabant] => 
            [1_bezoekers_nederland] => 
            [1_bezoekers_buitenland] => 
            [2_activiteiten_totaal] => 
            [2_nevenactiviteiten_totaal] => 
            [2_nevenactiviteiten_educatief] => 
            [2_nevenactiviteiten_overig] => 
            [2_nevenactiviteiten_over_text] => 
            [2_bezoekers_totaal] => 
            [2_bezoekers_standplaats] => 
            [2_bezoekers_noord_brabant] => 
            [2_bezoekers_nederland] => 
            [2_bezoekers_buitenland] => 
            [3_producties_totaal] => 
            [3_waarvan_opdracht] => 
            [3_waarvan_eigen_werk] => 
            [3_vertoningen_totaal] => 
            [3_vertoningen_standplaats] => 
            [3_vertoningen_noord_brabant] => 
            [3_vertoningen_nederland] => 
            [3_vertoningen_buitenland] => 
            [3_vertoningen_bioscoop] => 
            [3_vertoningen_filmhuis] => 
            [3_vertoningen_festival] => 
            [3_vertoningen_omroep] => 
            [3_vertoningen_internet] => 
            [3_vertoningen_internet_text] => 
            [4_activiteiten_totaal] => 
            [4_nevenactiviteiten_totaal] => 
            [4_nevenactiviteiten_educatief] => 
            [4_nevenactiviteiten_overig] => 
            [4_nevenactiviteiten_over_text] => 
            [4_bezoekers_totaal] => 
            [4_bezoekers_standplaats] => 
            [4_bezoekers_noord_brabant] => 
            [4_bezoekers_nederland] => 
            [4_bezoekers_buitenland] => 
            [5_activiteiten_totaal] => 
            [5_nevenactiviteiten_totaal] => 
            [5_nevenactiviteiten_educatief] => 
            [5_nevenactiviteiten_overig] => 
            [5_nevenactiviteiten_over_text] => 
            [5_bezoekers_totaal] => 
            [5_bezoekers_standplaats] => 
            [5_bezoekers_noord_brabant] => 
            [5_bezoekers_nederland] => 
            [5_bezoekers_buitenland] => 
            [6_activiteiten_totaal] => 186
            [6_activiteiten_premieres] => 186
            [6_uitvoeringen_standplaats] => 24
            [6_uitvoeringen_noord_brabant] => 79
            [6_uitvoeringen_nederland] => 64
            [6_uitvoeringen_buitenland] => 19
            [6_uitvoeringen_podiumcircuit] => 
            [6_uitvoeringen_festivals] => 
            [6_uitvoeringen_scholen] => 
            [6_uitvoeringen_overig] => 
            [6_nevenactiviteiten_totaal] => 81
            [6_waarvan_educatief] => 74
            [6_waarvan_overig] => 7
            [6_waarvan_overig_tekst] => 
            [6_bezoekers_totaal] => 24932
            [6_bezoekers_standplaats] => 4247
            [6_bezoekers_brabant] => 8344
            [6_bezoekers_nederland] => 10348
            [6_bezoekers_buitenland] => 1993
            [6_bezoekers_podiumcircuit] => 
            [6_bezoekers_festivals] => 
            [6_bezoekers_scholen] => 
            [6_bezoekers_overig] => 
            [7_podium_activiteiten_totaal] => 
            [7_podium_activiteiten_premieres] => 
            [7_podium_uitvoeringen_standplaats] => 
            [7_podium_uitvoeringen_noord_brabant] => 
            [7_podium_uitvoeringen_nederland] => 
            [7_podium_uitvoeringen_buitenland] => 
            [7_podium_uitvoeringen_podiumcircuit] => 
            [7_podium_uitvoeringen_festivals] => 
            [7_podium_uitvoeringen_scholen] => 
            [7_podium_uitvoeringen_overig] => 
            [7_podium_nevenactiviteiten_totaal] => 
            [7_podium_waarvan_educatief] => 
            [7_podium_waarvan_overig] => 
            [7_podium_waarvan_overig_tekst] => 
            [7_podium_bezoekers_totaal] => 
            [7_podium_bezoekers_standplaats] => 
            [7_podium_bezoekers_brabant] => 
            [7_podium_bezoekers_nederland] => 
            [7_podium_bezoekers_buitenland] => 
            [7_podium_bezoekers_podiumcircuit] => 
            [7_podium_bezoekers_festivals] => 
            [7_podium_bezoekers_scholen] => 
            [7_podium_bezoekers_overig] => 
            [7_film_activiteiten_totaal] => 
            [7_film_activiteiten_premieres] => 
            [7_film_uitvoeringen_standplaats] => 
            [7_film_uitvoeringen_noord_brabant] => 
            [7_film_uitvoeringen_nederland] => 
            [7_film_uitvoeringen_buitenland] => 
            [7_film_uitvoeringen_filmcircuit] => 
            [7_film_uitvoeringen_festivals] => 
            [7_film_uitvoeringen_scholen] => 
            [7_film_uitvoeringen_overig] => 
            [7_film_nevenactiviteiten_totaal] => 
            [7_film_waarvan_educatief] => 
            [7_film_waarvan_overig] => 
            [7_film_waarvan_overig_tekst] => 
            [7_film_bezoekers_totaal] => 
            [7_film_bezoekers_standplaats] => 
            [7_film_bezoekers_brabant] => 
            [7_film_bezoekers_nederland] => 
            [7_film_bezoekers_buitenland] => 
            [7_film_bezoekers_filmcircuit] => 
            [7_film_bezoekers_festivals] => 
            [7_film_bezoekers_scholen] => 
            [7_film_bezoekers_overig] => 
            */

	}


























	function show_import_export() {

		global $wp_error;
		//must check that the user has the required capability 
	    if (!current_user_can('manage_options'))
	    {
	      wp_die( __('You do not have sufficient permissions to access this page.') );
	    }

	    // See if the user has posted us some information
	    // If they did, this hidden field will be set to 'Y'
	    if( isset($_FILES['import_file']) ) {

	    	$error_return = $this->import_organisation($_FILES['import_file']);
	    	
	    	

	    	// Show feedback to the user
			if (isset($error_return)) {
				$errors = $error_return->get_error_messages();
				$error_list = "<ul>";
				foreach ($errors as $key => $error) {
					$error_list .= "<li>".$error."</li>";
				}
				$error_list .= "</ul>";
			
				echo '<div class="error"><h3>Some errors occured while importing:</h3>'.$error_list.'</div>';

			} else {
				echo '<div class="updated"><p>The organisations where successfully added to the system.</div>';
			}

		}

	 	if( isset($_FILES['import_data_file']) ) {

	    	$error_return = $this->import_data($_FILES['import_data_file']);
	    	
	    	

	    	// Show feedback to the user
			if (isset($error_return)) {
				$errors = $error_return->get_error_messages();
				$error_list = "<ul>";
				foreach ($errors as $key => $error) {
					$error_list .= "<li>".$error."</li>";
				}
				$error_list .= "</ul>";
			
				echo '<div class="error"><h3>Some errors occured while importing:</h3>'.$error_list.'</div>';

			} else {
				echo '<div class="updated"><p>The data is added to the system successfully.</div>';
			}

		}




	    // Now display the settings editing screen
	    echo '<div class="wrap">';

	    // header
	    echo "<h2>Import / Export</h2>";

	    // settings form
	    ?>

		<form name="form1" enctype="multipart/form-data" method="post" action="">

			<p><label>Upload organisation *.csv</label>
			<input type="file" name="import_file" />

			</p><hr />

			<p class="submit">
			<input type="submit" name="Submit" class="button-primary" value="import organisation" />
			</p>

		</form>
		<hr/>
		<form name="form1" enctype="multipart/form-data" method="post" action="">

			<p><label>Upload data *.csv</label>
			<input type="file" name="import_data_file" />

			</p><hr />

			<p class="submit">
			<input type="submit" name="Submit" class="button-primary" value="import data" />
			</p>

		</form>
	
		<?

		}

}


$cab_user = new cab_user();


?>