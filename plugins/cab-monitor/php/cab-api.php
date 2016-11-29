<?php

class cab_api
{

	var $settings;
			//$type_array
		var $type_array = array(
			array("id"=>1,"name"=>"Theater","total_2011"=>0,"total_2013"=>0),
			array("id"=>2,"name"=>"Concertzaal","total_2011"=>0,"total_2013"=>0),
			array("id"=>3,"name"=>"Filmtheater","total_2011"=>0,"total_2013"=>0),
			array("id"=>4,"name"=>"Festival","total_2011"=>0,"total_2013"=>0),
			array("id"=>5,"name"=>"Museum","total_2011"=>0,"total_2013"=>0),
			array("id"=>6,"name"=>"Bibliotheek","total_2011"=>0,"total_2013"=>0),
			array("id"=>7,"name"=>"Cbk","total_2011"=>0,"total_2013"=>0),
			array("id"=>8,"name"=>"Gezelschap","total_2011"=>0,"total_2013"=>0),
			array("id"=>9,"name"=>"Werkplaats","total_2011"=>0,"total_2013"=>0),
			array("id"=>10,"name"=>"Opleiding","total_2011"=>0,"total_2013"=>0),
			array("id"=>11,"name"=>"Steunfunctie","total_2011"=>0,"total_2013"=>0)
			);



var $export_organisations = array(

	"2011" => array(
		"tilburg" => array(
			"podiumkunsten" => array(412,413,414,415,416,417,418,419),
			"bkv_av" => array(421,423), //424
			"festivals" => array(428),
			"musea" => array(429 ),
			"pop_podia" => array(435),
			"overige_podia" => array(434)
			),
		"breda" => array(
			"podiumkunsten" => array(462),
			"bkv_av" => array(463,465,466),
			"festivals" => array(467),
			"musea" => array(468,469),
			"pop_podia" => array(476),
			"overige_podia" => array(474)
			),
		"den_bosch" => array(
			"podiumkunsten" => array(497,498,499,501,502),
			"bkv_av" => array(503,504), // 505
			"festivals" => array(506,507,508,509),
			"musea" => array( ),
			"pop_podia" => array(518 ),
			"overige_podia" => array(515,517,519)
			),
		"eindhoven" => array(
			"podiumkunsten" => array(536,537,538,539),
			"bkv_av" => array(540,541,542),
			"festivals" => array(543,544,545),
			"musea" => array(550),
			"pop_podia" => array(553),
			"overige_podia" => array(555,556) //554
			),
		"helmond" => array(
			"podiumkunsten" => array(578),
			"bkv_av" => array( 579),
			"festivals" => array( ),
			"musea" => array(580 ),
			"pop_podia" => array(584 ),
			"overige_podia" => array(582,583,585)
			)
	),

	"2013" => array(
		"tilburg" => array(
			"podiumkunsten" => array(412,414,415,418,420),
			"bkv_av" => array(421,423),
			"festivals" => array( ),
			"musea" => array( 429),
			"pop_podia" => array(435),
			"overige_podia" => array(434)
			),
		"breda" => array(
			"podiumkunsten" => array(462),
			"bkv_av" => array( 463,464,465,466),
			"festivals" => array(467),
			"musea" => array(468),
			"pop_podia" => array(476),
			"overige_podia" => array(474 )
			),
		"den_bosch" => array(
			"podiumkunsten" => array(497,499,501,502),
			"bkv_av" => array(503,504, 505),
			"festivals" => array(506,507,508,509),
			"musea" => array( ),
			"pop_podia" => array( ),
			"overige_podia" => array(515,517,519)
			),
		"eindhoven" => array(
			"podiumkunsten" => array(537,538,539),
			"bkv_av" => array(540,541),
			"festivals" => array(543,544,545 ),
			"musea" => array(550),
			"pop_podia" => array(553),
			"overige_podia" => array(555) //554
			),
		"helmond" => array(
			"podiumkunsten" => array(),
			"bkv_av" => array( 579),
			"festivals" => array( ),
			"musea" => array( 580),
			"pop_podia" => array(584 ),
			"overige_podia" => array( 582,583,585)
			)
	),

);



// subsidy, eigen inkomsten, omzet
var $export_organisations_merge_kunstpodium = array(

	"2011" => array(
		"tilburg" => array(
			"bkv_av" => array(424), //424
		)
	),

	"2013" => array(
		"tilburg" => array(
			"bkv_av" => array(424), //424
		)
	),

);

// subsidy, eigen inkomsten, omzet, organisatie, scholing, marketing, media(2011)
var $export_organisations_merge_kw14 = array(

	"2011" => array(
		"den_bosch" => array(
			"bkv_av" => array(505),
		)
	)

);

// subsidy, eigen inkomsten, omzet, organisatie, scholing, marketing, media(2011)
var $export_organisations_merge_plaza_futura = array(

	"2011" => array(
		"eindhoven" => array(
			"overige_podia" => array(554)
			),
	),

	"2013" => array(
		"eindhoven" => array(
			"overige_podia" => array(554)
			),
	),

);


//$export_organisations = combine_organisation_lists($export_organisations, $export_organisations_merge_kw14);



	function __construct()
	{

		global $cab_functions;

		$this->cab_functions = $cab_functions;

       // add_action( 'save_post', array($this, 'cd_meta_box_save'),1 );


	}

	function remove_autosave($organisation_id, $period_id) {
		return $this->cab_functions->form_activity_remove_autosave($organisation_id, $period_id);
	}

	function unlock_form($organisation_id, $period_id) {
		return $this->cab_functions->form_activity_release($organisation_id, $period_id);
	}

	function lock_form($organisation_id, $period_id) {
		return $this->cab_functions->form_activity_lock($organisation_id, $period_id);
	}

	function get_auto_save($organisation_id, $period_id) {

		$data = $this->cab_functions->form_activity_get_data($organisation_id, $period_id);
		return $data;
	}


	function get_organisation_passwords() {

		$organisations = $this->cab_functions->get_all_organisations();

		 foreach ($organisations as $key => $organisation) {
		 	$organisation_id = $organisation->ID;

		 	$owner_id = $this->cab_functions->get_user_id_by_organisation_id($organisation_id);
			$user_meta = get_user_meta($owner_id);



		// 		// Get meta data
		// 		$owner_id = $this->cab_functions->get_user_id_by_organisation_id($organisation->ID);
		// 		$user_meta = get_user_meta($owner_id);
		 	echo $organisation_id.",".$organisation->post_title.",".$user_meta['nickname'][0].",".$user_meta['user-cab_organisatie-password'][0]."\n";
		 }
	}





	function test_mail() {


		$mail_array = array();

		// Get all vragenlijst organisations
		//$organisations = $this->cab_functions->get_all_organisations();
		$organisations = $this->cab_functions->get_organisation_by_id(671);

		foreach ($organisations as $key => $organisation) {

			$allowed_applications = get_field("gekoppeld", $organisation->ID);

			// If organisation receives vragenlijst
			if (in_array(2, $allowed_applications)) {


				// Get meta data
				$owner_id = $this->cab_functions->get_user_id_by_organisation_id($organisation->ID);
				$user_meta = get_user_meta($owner_id);
				$post_data = get_post_custom($organisation->ID);


				$email_adresses = array($user_meta['user-cab_organisatie-email'][0],$user_meta['user-cab_organisatie-administratief-email'][0],$user_meta['user-cab_organisatie-directeur-email'][0]);


				$email_adresses = array_filter($email_adresses); // remove empty
				$email_adresses = array_unique($email_adresses); // remove duplicates



				array_push($mail_array, array(
					"organisation_id"=>$organisation->ID,
					"owner_id"=>$owner_id,
					"organisation_name"=>$organisation->post_title,
					"user_name" => $user_meta['nickname'][0],
					"user_password" => $user_meta['user-cab_organisatie-password'][0],
					"user_notes" => $post_data['vragenlijst_notities'][0],
					"email_adresses"=>$email_adresses
				));
							//		echo "<li>".$organisation->post_title." - <a href='".$organisation->guid."''>vragenlijst</a></li>"

			}

		}












		$subject = "Vul de vragenlijst voor de Monitor Professionele Kunsten voor 1 september in.";
		$message = '<html lang="en">
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>
Vul de vragenlijst voor de Monitor Professionele Kunsten voor 1 september in.
</title>

<!--[if gte mso 9]>
<style type="text/css">
.body{background: #ffffff;}
.case { background:none;}
.content p, .content td {
font-size:12px;
line-height:16px;
margin-bottom:10px;
}
</style>
<![endif]-->
</head>

<body style="color: #000000; margin: 0; padding: 0;" class="body">

<table cellpadding="10" cellspacing="0" border="0" style="font-family: Verdana; line-height: 1px;">


    <!-- Intro -->



    <!-- Spacer -->
    <tr>
        <td>
            <!-- Begin Spacer -->
            <table cellpadding="0" cellspacing="0" border="0" width="650" style="font-family: Verdana; line-height: 1px;">
            <tr><td height="0"></td></tr>
            </table>
            <!-- End Spacer -->
        </td>
    </tr>

    <!-- Content -->

     <tr>
        <td>
            <!-- Begin Spacer -->
            <table cellpadding="0" cellspacing="0" border="0" width="650" class="content" style="font-family: Verdana; line-height: 1px;">
            <tr>
                <td width="500" style="font-size: 13px; line-height: 16px; margin-bottom: 25px;">

<p style="font-size: 13px; line-height: 16px; margin-bottom: 25px;">Beste lezer,</p>
<p style="font-size: 13px; line-height: 16px; margin-bottom: 25px;">In het najaar van 2013 brengt bkkc voor het eerst de Monitor Professionele Kunsten uit. In deze tweejaarlijkse publicatie vindt u beleidsinformatie op basis van een selecte groep Brabantse culturele instellingen. Aan de basis van deze publicatie ligt zowel kwantitatief als kwalitatief onderzoek ten grondslag. Kwantitatief onderzoek middels een vragenlijst en kwalitatief onderzoek middels (sector)analyses geschreven door adviseurs van bkkc. Om tot juiste informatie te komen, <strong>willen we u vragen de volgende vragenlijst in te vullen</strong>. </p>

<p style="font-size: 13px; line-height: 16px; margin-bottom: 25px;">Het doel is om facts &amp; figures te bieden over de professionele kunsten in Brabant waarmee een beeld geschetst wordt van de trends en ontwikkelingen en input gegeven kan worden voor beleid. Ook kunnen we hiermee de discussie over cultuur(beleid) samen beter voeren. In deze eerste Monitor willen we de jaren 2011 en 2013 vergelijken. Over twee jaar doen we een soortgelijk onderzoek en vergelijken we 2013 met 2015. Uiteraard ontvangt u in november de publicatie.</p>

<p style="font-size: 13px; line-height: 16px; margin-bottom: 25px;"><strong>Deels al ingevuld</strong><br/>
Om u wat werk te besparen hebben wij de vragenlijst al zo goed mogelijk proberen in te vullen op basis van de eerder door u aangeleverde stukken en stukken die wij hebben opgevraagd bij gemeentes, de provincie en de landelijke fondsen. We willen u vragen de vragen nog een keer helemaal door te lopen en alles te controleren en aan te vullen. Eerst komen alle vragen over 2011, daarna de vragen over 2013. Het invullen duurt maximaal 20 minuten. </p>

<p style="font-size: 13px; line-height: 16px; margin-bottom: 25px;">U kunt de vragenlijst halverwege even wegleggen en later verdergaan als u wilt. Pas als u ook daadwerkelijk uw gegevens verzendt, dan kunt u niets meer aanpassen. Voorheen werden de gegevens openbaar gemaakt op de website <a href="http://www.cultureleatlasbrabant.nl">www.cultureleatlasbrabant.nl</a>. Dat is nu niet meer het geval, daar staan enkel uw basisgegevens. </p>

<p style="font-size: 13px; line-height: 16px; margin-bottom: 25px;"><strong><a href="http://www.cultureleatlasbrabant.nl/monitor">Klik hier voor de vragenlijst.</a></strong><br/>
Log in: {user-login}<br/>
Password: {user-password}</p>

<p style="font-size: 13px; line-height: 16px; margin-bottom: 25px;">{user-notities}</p>

<p style="font-size: 13px; line-height: 16px; margin-bottom: 25px;"><strong>Deadline</strong><br/>
Let op! De deadline voor het invullen van de vragenlijst is <b>1 september</b>!</p>

<p style="font-size: 13px; line-height: 16px; margin-bottom: 25px;"><strong>Tweejaarlijkse festivals</strong><br/>
Is uw organisatie een tweejaarlijks festival en heeft u geen cijfers van 2011 en 2013 of zijn deze niet representatief? Dan vragen we u cijfers te gebruiken uit de jaren 2012 en 2014. </p>

<p style="font-size: 13px; line-height: 16px; margin-bottom: 25px;"><strong>Meer informatie</strong><br/>
Voor meer informatie over de Culturele Atlas en voor praktische vragen over het invullen van de vragenlijst kunt u terecht op <a href="http://www.bkkc.nl/cultureleatlas">www.bkkc.nl/cultureleatlas</a>. U kunt ook contact opnemen met Sanne Swinkels (<a href="mailto:s.swinkels@bkkc.nl">s.swinkels@bkkc.nl</a>) of Nathalie Jansen (<a href="mailto:n.jansen@bkkc.nl">n.jansen@bkkc.nl</a>). </p>

<p style="font-size: 13px; line-height: 16px; margin-bottom: 25px;">Met vriendelijke groet,<br/>
Namens het projectteam Culturele Atlas</p>

<p style="font-size: 13px; line-height: 16px; margin-bottom: 25px;">Nathalie Jansen</br>
Senior adviseur bkkc</p>


                </td>
                <td style="font-size: 13px; line-height: 16px; margin-bottom: 25px;">&nbsp;</td>
            </tr>
            </table>
            <!-- End Spacer -->
        </td>
    </tr>













    </table>


<style type="text/css">
body { color: #000000 !important; }
img { display: block !important; }
</style>
</body>
</html>';



// single



		// $organisation_id = 517;
		// $owner_id = $this->cab_functions->get_user_id_by_organisation_id($organisation_id);

		// $post_data = get_post_custom($organisation_id);
		//  $user_meta = get_user_meta($owner_id);

		//  $user_name = $user_meta['nickname'][0];
		//  $user_password = $user_meta['user-cab_organisatie-password'][0];
		//  $user_notities = $post_data['vragenlijst_notities'][0];



// Mail loop
foreach ($mail_array as $key => $mail_session) {
	# code...

	$mail_message = $message;
	if ($mail_session['user_notes'] != '') {
		$mail_message = str_replace('{user-notities}', '[NOTITIE - '.$mail_session['user_notes'].' ]', $mail_message);
	} else {
		$mail_message = str_replace('{user-notities}', '', $mail_message);
	}

	$mail_message = str_replace('{user-login}', $mail_session['user_name'], $mail_message);
	$mail_message = str_replace('{user-password}', $mail_session['user_password'], $mail_message);


	$mail = new PHPMailer();
		$mail->From = "s.swinkels@bkkc.nl";
		$mail->FromName = "bkkc";

		foreach ($mail_session['email_adresses'] as $key => $email_adres) {
			$mail->AddAddress($email_adres);
		}
		$mail->AddBCC("s.swinkels@bkkc.nl"); // Eerste BCC
		$mail->AddBCC("jeroen@edhv.nl");

		//$mail->AddBCC("jeroen@edhv.nl");


		// $mail->Subject  = $subject;
		// $mail->Body     = $mail_message;
		// $mail->IsHTML(true);
		//  if(!$mail->Send()) {
		//    echo 'Message was not sent.';
		//    echo 'Mailer error: ' . $mail->ErrorInfo.'<br/>';
		//    } else {
		//    echo 'Message has been sent.<br/>';
		//    }
echo $mail_message;
	/*
	Array
(
    [organisation_id] => 434
    [owner_id] => 357
    [organisation_name] => Theaters Tilburg
    [user_name] => info@theatersTilburg.nl
    [user_password] => OuL0OH
    [user_notes] =>
    [email_adresses] => Array
        (
            [0] => info@theatersTilburg.nl
            [1] => JKlaasse@theatersTilburg.nl
            [2] => rvansteen@theatersTilburg.nl
        )

)*/
}













	}

	function form_auto_save($organisation_id, $period_id, $data) {

		// $activity_data = $this->cab_functions->form_activity_get_data($organisation_id, $period_id);


	 	$data = json_decode($data, true);
		// $activity_data['auto_save'] = $data;

	 //    $period_data = Array("2"=>$activity_data);
	 //    print_r($period_data);


		// print_r(serialize($period_data));
		// //print_r($this->cab_functions->form_activity_get_data($organisation_id, $period_id));

		// print_r("bb");
		return $this->cab_functions->form_activity_auto_save($organisation_id, $period_id, $data);
	}

	function get_all_periods() {
		// print_r(get_field_object('field_51e7d0d4776b7')['choices']);
		// die();
		//return get_field_object('vragenlijst_periodes');
		//return get_terms( 'post_tag', array('hide_empty' => 0) );
		$periods = get_field_object('field_51e7d0d4776b7');
		if (!isset($periods['choices'])) {
			return false;
		} else {
			return $periods['choices'];
		}

	}




	function count_export_organisations($organisations) {
		//print_r($organisations);

		$export = "";


		foreach ($organisations as $period => $cities) {
		$type_array = array();

			$nr_organisations_per_period = 0;

			foreach ($cities as $city_label => $types) {

				$nr_organisations_city = 0;

				foreach ($types as $type_label => $type) {

					if (!isset($type_array[$type_label])) {
						$type_array[$type_label] = 0;
					}
					$nr_organisations_per_type = 0;
					$nr_organisations_city += count($type);
					$nr_organisations_per_period += count($type);

					$organisation_list = "";

					foreach ($type as $organisation_id) {
						$nr_organisations_per_type += 1;
						$organisation_data = get_post($organisation_id);

						$organisation_list .= $period."_".$city_label."_".$type_label."_".$organisation_id." : ".$organisation_data->post_title."<br/>";

					}

					$type_array[$type_label] += $nr_organisations_per_type;

					echo "<strong>nr organisations (".$period." ".$city_label." ".$type_label.") : ".$nr_organisations_per_type."</strong><br/>";
					//echo $organisation_list;
				}

				echo "<strong>nr organisations (".$period." ".$city_label.") : ".$nr_organisations_city."</strong><br/><br/>";
				# code...
			}

			echo "<strong>nr organisations (".$period.") : ".$nr_organisations_per_period."</strong><br/><br/><br/>";
			print_r($type_array);
			# code...
		}
	}








	// function report_global_omzet() {

	// 	$total_2011_type = array();
	// 	$total_2013_type = array();
	// 	$total_2011 = 0;
	// 	$total_2013 = 0;

	// 	// Get organisations
	// 	$organisations = $this->cab_functions->get_all_vragenlijst_organisations_list();


	// 	// Get the data
	// 	foreach ($organisations as $organisation) {

	// 		//$organisation_data = $this->cab_functions->get_table_data("cab_omzet", $organisation['id'], 3);
	// 		$organisation_data = $this->cab_functions->get_organisation_data($organisation['id']);

	// 		// Check global data
	// 		if (isset($organisation_data['periodical'][2]['data']['cab_omzet'][0])) {
	// 			$total_2011 += $organisation_data['periodical'][2]['data']['cab_omzet'][0]['totaal'];
	// 		}

	// 		if (isset($organisation_data['periodical'][3]['data']['cab_omzet'][0])) {
	// 			$total_2013 += $organisation_data['periodical'][3]['data']['cab_omzet'][0]['totaal'];
	// 		}


	// 		// Check type data
	// 		foreach ($this->type_array as $key => $type) {
	// 			//2011
	// 			if(isset($organisation_data['periodical'][2])){
	// 				if (in_array($type["id"], $organisation_data['periodical'][2]['data']['kernactiviteiten']['type'])) {
	// 					$this->type_array[$key]['total_2011'] = $this->type_array[$key]['total_2011']+$organisation_data['periodical'][2]['data']['cab_omzet'][0]['totaal'];
	// 				}
	// 			}

	// 			//2013
	// 			if(isset($organisation_data['periodical'][3])){
	// 				if (in_array($type["id"], $organisation_data['periodical'][3]['data']['kernactiviteiten']['type'])) {
	// 					$this->type_array[$key]['total_2013'] = $this->type_array[$key]['total_2013']+$organisation_data['periodical'][3]['data']['cab_omzet'][0]['totaal'];
	// 				}
	// 			}
	// 		}



	// 	}



	// 	/* RETURN ARRAY */
	// 	foreach ($this->type_array as $key => $value) {
	// 		$total_2011_type[$value['name']] = $value['total_2011'];
	// 		$total_2013_type[$value['name']] = $value['total_2013'];
	// 	}

	// 	$global_array = array(
	// 		"2011"=>array(
	// 			"total"=>$total_2011,
	// 			"bytype"=>$total_2011_type
	// 		),
	// 		"2013"=>array(
	// 			"total"=>$total_2013,
	// 			"bytype"=>$total_2013_type
	// 		),
	// 	);

	// 	return $global_array;
	// }



	function report_global_subsidy() {


		$structure = array(
			"table" => "cab_subsidy",
			"value_fields" => array(
				"totaal",

				"rijk",
				"gemeente",
				"prov_nb",

				"rijk_meerjarig",
				"gemeente_meerjarig",
				"prov_nb_meerjarig",

				"overig",

				),
			"combine_fields" => array(
				array(
					"label" => "fondsen",
					"fields" => array(
						"fonds_podiumkunsten",
						"mondriaan_stichting",
						"fonds_bkvb",
						"mediafonds",
						"nl_filmfonds",
						"fonds_creatieve_industrie",
						"letterenfonds",
						"mondriaan_fonds"
						)
					),
				array(
					"label" => "fondsen_meerjarig",
					"fields" => array(
						"fonds_podiumkunsten_meerjarig",
						"mondriaan_stichting_meerjarig",
						"fonds_bkvb_meerjarig",
						"mediafonds_meerjarig",
						"nl_filmfonds_meerjarig",
						"fonds_creatieve_industrie_meerjarig",
						"letterenfonds_meerjarig",
						"mondriaan_fonds_meerjarig"
						)
				),
				array(
					"label" => "fondsen_totaal",
					"fields" => array(
						"fonds_podiumkunsten",
						"mondriaan_stichting",
						"fonds_bkvb",
						"mediafonds",
						"nl_filmfonds",
						"fonds_creatieve_industrie",
						"letterenfonds",
						"mondriaan_fonds",
						"fonds_podiumkunsten_meerjarig",
						"mondriaan_stichting_meerjarig",
						"fonds_bkvb_meerjarig",
						"mediafonds_meerjarig",
						"nl_filmfonds_meerjarig",
						"fonds_creatieve_industrie_meerjarig",
						"letterenfonds_meerjarig",
						"mondriaan_fonds_meerjarig",
						"overig" // Toegevoegd na gesprek jenneke
						)
				)
			),
			"meta_fields" => array(
				)
		);


		//$organisations = $this->cab_functions->convert_organisation_list_to_id_list( $this->cab_functions->get_all_vragenlijst_organisations_list() );
		//$organisations_by_type = $this->cab_functions->get_all_organisations_by_type_list();

		//print_r($this->cab_functions->organisation_list_to_id_list($this->export_organisations['2011'], "type"));



		$organisation_list = $this->export_organisations;

		// Merge organisation lists
		$organisation_list = $this->cab_functions->combine_organisation_lists($organisation_list,$this->export_organisations_merge_kunstpodium);
		$organisation_list = $this->cab_functions->combine_organisation_lists($organisation_list,$this->export_organisations_merge_kw14);
		$organisation_list = $this->cab_functions->combine_organisation_lists($organisation_list,$this->export_organisations_merge_plaza_futura);

		//$organisation_list_2013 = $this->cab_functions->combine_organisation_lists($organisation_list,$export_organisations_merge_kunstpodium);
		//$organisation_list_2013 = $this->cab_functions->combine_organisation_lists($organisation_list,$export_organisations_merge_kw14);
		//$organisation_list_2013 = $this->cab_functions->combine_organisation_lists($organisation_list,$export_organisations_merge_plaza_futura);


		$organisations_2011 = $this->cab_functions->organisation_list_to_id_list($organisation_list['2011'], "all");
		$organisations_by_type_2011 = $this->cab_functions->organisation_list_to_id_list($organisation_list['2011'], "type");

		$organisations_2013 = $this->cab_functions->organisation_list_to_id_list($organisation_list['2013'], "all");
		$organisations_by_type_2013 = $this->cab_functions->organisation_list_to_id_list($organisation_list['2013'], "type");

		// Get organisation total 2011
		$organisation_data_total_2011 = $this->cab_functions->get_organisation_data_sum(
			$organisations_2011,
			$structure['table'],
			$structure,
			2
		);
		//print_r($organisation_data_total_2011);

		// // Get organisation total 2013
		$organisation_data_total_2013 = $this->cab_functions->get_organisation_data_sum(
			$organisations_2013,
			$structure['table'],
			$structure,
			3
		);
		//print_r($organisation_data_totals_2013);

		// Get organisation data by type 2011
		$organisation_data_total_by_type_2011 = $this->cab_functions->get_organisation_data_sum_by_type(
			$organisations_by_type_2011,
			$structure['table'],
			$structure,
			2
		);

		// Get organisation data by type 2013
		$organisation_data_total_by_type_2013 = $this->cab_functions->get_organisation_data_sum_by_type(
			$organisations_by_type_2013,
			$structure['table'],
			$structure,
			3
		);


		$this->cab_functions->show_table_organisation_data("Subsidie totalen 2011", $organisation_data_total_2011);
		$this->cab_functions->show_table_organisation_data("Subsidie totalen 2013", $organisation_data_total_2013);
		$this->cab_functions->show_table_organisation_data_by_type("Subsidie totalen per type 2011", $organisation_data_total_by_type_2011);
		$this->cab_functions->show_table_organisation_data_by_type("Subsidie totalen per type 2013", $organisation_data_total_by_type_2013);
		$this->count_export_organisations($organisation_list);

		//return $organisation_data;
	}


	function report_global_omzet() {

		$structure = array(
			"table" => "cab_omzet",
			"value_fields" => array(
				"totaal"
				)
		);



		$organisation_list = $this->export_organisations;

		// Merge organisation lists
		$organisation_list = $this->cab_functions->combine_organisation_lists($organisation_list,$this->export_organisations_merge_kunstpodium);
		$organisation_list = $this->cab_functions->combine_organisation_lists($organisation_list,$this->export_organisations_merge_kw14);
		$organisation_list = $this->cab_functions->combine_organisation_lists($organisation_list,$this->export_organisations_merge_plaza_futura);

		//$organisation_list_2013 = $this->cab_functions->combine_organisation_lists($organisation_list,$export_organisations_merge_kunstpodium);
		//$organisation_list_2013 = $this->cab_functions->combine_organisation_lists($organisation_list,$export_organisations_merge_kw14);
		//$organisation_list_2013 = $this->cab_functions->combine_organisation_lists($organisation_list,$export_organisations_merge_plaza_futura);


		$organisations_2011 = $this->cab_functions->organisation_list_to_id_list($organisation_list['2011'], "all");
		$organisations_by_type_2011 = $this->cab_functions->organisation_list_to_id_list($organisation_list['2011'], "type");

		$organisations_2013 = $this->cab_functions->organisation_list_to_id_list($organisation_list['2013'], "all");
		$organisations_by_type_2013 = $this->cab_functions->organisation_list_to_id_list($organisation_list['2013'], "type");


		// Get organisations
		//$organisations = $this->cab_functions->convert_organisation_list_to_id_list( $this->cab_functions->get_all_vragenlijst_organisations_list() );
		//$organisations_by_type = $this->cab_functions->get_all_organisations_by_type_list();

		// Get organisation total 2011
		$organisation_data_total_2011 = $this->cab_functions->get_organisation_data_sum(
			$organisations_2011,
			$structure['table'],
			$structure,
			2
		);
		//print_r($organisation_data_total_2011);

		// // Get organisation total 2013
		$organisation_data_total_2013 = $this->cab_functions->get_organisation_data_sum(
			$organisations_2013,
			$structure['table'],
			$structure,
			3
		);
		//print_r($organisation_data_totals_2013);

		// Get organisation data by type 2011
		$organisation_data_total_by_type_2011 = $this->cab_functions->get_organisation_data_sum_by_type(
			$organisations_by_type_2011,
			$structure['table'],
			$structure,
			2
		);

		// Get organisation data by type 2013
		$organisation_data_total_by_type_2013 = $this->cab_functions->get_organisation_data_sum_by_type(
			$organisations_by_type_2013,
			$structure['table'],
			$structure,
			3
		);

		$this->cab_functions->show_table_organisation_data("Omzet totalen 2011", $organisation_data_total_2011);
		$this->cab_functions->show_table_organisation_data("Omzet totalen 2013", $organisation_data_total_2013);
		$this->cab_functions->show_table_organisation_data_by_type("Omzet totalen per type 2011", $organisation_data_total_by_type_2011);
		$this->cab_functions->show_table_organisation_data_by_type("Omzet totalen per type 2013", $organisation_data_total_by_type_2013);

		$this->count_export_organisations($organisation_list);


	}


	function report_global_organisatie() {

		$structure = array(
			"table" => "cab_organisatie",
			"value_fields" => array(
				"fte",
				"freelancers",
				"vrijwilligers",
				"stagiaires"
				)
		);


		// Get organisations
		//$organisations = $this->cab_functions->convert_organisation_list_to_id_list( $this->cab_functions->get_all_vragenlijst_organisations_list() );
		//$organisations_by_type = $this->cab_functions->get_all_organisations_by_type_list();

		$organisation_list = $this->export_organisations;

		// Merge organisation lists
		//$organisation_list = $this->cab_functions->combine_organisation_lists($organisation_list,$this->export_organisations_merge_kunstpodium);
		$organisation_list = $this->cab_functions->combine_organisation_lists($organisation_list,$this->export_organisations_merge_kw14);
		$organisation_list = $this->cab_functions->combine_organisation_lists($organisation_list,$this->export_organisations_merge_plaza_futura);

		//$organisation_list_2013 = $this->cab_functions->combine_organisation_lists($organisation_list,$export_organisations_merge_kunstpodium);
		//$organisation_list_2013 = $this->cab_functions->combine_organisation_lists($organisation_list,$export_organisations_merge_kw14);
		//$organisation_list_2013 = $this->cab_functions->combine_organisation_lists($organisation_list,$export_organisations_merge_plaza_futura);


		$organisations_2011 = $this->cab_functions->organisation_list_to_id_list($organisation_list['2011'], "all");
		$organisations_by_type_2011 = $this->cab_functions->organisation_list_to_id_list($organisation_list['2011'], "type");

		$organisations_2013 = $this->cab_functions->organisation_list_to_id_list($organisation_list['2013'], "all");
		$organisations_by_type_2013 = $this->cab_functions->organisation_list_to_id_list($organisation_list['2013'], "type");


		// Get organisation total 2011
		$organisation_data_total_2011 = $this->cab_functions->get_organisation_data_sum(
			$organisations_2011,
			$structure['table'],
			$structure,
			2
		);
		//print_r($organisation_data_total_2011);

		// // Get organisation total 2013
		$organisation_data_total_2013 = $this->cab_functions->get_organisation_data_sum(
			$organisations_2013,
			$structure['table'],
			$structure,
			3
		);
		//print_r($organisation_data_totals_2013);

		// Get organisation data by type 2011
		$organisation_data_total_by_type_2011 = $this->cab_functions->get_organisation_data_sum_by_type(
			$organisations_by_type_2011,
			$structure['table'],
			$structure,
			2
		);

		// Get organisation data by type 2013
		$organisation_data_total_by_type_2013 = $this->cab_functions->get_organisation_data_sum_by_type(
			$organisations_by_type_2013,
			$structure['table'],
			$structure,
			3
		);

		$this->cab_functions->show_table_organisation_data("Organisatie totalen 2011", $organisation_data_total_2011);
		$this->cab_functions->show_table_organisation_data("Organisatie totalen 2013", $organisation_data_total_2013);
		$this->cab_functions->show_table_organisation_data_by_type("Organisatie totalen per type 2011", $organisation_data_total_by_type_2011);
		$this->cab_functions->show_table_organisation_data_by_type("Organisatie totalen per type 2013", $organisation_data_total_by_type_2013);
		$this->count_export_organisations($organisation_list);
	}


	function report_global_eigen_inkomsten() {

		$structure = array(
			"table" => "cab_eigen_inkomsten",
			"value_fields" => array(
				"totaal",
				"publieksinkomsten",
				"sponsoring",
				"private_fondsen",
				"overig"
				)
		);


		// Get organisations
		//$organisations = $this->cab_functions->convert_organisation_list_to_id_list( $this->cab_functions->get_all_vragenlijst_organisations_list() );
		//$organisations_by_type = $this->cab_functions->get_all_organisations_by_type_list();

		$organisation_list = $this->export_organisations;

		// Merge organisation lists
		$organisation_list = $this->cab_functions->combine_organisation_lists($organisation_list,$this->export_organisations_merge_kunstpodium);
		$organisation_list = $this->cab_functions->combine_organisation_lists($organisation_list,$this->export_organisations_merge_kw14);
		$organisation_list = $this->cab_functions->combine_organisation_lists($organisation_list,$this->export_organisations_merge_plaza_futura);

		//$organisation_list_2013 = $this->cab_functions->combine_organisation_lists($organisation_list,$export_organisations_merge_kunstpodium);
		//$organisation_list_2013 = $this->cab_functions->combine_organisation_lists($organisation_list,$export_organisations_merge_kw14);
		//$organisation_list_2013 = $this->cab_functions->combine_organisation_lists($organisation_list,$export_organisations_merge_plaza_futura);


		$organisations_2011 = $this->cab_functions->organisation_list_to_id_list($organisation_list['2011'], "all");
		$organisations_by_type_2011 = $this->cab_functions->organisation_list_to_id_list($organisation_list['2011'], "type");

		$organisations_2013 = $this->cab_functions->organisation_list_to_id_list($organisation_list['2013'], "all");
		$organisations_by_type_2013 = $this->cab_functions->organisation_list_to_id_list($organisation_list['2013'], "type");

		// Get organisation total 2011
		$organisation_data_total_2011 = $this->cab_functions->get_organisation_data_sum(
			$organisations_2011,
			$structure['table'],
			$structure,
			2
		);
		//print_r($organisation_data_total_2011);

		// // Get organisation total 2013
		$organisation_data_total_2013 = $this->cab_functions->get_organisation_data_sum(
			$organisations_2013,
			$structure['table'],
			$structure,
			3
		);
		//print_r($organisation_data_totals_2013);

		// Get organisation data by type 2011
		$organisation_data_total_by_type_2011 = $this->cab_functions->get_organisation_data_sum_by_type(
			$organisations_by_type_2011,
			$structure['table'],
			$structure,
			2
		);

		// Get organisation data by type 2013
		$organisation_data_total_by_type_2013 = $this->cab_functions->get_organisation_data_sum_by_type(
			$organisations_by_type_2013,
			$structure['table'],
			$structure,
			3
		);

		$this->cab_functions->show_table_organisation_data("Eigen inkomsten totalen 2011", $organisation_data_total_2011);
		$this->cab_functions->show_table_organisation_data("Eigen inkomsten totalen 2013", $organisation_data_total_2013);
		$this->cab_functions->show_table_organisation_data_by_type("Eigen inkomsten totalen per type 2011", $organisation_data_total_by_type_2011);
		$this->cab_functions->show_table_organisation_data_by_type("Eigen inkomsten totalen per type 2013", $organisation_data_total_by_type_2013);
		$this->count_export_organisations($organisation_list);

	}

	function report_global_media() {

		$structure = array(
			"table" => "cab_media",
			"value_fields" => array(
				"aandacht"
				),
			"radio_values" => array(
				1 => "0-25",
				2 => "26-50",
				3 => "meer dan 50"
				)
		);

		// Get organisations
		//$organisations = $this->cab_functions->convert_organisation_list_to_id_list( $this->cab_functions->get_all_vragenlijst_organisations_list() );
		//$organisations_by_type = $this->cab_functions->get_all_organisations_by_type_list();




		$organisation_list = $this->export_organisations;

		// Merge organisation lists
		//$organisation_list = $this->cab_functions->combine_organisation_lists($organisation_list,$this->export_organisations_merge_kunstpodium);
		$organisation_list = $this->cab_functions->combine_organisation_lists($organisation_list,$this->export_organisations_merge_kw14);
		$organisation_list = $this->cab_functions->combine_organisation_lists($organisation_list,$this->export_organisations_merge_plaza_futura);

		//$organisation_list_2013 = $this->cab_functions->combine_organisation_lists($organisation_list,$export_organisations_merge_kunstpodium);
		//$organisation_list_2013 = $this->cab_functions->combine_organisation_lists($organisation_list,$export_organisations_merge_kw14);
		//$organisation_list_2013 = $this->cab_functions->combine_organisation_lists($organisation_list,$export_organisations_merge_plaza_futura);


		$organisations_2011 = $this->cab_functions->organisation_list_to_id_list($organisation_list['2011'], "all");
		$organisations_by_type_2011 = $this->cab_functions->organisation_list_to_id_list($organisation_list['2011'], "type");


		// Get organisation total 2011
		$organisation_data_total_2011 = $this->cab_functions->get_organisation_data_radio(
			$organisations_2011,
			$structure['table'],
			$structure,
			$structure['radio_values'],
			2
		);

		// Get organisation total 2011
		$organisation_data_total_by_type_2011 = $this->cab_functions->get_organisation_data_radio_by_type(
			$organisations_by_type_2011,
			$structure['table'],
			$structure,
			$structure['radio_values'],
			2
		);

		$this->cab_functions->show_table_organisation_radio_data("Media totalen 2011", $organisation_data_total_2011);
		$this->cab_functions->show_table_organisation_radio_data_by_type("Media by type 2011", $organisation_data_total_by_type_2011);
		$this->count_export_organisations($organisation_list);

	}



	function report_global_scholing() {

		$structure = array(
			"table" => "cab_scholing",
			"value_fields" => array(
				"uitgaven"
				)
		);


		// Get organisations
		//$organisations = $this->cab_functions->convert_organisation_list_to_id_list( $this->cab_functions->get_all_vragenlijst_organisations_list() );
		///$organisations_by_type = $this->cab_functions->get_all_organisations_by_type_list();

		$organisation_list = $this->export_organisations;

		// Merge organisation lists
		//$organisation_list = $this->cab_functions->combine_organisation_lists($organisation_list,$this->export_organisations_merge_kunstpodium);
		$organisation_list = $this->cab_functions->combine_organisation_lists($organisation_list,$this->export_organisations_merge_kw14);
		$organisation_list = $this->cab_functions->combine_organisation_lists($organisation_list,$this->export_organisations_merge_plaza_futura);

		//$organisation_list_2013 = $this->cab_functions->combine_organisation_lists($organisation_list,$export_organisations_merge_kunstpodium);
		//$organisation_list_2013 = $this->cab_functions->combine_organisation_lists($organisation_list,$export_organisations_merge_kw14);
		//$organisation_list_2013 = $this->cab_functions->combine_organisation_lists($organisation_list,$export_organisations_merge_plaza_futura);


		$organisations_2011 = $this->cab_functions->organisation_list_to_id_list($organisation_list['2011'], "all");
		$organisations_by_type_2011 = $this->cab_functions->organisation_list_to_id_list($organisation_list['2011'], "type");

		$organisations_2013 = $this->cab_functions->organisation_list_to_id_list($organisation_list['2013'], "all");
		$organisations_by_type_2013 = $this->cab_functions->organisation_list_to_id_list($organisation_list['2013'], "type");


		// Get organisation total 2011
		$organisation_data_total_2011 = $this->cab_functions->get_organisation_data_sum(
			$organisations_2011,
			$structure['table'],
			$structure,
			2
		);
		//print_r($organisation_data_total_2011);

		// // Get organisation total 2013
		$organisation_data_total_2013 = $this->cab_functions->get_organisation_data_sum(
			$organisations_2013,
			$structure['table'],
			$structure,
			3
		);
		//print_r($organisation_data_totals_2013);

		// Get organisation data by type 2011
		$organisation_data_total_by_type_2011 = $this->cab_functions->get_organisation_data_sum_by_type(
			$organisations_by_type_2011,
			$structure['table'],
			$structure,
			2
		);

		// Get organisation data by type 2013
		$organisation_data_total_by_type_2013 = $this->cab_functions->get_organisation_data_sum_by_type(
			$organisations_by_type_2013,
			$structure['table'],
			$structure,
			3
		);

		$this->cab_functions->show_table_organisation_data("Scholing totalen 2011", $organisation_data_total_2011);
		$this->cab_functions->show_table_organisation_data("Scholing totalen 2013", $organisation_data_total_2013);
		$this->cab_functions->show_table_organisation_data_by_type("Scholing totalen per type 2011", $organisation_data_total_by_type_2011);
		$this->cab_functions->show_table_organisation_data_by_type("Scholing totalen per type 2013", $organisation_data_total_by_type_2013);
		$this->count_export_organisations($organisation_list);
	}


	function report_global_geldstromen() {
		print_r($this->cab_functions->get_all_organisations_by_type_list());
	}

	function report_global_marketing() {

		$structure = array(
			"table" => "cab_marketing",
			"value_fields" => array(
				"uitgaven"
				)
		);


		// Get organisations
		//$organisations = $this->cab_functions->convert_organisation_list_to_id_list( $this->cab_functions->get_all_vragenlijst_organisations_list() );
		//$organisations_by_type = $this->cab_functions->get_all_organisations_by_type_list();



		$organisation_list = $this->export_organisations;

		// Merge organisation lists
		//$organisation_list = $this->cab_functions->combine_organisation_lists($organisation_list,$this->export_organisations_merge_kunstpodium);
		$organisation_list = $this->cab_functions->combine_organisation_lists($organisation_list,$this->export_organisations_merge_kw14);
		$organisation_list = $this->cab_functions->combine_organisation_lists($organisation_list,$this->export_organisations_merge_plaza_futura);

		//$organisation_list_2013 = $this->cab_functions->combine_organisation_lists($organisation_list,$export_organisations_merge_kunstpodium);
		//$organisation_list_2013 = $this->cab_functions->combine_organisation_lists($organisation_list,$export_organisations_merge_kw14);
		//$organisation_list_2013 = $this->cab_functions->combine_organisation_lists($organisation_list,$export_organisations_merge_plaza_futura);


		$organisations_2011 = $this->cab_functions->organisation_list_to_id_list($organisation_list['2011'], "all");
		$organisations_by_type_2011 = $this->cab_functions->organisation_list_to_id_list($organisation_list['2011'], "type");

		$organisations_2013 = $this->cab_functions->organisation_list_to_id_list($organisation_list['2013'], "all");
		$organisations_by_type_2013 = $this->cab_functions->organisation_list_to_id_list($organisation_list['2013'], "type");


		// Get organisation total 2011
		$organisation_data_total_2011 = $this->cab_functions->get_organisation_data_sum(
			$organisations_2011,
			$structure['table'],
			$structure,
			2
		);
		//print_r($organisation_data_total_2011);

		// // Get organisation total 2013
		$organisation_data_total_2013 = $this->cab_functions->get_organisation_data_sum(
			$organisations_2013,
			$structure['table'],
			$structure,
			3
		);
		//print_r($organisation_data_totals_2013);

		// Get organisation data by type 2011
		$organisation_data_total_by_type_2011 = $this->cab_functions->get_organisation_data_sum_by_type(
			$organisations_by_type_2011,
			$structure['table'],
			$structure,
			2
		);

		// Get organisation data by type 2013
		$organisation_data_total_by_type_2013 = $this->cab_functions->get_organisation_data_sum_by_type(
			$organisations_by_type_2013,
			$structure['table'],
			$structure,
			3
		);

		$this->cab_functions->show_table_organisation_data("Marketing totalen 2011", $organisation_data_total_2011);
		$this->cab_functions->show_table_organisation_data("Marketing totalen 2013", $organisation_data_total_2013);
		$this->cab_functions->show_table_organisation_data_by_type("Marketing totalen per type 2011", $organisation_data_total_by_type_2011);
		$this->cab_functions->show_table_organisation_data_by_type("Marketing totalen per type 2013", $organisation_data_total_by_type_2013);
		$this->count_export_organisations($organisation_list);

	}




	function report_global_activiteiten() {

		$structure = array(
			"table" => "cab_activiteiten",
			"value_fields" => array(
				"totaal",
				"in_opdracht",
				"eigen_werk",
				"premieres"
				)
		);


		// Get organisations
		//$organisations = $this->cab_functions->convert_organisation_list_to_id_list( $this->cab_functions->get_all_vragenlijst_organisations_list() );
		//$organisations_by_type = $this->cab_functions->get_all_organisations_by_type_list();

		$organisation_list = $this->export_organisations;

		$organisations_2011 = $this->cab_functions->organisation_list_to_id_list($organisation_list['2011'], "all");
		$organisations_by_type_2011 = $this->cab_functions->organisation_list_to_id_list($organisation_list['2011'], "type");


		// Get organisation total 2011
		$organisation_data_total_2011 = $this->cab_functions->get_organisation_data_sum(
			$organisations_2011,
			$structure['table'],
			$structure,
			2
		);
		//print_r($organisation_data_total_2011);



		// Get organisation data by type 2011
		$organisation_data_total_by_type_2011 = $this->cab_functions->get_organisation_data_sum_by_type(
			$organisations_by_type_2011,
			$structure['table'],
			$structure,
			2
		);


		$this->cab_functions->show_table_organisation_data("Activiteiten totalen 2011", $organisation_data_total_2011);
		$this->cab_functions->show_table_organisation_data_by_type("Activiteiten totalen per type 2011", $organisation_data_total_by_type_2011);
		$this->count_export_organisations($organisation_list);
	}


	function report_global_nevenactiviteiten() {

		$structure = array(
			"table" => "cab_nevenactiviteiten",
			"value_fields" => array(
				"totaal",
				"educatief",
				"overig"
				)
		);


		// Get organisations
		//$organisations = $this->cab_functions->convert_organisation_list_to_id_list( $this->cab_functions->get_all_vragenlijst_organisations_list() );
		//$organisations_by_type = $this->cab_functions->get_all_organisations_by_type_list();

		$organisation_list = $this->export_organisations;

		$organisations_2011 = $this->cab_functions->organisation_list_to_id_list($organisation_list['2011'], "all");
		$organisations_by_type_2011 = $this->cab_functions->organisation_list_to_id_list($organisation_list['2011'], "type");

		// Get organisation total 2011
		$organisation_data_total_2011 = $this->cab_functions->get_organisation_data_sum(
			$organisations_2011,
			$structure['table'],
			$structure,
			2
		);
		//print_r($organisation_data_total_2011);



		// Get organisation data by type 2011
		$organisation_data_total_by_type_2011 = $this->cab_functions->get_organisation_data_sum_by_type(
			$organisations_by_type_2011,
			$structure['table'],
			$structure,
			2
		);


		$this->cab_functions->show_table_organisation_data("Nevenactiviteiten totalen 2011", $organisation_data_total_2011);
		$this->cab_functions->show_table_organisation_data_by_type("Nevenactiviteiten totalen per type 2011", $organisation_data_total_by_type_2011);
			$this->count_export_organisations($organisation_list);
	}



		function report_global_bezoekers() {

		$structure = array(
			"table" => "cab_bezoekers",
			"value_fields" => array(
				"totaal",
				"standplaats",
				"provincie",
				"nederland",
				"buitenland",

				"podium",
				"festivals",
				"scholen",
				"overig"
				)
		);


		// Get organisations
		//$organisations = $this->cab_functions->convert_organisation_list_to_id_list( $this->cab_functions->get_all_vragenlijst_organisations_list() );
		//$organisations_by_type = $this->cab_functions->get_all_organisations_by_type_list();

				$organisation_list = $this->export_organisations;

		$organisations_2011 = $this->cab_functions->organisation_list_to_id_list($organisation_list['2011'], "all");
		$organisations_by_type_2011 = $this->cab_functions->organisation_list_to_id_list($organisation_list['2011'], "type");



		foreach ($organisations_by_type_2011 as $key => $value) {
			echo $value['label'];
			$counter = 0;

			foreach ($value['organisations'] as $key_b => $value_b) {
				$organisation_data = $this->cab_functions->get_organisation_data($value_b);
				if ($organisation_data['periodical'][2]['data']['cab_bezoekers'][0]['totaal'] > 0) {
					$counter++;

				}
			}
			echo $counter."<br/>";
			# code...
		}

		// Get organisation total 2011
		$organisation_data_total_2011 = $this->cab_functions->get_organisation_data_sum(
			$organisations_2011,
			$structure['table'],
			$structure,
			2
		);
		//print_r($organisation_data_total_2011);



		// Get organisation data by type 2011
		$organisation_data_total_by_type_2011 = $this->cab_functions->get_organisation_data_sum_by_type(
			$organisations_by_type_2011,
			$structure['table'],
			$structure,
			2
		);


		$this->cab_functions->show_table_organisation_data("Bezoekers totalen 2011", $organisation_data_total_2011);
		$this->cab_functions->show_table_organisation_data_by_type("Bezoekers totalen per type 2011", $organisation_data_total_by_type_2011);
			$this->count_export_organisations($organisation_list);
	}


	function report_global_spreiding_activiteiten() {

		$structure = array(
			"table" => "cab_spreiding",
			"value_fields" => array(
				"standplaats",
				"provincie",
				"nederland",
				"buitenland",

				"podium",
				"festivals",
				"scholen",
				"overig"
				)
		);


		// Get organisations
		//$organisations = $this->cab_functions->convert_organisation_list_to_id_list( $this->cab_functions->get_all_vragenlijst_organisations_list() );
		//$organisations_by_type = $this->cab_functions->get_all_organisations_by_type_list();

	$organisation_list = $this->export_organisations;

		$organisations_2011 = $this->cab_functions->organisation_list_to_id_list($organisation_list['2011'], "all");
		$organisations_by_type_2011 = $this->cab_functions->organisation_list_to_id_list($organisation_list['2011'], "type");

		// Get organisation total 2011
		$organisation_data_total_2011 = $this->cab_functions->get_organisation_data_sum(
			$organisations_2011,
			$structure['table'],
			$structure,
			2
		);
		//print_r($organisation_data_total_2011);



		// Get organisation data by type 2011
		$organisation_data_total_by_type_2011 = $this->cab_functions->get_organisation_data_sum_by_type(
			$organisations_by_type_2011,
			$structure['table'],
			$structure,
			2
		);


		$this->cab_functions->show_table_organisation_data("Spreiding activiteiten 2011", $organisation_data_total_2011);
		$this->cab_functions->show_table_organisation_data_by_type("Spreiding activiteiten per type 2011", $organisation_data_total_by_type_2011);
		$this->count_export_organisations($organisation_list);
	}



	function report_global_vertoningen() {

		$structure = array(
			"table" => "cab_vertoningen",
			"value_fields" => array(
				"totaal",
				"standplaats",
				"provincie",
				"nederland",
				"buitenland",

				"bioscoop",
				"filmhuis",
				"festival",
				"omroep",
				"internet"

				)
		);


		// Get organisations
		//$organisations = $this->cab_functions->convert_organisation_list_to_id_list( $this->cab_functions->get_all_vragenlijst_organisations_list() );
		//$organisations_by_type = $this->cab_functions->get_all_organisations_by_type_list();


	$organisation_list = $this->export_organisations;

		$organisations_2011 = $this->cab_functions->organisation_list_to_id_list($organisation_list['2011'], "all");
		$organisations_by_type_2011 = $this->cab_functions->organisation_list_to_id_list($organisation_list['2011'], "type");

		// Get organisation total 2011
		$organisation_data_total_2011 = $this->cab_functions->get_organisation_data_sum(
			$organisations_2011,
			$structure['table'],
			$structure,
			2
		);
		//print_r($organisation_data_total_2011);



		// Get organisation data by type 2011
		$organisation_data_total_by_type_2011 = $this->cab_functions->get_organisation_data_sum_by_type(
			$organisations_by_type_2011,
			$structure['table'],
			$structure,
			2
		);


		$this->cab_functions->show_table_organisation_data("Vertoningen 2011", $organisation_data_total_2011);
		$this->cab_functions->show_table_organisation_data_by_type("Vertoningen per type 2011", $organisation_data_total_by_type_2011);
			$this->count_export_organisations($organisation_list);
	}



	function organisations_by_type() {
		print_r($this->cab_functions->get_all_organisations_by_type_list());

	}




	function export_all_vragenlijst_organisations() {
		$html = "";
		$html_body = "";

		$type_array[1] = "Festivals";
		$type_array[2] = "BKV/AV";
		$type_array[3] = "Film(producenten)";
		$type_array[4] = "Musea";
		$type_array[5] = "Podia";
		$type_array[6] = "Producenten podiumkunsten";
		$type_array[7] = "Podia Theater";


		$export_structure = array(

		);







$period = 2;

$html = "
		<table>
		<thead>
		<tr><th>id</th><th>name</th><th>type</th><th>bezoekadres straat</th><th>bezoekadres huisnummer</th><th>bezoekadres plaats</th><th>bezoekadres postcode</th><th></th><th>postadres straat</th><th>postadres huisnummer</th><th>postadres plaats</th><th>postadres postcode</th>";

				foreach ($export_structure as $table_name => $fields) {
						$html .= "<th></th><th>".$table_name."</th>";
					foreach ($fields as $field) {
						$html .= "<th>".$field."</th>";
					}
				}


$html .= "</tr>
		</thead>
		<tbody>";








		//$organisations = $this->cab_functions->organisation_list_to_id_list($this->cab_functions->get_all_vragenlijst_organisations_list() "all");
		$organisations = $this->cab_functions->convert_organisation_list_to_id_list(  $this->cab_functions->get_all_vragenlijst_organisations_list() );
		//$organisations = $this->cab_functions->convert_organisation_list_to_id_list( $this->cab_functions->get_all_vragenlijst_organisations_list() );
		$i = 0;
		foreach ($organisations as $organisation_id) {
		//	echo "-".$organisation_id."-";
			$organisation_data = $this->cab_functions->get_organisation_data($organisation_id);

			$owner_id = $this->cab_functions->get_user_id_by_organisation_id($organisation_id);
			$organisation_meta = get_user_meta($owner_id);
			// $post_data = get_post_custom($organisation_id);
		//  $user_meta = get_user_meta($owner_id);

			# code...
			//print_r($organisation_data);
			//echo $organisation_data['general']['name'];

			$categories = get_field('vragenlijst_categorie',$organisation_id);

			$categories_value = "";
			foreach ($categories as $key => $value) {
				$categories_value .= " ".$type_array[$value].",";
			}
			$categories_value = rtrim($categories_value, ",");


/*
user-cab_bezoekadres-straat
user-cab_bezoekadres-huisnummer
user-cab_bezoekadres-plaats
user-cab_bezoekadres-postcode

user-cab_postadres-straat
user-cab_postadres-huisnummer
user-cab_postadres-plaats
user-cab_postadres-postcode


*/

			$html_body .= "<tr>
				<td>".$organisation_data['general']['id']."</td>
				<td>".$organisation_data['general']['name']."</td>
				<td>".$categories_value."</td>
				<td>".$organisation_meta['user-cab_bezoekadres-straat'][0]."</td>
				<td>".$organisation_meta['user-cab_bezoekadres-huisnummer'][0]."</td>
				<td>".$organisation_meta['user-cab_bezoekadres-plaats'][0]."</td>
				<td>".$organisation_meta['user-cab_bezoekadres-postcode'][0]."</td>
				<td></td>
				<td>".$organisation_meta['user-cab_postadres-straat'][0]."</td>
				<td>".$organisation_meta['user-cab_postadres-huisnummer'][0]."</td>
				<td>".$organisation_meta['user-cab_postadres-plaats'][0]."</td>
				<td>".$organisation_meta['user-cab_postadres-postcode'][0]."</td>
			";

				foreach ($export_structure as $table_name => $fields) {
						$html_body .= "<td></td><td></td>";
					foreach ($fields as $field) {
						$html_body .= "<td>";
						if (isset($organisation_data['periodical'][$period]['data'][$table_name][0][$field])) {
							$html_body .= $organisation_data['periodical'][$period]['data'][$table_name][0][$field];
						}
						$html_body .= "</td>";
					}
				}


				// print_r($organisation_data['periodical'][2]['data']);
				// foreach ($organisation_data['periodical'][2]['data'] as $label => $group) {

				// 	foreach ($group as $sub_group) {

				// 		foreach ($sub_group as $field => $value) {
				// 			if ($field != 'id' && $field != 'period_id' && $field != 'organisation_id' && $field != 'aanv_vragenlijst_id') {
				// 				$html_body .= "<td>".$field."</td>";
				// 			}
				// 		}
				// 	}
				// 	# code...
				// }
				$html_body .= "</tr>";

			$i+=1;
		}


/*
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

		echo $html;*/



		$html .= $html_body;

		$html .= "</tbody>
		</table>";

		echo $html;
	}




	function export_all_organisations_csv( $period ) {

		$html = "";
		$html_body = "";
		$periods = $this->cab_functions->get_all_periods();

		if (!isset($periods[$period])) {
			return false;
		}

		$nullValue = '-1';
		// header
		header('Content-Type: text/csv; charset=utf-8');
		header( 'Content-Description: File Transfer' );
		header( 'Content-Disposition: attachment; filename=export-periode-'.$periods[$period].'_'.date('d-m-Y_Hi').'.csv' );




		$type_array[1] = "Festivals";
		$type_array[2] = "Visuele kunsten";
		$type_array[3] = "Film(producenten)";
		$type_array[4] = "Musea";
		$type_array[5] = "Podia";
		$type_array[6] = "Producenten podiumkunsten";


		$export_structure = array(
			"kernactiviteiten" => array(
				array(
					"gform_field_id" => 17,
					"label" => "functie",
					"database_field" => "functie"
				),
				array(
					"gform_field_id" => 14,
					"label" => "type",
					"database_field" => "type"
				),
				array(
					"gform_field_id" => 16,
					"label" => "sector",
					"database_field" => "sector"
				)
			),
			"cab_subsidy" => array(
				//'totaal',
				'gemeente',
				'gemeente_meerjarig',
				'prov_nb',
				'prov_nb_meerjarig',
				'rijk',
				'rijk_meerjarig',
				'fonds_podiumkunsten',
				'fonds_podiumkunsten_meerjarig',
				'mondriaan_stichting',
				'mondriaan_stichting_meerjarig',
				'fonds_bkvb',
				'fonds_bkvb_meerjarig',
				'mediafonds',
				'mediafonds_meerjarig',
				'nl_filmfonds',
				'nl_filmfonds_meerjarig',
				'fonds_creatieve_industrie',
				'fonds_creatieve_industrie_meerjarig',
				'letterenfonds',
				'letterenfonds_meerjarig',
				'mondriaan_fonds',
				'mondriaan_fonds_meerjarig',
				'fonds_cultuurparticipatie',
				'fonds_cultuurparticipatie_meerjarig',
				'overig',
				'overig_toelichting'
				// "totaal",
				// "gemeente",
				// "prov_nb",
				// "rijk",

				// "gemeente_meerjarig",
				// "prov_nb_meerjarig",
				// "rijk_meerjarig",

				// "fonds_podiumkunsten",
				// "mondriaan_stichting",
				// "fonds_bkvb",
				// "mediafonds",
				// "nl_filmfonds",
				// "fonds_creatieve_industrie",
				// "letterenfonds",
				// "mondriaan_fonds",

				// "fonds_podiumkunsten_meerjarig",
				// "mondriaan_stichting_meerjarig",
				// "mondriaan_fonds_meerjarig",
				// "fonds_bkvb_meerjarig",
				// "mediafonds_meerjarig",
				// "nl_filmfonds_meerjarig",
				// "fonds_creatieve_industrie_meerjarig",
				// "letterenfonds_meerjarig",

				// "overig"
			),
			"cab_eigen_inkomsten" => array (
				"publieksinkomsten",
				"sponsoring",
				"private_fondsen",
				"overig",
				"totaal"
			),
			"cab_organisatie" => array(
				//"werknemers",
				//"freelancers",
				//"vrijwilligers",
				//"stagiaires",
				"werknemers_fte",
				"freelancers_fte",
				"vrijwilligers",
				"vrijwilligers_fte",
				"lasten_vastcontract",
				"lasten_tijdelijk",
				"lasten_inhuur"
				//"stagiaires_fte"
			),
			"cab_omzet" => array(
				"totaal"
			),
			"cab_scholing" => array(
				"uitgaven",
				array(
					"gform_field_id" => 260,
					"label" => "onderwerpen",
					"database_field" => "onderwerpen"
				),
				"onderwerpen_anders"
			),
			"cab_marketing" => array(
				"uitgaven"
			),
			"cab_media" => array(
				"aandacht",
				"aandacht_twitter",
				"aandacht_twitter_toelichting",
				"aandacht_facebook",
				"aandacht_facebook_toelichting"
			),
			"cab_activiteiten" => array(
				"aantal",
				"in_opdracht",
				"eigen_werk",
				"premieres",
				"reprises"
			),
			"cab_nevenactiviteiten" => array(
				"totaal",
				"educatief",
				"overig",
				"overig_toelichting"
			),
			"cab_bezoekers" => array(
				//"totaal",
				"standplaats",
				"provincie",
				"nederland",
				"buitenland",
				"podium",
				"festivals",
				"scholen",
				"overig",
				"betaald",
				"niet_betaald"
			),
			"cab_spreiding" => array(
				"standplaats",
				"provincie",
				"nederland",
				"buitenland",

				"podium",
				"festivals",
				"scholen",
				"overig"
			),
			"cab_vertoningen" => array(
				"totaal",
				"standplaats",
				"provincie",
				"nederland",
				"buitenland",

				"bioscoop",
				"filmhuis",
				"festival",
				"omroep",
				"internet",
				"internet_toelichting"
			)

		);



		$csv = '';


		// compose header row
		$header_row = array(
			'id',
			'Formulier status',
			'Datum start',
			'Datum eind',
			'Naam',
      'Directie',
			'Type',
			'Postadres straat',
			'Postadres huisnummer',
			'Postadres postcode',
			'Postadres plaats',
			'Bezoekadres straat',
			'Bezoekadres huisnummer',
			'Bezoekadres postcode',
			'Bezoekadres plaats'
		);

		// form object for later reference
		$form = GFAPI::get_form( 1 );
		//print_r($form);
		foreach ($export_structure as $table_name => $fields) {

			array_push($header_row, $table_name);

			// walk through the fields
			foreach ($fields as $key => $field) {

				// if this is a gform multiple choice
				if (is_array($field) && isset($field['gform_field_id'])) {

					// walk through all gform fields to find the right field
					foreach ($form['fields'] as $formIndex => $gformField) {

						if ($gformField->id === $field['gform_field_id']) {

							// create the multiple choice columns
							foreach ($gformField['choices'] as $choice) {
								array_push($header_row, $field['label']."_".$choice['value']);
							}







						}
					}
				} else {
					array_push($header_row, $field);
				}
			}
		}


		$csv .= $this->cab_functions->convertArrayToCsvRow($header_row);
		// $html = "
		// 		<table>
		// 		<thead>
		// 		<tr><th>id</th><th>name</th><th>type</th><th>Bezoekadres plaats</th><th>Postadres plaats</th>";

		// 				foreach ($export_structure as $table_name => $fields) {
		// 						$html .= "<th></th><th>".$table_name."</th>";
		// 					foreach ($fields as $field) {
		// 						$html .= "<th>".$field."</th>";
		// 					}
		// 				}


		// $html .= "</tr>
		// 		</thead>
		// 		<tbody>";




		$organisations = array();
		$organisation_list = $this->cab_functions->get_all_exportable_organisations($period);

		foreach ($organisation_list as $organisation) {
			array_push($organisations, $organisation->ID);
			# code...
		}

		//$organisation_list = $this->export_organisations;
		//$organisation_list = $this->cab_functions->combine_organisation_lists($organisation_list,$this->export_organisations_merge_kunstpodium);
		//$organisation_list = $this->cab_functions->combine_organisation_lists($organisation_list,$this->export_organisations_merge_kw14);
		//$organisation_list = $this->cab_functions->combine_organisation_lists($organisation_list,$this->export_organisations_merge_plaza_futura);


		//if (!isset($organisation_list[ $periods[$period] ]) {
		//	return false;
		//}

		//$organisations = $this->cab_functions->organisation_list_to_id_list($organisation_list[ $periods[$period] ], "all");
		//print_r($organisations);
		//$organisations = $this->cab_functions->convert_organisation_list_to_id_list( $this->cab_functions->get_all_vragenlijst_organisations_list() );
		$i = 0;

		foreach ($organisations as $organisation_id) {

			// get organisation data
			$organisation_data = $this->cab_functions->get_organisation_data($organisation_id);

			$form_data = $this->cab_functions->form_activity_get_data($organisation_id, $period);

			//print_r($organisation_data);
			// id of the owning user
			$owner_id = $this->cab_functions->get_user_id_by_organisation_id($organisation_id);
			// get organisation meta
			$organisation_meta = get_user_meta($owner_id);

			// organisation type
			$field = get_field_object('field_51e3f440c250c');
			$field_choices = $field['choices'];

			$categories = get_field('vragenlijst_categorie',$organisation_id);
			$categories_value = "";

			foreach ($categories as $key => $value) {
				if (!isset($field_choices[$value])) {
					continue;
				}
				$categories_value .= " ".$field_choices[$value].",";
			}
			$categories_value = rtrim($categories_value, ",");


			// Form status
			$formStatus = 'open';

			if ($form_data['is_finished'] && !$form_data['entry_id']) {
				$formStatus = 'geblokkeerd';
			} else if ($form_data['is_finished']) {
				$formStatus = 'afgerond';
			} else if ($form_data['last_access_date']) {
				$formStatus = 'in_behandeling';
			}



			// build up row
			$row = array(
				$organisation_data['general']['id'],
				$formStatus,
				$organisation_data['general']['start_date'],
				$organisation_data['general']['end_date'],
				$organisation_data['general']['name'],
        $organisation_meta['user-cab_organisatie-directeur-naam'][0],
				$categories_value,
				$organisation_meta['user-cab_postadres-straat'][0],
				$organisation_meta['user-cab_postadres-huisnummer'][0],
				$organisation_meta['user-cab_postadres-postcode'][0],
				$organisation_meta['user-cab_postadres-plaats'][0],
				$organisation_meta['user-cab_bezoekadres-straat'][0],
				$organisation_meta['user-cab_bezoekadres-huisnummer'][0],
				$organisation_meta['user-cab_bezoekadres-postcode'][0],
				$organisation_meta['user-cab_bezoekadres-plaats'][0]
			);


			foreach ($export_structure as $table_name => $fields) {
				array_push($row, '');
				//print_r($fields);
				foreach ($fields as $key => $field) {
					$value = $nullValue;
					$choicesData = array();

						// if this is a gform multiple choice
						if (is_array($field) && isset($field['gform_field_id'])) {							//print_r($field);
							// // walk through all gform fields to find the right field
							foreach ($form['fields'] as $formIndex => $gformField) {
									//echo $gformField->id." - ".$field."<br/>";

								if ($gformField->id === $field['gform_field_id']) {

									//if (isset($organisation_data['periodical'][$period]['data'][$table_name][0][$field['database_field']])) {
									if (isset($organisation_data['periodical'][$period]['data'][$table_name])) {

										$tableData = $organisation_data['periodical'][$period]['data'][$table_name];

										// two ways to access data based on structure
										if (isset($tableData[0][$field['database_field']])) {
											$choicesData = explode(",", $organisation_data['periodical'][$period]['data'][$table_name][0][$field['database_field']]);
										} else if (isset($tableData[$field['database_field']])) {
											//print_r(isset($tableData[$field['database_field']]));
											$choicesData = $organisation_data['periodical'][$period]['data'][$table_name][$field['database_field']];
										}

										//echo $organisation_id." - ".$key."<br/>";

									}

									// create the multiple choice columns
									foreach ($gformField['choices'] as $choice) {

										// check if the choice was selected by the organisation
										if (in_array($choice['value'],$choicesData)) {
											$value = 1;
										} else {
											$value = 0;
										}

										array_push($row, $value);


									// 	array_push($header_row, $field['label']."_".$choice['value']);
									}


								}
							}

						} else {

							if (isset($organisation_data['periodical'][$period]['data'][$table_name][0][$field])) {
								$value = $organisation_data['periodical'][$period]['data'][$table_name][0][$field];
							}

							if ($value == 'null' || $value == 'NULL') {
								$value = $nullValue;
							}

							array_push($row, $value);

						}




					//print_r( $field );
		//			if ($field == 'gform_multiple_choice') {
						//$value = 'KRA';
					// } else {
					// 	if (isset($organisation_data['periodical'][$period]['data'][$table_name][0][$field])) {
					// 		$value = $organisation_data['periodical'][$period]['data'][$table_name][0][$field];
					// 	}

					// 	if ($value == 'null' || $value == 'NULL') {
					// 		$value = $nullValue;
					// 	}

					// }


				}

			}

			$csv .= $this->cab_functions->convertArrayToCsvRow($row);

			$i+=1;
		}






		//$html .= $html_body;

		//$html .= "</tbody>
		//</table>";

		//echo $html;

			// // Setup csv file

			echo $csv;
	    	//echo chr(255) . chr(254) . $csv;


	}



	function export_all_organisations( $period ) {

		$html = "";
		$html_body = "";

		$type_array[1] = "Festivals";
		$type_array[2] = "Visuele kunsten";
		$type_array[3] = "Film(producenten)";
		$type_array[4] = "Musea";
		$type_array[5] = "Podia";
		$type_array[6] = "Producenten podiumkunsten";


		$export_structure = array(
			"cab_subsidy" => array(
				"totaal",
				"gemeente",
				"prov_nb",
				"rijk",

				"gemeente_meerjarig",
				"prov_nb_meerjarig",
				"rijk_meerjarig",

				"fonds_podiumkunsten",
				"mondriaan_stichting",
				"fonds_bkvb",
				"mediafonds",
				"nl_filmfonds",
				"fonds_creatieve_industrie",
				"letterenfonds",
				"mondriaan_fonds",

				"fonds_podiumkunsten_meerjarig",
				"mondriaan_stichting_meerjarig",
				"mondriaan_fonds_meerjarig",
				"fonds_bkvb_meerjarig",
				"mediafonds_meerjarig",
				"nl_filmfonds_meerjarig",
				"fonds_creatieve_industrie_meerjarig",
				"letterenfonds_meerjarig",

				"overig"
			),
			"cab_omzet" => array(
				"totaal"
			),
			"cab_eigen_inkomsten" => array (
				"publieksinkomsten",
				"sponsoring",
				"private_fondsen",
				"overig",
				"totaal"
			),
			"cab_organisatie" => array(
				"fte",
				"freelancers",
				"vrijwilligers",
				"stagiaires"
			),
			"cab_scholing" => array(
				"uitgaven"
			),
			"cab_marketing" => array(
				"uitgaven"
			),
			"cab_media" => array(
				"aandacht",
				"aandacht_twitter",
				"aandacht_twitter_toelichting",
				"aandacht_facebook",
				"aandacht_facebook_toelichting"
			),
			"cab_activiteiten" => array(
				"totaal",
				"in_opdracht",
				"eigen_werk",
				"premieres"
			),
			"cab_nevenactiviteiten" => array(
				"totaal",
				"educatief",
				"overig",
				"overig_toelichting"
			),
			"cab_bezoekers" => array(
				"totaal",
				"standplaats",
				"provincie",
				"nederland",
				"buitenland",
				"podium",
				"festivals",
				"scholen",
				"overig"
			),
			"cab_spreiding" => array(
				"standplaats",
				"provincie",
				"nederland",
				"buitenland",

				"podium",
				"festivals",
				"scholen",
				"overig"
			),
			"cab_vertoningen" => array(
				"totaal",
				"standplaats",
				"provincie",
				"nederland",
				"buitenland",

				"bioscoop",
				"filmhuis",
				"festival",
				"omroep",
				"internet"
			)
		);








		$html = "
				<table>
				<thead>
				<tr><th>id</th><th>name</th><th>type</th><th>Bezoekadres plaats</th><th>Postadres plaats</th>";

						foreach ($export_structure as $table_name => $fields) {
								$html .= "<th></th><th>".$table_name."</th>";
							foreach ($fields as $field) {
								$html .= "<th>".$field."</th>";
							}
						}


		$html .= "</tr>
				</thead>
				<tbody>";







		$organisation_list = $this->export_organisations;
		$organisation_list = $this->cab_functions->combine_organisation_lists($organisation_list,$this->export_organisations_merge_kunstpodium);
		$organisation_list = $this->cab_functions->combine_organisation_lists($organisation_list,$this->export_organisations_merge_kw14);
		$organisation_list = $this->cab_functions->combine_organisation_lists($organisation_list,$this->export_organisations_merge_plaza_futura);


		$organisations = $this->cab_functions->organisation_list_to_id_list($organisation_list['2013'], "all");

		//$organisations = $this->cab_functions->convert_organisation_list_to_id_list( $this->cab_functions->get_all_vragenlijst_organisations_list() );
		$i = 0;

		foreach ($organisations as $organisation_id) {
		//	echo "-".$organisation_id."-";
			$organisation_data = $this->cab_functions->get_organisation_data($organisation_id);

			$owner_id = $this->cab_functions->get_user_id_by_organisation_id($organisation_id);
			$organisation_meta = get_user_meta($owner_id);
			// $post_data = get_post_custom($organisation_id);
		//  $user_meta = get_user_meta($owner_id);

			# code...
			//print_r($organisation_data);
			//echo $organisation_data['general']['name'];

			$categories = get_field('vragenlijst_categorie',$organisation_id);

			$categories_value = "";
			foreach ($categories as $key => $value) {
				$categories_value .= " ".$type_array[$value].",";
			}
			$categories_value = rtrim($categories_value, ",");



			$html_body .= "<tr>
				<td>".$organisation_data['general']['id']."</td>
				<td>".$organisation_data['general']['name']."</td>
				<td>".$categories_value."</td>
				<td>".$organisation_meta['user-cab_bezoekadres-plaats'][0]."</td>
				<td>".$organisation_meta['user-cab_postadres-plaats'][0]."</td>

				";

				foreach ($export_structure as $table_name => $fields) {
						$html_body .= "<td></td><td></td>";
					foreach ($fields as $field) {
						$html_body .= "<td>";
						if (isset($organisation_data['periodical'][$period]['data'][$table_name][0][$field])) {
							$html_body .= $organisation_data['periodical'][$period]['data'][$table_name][0][$field];
						} else {
							$html_body .= 'null';
						}
						$html_body .= "</td>";
					}
				}

				$html_body .= "</tr>";

			$i+=1;
		}






		$html .= $html_body;

		$html .= "</tbody>
		</table>";

		echo $html;

	}





/*
Array
(
    [periodical] => Array
        (
            [2] => Array
                (
                    [period] => 2011
                    [data] => Array
                        (
                            [kernactiviteiten] => Array
                                (
                                    [discipline] => Array
                                        (
                                            [0] => 8
                                        )

                                    [type] => Array
                                        (
                                            [0] => 5
                                        )

                                    [keten] => Array
                                        (
                                            [0] => 3
                                        )

                                )

                            [cab_activiteiten] => Array
                                (
                                    [0] => Array
                                        (
                                            [id] => 1030
                                            [period_id] => 2
                                            [organisation_id] => 468
                                            [totaal] => 0
                                            [in_opdracht] => 0
                                            [eigen_werk] => 0
                                            [aanv_vragenlijst_id] => 4
                                            [premieres] => 0
                                        )

                                )

                            [cab_bezoekers] => Array
                                (
                                    [0] => Array
                                        (
                                            [id] => 877
                                            [period_id] => 2
                                            [organisation_id] => 468
                                            [totaal] => 47530
                                            [standplaats] => 0
                                            [provincie] => 0
                                            [nederland] => 0
                                            [buitenland] => 0
                                            [aanv_vragenlijst_id] => 4
                                            [podium] => 0
                                            [festivals] => 0
                                            [scholen] => 0
                                            [overig] => 0
                                        )

                                )

                            [cab_eigen_inkomsten] => Array
                                (
                                    [0] => Array
                                        (
                                            [id] => 209
                                            [period_id] => 2
                                            [organisation_id] => 468
                                            [publieksinkomsten] => 285390
                                            [sponsoring] => 5600
                                            [private_fondsen] => 207097
                                            [overig] => 16360
                                            [totaal] => 514447
                                        )

                                )

                            [cab_marketing] => Array
                                (
                                    [0] => Array
                                        (
                                            [id] => 197
                                            [period_id] => 2
                                            [organisation_id] => 468
                                            [uitgaven] => 47000
                                        )

                                )

                            [cab_media] => Array
                                (
                                    [0] => Array
                                        (
                                            [id] => 199
                                            [period_id] => 2
                                            [organisation_id] => 468
                                            [aandacht] => 3
                                        )

                                )

                            [cab_nevenactiviteiten] => Array
                                (
                                    [0] => Array
                                        (
                                            [id] => 866
                                            [period_id] => 2
                                            [organisation_id] => 468
                                            [totaal] => 180
                                            [educatief] => 165
                                            [overig] => 15
                                            [overig_toelichting] =>
                                            [aanv_vragenlijst_id] => 4
                                        )

                                )

                            [cab_omzet] => Array
                                (
                                    [0] => Array
                                        (
                                            [id] => 194
                                            [period_id] => 2
                                            [organisation_id] => 468
                                            [totaal] => 3618518
                                        )

                                )

                            [cab_organisatie] => Array
                                (
                                    [0] => Array
                                        (
                                            [id] => 83
                                            [period_id] => 2
                                            [organisation_id] => 468
                                            [fte] => 17.7
                                            [freelancers] => 9
                                            [vrijwilligers] => 6
                                            [stagiaires] => 4
                                        )

                                )

                            [cab_scholing] => Array
                                (
                                    [0] => Array
                                        (
                                            [id] => 191
                                            [period_id] => 2
                                            [organisation_id] => 468
                                            [uitgaven] => 7990
                                        )

                                )

                            [cab_spreiding] => Array
                                (
                                )

                            [cab_subsidy] => Array
                                (
                                    [0] => Array
                                        (
                                            [id] => 200
                                            [organisation_id] => 468
                                            [label] =>
                                            [period_id] => 2
                                            [totaal] => 3104135
                                            [gemeente] => 0
                                            [prov_nb] => 0
                                            [rijk] => 0
                                            [fonds_podiumkunsten] => 0
                                            [mondriaan_stichting] => 0
                                            [fonds_bkvb] => 0
                                            [mediafonds] => 0
                                            [nl_filmfonds] => 0
                                            [fonds_creatieve_industrie] => 0
                                            [letterenfonds] => 0
                                            [overig] => 0
                                            [mondriaan_fonds] => 0
                                            [gemeente_meerjarig] => 2348154
                                            [prov_nb_meerjarig] => 258000
                                            [rijk_meerjarig] => 497981
                                            [fonds_podiumkunsten_meerjarig] => 0
                                            [mondriaan_stichting_meerjarig] => 0
                                            [mondriaan_fonds_meerjarig] => 0
                                            [fonds_bkvb_meerjarig] => 0
                                            [mediafonds_meerjarig] => 0
                                            [nl_filmfonds_meerjarig] => 0
                                            [fonds_creatieve_industrie_meerjarig] => 0
                                            [letterenfonds_meerjarig] => 0
                                            [overig_toelichting] =>
                                        )

                                )

                            [cab_vertoningen] => Array
                                (
                                )

                        )

                )

            [3] => Array
                (
                    [period] => 2013
                    [data] => Array
                        (
                            [kernactiviteiten] => Array
                                (
                                    [discipline] => Array
                                        (
                                            [0] => 8
                                        )

                                    [type] => Array
                                        (
                                            [0] => 5
                                        )

                                    [keten] => Array
                                        (
                                            [0] => 3
                                        )

                                )

                            [cab_activiteiten] => Array
                                (
                                    [0] => Array
                                        (
                                            [id] => 1036
                                            [period_id] => 3
                                            [organisation_id] => 468
                                            [totaal] => 0
                                            [in_opdracht] => 0
                                            [eigen_werk] => 0
                                            [aanv_vragenlijst_id] => 4
                                            [premieres] => 0
                                        )

                                )

                            [cab_bezoekers] => Array
                                (
                                    [0] => Array
                                        (
                                            [id] => 882
                                            [period_id] => 3
                                            [organisation_id] => 468
                                            [totaal] => 0
                                            [standplaats] => 0
                                            [provincie] => 0
                                            [nederland] => 0
                                            [buitenland] => 0
                                            [aanv_vragenlijst_id] => 4
                                            [podium] => 0
                                            [festivals] => 0
                                            [scholen] => 0
                                            [overig] => 0
                                        )

                                )

                            [cab_eigen_inkomsten] => Array
                                (
                                    [0] => Array
                                        (
                                            [id] => 210
                                            [period_id] => 3
                                            [organisation_id] => 468
                                            [publieksinkomsten] => 162000
                                            [sponsoring] => 2500
                                            [private_fondsen] => 388000
                                            [overig] => 140000
                                            [totaal] => 692500
                                        )

                                )

                            [cab_marketing] => Array
                                (
                                    [0] => Array
                                        (
                                            [id] => 198
                                            [period_id] => 3
                                            [organisation_id] => 468
                                            [uitgaven] => 45000
                                        )

                                )

                            [cab_media] => Array
                                (
                                    [0] => Array
                                        (
                                            [id] => 200
                                            [period_id] => 3
                                            [organisation_id] => 468
                                            [aandacht] => 0
                                        )

                                )

                            [cab_nevenactiviteiten] => Array
                                (
                                    [0] => Array
                                        (
                                            [id] => 871
                                            [period_id] => 3
                                            [organisation_id] => 468
                                            [totaal] => 0
                                            [educatief] => 0
                                            [overig] => 0
                                            [overig_toelichting] =>
                                            [aanv_vragenlijst_id] => 4
                                        )

                                )

                            [cab_omzet] => Array
                                (
                                    [0] => Array
                                        (
                                            [id] => 195
                                            [period_id] => 3
                                            [organisation_id] => 468
                                            [totaal] => 3148516
                                        )

                                )

                            [cab_organisatie] => Array
                                (
                                    [0] => Array
                                        (
                                            [id] => 84
                                            [period_id] => 3
                                            [organisation_id] => 468
                                            [fte] => 14
                                            [freelancers] => 5
                                            [vrijwilligers] => 5
                                            [stagiaires] => 5
                                        )

                                )

                            [cab_scholing] => Array
                                (
                                    [0] => Array
                                        (
                                            [id] => 192
                                            [period_id] => 3
                                            [organisation_id] => 468
                                            [uitgaven] => 7000
                                        )

                                )

                            [cab_spreiding] => Array
                                (
                                )

                            [cab_subsidy] => Array
                                (
                                    [0] => Array
                                        (
                                            [id] => 201
                                            [organisation_id] => 468
                                            [label] =>
                                            [period_id] => 3
                                            [totaal] => 2456016
                                            [gemeente] => 0
                                            [prov_nb] => 0
                                            [rijk] => 0
                                            [fonds_podiumkunsten] => 0
                                            [mondriaan_stichting] => 0
                                            [fonds_bkvb] => 0
                                            [mediafonds] => 0
                                            [nl_filmfonds] => 0
                                            [fonds_creatieve_industrie] => 25630
                                            [letterenfonds] => 0
                                            [overig] => 62000
                                            [mondriaan_fonds] => 15000
                                            [gemeente_meerjarig] => 2353386
                                            [prov_nb_meerjarig] => 0
                                            [rijk_meerjarig] => 0
                                            [fonds_podiumkunsten_meerjarig] => 0
                                            [mondriaan_stichting_meerjarig] => 0
                                            [mondriaan_fonds_meerjarig] => 0
                                            [fonds_bkvb_meerjarig] => 0
                                            [mediafonds_meerjarig] => 0
                                            [nl_filmfonds_meerjarig] => 0
                                            [fonds_creatieve_industrie_meerjarig] => 0
                                            [letterenfonds_meerjarig] => 0
                                            [overig_toelichting] =>
                                        )

                                )

                            [cab_vertoningen] => Array
                                (
                                )

                        )

                )

        )

)*/











// 	function report_global_income() {


// 		// Get organisations array by type
// 		//print_r($this->cab_functions->get_all_organisations_by_type_list());

// //		$organisation_ids_by_type = $this->cab_functions->get_all_organisations_by_type_list();

// 		// foreach ($organisation_ids_by_type as $key => $type) {
// 		// 	print_r($)
// 		// 	# code...
// 		// }
// 		// 		//print_r($this->cab_functions->get_all_organisations_by_type_list());

// 		$organisation_data = $this->cab_functions->get_organisation_data_sum_by_type(
// 			$this->cab_functions->get_all_organisations_by_type_list(),
// 			"cab_subsidy",
// 			array(
// 				"totaal",
// 				"rijk",
// 				"rijk_meerjarig",
// 				"gemeente",
// 				"gemeente_meerjarig",
// 				"prov_nb",
// 				"prov_nb_meerjarig",
// 				"overig"
// 			),
// 			2
// 		);


// 		// $organisation_data = $this->cab_functions->get_organisation_data_sum(
// 		// 	array(503),
// 		// 	"cab_eigen_inkomsten",
// 		// 	array("publieksinkomsten","sponsoring","private_fondsen","overig","totaal"),
// 		// 	2
// 		// );



// 		return $organisation_data;
// 	}

	/*
	Array
(
    [periodical] => Array
        (
            [2] => Array
                (
                    [period] => 2011
                    [data] => Array
                        (
                            [kernactiviteiten] => Array
                                (
                                    [discipline] => Array
                                        (
                                            [0] => 8
                                            [1] => 5
                                        )

                                    [type] => Array
                                        (
                                            [0] => 9
                                        )

                                    [keten] => Array
                                        (
                                            [0] => 1
                                        )

                                )

                            [cab_activiteiten] => Array
                                (
                                    [0] => Array
                                        (
                                            [id] => 1202
                                            [period_id] => 2
                                            [organisation_id] => 503
                                            [totaal] => 76
                                            [in_opdracht] => 0
                                            [eigen_werk] => 0
                                            [aanv_vragenlijst_id] => 2
                                            [premieres] => 0
                                        )

                                )

                            [cab_bezoekers] => Array
                                (
                                    [0] => Array
                                        (
                                            [id] => 1021
                                            [period_id] => 2
                                            [organisation_id] => 503
                                            [totaal] => 0
                                            [standplaats] => 0
                                            [provincie] => 0
                                            [nederland] => 0
                                            [buitenland] => 0
                                            [aanv_vragenlijst_id] => 2
                                            [podium] => 0
                                            [festivals] => 0
                                            [scholen] => 0
                                            [overig] => 0
                                        )

                                )

                            [cab_eigen_inkomsten] => Array
                                (
                                    [0] => Array
                                        (
                                            [id] => 238
                                            [period_id] => 2
                                            [organisation_id] => 503
                                            [publieksinkomsten] => 0
                                            [sponsoring] => 0
                                            [private_fondsen] => 3367
                                            [overig] => 205401
                                            [totaal] => 208768
                                        )

                                )

                            [cab_marketing] => Array
                                (
                                    [0] => Array
                                        (
                                            [id] => 226
                                            [period_id] => 2
                                            [organisation_id] => 503
                                            [uitgaven] => 76252
                                        )

                                )

                            [cab_media] => Array
                                (
                                    [0] => Array
                                        (
                                            [id] => 228
                                            [period_id] => 2
                                            [organisation_id] => 503
                                            [aandacht] => 3
                                        )

                                )

                            [cab_nevenactiviteiten] => Array
                                (
                                    [0] => Array
                                        (
                                            [id] => 1010
                                            [period_id] => 2
                                            [organisation_id] => 503
                                            [totaal] => 0
                                            [educatief] => 0
                                            [overig] => 0
                                            [overig_toelichting] =>
                                            [aanv_vragenlijst_id] => 2
                                        )

                                )

                            [cab_omzet] => Array
                                (
                                    [0] => Array
                                        (
                                            [id] => 223
                                            [period_id] => 2
                                            [organisation_id] => 503
                                            [totaal] => 0
                                        )

                                )

                            [cab_organisatie] => Array
                                (
                                    [0] => Array
                                        (
                                            [id] => 112
                                            [period_id] => 2
                                            [organisation_id] => 503
                                            [fte] => 14
                                            [freelancers] => 0
                                            [vrijwilligers] => 5
                                            [stagiaires] => 2
                                        )

                                )

                            [cab_scholing] => Array
                                (
                                    [0] => Array
                                        (
                                            [id] => 220
                                            [period_id] => 2
                                            [organisation_id] => 503
                                            [uitgaven] => 8267
                                        )

                                )

                            [cab_spreiding] => Array
                                (
                                )

                            [cab_subsidy] => Array
                                (
                                    [0] => Array
                                        (
                                            [id] => 229
                                            [organisation_id] => 503
                                            [label] =>
                                            [period_id] => 2
                                            [totaal] => 1042412
                                            [gemeente] => 5000
                                            [prov_nb] => 0
                                            [rijk] => 947414
                                            [fonds_podiumkunsten] => 0
                                            [mondriaan_stichting] => 0
                                            [fonds_bkvb] => 0
                                            [mediafonds] => 0
                                            [nl_filmfonds] => 0
                                            [fonds_creatieve_industrie] => 0
                                            [letterenfonds] => 0
                                            [overig] => 89998
                                            [mondriaan_fonds] => 0
                                            [gemeente_meerjarig] => 0
                                            [prov_nb_meerjarig] => 1
                                            [rijk_meerjarig] => 1
                                            [fonds_podiumkunsten_meerjarig] => 0
                                            [mondriaan_stichting_meerjarig] => 0
                                            [mondriaan_fonds_meerjarig] => 0
                                            [fonds_bkvb_meerjarig] => 0
                                            [mediafonds_meerjarig] => 0
                                            [nl_filmfonds_meerjarig] => 0
                                            [fonds_creatieve_industrie_meerjarig] => 0
                                            [letterenfonds_meerjarig] => 0
                                            [overig_toelichting] => op zuid
                                        )

                                )

                            [cab_vertoningen] => Array
                                (
                                )

                        )

                )
*/


/* GET ORGANISATIONS
---------------------------------------------------------------------------------------------------*/















}

$cab_api = new cab_api();

?>
