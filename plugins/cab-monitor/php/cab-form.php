<?php
class cab_form
{
	
	var $settings;
	
	function __construct()
	{
		global $cab_functions;
		$this->cab_functions = $cab_functions;

		add_action('init', array($this, 'init'), 1);

		// Function is called when form is accessed
		add_filter("gform_pre_render_1", array($this, 'gform_start_entry'));
		add_filter("gform_pre_render_1", array($this, 'gform_replace_tags'));

		add_filter( 'gform_field_validation_1', array($this, 'gform_custom_validation'), 10, 4 );

		// Function is called when form finished submission
		add_action("gform_after_submission_1", array($this, 'gform_entry_submitted'), 10, 2);



		$this->_general_array = array(
			'formulier_periode' => array ('gform-field-id' => 181, 'gform-populate-slug' => 'formulier_periode'),
			'formulier_organisatie_id' => array ('gform-field-id' => 273, 'gform-populate-slug' => 'formulier_organisatie_id')
		);

		$this->_kernactiviteiten_array = array(
			'type_id' => array ('gform-field-id' => 14, 'gform-populate-slug' => 'kernactiviteiten_type', 'table-name' => 'cab_organisatie_type'),
			'discipline_id' => array ('gform-field-id' => 16, 'gform-populate-slug' => 'kernactiviteiten_discipline', 'table-name' => 'cab_organisatie_discipline'),
			'keten_id' => array ('gform-field-id' => 17, 'gform-populate-slug' => 'kernactiviteiten_keten', 'table-name' => 'cab_organisatie_keten'),

		);


		$this->_user_meta_array = array(
		    'user-cab_organisatie-naam' => array ('gform-field-id' => 1, 'gform-populate-slug' => 'naam_organisatie'),
		    'user-cab_organisatie-directeur-naam' => array ('gform-field-id' => 3, 'gform-populate-slug' => 'naam_directeur'),
		    'user-cab_organisatie-directeur-email' => array ('gform-field-id' => 183, 'gform-populate-slug' => 'email_directeur'),

		    'user-cab_organisatie-directeur-functie' => array ('gform-field-id' => 210, 'gform-populate-slug' => 'functie_administratief-contactpersoon'),
		    'user-cab_organisatie-administratief-naam' => array ('gform-field-id' => 184, 'gform-populate-slug' => 'naam_administratief-contactpersoon'),
		    'user-cab_organisatie-administratief-email' => array ('gform-field-id' => 185, 'gform-populate-slug' => 'email_administratief-contactpersoon'),

		    'user-cab_organisatie-telefoon' => array ('gform-field-id' => 28, 'gform-populate-slug' => 'naw_telefoonnummer'),
		    'user-cab_organisatie-website' => array ('gform-field-id' => 29, 'gform-populate-slug' => 'naw_website'),
		    'user-cab_organisatie-email' => array ('gform-field-id' => 30, 'gform-populate-slug' => 'naw_email_algemeen'),
		    'user-cab_organisatie-facebook' => array ('gform-field-id' => 31, 'gform-populate-slug' => 'naw_facebook_gebruikersnaam'),
		    'user-cab_organisatie-twitter' => array ('gform-field-id' => 32, 'gform-populate-slug' => 'naw_twitter_gebruikersnaam'),

		    'user-cab_postadres-straat' => array ('gform-field-id' => 4, 'gform-populate-slug' => 'postadres_straat'),
		    'user-cab_postadres-huisnummer' => array ('gform-field-id' => 5, 'gform-populate-slug' => 'postadres_huisnummer'),
		    'user-cab_postadres-postcode' => array ('gform-field-id' => 6, 'gform-populate-slug' => 'postadres_postcode'),
		    'user-cab_postadres-plaats' => array ('gform-field-id' => 7, 'gform-populate-slug' => 'postadres_plaats'),
		    'user-cab_bezoekadres-straat' => array ('gform-field-id' => 23, 'gform-populate-slug' => 'bezoekadres_straat'),
		    'user-cab_bezoekadres-huisnummer' => array ('gform-field-id' => 24, 'gform-populate-slug' => 'bezoekadres_huisnummer'),
		    'user-cab_bezoekadres-postcode' => array ('gform-field-id' => 25, 'gform-populate-slug' => 'bezoekadres_postcode'),
		    'user-cab_bezoekadres-plaats' => array ('gform-field-id' => 26, 'gform-populate-slug' => 'bezoekadres_plaats')
		    );



		$this->_subsidy_array = array(
		    '_id' => array ('gform-field-id' => 49, 'gform-populate-slug' => 'subsidie_row-id'),
		    // 'totaal' => array ('gform-field-id' => 43, 'gform-populate-slug' => 'subsidie_totaal'),
		    // 'totaal_meerjarig' => array ('gform-field-id' => 252, 'gform-populate-slug' => 'subsidie_totaal_meerjarig'),

		    'gemeente' => array ('gform-field-id' => 10, 'gform-populate-slug' => 'subsidie_gemeente'),
		    'gemeente_meerjarig' => array ('gform-field-id' => 235, 'gform-populate-slug' => 'subsidie_gemeente_meerjarig'),

		    'prov_nb' => array ('gform-field-id' => 33, 'gform-populate-slug' => 'subsidie_prov-nb'),
		    'prov_nb_meerjarig' => array ('gform-field-id' => 236, 'gform-populate-slug' => 'subsidie_prov-nb_meerjarig'),

		    'rijk' => array ('gform-field-id' => 34, 'gform-populate-slug' => 'subsidie_rijk'),
		    'rijk_meerjarig' => array ('gform-field-id' => 237, 'gform-populate-slug' => 'subsidie_rijk_meerjarig'),

		    'fonds_podiumkunsten' => array ('gform-field-id' => 35, 'gform-populate-slug' => 'subsidie_fonds-podiumkunsten'),
		    'fonds_podiumkunsten_meerjarig' => array ('gform-field-id' => 238, 'gform-populate-slug' => 'subsidie_fonds-podiumkunsten_meerjarig'),

		    // 'mondriaan_stichting' => array ('gform-field-id' => 36, 'gform-populate-slug' => 'subsidie_mondriaan-stichting'),
		    // 'mondriaan_stichting_meerjarig' => array ('gform-field-id' => 239, 'gform-populate-slug' => 'subsidie_mondriaan-stichting_meerjarig'),

		    'mondriaan_fonds' => array ('gform-field-id' => 182, 'gform-populate-slug' => 'subsidie_mondriaan-fonds'),
		    'mondriaan_fonds_meerjarig' => array ('gform-field-id' => 241, 'gform-populate-slug' => 'subsidie_mondriaan-fonds_meerjarig'),

		    // 'fonds_bkvb' => array ('gform-field-id' => 37, 'gform-populate-slug' => 'subsidie_fonds-bkvb'),
		    // 'fonds_bkvb_meerjarig' => array ('gform-field-id' => 240, 'gform-populate-slug' => 'subsidie_fonds-bkvb_meerjarig'),

		    'fonds_cultuurparticipatie' => array ('gform-field-id' => 251, 'gform-populate-slug' => 'subsidie_cultuurparticipatie-fonds'),
		    'fonds_cultuurparticipatie_meerjarig' => array ('gform-field-id' => 250, 'gform-populate-slug' => 'subsidie_cultuurparticipatie-fonds_meerjarig'),

		    'mediafonds' => array ('gform-field-id' => 38, 'gform-populate-slug' => 'subsidie_mediafonds'),
		    'mediafonds_meerjarig' => array ('gform-field-id' => 242, 'gform-populate-slug' => 'subsidie_mediafonds_meerjarig'),

		    'nl_filmfonds' => array ('gform-field-id' => 40, 'gform-populate-slug' => 'subsidie_nl-filmfonds'),
		    'nl_filmfonds_meerjarig' => array ('gform-field-id' => 243, 'gform-populate-slug' => 'subsidie_nl-filmfonds_meerjarig'),

		    'fonds_creatieve_industrie' => array ('gform-field-id' => 39, 'gform-populate-slug' => 'subsidie_fonds-creatieve-industrie'),
		    'fonds_creatieve_industrie_meerjarig' => array ('gform-field-id' => 244, 'gform-populate-slug' => 'subsidie_fonds-creatieve-industrie_meerjarig'),

		    'letterenfonds' => array ('gform-field-id' => 41, 'gform-populate-slug' => 'subsidie_letterenfonds'),
		    'letterenfonds_meerjarig' => array ('gform-field-id' => 245, 'gform-populate-slug' => 'subsidie_letterenfonds_meerjarig'),

		    'overig' => array ('gform-field-id' => 42, 'gform-populate-slug' => 'subsidie_overig'),
		    'overig_meerjarig' => array ('gform-field-id' => 253, 'gform-populate-slug' => 'subsidie_overig_meerjarig'),

		    'overig_toelichting' => array ('gform-field-id' => 206, 'gform-populate-slug' => 'subsidie_overig_toelichting'),
		    );

		$this->eigen_inkomsten_array = array(
		    '_id' => array ('gform-field-id' => 55, 'gform-populate-slug' => 'eigen-inkomsten_row-id'),
		    'totaal' => array ('gform-field-id' => 207, 'gform-populate-slug' => 'eigen-inkomsten_totaal'),
		    'publieksinkomsten' => array ('gform-field-id' => 64, 'gform-populate-slug' => 'eigen-inkomsten_publieksinkomsten'),
		    'sponsoring' => array ('gform-field-id' => 52, 'gform-populate-slug' => 'eigen-inkomsten_sponsoring'),
		    'private_fondsen' => array ('gform-field-id' => 53, 'gform-populate-slug' => 'eigen-inkomsten_private-fondsen'),
		    'overig' => array ('gform-field-id' => 54, 'gform-populate-slug' => 'eigen-inkomsten_overig')
		    ); 

		$this->_organisatie_array = array(
		    '_id' => array ('gform-field-id' => 57, 'gform-populate-slug' => 'organisatie_row-id'),
		    'werknemers' => array ('gform-field-id' => 254, 'gform-populate-slug' => 'organisatie_loondienst_personen'),
		    'werknemers_fte' => array ('gform-field-id' => 256, 'gform-populate-slug' => 'organisatie_loondienst_personen_fte', 'format'=>'decimal'),
		    'freelancers' => array ('gform-field-id' => 59, 'gform-populate-slug' => 'organisatie_freelancers'),
		    'freelancers_fte' => array ('gform-field-id' => 257, 'gform-populate-slug' => 'organisatie_freelancers_fte', 'format'=>'decimal'),
		    'vrijwilligers' => array ('gform-field-id' => 60, 'gform-populate-slug' => 'organisatie_vrijwilligers'),
		    'vrijwilligers_fte' => array ('gform-field-id' => 259, 'gform-populate-slug' => 'organisatie_vrijwilligers_fte', 'format'=>'decimal'),
		    'stagiaires' => array ('gform-field-id' => 61, 'gform-populate-slug' => 'organisatie_stagiaires'),
		    'stagiaires_fte' => array ('gform-field-id' => 258, 'gform-populate-slug' => 'organisatie_stagiaires_fte', 'format'=>'decimal'),
		    'lasten_vastcontract' => array ('gform-field-id' => 277, 'gform-populate-slug' => 'organisatie_personeelslasten_vastcontract'),
		    'lasten_tijdelijk' => array ('gform-field-id' => 278, 'gform-populate-slug' => 'organisatie_personeelslasten_tijdelijk'),
		    'lasten_inhuur' => array ('gform-field-id' => 279, 'gform-populate-slug' => 'organisatie_personeelslasten_inhuur')

		    ); 

		$this->_omzet_array = array(
		    '_id' => array ('gform-field-id' => 63, 'gform-populate-slug' => 'omzet_row-id'),
		    'totaal' => array ('gform-field-id' => 51, 'gform-populate-slug' => 'omzet_totaal')
		    ); 

		$this->_scholing_array = array(
		    '_id' => array ('gform-field-id' => 67, 'gform-populate-slug' => 'scholing_row-id'),
		    'uitgaven' => array ('gform-field-id' => 66, 'gform-populate-slug' => 'scholing_uitgaven'),
		    'onderwerpen' => array ('gform-field-id' => 260, 'gform-populate-slug' => 'scholing_onderwerpen'),
		    'onderwerpen_anders' => array ('gform-field-id' => 261, 'gform-populate-slug' => 'scholing_onderwerpen_anders')

			); 

		$this->_marketing_array = array(
		    '_id' => array ('gform-field-id' => 69, 'gform-populate-slug' => 'marketing_row-id'),
		    'uitgaven' => array ('gform-field-id' => 70, 'gform-populate-slug' => 'marketing_uitgaven')
		    ); 

		$this->_media_array = array(
		    '_id' => array ('gform-field-id' => 73, 'gform-populate-slug' => 'media_row-id'),
		    'aandacht' => array ('gform-field-id' => 72, 'gform-populate-slug' => 'media_aandacht'),
		    'aandacht_twitter' => array ('gform-field-id' => 264, 'gform-populate-slug' => 'media_aandacht_twitter'),
		    'aandacht_twitter_toelichting' => array ('gform-field-id' => 265, 'gform-populate-slug' => 'media_aandacht_twitter_toelichting'),
		    'aandacht_facebook' => array ('gform-field-id' => 268, 'gform-populate-slug' => 'media_aandacht_facebook'),
		    'aandacht_facebook_toelichting' => array ('gform-field-id' => 267, 'gform-populate-slug' => 'media_aandacht_facebook_toelichting')
		    ); 



		/* AANVULLEND 1 Festivals */
		$this->_aanv_1 = array(
			"_aanv_1_activiteiten" => array(
				"table" => "cab_activiteiten",
				"id" => array ('gform-field-id' => 75, 'gform-populate-slug' => 'aanv-1-activiteiten_row-id'),
				"aanv_vragenlijst_id" => 1,
				"data" => array(
			    	'aantal' => array ('gform-field-id' => 76, 'gform-populate-slug' => 'aanv-1-activiteiten_aantal')
			    )
			),

			"_aanv_1_nevenactiviteiten" => array(
				"table" => "cab_nevenactiviteiten",
				"id" => array ('gform-field-id' => 78, 'gform-populate-slug' => 'aanv-1-nevenactiviteiten_row-id'),
				"aanv_vragenlijst_id" => 1,
				"data" => array(
				    'totaal' => array ('gform-field-id' => 79, 'gform-populate-slug' => 'aanv-1-nevenactiviteiten_totaal'),
				    'educatief' => array ('gform-field-id' => 80, 'gform-populate-slug' => 'aanv-1-nevenactiviteiten_educatief')
				    // 'overig' => array ('gform-field-id' => 81, 'gform-populate-slug' => 'aanv-1-nevenactiviteiten_overig'),
				    // 'overig_toelichting' => array ('gform-field-id' => 195, 'gform-populate-slug' => 'aanv-1-nevenactiviteiten_overig_toelichting')
				)
			),

			"_aanv_1_bezoekers" => array(
				"table" => "cab_bezoekers",
				"id" => array ('gform-field-id' => 83, 'gform-populate-slug' => 'aanv-1-bezoekers_row-id'),
				"aanv_vragenlijst_id" => 1,
				"data" => array(
				    //'totaal' => array ('gform-field-id' => 84, 'gform-populate-slug' => 'aanv-1-bezoekers_totaal'),
				    'standplaats' => array ('gform-field-id' => 85, 'gform-populate-slug' => 'aanv-1-bezoekers_standplaats'),
				    'provincie' => array ('gform-field-id' => 86, 'gform-populate-slug' => 'aanv-1-bezoekers_provincie'),
				    'nederland' => array ('gform-field-id' => 87, 'gform-populate-slug' => 'aanv-1-bezoekers_nederland'),
				    'buitenland' => array ('gform-field-id' => 88, 'gform-populate-slug' => 'aanv-1-bezoekers_buitenland'),
				    'betaald' => array ('gform-field-id' => 84, 'gform-populate-slug' => 'aanv-1-bezoekers_betaald'),
				    'niet_betaald' => array ('gform-field-id' => 287, 'gform-populate-slug' => 'aanv-1-bezoekers_nietbetaald')
				)
			)

		);

		$this->_aanv_2 = array(
			"_aanv_2_activiteiten" => array(
				"table" => "cab_activiteiten",
				"id" => array ('gform-field-id' => 90, 'gform-populate-slug' => 'aanv-2-activiteiten_row-id'),
				"aanv_vragenlijst_id" => 2,
				"data" => array(
			    	'aantal' => array ('gform-field-id' => 91, 'gform-populate-slug' => 'aanv-2-activiteiten_aantal')
			    )
			),

			"_aanv_2_nevenactiviteiten" => array(
				"table" => "cab_nevenactiviteiten",
				"id" => array ('gform-field-id' => 93, 'gform-populate-slug' => 'aanv-2-nevenactiviteiten_row-id'),
				"aanv_vragenlijst_id" => 2,
				"data" => array(
				    'totaal' => array ('gform-field-id' => 94, 'gform-populate-slug' => 'aanv-2-nevenactiviteiten_totaal'),
				    'educatief' => array ('gform-field-id' => 95, 'gform-populate-slug' => 'aanv-2-nevenactiviteiten_educatief')
				    // 'overig' => array ('gform-field-id' => 96, 'gform-populate-slug' => 'aanv-2-nevenactiviteiten_overig'),
				    // 'overig_toelichting' => array ('gform-field-id' => 193, 'gform-populate-slug' => 'aanv-2-nevenactiviteiten_overig_toelichting')
				)
			),

			"_aanv_2_bezoekers" => array(
				"table" => "cab_bezoekers",
				"id" => array ('gform-field-id' => 98, 'gform-populate-slug' => 'aanv-2-bezoekers_row-id'),
				"aanv_vragenlijst_id" => 2,
				"data" => array(
				    //'totaal' => array ('gform-field-id' => 99, 'gform-populate-slug' => 'aanv-2-bezoekers_totaal'),
				    'standplaats' => array ('gform-field-id' => 100, 'gform-populate-slug' => 'aanv-2-bezoekers_standplaats'),
				    'provincie' => array ('gform-field-id' => 101, 'gform-populate-slug' => 'aanv-2-bezoekers_provincie'),
				    'nederland' => array ('gform-field-id' => 102, 'gform-populate-slug' => 'aanv-2-bezoekers_nederland'),
				    'buitenland' => array ('gform-field-id' => 103, 'gform-populate-slug' => 'aanv-2-bezoekers_buitenland'),
				    'betaald' => array ('gform-field-id' => 286, 'gform-populate-slug' => 'aanv-2-bezoekers_betaald'),
				    'niet_betaald' => array ('gform-field-id' => 285, 'gform-populate-slug' => 'aanv-2-bezoekers_nietbetaald')
			
				    )
			)

		);


		$this->_aanv_3 = array(
			"_aanv_3_activiteiten" => array(
				"table" => "cab_activiteiten",
				"id" => array ('gform-field-id' => 105, 'gform-populate-slug' => 'aanv-3-activiteiten_row-id'),
				"aanv_vragenlijst_id" => 3,
				"data" => array(
			    	'aantal' => array ('gform-field-id' => 106, 'gform-populate-slug' => 'aanv-3-activiteiten_aantal')
			    	// 'in_opdracht' => array ('gform-field-id' => 179, 'gform-populate-slug' => 'aanv-3-activiteiten_in-opdracht'),
			    	// 'eigen_werk' => array ('gform-field-id' => 180, 'gform-populate-slug' => 'aanv-3-activiteiten_eigen-werk')

			    )
			),

			"_aanv_3_vertoningen" => array(
				"table" => "cab_vertoningen",
				"id" => array ('gform-field-id' => 108, 'gform-populate-slug' => 'aanv-3-vertoningen_row-id'),
				"aanv_vragenlijst_id" => 3,
				"data" => array(
				    'totaal' => array ('gform-field-id' => 109, 'gform-populate-slug' => 'aanv-3-vertoningen_totaal'),
				    'standplaats' => array ('gform-field-id' => 110, 'gform-populate-slug' => 'aanv-3-vertoningen_standplaats'),
				    'provincie' => array ('gform-field-id' => 111, 'gform-populate-slug' => 'aanv-3-vertoningen_provincie'),
				    'nederland' => array ('gform-field-id' => 112, 'gform-populate-slug' => 'aanv-3-vertoningen_nederland'),
				    'buitenland' => array ('gform-field-id' => 113, 'gform-populate-slug' => 'aanv-3-vertoningen_buitenland'),
				    'bioscoop' => array ('gform-field-id' => 114, 'gform-populate-slug' => 'aanv-3-vertoningen_bioscoop'),
				    'filmhuis' => array ('gform-field-id' => 115, 'gform-populate-slug' => 'aanv-3-vertoningen_filmhuis'),
				    'festival' => array ('gform-field-id' => 116, 'gform-populate-slug' => 'aanv-3-vertoningen_festival'),
				    'omroep' => array ('gform-field-id' => 117, 'gform-populate-slug' => 'aanv-3-vertoningen_omroep'),
				    'internet' => array ('gform-field-id' => 118, 'gform-populate-slug' => 'aanv-3-vertoningen_internet'),
				    'internet_toelichting' => array ('gform-field-id' => 196, 'gform-populate-slug' => 'aanv-3-vertoningen_internet-toelichting')

			
				    )
			)

		);


		$this->_aanv_4 = array(
			"_aanv_4_activiteiten" => array(
				"table" => "cab_activiteiten",
				"id" => array ('gform-field-id' => 120, 'gform-populate-slug' => 'aanv-4-activiteiten_row-id'),
				"aanv_vragenlijst_id" => 4,
				"data" => array(
			    	'aantal' => array ('gform-field-id' => 121, 'gform-populate-slug' => 'aanv-4-activiteiten_aantal')
			    )
			),

			"_aanv_4_nevenactiviteiten" => array(
				"table" => "cab_nevenactiviteiten",
				"id" => array ('gform-field-id' => 123, 'gform-populate-slug' => 'aanv-4-nevenactiviteiten_row-id'),
				"aanv_vragenlijst_id" => 4,
				"data" => array(
				    'totaal' => array ('gform-field-id' => 124, 'gform-populate-slug' => 'aanv-4-nevenactiviteiten_totaal'),
				    'educatief' => array ('gform-field-id' => 125, 'gform-populate-slug' => 'aanv-4-nevenactiviteiten_educatief')
				    // 'overig' => array ('gform-field-id' => 126, 'gform-populate-slug' => 'aanv-4-nevenactiviteiten_overig'),
				    // 'overig_toelichting' => array ('gform-field-id' => 194, 'gform-populate-slug' => 'aanv-4-nevenactiviteiten_overig-toelichting')
				)
			),

			"_aanv_4_bezoekers" => array(
				"table" => "cab_bezoekers",
				"id" => array ('gform-field-id' => 128, 'gform-populate-slug' => 'aanv-4-bezoekers_row-id'),
				"aanv_vragenlijst_id" => 4,
				"data" => array(
				    //'totaal' => array ('gform-field-id' => 129, 'gform-populate-slug' => 'aanv-4-bezoekers_totaal'),
				    'standplaats' => array ('gform-field-id' => 130, 'gform-populate-slug' => 'aanv-4-bezoekers_standplaats'),
				    'provincie' => array ('gform-field-id' => 131, 'gform-populate-slug' => 'aanv-4-bezoekers_provincie'),
				    'nederland' => array ('gform-field-id' => 132, 'gform-populate-slug' => 'aanv-4-bezoekers_nederland'),
				    'buitenland' => array ('gform-field-id' => 133, 'gform-populate-slug' => 'aanv-4-bezoekers_buitenland'),
				    'betaald' => array ('gform-field-id' => 129, 'gform-populate-slug' => 'aanv-4-bezoekers_betaald'),
				    'niet_betaald' => array ('gform-field-id' => 289, 'gform-populate-slug' => 'aanv-4-bezoekers_nietbetaald')
			
				    )
			)

		);


		$this->_aanv_5 = array(
			"_aanv_5_activiteiten" => array(
				"table" => "cab_activiteiten",
				"id" => array ('gform-field-id' => 135, 'gform-populate-slug' => 'aanv-5-activiteiten_row-id'),
				"aanv_vragenlijst_id" => 5,
				"data" => array(
			    	'aantal' => array ('gform-field-id' => 136, 'gform-populate-slug' => 'aanv-5-activiteiten_aantal')
			    )
			),

			"_aanv_5_nevenactiviteiten" => array(
				"table" => "cab_nevenactiviteiten",
				"id" => array ('gform-field-id' => 138, 'gform-populate-slug' => 'aanv-5-nevenactiviteiten_row-id'),
				"aanv_vragenlijst_id" => 5,
				"data" => array(
				    'totaal' => array ('gform-field-id' => 139, 'gform-populate-slug' => 'aanv-5-nevenactiviteiten_totaal'),
				    'educatief' => array ('gform-field-id' => 140, 'gform-populate-slug' => 'aanv-5-nevenactiviteiten_educatief')
				    // 'overig' => array ('gform-field-id' => 141, 'gform-populate-slug' => 'aanv-5-nevenactiviteiten_overig'),
				    // 'overig_toelichting' => array ('gform-field-id' => 197, 'gform-populate-slug' => 'aanv-5-nevenactiviteiten_overig-toelichting')
				)
			),

			"_aanv_5_bezoekers" => array(
				"table" => "cab_bezoekers",
				"id" => array ('gform-field-id' => 143, 'gform-populate-slug' => 'aanv-5-bezoekers_row-id'),
				"aanv_vragenlijst_id" => 5,
				"data" => array(
				    //'totaal' => array ('gform-field-id' => 144, 'gform-populate-slug' => 'aanv-5-bezoekers_totaal'),
				    'standplaats' => array ('gform-field-id' => 145, 'gform-populate-slug' => 'aanv-5-bezoekers_standplaats'),
				    'provincie' => array ('gform-field-id' => 146, 'gform-populate-slug' => 'aanv-5-bezoekers_provincie'),
				    'nederland' => array ('gform-field-id' => 147, 'gform-populate-slug' => 'aanv-5-bezoekers_nederland'),
				    'buitenland' => array ('gform-field-id' => 148, 'gform-populate-slug' => 'aanv-5-bezoekers_buitenland'),
				    'betaald' => array ('gform-field-id' => 290, 'gform-populate-slug' => 'aanv-5-bezoekers_betaald'),
				    'niet_betaald' => array ('gform-field-id' => 291, 'gform-populate-slug' => 'aanv-5-bezoekers_nietbetaald')
			
				    )
			)

		);



		$this->_aanv_6 = array(
			"_aanv_6_activiteiten" => array(
				"table" => "cab_activiteiten",
				"id" => array ('gform-field-id' => 150, 'gform-populate-slug' => 'aanv-6-activiteiten_row-id'),
				"aanv_vragenlijst_id" => 6,
				"data" => array(
			    	'aantal' => array ('gform-field-id' => 151, 'gform-populate-slug' => 'aanv-6-activiteiten_aantal'),
			    	'premieres' => array ('gform-field-id' => 178, 'gform-populate-slug' => 'aanv-6-activiteiten_premieres'),
			    	'reprises' => array ('gform-field-id' => 269, 'gform-populate-slug' => 'aanv-6-activiteiten_reprises')

			    )
			),

			"_aanv_6_spreiding" => array(
				"table" => "cab_spreiding",
				"id" => array ('gform-field-id' => 153, 'gform-populate-slug' => 'aanv-6-spreiding_row-id'),
				"aanv_vragenlijst_id" => 6,
				"data" => array(
				    'standplaats' => array ('gform-field-id' => 154, 'gform-populate-slug' => 'aanv-6-spreiding_standplaats'),
				    'provincie' => array ('gform-field-id' => 155, 'gform-populate-slug' => 'aanv-6-spreiding_provincie'),
				    'nederland' => array ('gform-field-id' => 156, 'gform-populate-slug' => 'aanv-6-spreiding_nederland'),
				    'buitenland' => array ('gform-field-id' => 157, 'gform-populate-slug' => 'aanv-6-spreiding_buitenland'),
				    'podium' => array ('gform-field-id' => 158, 'gform-populate-slug' => 'aanv-6-spreiding_podium'),
				    'festivals' => array ('gform-field-id' => 159, 'gform-populate-slug' => 'aanv-6-spreiding_festivals'),
				    'scholen' => array ('gform-field-id' => 160, 'gform-populate-slug' => 'aanv-6-spreiding_scholen'),
				    'overig' => array ('gform-field-id' => 161, 'gform-populate-slug' => 'aanv-6-spreiding_overig')
				    )
			),

			"_aanv_6_nevenactiviteiten" => array(
				"table" => "cab_nevenactiviteiten",
				"id" => array ('gform-field-id' => 163, 'gform-populate-slug' => 'aanv-6-nevenactiviteiten_row-id'),
				"aanv_vragenlijst_id" => 6,
				"data" => array(
				    'totaal' => array ('gform-field-id' => 164, 'gform-populate-slug' => 'aanv-6-nevenactiviteiten_totaal'),
				    'educatief' => array ('gform-field-id' => 165, 'gform-populate-slug' => 'aanv-6-nevenactiviteiten_educatief'),
				    'overig' => array ('gform-field-id' => 166, 'gform-populate-slug' => 'aanv-6-nevenactiviteiten_overig'),
				    'overig_toelichting' => array ('gform-field-id' => 198, 'gform-populate-slug' => 'aanv-6-nevenactiviteiten_overig-toelichting')
				)
			),

			"_aanv_6_bezoekers" => array(
				"table" => "cab_bezoekers",
				"id" => array ('gform-field-id' => 168, 'gform-populate-slug' => 'aanv-6-bezoekers_row-id'),
				"aanv_vragenlijst_id" => 6,
				"data" => array(
				   // 'totaal' => array ('gform-field-id' => 169, 'gform-populate-slug' => 'aanv-6-bezoekers_totaal'),
				    'standplaats' => array ('gform-field-id' => 170, 'gform-populate-slug' => 'aanv-6-bezoekers_standplaats'),
				    'provincie' => array ('gform-field-id' => 171, 'gform-populate-slug' => 'aanv-6-bezoekers_provincie'),
				    'nederland' => array ('gform-field-id' => 172, 'gform-populate-slug' => 'aanv-6-bezoekers_nederland'),
				    'buitenland' => array ('gform-field-id' => 173, 'gform-populate-slug' => 'aanv-6-bezoekers_buitenland'),
				    'podium' => array ('gform-field-id' => 174, 'gform-populate-slug' => 'aanv-6-bezoekers_podium'),
				    'festivals' => array ('gform-field-id' => 175, 'gform-populate-slug' => 'aanv-6-bezoekers_festivals'),
				    'scholen' => array ('gform-field-id' => 176, 'gform-populate-slug' => 'aanv-6-bezoekers_scholen'),
				    'overig' => array ('gform-field-id' => 177, 'gform-populate-slug' => 'aanv-6-bezoekers_overig'),
				    'betaald' => array ('gform-field-id' => 293, 'gform-populate-slug' => 'aanv-6-bezoekers_betaald'),
				    'niet_betaald' => array ('gform-field-id' => 292, 'gform-populate-slug' => 'aanv-6-bezoekers_nietbetaald')
				    )
			)

		);


		// $this->_aanv_7 = array(
		// 	"_aanv_7_activiteiten" => array(
		// 		"table" => "cab_activiteiten",
		// 		"id" => array ('gform-field-id' => 213, 'gform-populate-slug' => 'aanv-7-activiteiten_row-id'),
		// 		"aanv_vragenlijst_id" => 7,
		// 		"data" => array(
		// 	    	'totaal' => array ('gform-field-id' => 214, 'gform-populate-slug' => 'aanv-7-activiteiten_aantal')
		// 	    )
		// 	),

		// 	"_aanv_7_nevenactiviteiten" => array(
		// 		"table" => "cab_nevenactiviteiten",
		// 		"id" => array ('gform-field-id' => 216, 'gform-populate-slug' => 'aanv-7-nevenactiviteiten_row-id'),
		// 		"aanv_vragenlijst_id" => 7,
		// 		"data" => array(
		// 		    'totaal' => array ('gform-field-id' => 217, 'gform-populate-slug' => 'aanv-7-nevenactiviteiten_totaal'),
		// 		    'educatief' => array ('gform-field-id' => 218, 'gform-populate-slug' => 'aanv-7-nevenactiviteiten_educatief'),
		// 		    'overig' => array ('gform-field-id' => 219, 'gform-populate-slug' => 'aanv-7-nevenactiviteiten_overig'),
		// 		    'overig_toelichting' => array ('gform-field-id' => 220, 'gform-populate-slug' => 'aanv-7-nevenactiviteiten_overig-toelichting')
		// 		)
		// 	),

		// 	"_aanv_7_bezoekers" => array(
		// 		"table" => "cab_bezoekers",
		// 		"id" => array ('gform-field-id' => 222, 'gform-populate-slug' => 'aanv-7-bezoekers_row-id'),
		// 		"aanv_vragenlijst_id" => 7,
		// 		"data" => array(
		// 		    'totaal' => array ('gform-field-id' => 223, 'gform-populate-slug' => 'aanv-7-bezoekers_totaal'),
		// 		    'standplaats' => array ('gform-field-id' => 224, 'gform-populate-slug' => 'aanv-7-bezoekers_standplaats'),
		// 		    'provincie' => array ('gform-field-id' => 225, 'gform-populate-slug' => 'aanv-7-bezoekers_provincie'),
		// 		    'nederland' => array ('gform-field-id' => 226, 'gform-populate-slug' => 'aanv-7-bezoekers_nederland'),
		// 		    'buitenland' => array ('gform-field-id' => 227, 'gform-populate-slug' => 'aanv-7-bezoekers_buitenland')
			
		// 		    )
		// 	)

		// );


		// mapping which combines all fields
		$this->sectionsMap = array(
			'subsidy' => $this->_subsidy_array,
			'eigen_inkomsten' => $this->eigen_inkomsten_array,
			'organisatie' => $this->_organisatie_array,
			'omzet' => $this->_omzet_array,
			'scholing' => $this->_scholing_array,
			'marketing' => $this->_marketing_array,
			'media' => $this->_media_array,
			'aanv_1' => $this->_aanv_1,
			'aanv_2' => $this->_aanv_2,
			'aanv_3' => $this->_aanv_3,
			'aanv_4' => $this->_aanv_4,
			'aanv_5' => $this->_aanv_5,
			'aanv_6' => $this->_aanv_6
			//'aanv_7' => $this->_aanv_7

		);


		


        // Gravity forms actions
        add_action('gform_after_submission', array($this, 'parse_form_db'), 1);



        // Fix jquery error which hides the form
		add_filter("gform_init_scripts_footer", array($this, 'init_scripts'));


		
	}

	function init() {


    	global $current_user;
    	global $post;
    	//print_r($current_user);


		wp_enqueue_script("cab-formview-functions", plugins_url()."/cab-monitor/js/cab-formview-functions.js", array( 'jquery','cab-monitor-main' ));
		wp_enqueue_script("validate", plugins_url()."/cab-monitor/js/jquery.validate.js", array( 'jquery' ));
		wp_enqueue_script("additional-methods", plugins_url()."/cab-monitor/js/additional-methods.js", array( 'jquery','validate' ));

    	if (isset($current_user->data)) {

		// Set some basic settings, mostly temporary
			$this->settings = array(
	    		//'organisation_id' => $this->cab_functions->get_organisation_id_by_user_id($current_user->data->ID),
	    		//'organisation_id' => $post->ID,
	    		//'user_id' => $this->cab_functions->get_user_id_by_organisation_id($post->ID),
	    		'current_user_id' => $current_user->ID,
				'data_period' => $this->cab_functions->get_current_period() // 2011
			);
		// Populate user meta

        }

	}

	function init_scripts() {
		return true;
	}


	// i.e get_field_id_by_db_field( 'subsidy', 'totaal');
	function get_field_id_by_db_field( $section, $field ) {

		if (!isset($this->sectionsMap[$section])) {
			return;
		}

		if (!isset($this->sectionsMap[$section][$field])) {
			return;
		}

		$value = $this->sectionsMap[$section][$field];
		return $value['gform-field-id'];

	}

	function get_field_list_by_section( $section ) {
		if (!isset($this->sectionsMap[$section])) {
			return;
		}

		return $this->sectionsMap[$section];

	}


	function update_settings() {
				global $post;

		    $this->settings['organisation_id'] = $post->ID;
			$this->settings['user_id'] = $this->cab_functions->get_user_id_by_organisation_id($post->ID);
			$this->settings['data_period'] = $this->cab_functions->get_current_period();
	}

	function parse_form_db($entry) {

		    global $wpdb;




		    $this->update_settings();

		    // remove the organisation cache
		    delete_transient( 'organisation_'.$this->settings['organisation_id'] ); 



		    $additionalCategories = get_field('vragenlijst_categorie', $this->settings['organisation_id']);


		    // get the current form activity
		    $form_activity = $this->cab_functions->form_activity_get_data( $this->settings['organisation_id'], $this->settings['data_period'] );

		    if ($form_activity['is_finished']) {
		    	return false;
		    }
		
		    //print_r($entry);


			// User meta
			// Walk through meta array to automate form parsing
		    foreach ($this->_user_meta_array as $key => $value) {
		        update_user_meta( $this->settings['user_id'], $key, $entry[$value['gform-field-id']]);
		    }


		   	$this->parse_kernactiviteiten($entry, $this->_kernactiviteiten_array);

		    $this->parse_subsidie($entry, $this->_subsidy_array);
		    $this->parse_eigen_inkomsten($entry, $this->eigen_inkomsten_array);
		    $this->parse_organisatie($entry, $this->_organisatie_array);
		    $this->parse_omzet($entry, $this->_omzet_array);
		    $this->parse_scholing($entry, $this->_scholing_array);
		    $this->parse_marketing($entry, $this->_marketing_array);
		    $this->parse_media($entry, $this->_media_array);


		    foreach ($additionalCategories as $category) {
		    	$this->parse_aanv($entry, $this->sectionsMap['aanv_'.$category]);
		    }
		 //    $this->parse_aanv($entry, $this->_aanv_1);
		 //   	$this->parse_aanv($entry, $this->_aanv_2);
			// $this->parse_aanv($entry, $this->_aanv_3);
			// $this->parse_aanv($entry, $this->_aanv_4);
			// $this->parse_aanv($entry, $this->_aanv_5);
			// $this->parse_aanv($entry, $this->_aanv_6);
			//$this->parse_aanv($entry, $this->_aanv_7);

	}


	/* PARSE --------------- */

	/* Parse subsidie */
	function parse_subsidie($entry, $structure_array) {
	    $this->parse_auto("cab_subsidy", $structure_array, $entry);
	}

	function parse_eigen_inkomsten($entry, $structure_array) {
	    $this->parse_auto("cab_eigen_inkomsten", $structure_array, $entry);
	}

	function parse_organisatie($entry, $structure_array) {
	    $this->parse_auto("cab_organisatie", $structure_array, $entry);
	}

	function parse_omzet($entry, $structure_array) {
	    $this->parse_auto("cab_omzet", $structure_array, $entry);
	}

	function parse_scholing($entry, $structure_array) {

		global $wpdb;

		//foreach ($structure_array as $key => $value) {

			$table = 'cab_scholing';
			$row_id = $entry[$structure_array['_id']['gform-field-id']];

			$columns['uitgaven'] = $entry[$structure_array['uitgaven']['gform-field-id']];
			$columns['onderwerpen'] = implode(", ", $this->cab_functions->get_gform_checkbox_values(260, $entry));
			$columns['onderwerpen_anders'] = $entry[$structure_array['onderwerpen_anders']['gform-field-id']];
			 

			// If there is already a row in the database update, else add new
		    if ($row_id) {
				// Store in the database
		       $wpdb->update( $wpdb->prefix . $table, $columns, array( 'id' => $row_id ));
		    } else {

		        $this->cab_functions->add_data($table, $this->settings['organisation_id'], $this->settings['data_period'],$columns); 
		    }

			//print_r($checkbox_onderwerpen);
			// Delete the current kernactiviteiten
			// $this->cab_functions->delete_row($value['table-name'], array('period_id' => $this->settings['data_period'], 'organisation_id' => $this->settings['organisation_id']));

			// // Add new scholing
			// foreach ($checkbox_values as $checkbox_key => $checkbox_value) {
			// 	$this->cab_functions->add_data($value['table-name'], $this->settings['organisation_id'], $this->settings['data_period'],array($key => $checkbox_value)); 
			// }
		//}
//
	   // $this->parse_auto("cab_scholing", $structure_array, $entry);
	}

	function parse_marketing($entry, $structure_array) {
	    $this->parse_auto("cab_marketing", $structure_array, $entry);
	}

	function parse_media($entry, $structure_array) {
	    $this->parse_auto("cab_media", $structure_array, $entry);
	}

	function parse_kernactiviteiten($entry, $structure_array) {
		global $wpdb;

		foreach ($structure_array as $key => $value) {

			$checkbox_values = $this->cab_functions->get_gform_checkbox_values($value['gform-field-id'], $entry);
				
			// Delete the current kernactiviteiten
			$this->cab_functions->delete_row($value['table-name'], array('period_id' => $this->settings['data_period'], 'organisation_id' => $this->settings['organisation_id']));

			// Add new kernactiviteiten
			foreach ($checkbox_values as $checkbox_key => $checkbox_value) {
				$this->cab_functions->add_data($value['table-name'], $this->settings['organisation_id'], $this->settings['data_period'],array($key => $checkbox_value)); 
			}
		}
		//die();
	}

	function parse_aanv($entry_array, $structure_array) {
		global $wpdb;

		// Add a filter to replace the 'NULL' string with NULL because wordpres doesn't support null
		add_filter( 'query', 'wp_db_null_value' );			
	

		//print_r($structure_array);
		foreach ($structure_array as $key => $structure) {

			$table = $structure['table'];
			$row_id = $entry_array[$structure['id']['gform-field-id']];
			$aanv_vragenlijst_id = $structure['aanv_vragenlijst_id'];

			$columns = $this->cab_functions->convert_entry_to_db($structure['data'], $entry_array);
		

			// if all the columns and the row_id is undefined, dont save it to the database
			if(!$row_id && !array_filter($columns)) {
				return false;
			} else {

				// Add the aanv_vragenlijst_id to the columns
				$columns = array_merge($columns, array('aanv_vragenlijst_id'=>$aanv_vragenlijst_id));

				// If there is already a row in the database update, else add new
			    if ($row_id) {
					// Store in the database
			       $wpdb->update( $wpdb->prefix . $table, $columns, array( 'id' => $row_id ));
			    } else {

			        $this->cab_functions->add_data($table, $this->settings['organisation_id'], $this->settings['data_period'],$columns); 
			    }
			}
		}

		// Remove the filter again:
		remove_filter( 'query', 'wp_db_null_value' );
	}

	function parse_auto($table, $structure_array, $entry_array) {
	    global $wpdb;

	    // Add a filter to replace the 'NULL' string with NULL because wordpres doesn't support null
	    add_filter( 'query', 'wp_db_null_value' );	


	    $row_id = $this->cab_functions->get_entry_row_id($entry_array, $structure_array);
	    $input_array = $this->cab_functions->system_split_array($structure_array);

	    // Convert entry to db ready columns
	    $columns = $this->cab_functions->convert_entry_to_db($input_array['data'], $entry_array);

		// If there is already a row in the database update, else add new
	    if ($this->cab_functions->entry_has_row_id($entry_array, $structure_array)) {
	    
			// Store in the database
	        $wpdb->update( $wpdb->prefix . $table, $columns, array( 'id' => $row_id ));
	    } else {
	    	
	    	// Check if the fields are set, if not, don't add to the database
	    	if (!array_filter($columns)) {

	    	} else {
	       	 	$this->cab_functions->add_data($table, $this->settings['organisation_id'], $this->settings['data_period'],$columns); 
	    	}
	    }

	    // Remove the filter again:
	    remove_filter( 'query', 'wp_db_null_value' );

	}








	// MOVED TO CAB FUNCTIONS

	// function add_data($table, $organisation_id, $period, $columns) {

	//     global $wpdb;

	//     $columns = array_merge($columns, array("organisation_id"=>$organisation_id,"period_id"=>$period));


	//     $wpdb->insert( 
	//         $wpdb->prefix.$table, 
	//         $columns
	//         );

	// }


// function add_omzet($organisation_id, $period, $columns) {
//     global $wpdb;

//     $columns = array_merge($columns, array("organisation_id"=>$organisation_id,"period_id"=>$period));


//     $wpdb->insert( 
//         $wpdb->prefix.'cab_omzet', 
//         $columns
//         );
// }








	/* POPULATE ------------ */

	/* Populate User Meta */
	function populate_user_meta() {

		$this->update_settings();

		// Get the meta from the system
	    $user_meta = get_user_meta( $this->settings['user_id'] );

		// Pre populate the user meta by walking through the $this->_user_meta_array
	    foreach ($this->_user_meta_array as $key => $value) {
	    	if (isset($user_meta[$key][0])) {
	        add_filter(
	            'gform_field_value_'.$value['gform-populate-slug'], 
	            create_function("", 'return "'.$user_meta[$key][0].'";' )
	            );  
	        }        
	    }


	    // If director function is filled, show it
	    if (isset($user_meta['user-cab_organisatie-directeur-functie'])) {

	    	if ($user_meta['user-cab_organisatie-directeur-functie'][0] != '') {
			add_filter(
	            'gform_field_value_directeur_functie_anders', 
	            create_function("", 'return 1;' )
	            );  

			}
		
	    }  


	    // If bezoek adres is filled, show it
	    if (isset($user_meta['user-cab_bezoekadres-straat'])) {

	    	if ($user_meta['user-cab_bezoekadres-straat'][0] != '') {
			add_filter(
	            'gform_field_value_bezoekadres_anders', 
	            create_function("", 'return 1;' )
	            );  

			}
		
	    }        
	    


	}


	function search_array($array, $needle) {
		foreach ($array as $key => $value) {
			if ($value['id'] == $needle) {
				return $array[$key];
				break;
			}
		}
	}

	function populate_autosave($autosave) {


		$form_data = $this->cab_functions->get_gform_form_data(1);

		

		$form_data_fields = $form_data['fields'];
		//print_r($form_data_fields);
		// foreach ($form_data[''] as $key => $value) {
		// 	# code...
		// }

		// //print_r($this->cab_functions->get_gform_form_data(1));
		$prev_id = 0;
		$field_group_array = array();
		foreach ($autosave as $key => $field) {
		// 	# code...	

		 	if (array_key_exists('name',$field)) {
		 		if (substr($field['name'], 0, 6) == "input_") {

		 			$raw_field_number = substr($field['name'], 6);

		 			$dot_position = strpos($raw_field_number, ".");


		 			// If there is a dot this could be a checkbox or radio group
		 			if ($dot_position > 0) {
		 				$field_id = substr($raw_field_number, 0, $dot_position);

		 				if ($field['type'] == 'checkbox') {
		 					if ($field['checked']) {
		 						$field_group_array[$field_id][] = $field['value'];
		 					}

		 				}

		 			} else {
		 				$field_id = $raw_field_number;
		 			}


		 			// Set gform populate
		 			if ($field_id != $prev_id && !$dot_position) {

		 				$gform_field = $this->search_array($form_data_fields, $field_id);
		 				
		 				add_filter(
		 					'gform_field_value_'.$gform_field['inputName'],
		 					create_function("", 'return "'.$field['value'].'";' )
		 				);  
		 				//echo $field_id." : ".$gform_field['inputName']." : ".$field['value']."<br/>";

		 			}


		 			$prev_id = $field_id;
		 	

		 		}
		 	}

		}

		// Walk through the checkbox fields
		foreach ($field_group_array as $key => $fields) {
			$gform_field = $this->search_array($form_data_fields, $key);
 
					add_filter(
		           'gform_field_value_'.$gform_field['inputName'],
		            create_function("", 'return "'.implode(",", $fields).'";' )
		            ); 

			 
		}
		

	}

	/* Populate Eigen inkomsten */
	function populate_subsidie() {

	    $this->populate_auto($this->_subsidy_array,$this->cab_functions->get_table_data("cab_subsidy", $this->settings['organisation_id'], $this->settings['data_period']), $this->settings);

	}

	/* Populate Eigen inkomsten */
	function populate_eigen_inkomsten() {

	    $this->populate_auto($this->eigen_inkomsten_array,$this->cab_functions->get_table_data("cab_eigen_inkomsten", $this->settings['organisation_id'], $this->settings['data_period']), $this->settings);

	}

	/* Populate Eigen inkomsten */
	function populate_organisatie() {

	    $this->populate_auto($this->_organisatie_array,$this->cab_functions->get_table_data("cab_organisatie", $this->settings['organisation_id'], $this->settings['data_period']), $this->settings);

	}

	/* Populate Omzet */
	function populate_omzet() {

	    $this->populate_auto($this->_omzet_array,$this->cab_functions->get_table_data("cab_omzet", $this->settings['organisation_id'], $this->settings['data_period']), $this->settings);

	}

	/* Populate Omzet */
	function populate_scholing() {

	    $this->populate_auto($this->_scholing_array,$this->cab_functions->get_table_data("cab_scholing", $this->settings['organisation_id'], $this->settings['data_period']), $this->settings);

	}

	function populate_marketing() {

	    $this->populate_auto($this->_marketing_array,$this->cab_functions->get_table_data("cab_marketing", $this->settings['organisation_id'], $this->settings['data_period']), $this->settings);

	}


	function populate_media() {

	    $this->populate_auto($this->_media_array,$this->cab_functions->get_table_data("cab_media", $this->settings['organisation_id'], $this->settings['data_period']), $this->settings);
	}


	function populate_aanv($structure_array) {
		foreach ($structure_array as $key => $structure) {

			// Prepare the array for use in the foreach
	    	$table = $structure['table'];

	    	$data_array = $this->cab_functions->get_table_data($table , $this->settings['organisation_id'], $this->settings['data_period'], $structure['aanv_vragenlijst_id']);

			// Pre populate the fields
	    	foreach ($structure['data'] as $key => $value) {
	        	add_filter(
	            	'gform_field_value_'.$value['gform-populate-slug'],
	            	create_function("", 'return "'.$data_array[$key].'";' )
	            );   

	        	// Add id to the hidden field
	    		add_filter(
	    			'gform_field_value_'.$structure['id']['gform-populate-slug'], 
	    			create_function("", 'return "'.$data_array['id'].'";' )
	    		);      
	    	}

	    }




	}


	function populate_kernactiviteiten() {
		foreach ($this->_kernactiviteiten_array as $key => $value) {

			$kernactiviteiten_data = $this->cab_functions->get_habtm_table_data($value['table-name'],$key,$this->settings['organisation_id'], $this->settings['data_period']);

			if ($kernactiviteiten_data['values']) {
			
				// Set the filter to prepopulate the values
				add_filter(
		            'gform_field_value_'.$value['gform-populate-slug'],
		            create_function("", 'return "'.implode(",", $kernactiviteiten_data['values']).'";' )
		            ); 
			}
		}

	}


	/* Global function which populates all the normal fields defined in the structure array */
	function populate_auto($structure_array, $data_array, $settings) {
	// Prepare the array for use in the foreach
	    $input_array = $this->cab_functions->system_split_array($structure_array);
	

	// Pre populate the fields
	    foreach ($input_array['data'] as $key => $value) {

	    	$fieldValue = $data_array[$key];

	    	// if a format is set
	    	if (isset($structure_array[$key]['format'])) {

	    		// if the format is set as decimal, try to convert from dutch to normalized
	    		if ($structure_array[$key]['format'] == "decimal" ) {
	    			$fieldValue = number_format( $fieldValue, 2 , "," , "."  );
	    		}
	    		
	    	} 

	        add_filter(
	            'gform_field_value_'.$value['gform-populate-slug'],
	            create_function("", 'return "'.$fieldValue.'";' )
	            );          
	    }

	// Add id to the hidden field
	    add_filter('gform_field_value_'.$input_array['system']['_id']['gform-populate-slug'], create_function("", 'return "'.$data_array['id'].'";' )); 


	}

	function populate_formulier_categorieen($form) {
		$categorieen = get_field( "vragenlijst_categorie", $this->settings['organisation_id'] );

		if ($categorieen) {
			//return 7;
	    	return implode(",", $categorieen);
		} else {
			return false;
		}
	}

	function populate_formulier_periode($form) {
		return $this->cab_functions->get_current_period();
	}
	function populate_formulier_organisatie_id($form) {
		//global $post;
		return $this->settings['organisation_id'];
	}


	function replace_period_tags( $string, $period_id ) {

		$result_str = $string;

		// Find the { } parts
		preg_match_all("/(\{[$period_id][^\}]*\})/", $string, $matches, PREG_SET_ORDER);

		foreach ($matches as $match_key => $match_value) {

			// Dissect the {2:text}
			preg_match_all("/\{(.*):(.*)\}/s", $match_value[0], $sub_matches, PREG_SET_ORDER);
			$result_str = $sub_matches[0][2]; 

		}

		return $result_str;
	}

	function replace_string( $string, $key, $value ) {
		return str_replace( $key, $value, $string);
	}

	function gform_replace_tags($form) {

		$period_id = $this->cab_functions->get_current_period();
		$periods = $this->cab_functions->get_all_periods();


		foreach ($form['fields'] as $key => $form_section) {


			//print_r($form_section);
			$form['fields'][$key]['label'] = $this->replace_period_tags( $form_section['label'], $period_id );
			$form['fields'][$key]['label'] = $this->replace_string( $form['fields'][$key]['label'], '{period}', $periods[$period_id]);

			$form['fields'][$key]['content'] = $this->replace_period_tags( $form_section['content'], $period_id );

			

			// find fields that have options
			if ($form_section['choices']) {

				$choices = $form_section['choices'];
				foreach ($choices as $choice_key => $choice) {
					$choices[$choice_key]['text'] = $this->replace_string( $choice['text'], '{period}', $periods[$period_id]);
				}
				$form['fields'][$key]['choices'] = $choices;
			}


			// Find fields that have a description
			if ($form_section['description'] != '') {

				$replacedDescription = $form_section['description'];
				$replacedDescription = $this->replace_period_tags( $replacedDescription, $period_id );
				$replacedDescription = $this->replace_string( $replacedDescription, '{period}', $periods[$period_id]);


				$form['fields'][$key]['description'] = $replacedDescription;
				//$form['fields']
				// // Find the { } parts
				// $raw_section_desc = $form_section['description'];
				// preg_match_all("/(\{[$period_id][^\}]*\})/", $raw_section_desc, $matches, PREG_SET_ORDER);

				// foreach ($matches as $match_key => $match_value) {

				// 	// Dissect the {2:text}
				// 	preg_match_all("/\{(.*):(.*)\}/s", $match_value[0], $sub_matches, PREG_SET_ORDER);
				// 	$result_str = $sub_matches[0][2];
				// 	$form['fields'][$key]['description'] = $result_str; 

				// }

			}

			if ($form_section['content'] != '') {
				$replacedContent = $form_section['content'];

				$replacedContent = $this->replace_period_tags( $replacedContent, $period_id );
				$replacedContent = $this->replace_string( $replacedContent, '{period}', $periods[$period_id]);
				$form['fields'][$key]['content'] = $replacedContent;

			}
		}

		// {nr} - label

		/*
		   [201] => Array
                (
                    [adminLabel] => vragen-opmerkingen
                    [adminOnly] => 
                    [allowsPrepopulate] => 1
                    [defaultValue] => 
                    [description] => 
                    [content] => 
                    [cssClass] => 
                    [errorMessage] => 
                    [id] => 187
                    [inputName] => vragen-opmerkingen
                    [isRequired] => 
                    [label] => Vragen / Opmerkingen
                    [noDuplicates] => 
                    [size] => medium
                    [type] => textarea
                    [postCustomFieldName] => 
                    [displayAllCategories] => 
                    [displayCaption] => 
                    [displayDescription] => 
                    [displayTitle] => 
                    [inputType] => 
                    [rangeMin] => 
                    [rangeMax] => 
                    [calendarIconType] => 
                    [calendarIconUrl] => 
                    [dateType] => 
                    [dateFormat] => 
                    [phoneFormat] => 
                    [addressType] => 
                    [defaultCountry] => 
                    [defaultProvince] => 
                    [defaultState] => 
                    [hideAddress2] => 
                    [hideCountry] => 
                    [hideState] => 
                    [inputs] => 
                    [nameFormat] => 
                    [allowedExtensions] => 
                    [captchaType] => 
                    [pageNumber] => 1
                    [captchaTheme] => 
                    [simpleCaptchaSize] => 
                    [simpleCaptchaFontColor] => 
                    [simpleCaptchaBackgroundColor] => 
                    [failed_validation] => 
                    [productField] => 
                    [enablePasswordInput] => 
                    [maxLength] => 
                    [enablePrice] => 
                    [basePrice] => 
                    [calculationFormula] => 
                    [calculationRounding] => 
                    [enableCalculation] => 
                    [disableQuantity] => 
                    [inputMask] => 
                    [inputMaskValue] => 
                    [formId] => 1
                    [descriptionPlacement] => below
                )
                */

		return $form;
	}


	function gform_start_entry($form) {


		$organisation_id = $this->cab_functions->get_current_organisationd_id();
		$period_id = $this->cab_functions->get_current_period();
		// check if there is an autosave

		$auto_save = $this->cab_functions->form_activity_get_auto_save($organisation_id, $period_id);

		if ($auto_save) {

			$this->populate_autosave($auto_save);
		} else {

				// Pre populate the hidden categories
	        add_filter("gform_field_value_formulier_categorieen", array($this, 'populate_formulier_categorieen'));
			add_filter("gform_field_value_formulier_periode", array($this, 'populate_formulier_periode'));
			add_filter("gform_field_value_formulier_organisatie_id", array($this, 'populate_formulier_organisatie_id'));

			$this->populate_user_meta();
	        $this->populate_kernactiviteiten();
	        $this->populate_subsidie();
	        $this->populate_eigen_inkomsten();
	        $this->populate_organisatie();
	        $this->populate_omzet();
	        $this->populate_scholing();
	        $this->populate_marketing();
	        $this->populate_media();

	        $this->populate_aanv($this->_aanv_1);
	        $this->populate_aanv($this->_aanv_2);
	        $this->populate_aanv($this->_aanv_3);
			$this->populate_aanv($this->_aanv_4);
			$this->populate_aanv($this->_aanv_5);
			$this->populate_aanv($this->_aanv_6);
			//$this->populate_aanv($this->_aanv_7);
		}

		// Form activity update entry, logs information about the completion
		$this->cab_functions->form_activity_update_entry($organisation_id,$period_id);


		return $form;
	}

	function gform_entry_submitted($entry, $form) {
		// Tell the activity function that the form is finished
		$this->cab_functions->form_activity_finished($this->cab_functions->get_current_organisationd_id(),$this->cab_functions->get_current_period(), $entry);
	}


	function gform_custom_validation( $result, $value, $form, $field ) {

		return array( 'is_valid' => true, 'message' => '' );
		//print_r($field);
		//print_r($result);
	    // if ( $result['is_valid'] && intval( $value ) > 10 ) {
	    //     $result['is_valid'] = false;
	    //     $result['message'] = 'Please enter a value less than 10';
	    // }
	    //return $result;
	}

}


$cab_form = new cab_form();

?>