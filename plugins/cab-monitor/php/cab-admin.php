<?php

class cab_admin
{

	var $settings;

	function __construct()
	{

	global $cab_functions;

		$this->cab_functions = $cab_functions;



		add_action( 'add_meta_boxes', array($this, 'cd_meta_box_add'),1 );

		add_action('admin_head', array($this, 'init'), 1);

        add_action( 'save_post', array($this, 'cd_meta_box_save'),1 );
		add_action( 'admin_init', array($this, 'cab_admin_init'),1 );


		add_filter('manage_cab_organisation_posts_columns', array($this, 'cab_organisation_columns_head'), 10);
		add_action('manage_cab_organisation_posts_custom_column', array($this, 'cab_organisation_columns_content'), 10, 2);
		add_filter("manage_edit-cab_organisation_sortable_columns", array($this, 'cab_organisation_register_sortable'), 10 );
		add_action( 'pre_get_posts', array($this, 'vragenlijst_column_orderby') );


		add_action( 'admin_notices', array($this, 'my_admin_notices') );


		// $this->_user_meta_array = array(
		//     'user-cab_organisatie-naam' => array ('label' => 'organisatie naam', 'type' => 'user_metagform-field-id' => 1, 'gform-populate-slug' => 'naam_organisatie'),
		//     'user-cab_organisatie-directeur-naam' => array ('gform-field-id' => 3, 'gform-populate-slug' => 'naam_directeur'),
		//     'user-cab_organisatie-directeur-email' => array ('gform-field-id' => 183, 'gform-populate-slug' => 'email_directeur'),
		//     'user-cab_organisatie-directeur-functie' => array ('gform-field-id' => 210, 'gform-populate-slug' => 'functie_directeur'),

		//     'user-cab_organisatie-administratief-naam' => array ('gform-field-id' => 184, 'gform-populate-slug' => 'naam_administratief-contactpersoon'),
		//     'user-cab_organisatie-administratief-email' => array ('gform-field-id' => 185, 'gform-populate-slug' => 'email_administratief-contactpersoon'),
		//     'user-cab_organisatie-telefoon' => array ('gform-field-id' => 28, 'gform-populate-slug' => 'naw_telefoonnummer'),
		//     'user-cab_organisatie-website' => array ('gform-field-id' => 29, 'gform-populate-slug' => 'naw_website'),
		//     'user-cab_organisatie-email' => array ('gform-field-id' => 30, 'gform-populate-slug' => 'naw_email_algemeen'),
		//     'user-cab_organisatie-facebook' => array ('gform-field-id' => 31, 'gform-populate-slug' => 'naw_facebook_gebruikersnaam'),
		//     'user-cab_organisatie-twitter' => array ('gform-field-id' => 32, 'gform-populate-slug' => 'naw_twitter_gebruikersnaam'),

		//     'user-cab_postadres-straat' => array ('gform-field-id' => 4, 'gform-populate-slug' => 'postadres_straat'),
		//     'user-cab_postadres-huisnummer' => array ('gform-field-id' => 5, 'gform-populate-slug' => 'postadres_huisnummer'),
		//     'user-cab_postadres-postcode' => array ('gform-field-id' => 6, 'gform-populate-slug' => 'postadres_postcode'),
		//     'user-cab_postadres-plaats' => array ('gform-field-id' => 7, 'gform-populate-slug' => 'postadres_plaats'),
		//     'user-cab_bezoekadres-straat' => array ('gform-field-id' => 23, 'gform-populate-slug' => 'bezoekadres_straat'),
		//     'user-cab_bezoekadres-huisnummer' => array ('gform-field-id' => 24, 'gform-populate-slug' => 'bezoekadres_huisnummer'),
		//     'user-cab_bezoekadres-postcode' => array ('gform-field-id' => 25, 'gform-populate-slug' => 'bezoekadres_postcode'),
		//     'user-cab_bezoekadres-plaats' => array ('gform-field-id' => 26, 'gform-populate-slug' => 'bezoekadres_plaats')
		// );



		$this->organisation_user_data = array(
			"structure" => array(
				"type" => "metadata"
			),
			"fieldgroups" => array(
				"group" => array(
					"fields" => array(
						"user-cab_organisatie-naam" => array("label"=>"Organisatie naam", "type"=>"text"),
						"user-cab_organisatie-directeur-naam" => array("label"=>"Directeur naam", "type"=>"text"),
						"user-cab_organisatie-directeur-email" => array("label"=>"Directeur email", "type"=>"text"),
						"user-cab_organisatie-administratief-naam" => array("label"=>"Administratief naam", "type"=>"text"),
						"user-cab_organisatie-directeur-functie" => array("label"=>"Administratief functie", "type"=>"text"),
						"user-cab_organisatie-administratief-email" => array("label"=>"Administratief email", "type"=>"text"),
						"user-cab_organisatie-telefoon" => array("label"=>"Telefoon", "type"=>"text"),
						"user-cab_organisatie-website" => array("label"=>"Website", "type"=>"text"),
						"user-cab_organisatie-email" => array("label"=>"Email", "type"=>"text"),
						"user-cab_organisatie-facebook" => array("label"=>"Facebook", "type"=>"text"),
						"user-cab_organisatie-twitter" => array("label"=>"Twitter", "type"=>"text"),
						"user-cab_postadres-straat" => array("label"=>"Postadres Straat", "type"=>"text"),
						"user-cab_postadres-huisnummer" => array("label"=>"Postadres Huisnummer", "type"=>"text"),
						"user-cab_postadres-postcode" => array("label"=>"Postadres Postcode", "type"=>"text"),
						"user-cab_postadres-plaats" => array("label"=>"Postadres Plaats", "type"=>"text"),
						"user-cab_bezoekadres-straat" => array("label"=>"Bezoekadres Straat", "type"=>"text"),
						"user-cab_bezoekadres-huisnummer" => array("label"=>"Bezoekadres Huisnummer", "type"=>"text"),
						"user-cab_bezoekadres-postcode" => array("label"=>"Bezoekadres Postcode", "type"=>"text"),
						"user-cab_bezoekadres-plaats" => array("label"=>"Bezoekadres Plaats", "type"=>"text")
					)
				)
			)
		);

/*		array("cab_activiteiten",true),
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
			array("cab_vertoningen",true)*/

		$this->all_data = array(
			array(
				"label" => "Kernactiviteiten",
				"slug" => 'kernactiviteiten',
				"data" => array(
					"structure" => array(
						"type" => "function",
						"function" => "tab_content_kernactiviteiten"
					)
				)
			),
			array(
				"label" => "Subsidie",
				"slug" => 'subsidy',
				"data" => array(
					"structure" => array(
						"type" => "database",
						"database" => "cab_subsidy"
					),
					"columns" => array(
						array(
							"fieldgroups" => array(
								"group" => array(
									"label" => "Subsidie Eenmalig",
									"fields" => array(
										"id" => array("label"=>"id","type"=>"hidden"),
										// "totaal" => array("label"=>"Totaal", "type"=>"text"),
										"gemeente" => array("label"=>"Gemeente", "type"=>"text"),
										"prov_nb" => array("label"=>"Provincie Noord Brabant", "type"=>"text"),
										"rijk" => array("label"=>"Rijk", "type"=>"text"),
										"fonds_podiumkunsten" => array("label"=>"Fonds podiumkunsten", "type"=>"text"),
										// "mondriaan_stichting" => array("label"=>"Mondriaan stiching", "type"=>"text"),
										// "fonds_bkvb" => array("label"=>"Fonds bkvb", "type"=>"text"),
										"mondriaan_fonds" => array("label"=>"Mondriaan fonds", "type"=>"text"),
										"fonds_cultuurparticipatie" => array("label"=>"Fonds Cultuurparticipatie", "type"=>"text"),
										"mediafonds" => array("label"=>"Media fonds", "type"=>"text"),
										"nl_filmfonds" => array("label"=>"Nederlands filmfonds", "type"=>"text"),
										"fonds_creatieve_industrie" => array("label"=>"Fonds creatieve industrie", "type"=>"text"),
										"letterenfonds" => array("label"=>"letteren fonds", "type"=>"text"),
										"overig" => array("label"=>"Overig", "type"=>"text"),
										"overig_toelichting" => array("label"=>"Overig Toelichting", "type"=>"text"),
                    "totaal" => array("label"=>"Totaal", "type"=>"text")


									)
								)
							)
						),
						array(
							"fieldgroups" => array(
								"group" => array(
									"label" => "Subsidie Meerjarig",
									"fields" => array(
										"gemeente_meerjarig" => array("label"=>"Gemeente", "type"=>"text"),
										"prov_nb_meerjarig" => array("label"=>"Provincie Noord Brabant", "type"=>"text"),
										"rijk_meerjarig" => array("label"=>"Rijk", "type"=>"text"),
										"fonds_podiumkunsten_meerjarig" => array("label"=>"Fonds podiumkunsten", "type"=>"text"),
										// "mondriaan_stichting_meerjarig" => array("label"=>"Mondriaan stiching", "type"=>"text"),
										// "fonds_bkvb_meerjarig" => array("label"=>"Fonds bkvb", "type"=>"text"),
										"mondriaan_fonds_meerjarig" => array("label"=>"Mondriaan fonds", "type"=>"text"),
										"fonds_cultuurparticipatie_meerjarig" => array("label"=>"Fonds Cultuurparticipatie", "type"=>"text"),
										"mediafonds_meerjarig" => array("label"=>"Media fonds", "type"=>"text"),
										"nl_filmfonds_meerjarig" => array("label"=>"Nederlands filmfonds", "type"=>"text"),
										"fonds_creatieve_industrie_meerjarig" => array("label"=>"Fonds creatieve industrie", "type"=>"text"),
										"letterenfonds_meerjarig" => array("label"=>"letteren fonds", "type"=>"text"),
										"overig_meerjarig" => array("label"=>"Overig", "type"=>"text")

									)
								)
							)
						)
					)
				)
			),

			array(
				"label" => "Eigen inkomsten",
				"slug" => 'eigen_inkomsten',
				"data" => array(
					"structure" => array(
						"type" => "database",
						"database" => "cab_eigen_inkomsten"
					),
					"columns" => array(
						array(
							"fieldgroups" => array(

								"group" => array(
									"label" => "Eigen inkomsten",
									"slug" => "eigen_inkomsten",
									"fields" => array(
										"id" => array("label"=>"id","type"=>"hidden"),
										"publieksinkomsten" => array("label"=>"Publieksinkomsten", "type"=>"text"),
										"sponsoring" => array("label"=>"Sponsorinkomsten", "type"=>"text"),
										"private_fondsen" => array("label"=>"Private fondsen", "type"=>"text"),
										"overig" => array("label"=>"Overige inkomsten", "type"=>"text"),
										"totaal" => array("label"=>"Totaal", "type"=>"text")
									)
								)
							)
						)
					)
				)
			),
			array(
				"label" => "Organisatie",
				"slug" => 'organisatie',
				"data" => array(
					"structure" => array(
						"type" => "database",
						"database" => "cab_organisatie"
					),
					"columns" => array(
						array(
							"fieldgroups" => array(

								"group" => array(
									"label" => "Organisatie",
									"slug" => "organisatie",
									"fields" => array(
										"id" => array("label"=>"id","type"=>"hidden"),
										"werknemers_fte" => array("label"=>"Fte vast contract", "type"=>"text"),
										"freelancers_fte" => array("label"=>"Fte tijdelijk contract", "type"=>"text"),
										"vrijwilligers" => array("label"=>"Vrijwilligers personen", "type"=>"text"),
										"vrijwilligers_fte" => array("label"=>"Vrijwilligers fte", "type"=>"text"),
                    "totaal_fte" => array("label"=>"Totaal fte", "type"=>"text")
									)
								)
							)
						),
						array(
							"fieldgroups" => array(

								"group" => array(
									"label" => "Personeelslasten",
									"slug" => "organisatie",
									"fields" => array(
										"lasten_vastcontract" => array("label"=>"Vast contract", "type"=>"text"),
										"lasten_tijdelijk" => array("label"=>"Tijdelijk personeel", "type"=>"text"),
										"lasten_inhuur" => array("label"=>"Inhuur", "type"=>"text"),
                    "totaal" => array("label"=>"Totaal", "type"=>"text")
									)
								)
							)
						)
					)
				)
			),
			// array(
			// 	"label" => "Organisatie",
			// 	"slug" => 'organisatie',
			// 	"data" => array(
			// 		"structure" => array(
			// 			"type" => "database",
			// 			"database" => "cab_organisatie"
			// 		),
			// 		"columns" => array(
			// 			array(
			// 				"fieldgroups" => array(

			// 					"group" => array(
			// 						"label" => "Organisatie personen",
			// 						"slug" => "organisatie",
			// 						"fields" => array(
			// 							"id" => array("label"=>"id","type"=>"hidden"),
			// 							"werknemers" => array("label"=>"Werknemers personen", "type"=>"text"),
			// 							"freelancers" => array("label"=>"Freelancers personen", "type"=>"text"),
			// 							"vrijwilligers" => array("label"=>"Vrijwilligers personen", "type"=>"text"),
			// 							"stagiaires" => array("label"=>"Stagiaires personen", "type"=>"text"),

			// 						)
			// 					)
			// 				)
			// 			),
			// 			array(
			// 				"fieldgroups" => array(

			// 					"group" => array(
			// 						"label" => "Organisatie fte",
			// 						"slug" => "organisatie_fte",
			// 						"fields" => array(
			// 							"werknemers_fte" => array("label"=>"Werknemers fte", "type"=>"text"),
			// 							"freelancers_fte" => array("label"=>"Freelancers fte", "type"=>"text"),
			// 							"vrijwilligers_fte" => array("label"=>"Vrijwilligers fte", "type"=>"text"),
			// 							"stagiaires_fte" => array("label"=>"Stagiaires fte", "type"=>"text")

			// 						)
			// 					)
			// 				)
			// 			)
			// 		)
			// 	)
			// ),
			array(
				"label" => "Scholing &amp; professionalisering",
				"slug" => 'scholing',
				"data" => array(
					"structure" => array(
						"type" => "database",
						"database" => "cab_scholing"
					),
					"columns" => array(
						array(
							"fieldgroups" => array(

								"group" => array(
									"label" => "Scholing &amp; professionalisering",
									"slug" => "scholing",
									"fields" => array(
										"id" => array("label"=>"id","type"=>"hidden"),
										"uitgaven" => array("label"=>"Uitgaven", "type"=>"text")
									)
								)
							)
						)
					)
				)
			),
			array(
				"label" => "Marketing &amp; communicatie",
				"slug" => 'marketing',
				"data" => array(
					"structure" => array(
						"type" => "database",
						"database" => "cab_marketing"
					),
					"columns" => array(
						array(
							"fieldgroups" => array(

								"group" => array(
									"label" => "Marketing &amp; communicatie",
									"slug" => "marketing",
									"fields" => array(
										"id" => array("label"=>"id","type"=>"hidden"),
										"uitgaven" => array("label"=>"Uitgaven", "type"=>"text")
									)
								)
							)
						)
					)
				)
			),
			array(
				"label" => "Media",
				"slug" => 'media',
				"data" => array(
					"structure" => array(
						"type" => "database",
						"database" => "cab_media"
					),
					"columns" => array(
						array(
							"fieldgroups" => array(

								"group" => array(
									"label" => "Media",
									"slug" => "media",
									"fields" => array(
										"id" => array("label"=>"id","type"=>"hidden"),
										"aandacht" => array("label"=>"aandacht", "type"=>"radio", "gfield_id"=>72),
									)
								)
							)
						),
						array(
							"fieldgroups" => array(

								"group" => array(
									"label" => "Social Media",
									"slug" => "media",
									"fields" => array(
										"aandacht_twitter" => array("label"=>"aandacht Twitter", "type"=>"text"),
										"aandacht_twitter_toelichting" => array("label"=>"aandacht Twitter toelichting", "type"=>"text"),
										"aandacht_facebook" => array("label"=>"aandacht Facebook", "type"=>"text"),
										"aandacht_facebook_toelichting" => array("label"=>"aandacht Facebook toelichting", "type"=>"text")

									)
								)
							)
						)
					)
				)
			),
			array(
				"label" => "Activiteiten",
				"slug" => 'activiteiten',
				"data" => array(
					"structure" => array(
						"type" => "database",
						"database" => "cab_activiteiten"
					),
					"columns" => array(
						array(
							"fieldgroups" => array(

								"group" => array(
									"label" => "Activiteiten",
									"slug" => "activiteiten",
									"fields" => array(
										"id" => array("label"=>"id","type"=>"hidden"),
										"aanv_vragenlijst_id" => array("label"=>"aan_vragenlijst_id", "type"=>"hidden"),
										"aantal" => array("label"=>"Aantal", "type"=>"text"),
										// "in_opdracht" => array("label"=>"In opdracht", "type"=>"text"),
										// "eigen_werk" => array("label"=>"Eigen werk", "type"=>"text"),
										"premieres" => array("label"=>"Premieres", "type"=>"text"),
										"reprises" => array("label"=>"Reprises", "type"=>"text")

									)
								)
							)
						)
					)
				)
			),
			array(
				"label" => "Nevenactiviteiten",
				"slug" => 'nevenactiviteiten',
				"data" => array(
					"structure" => array(
						"type" => "database",
						"database" => "cab_nevenactiviteiten"
					),
					"columns" => array(
						array(
							"fieldgroups" => array(

								"group" => array(
									"label" => "Nevenactiviteiten",
									"slug" => "nevenactiviteiten",
									"fields" => array(
										"id" => array("label"=>"id","type"=>"hidden"),
										"aanv_vragenlijst_id" => array("label"=>"aan_vragenlijst_id", "type"=>"hidden"),
										"totaal" => array("label"=>"Totaal", "type"=>"text"),
										"educatief" => array("label"=>"Educatief", "type"=>"text"),
										"overig" => array("label"=>"Overig", "type"=>"text"),
										"overig_toelichting" => array("label"=>"Overig toelichting", "type"=>"text")
									)
								)
							)
						)
					)
				)
			),
			array(
				"label" => "Bezoeken",
				"slug" => 'bezoekers',
				"data" => array(
					"structure" => array(
						"type" => "database",
						"database" => "cab_bezoekers"
					),
					"columns" => array(
						array(
							"fieldgroups" => array(
								"group" => array(
									"label" => "Bezoeken",
									"fields" => array(
										"id" => array("label"=>"id","type"=>"hidden"),
										"aanv_vragenlijst_id" => array("label"=>"aan_vragenlijst_id", "type"=>"hidden"),
										"totaal" => array("label"=>"Totaal", "type"=>"text"),
										"standplaats" => array("label"=>"Standplaats", "type"=>"text"),
										"provincie" => array("label"=>"Provincie", "type"=>"text"),
										"nederland" => array("label"=>"Nederland", "type"=>"text"),
										"buitenland" => array("label"=>"Buitenland", "type"=>"text"),
										"podium" => array("label"=>"Podium", "type"=>"text"),
										"festivals" => array("label"=>"Festivals", "type"=>"text"),
										"scholen" => array("label"=>"Scholen", "type"=>"text"),
										"overig" => array("label"=>"Overig", "type"=>"text"),
										"betaald" => array("label"=>"Betaald", "type"=>"text"),
										"niet_betaald" => array("label"=>"Niet betaald", "type"=>"text")
									)
								)
							)
						)
					)
				)
			),
			// array(
			// 	"label" => "omzet",
			// 	"slug" => 'omzet',
			// 	"data" => array(
			// 		"structure" => array(
			// 			"type" => "database",
			// 			"database" => "cab_omzet"
			// 		),
			// 		"columns" => array(
			// 			array(
			// 				"fieldgroups" => array(

			// 					"group" => array(
			// 						"label" => "omzet",
			// 						"slug" => "omzet",
			// 						"fields" => array(
			// 							"id" => array("label"=>"id","type"=>"hidden"),
			// 							"totaal" => array("label"=>"Totaal", "type"=>"text")
			// 						)
			// 					)
			// 				)
			// 			)
			// 		)
			// 	)
			// ),
			array(
				"label" => "Spreiding",
				"slug" => 'spreiding',
				"data" => array(
					"structure" => array(
						"type" => "database",
						"database" => "cab_spreiding"
					),
					"columns" => array(
						array(
							"fieldgroups" => array(
								"group" => array(
									"label" => "Spreiding",
									"slug" => "spreiding",
									"fields" => array(
										"id" => array("label"=>"id","type"=>"hidden"),
										"aanv_vragenlijst_id" => array("label"=>"aan_vragenlijst_id", "type"=>"hidden"),
										"standplaats" => array("label"=>"Standplaats", "type"=>"text"),
										"provincie" => array("label"=>"Provincie", "type"=>"text"),
										"nederland" => array("label"=>"Nederland", "type"=>"text"),
										"buitenland" => array("label"=>"Buitenland", "type"=>"text"),
										"podium" => array("label"=>"Podiumcircuit/theater/zaal", "type"=>"text"),
										"festivals" => array("label"=>"Festivals", "type"=>"text"),
										"scholen" => array("label"=>"Scholen", "type"=>"text"),
										"overig" => array("label"=>"Overig", "type"=>"text"),
									)
								)
							)
						)
					)
				)
			),
			array(
				"label" => "Vertoningen",
				"slug" => 'vertoningen',
				"data" => array(
					"structure" => array(
						"type" => "database",
						"database" => "cab_vertoningen"
					),
					"columns" => array(
						array(
							"fieldgroups" => array(
								"group" => array(
									"label" => "vertoningen",
									"fields" => array(
										"id" => array("label"=>"id", "type"=>"hidden"),
										"aanv_vragenlijst_id" => array("label"=>"aan_vragenlijst_id", "type"=>"hidden"),
										"totaal" => array("label"=>"totaal", "type"=>"text"),
										"standplaats" => array("label"=>"standplaats", "type"=>"text"),
										"provincie" => array("label"=>"provincie", "type"=>"text"),
										"nederland" => array("label"=>"nederland", "type"=>"text"),
										"buitenland" => array("label"=>"buitenland", "type"=>"text"),
										"bioscoop" => array("label"=>"bioscoop", "type"=>"text"),
										"filmhuis" => array("label"=>"filmhuis", "type"=>"text"),
										"festival" => array("label"=>"festival", "type"=>"text"),
										"omroep" => array("label"=>"omroep", "type"=>"text"),
										"internet" => array("label"=>"internet", "type"=>"text"),
										"internet_toelichting" => array("label"=>"internet_toelichting", "type"=>"text"),
									)
								)
							)
						)
					)
				)
			)
		);



		// Here some defenition can be set for the values, for instance to format them before being saved to the database
		$this->organisation_data_field_formats = array(
			"cab_organisatie" => array(
				"werknemers_fte" => array("format"=>"decimal"),
				"freelancers_fte" => array("format"=>"decimal"),
				"vrijwilligers_fte" => array("format"=>"decimal")
			)
		);

		//print_r($this->organisation_data);

	}


	/* ADMIN */


// Do some init stuff
function init() {


   	global $post;
   	global $wpdb;

   	if (isset($post)) {
   		$user_id = $this->cab_functions->get_user_id_by_organisation_id($post->ID);

   		$this->user_meta = get_user_meta( $user_id);





   //print_r($cab_functions);
   // $blogusers = get_users( 'role=cab_organisation' );
   // // // Array of WP_User objects.
   // foreach ( $blogusers as $user ) {
   // 		$userId = $user->ID;
   // 		$userMail = $user->user_email;
   // 		$userMeta = get_user_meta( $userId );
   // 		//echo "a";
   // 		//print_r($user);
   // 		//echo $userMeta['user-cab_organisatie-naam'][0]."  ".sanitize_title($userMeta['user-cab_organisatie-naam'][0], $userMail)."<br/>";

   // 		//$wpdb->update($wpdb->users, array('user_login' => sanitize_title($userMeta['user-cab_organisatie-naam'][0], $userMail)), array('ID' => $userId));
   // 		echo $wpdb->update($wpdb->users, array(
   // 				'display_name' => $user->user_login
   // 			),
   // 			array('ID' => $userId)
   // 		);


   // //	print_r($userMeta);
   // 	//echo '<span>' . esc_html( $user->user_email ) . '</span>';
   // }

   // $organisations = $this->cab_functions->get_all_organisations();
   // foreach ($organisations as $key => $organisation) {
   // 	# code...

   // 		if ($this->cab_functions->is_application_allowed($organisation->ID,'vragenlijst')) {
   // 			update_post_meta($organisation->ID, 'organisation_status', 0);
   // 		} else {
   // 			   			update_post_meta($organisation->ID, 'organisation_status', 2);

   // 		}

   // }

   // Get vragenlijst categoriee



	}










}

function my_admin_notices(){
  if(!empty($_SESSION['my_admin_notices'])) print  $_SESSION['my_admin_notices'];
  unset ($_SESSION['my_admin_notices']);
}

function cd_meta_box_add()
{
	global $post;
	// Check if there is a user connected to the organisation.
	// If not give the option to create a user
	 if (!get_field("gekoppelde_gebruiker", $post->ID)) {
	 	add_meta_box( 'cab-metabox_add-user', 'Gebruiker toevoegen', array($this, 'cab_metabox_add_user'), 'cab_organisation', 'side', 'high' );

	 } else {
	 	add_meta_box( 'cab-metabox_user-data', 'Organisatie informatie', array($this, 'cab_metabox_user_data'), 'cab_organisation', 'normal', 'high' );

	 }

	add_meta_box( 'cab-metabox_geo-data', 'Geo locatie', array($this, 'cab_metabox_geo_data'), 'cab_organisation', 'normal', 'high' );


	$periods = $this->cab_functions->get_available_forms($post->ID, false);

	foreach ($periods as $key => $period) {
		add_meta_box( 'cab-metabox_data-'.$period->slug, $period->name, array($this, 'cab_metabox_all_data'), 'cab_organisation', 'normal', 'default', array( 'organisation_id' => $post->ID, 'period_id' => $period->term_id));

		# code...
	}


}



function cab_admin_init() {

	wp_enqueue_style( 'jquery-ui', plugins_url( '/css/jquery-ui.css', dirname(__FILE__)) );
	wp_enqueue_style( 'cab-admin-css', plugins_url( '/css/cab-admin.css', dirname(__FILE__)) );

	wp_enqueue_script( 'cab-admin-main', plugins_url( '/js/admin-main.js', dirname(__FILE__)), array( 'jquery' ) );

	// Localize the script with new data
	$wordpress_vars_array = array(
		'wpurl' => get_bloginfo('wpurl'),
		'url' => get_bloginfo('url')
	);
	wp_localize_script( 'cab-admin-main', 'wordpress_vars', $wordpress_vars_array );
}




function cab_metabox_geo_data() {
	//     echo $klaas;
 	wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );

 	 global $post;

 	// Check if organisation has geo data
 	if (!isset($this->user_meta['user-cab_geo']) || $this->user_meta['user-cab_geo'] != '' ) {

 		$geo_coordinates = $this->cab_functions->get_geo_coordinates_by_id($post->ID);

 		if ($geo_coordinates != false) {
 			$geo_string = $geo_coordinates['lat'].", ".$geo_coordinates['lng'];
 		} else {
 			$geo_string = "";
 		}

 	} else {

 		$geo_string = $this->user_meta['user-cab_geo'][0];

 	}

 		// If not, get address of the organisation


 		// get geo of the address
	 	echo '<div class="metabox_section clearfix">';
	 	echo '<input type="text" name="data[user_data][user-cab_geo]" id="user-cab_geo" value="'.$geo_string.'" />';
    	echo '</div>';
}


function cab_metabox_user_data() {


	//     echo $klaas;
 	wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
	 		echo '<div class="metabox_section clearfix">';
	 foreach ($this->organisation_user_data['fieldgroups']['group']['fields'] as $key => $field) {

	 		$value = false;
	 		if (isset($this->user_meta[$key])) {
	 			$value = $this->user_meta[$key][0];
	 		}
     		echo $this->cab_metabox_create_input($key, 'user_data', $field['label'], false, 'text', $value);
    	}
    	     		echo '</div>';

}


function cab_metabox_add_user() {

	 echo '<div class="metabox_section">';
	 echo '<ul class="cab_fieldgroup">';
	 echo '<li><label for="add_user_name">Gebruikersnaam</label><input type="text" name="add_user_name" id="add_user_name" value="" /></li>';
	 echo '<li><label for="add_user_email">Email</label><input type="text" name="add_user_email" id="add_user_email" value="" /></li>';
	 echo '</ul>';
	 echo '</div>';



}


function cab_metabox_all_data($post, $metabox) {

	//echo '<a class="button">Preview Changes</a>';
	$period_id = $metabox['args']['period_id'];
	$organisation_id = $post->ID;

	// Check form activity
	$form_activity = $this->cab_functions->form_activity_get_data($organisation_id, $period_id);


	$this->organisation_data = $this->cab_functions->get_organisation_data($organisation_id);
	$gform_fields = $this->cab_functions->get_gform_form_data(1);

	// get all vragenlijst Categories
	$vragenlijstCategories = get_field('vragenlijst_categorie', $organisation_id);


	$tab_list_header = "<ul>";
	$tab_list_body = "";
	$tab_list_footer = "</ul>";

	$tab_content = array();

	foreach ($this->all_data as $tab_key => $tab_values) {
		// Check how many vragenlijst variations are there, this depends on the number of vragenlijsten
		// a user has to fill
		   // [periodical] => Array
     //    (
     //        [2] => Array
     //            (
     //                [period] => 2011
     //                [data] => Array
     //                    (
     //                        [cab_activiteiten] => Array
     //


		# code...
		$tab_list_body .= '<li><a href="#'.$tab_values['slug'].'-'.$metabox['args']['period_id'].'">'.$tab_values['label'].'</a></li>';

		$tab_header = '<div id="'.$tab_values['slug'].'-'.$metabox['args']['period_id'].'" class="clearfix" style="display:none;">';
		$tab_body = '';
		$tab_body_columns = array();


		$tab_footer = '</div>';



		// Type = database
		if ($tab_values['data']['structure']['type'] == "database") {


			$table_name = $tab_values['data']['structure']['database'];

			$data_group_id = false; // holds the id of the vragenlijst group
			$nr_of_vragenlijsten = count($this->organisation_data['periodical'][$period_id]['data'][$table_name]);
			//$value = $this->organisation_data['periodical'][$period_id]['data'][$table_name][0][$key];

			// if there is data available
			if ($this->organisation_data['periodical'][$period_id]['data'][$table_name]) {


				// Walk through all vragenlijst variations
				foreach ($this->organisation_data['periodical'][$period_id]['data'][$table_name] as $key => $vragenlijst) {
					# code...
					$vragenlijst_id = false;

					if ($nr_of_vragenlijsten > 1 && isset($vragenlijst['aanv_vragenlijst_id'])) {

						$vragenlijst_id = $vragenlijst['aanv_vragenlijst_id'];
						$data_group_id = $key;
					}


					// Walk through columns
					foreach ($tab_values['data']['columns'] as $key => $tab_column) {

						$i = 0;
						// Walk through fieldgroups
						foreach ($tab_column['fieldgroups'] as $group_key => $group) {
						 	//$tab_body .= $this->cab_metabox_create_group($group,$table_name,$period_id, $vragenlijst_id, $data_group_id);
						 	$tab_body_columns[$key][$i] = $this->cab_metabox_create_group($group,$table_name,$period_id, $vragenlijst_id, $data_group_id);
						 	$i++;
						}


					}

					// Create html
					foreach ($tab_body_columns as $key => $tab_column) {
						$tab_body .= '<div class="tab_column">';

						foreach ($tab_column as $column_fieldgroup) {
							$tab_body .= $column_fieldgroup;
						}

						$tab_body .= '</div>';

					}
					//print_r($tab_body_columns);


				}

			} else {




				// Walk through all vragenlijst variations
				// foreach ($this->organisation_data['periodical'][$period_id]['data'][$table_name] as $key => $vragenlijst) {

				// 	# code...
				// 	$vragenlijst_id = false;

				// 	if ($nr_of_vragenlijsten > 1) {
				// 		$vragenlijst_id = $vragenlijst['aanv_vragenlijst_id'];
				// 		$data_group_id = $key;
				// 	}


					// Walk through columns
					foreach ($tab_values['data']['columns'] as $key => $tab_column) {

						$i = 0;
						// Walk through fieldgroups
						foreach ($tab_column['fieldgroups'] as $group_key => $group) {

							$vragenlijst_id = false;

							// is this is an additional questions list
							if (isset($group['fields']['aanv_vragenlijst_id']) && isset($vragenlijstCategories[0])) {
								$vragenlijst_id = $vragenlijstCategories[0];
							}

							//if
							//print_r($group);
						 	//$tab_body .= $this->cab_metabox_create_group($group,$table_name,$period_id, $vragenlijst_id, $data_group_id);
						 	$tab_body_columns[$key][$i] = $this->cab_metabox_create_group($group,$table_name,$period_id, $vragenlijst_id);
						 	$i++;
						}


					}

					// Create html
					foreach ($tab_body_columns as $key => $tab_column) {
						$tab_body .= '<div class="tab_column">';

						foreach ($tab_column as $column_fieldgroup) {
							$tab_body .= $column_fieldgroup;
						}

						$tab_body .= '</div>';

					}
					//print_r($tab_body_columns);


				// }






			}




		} else if ($tab_values['data']['structure']['type'] == "function") {

			$tab_body .= call_user_func(array($this, $tab_values['data']['structure']['function']), array("data"=>$this->organisation_data['periodical'][$period_id]['data']['kernactiviteiten'],"organisation_id"=>$organisation_id,"period_id"=>$period_id));

		}



		array_push($tab_content, $tab_header.$tab_body.$tab_footer);

		// $tab_body = "";
		// print_r($tab_values['data']['fieldgroups']);

	}

	echo '<div class="metabox_section metabox_section_white"><div id="tabs-'.$metabox['args']['period_id'].'" class="tabs">';
	echo $tab_list_header;
	echo $tab_list_body;
	echo $tab_list_footer;


	foreach ($tab_content as $key => $value) {
		echo $value;
		# code...
	}
	echo '</div></div>';


	echo '<div class="metabox_section clearfix">';

	$status = "";
	if ($form_activity['last_access_date']) {
		$status = "In process";
	} else {
		$status = "Open";
	}

	if ($form_activity['is_finished']) {
		$status = "Finished";
	}

	echo '<div class="column column-25"><h4>Status</h4>'.$status.'</div>';
	echo '<div class="column column-25"><h4>Last accessed</h4>'.$form_activity['last_access_date'].'</div>';
	echo '<div class="column column-25"><h4>Options</h4>';

	if ($form_activity['is_finished']) {
		echo '<a class="button button-period-options" data-organisation_id="'.$organisation_id.'" data-period_id="'.$period_id.'" data-method="unlock_form" >Unlock form</a>';
	} else {
		echo '<a class="button button-period-options" data-organisation_id="'.$organisation_id.'" data-period_id="'.$period_id.'" data-method="lock_form" >Lock form</a>';
	}

	if ($form_activity['auto_save']) {
		echo '<a class="button button-period-options" data-organisation_id="'.$organisation_id.'" data-period_id="'.$period_id.'" data-method="remove_autosave" >Delete autosave</a>';
	}
			echo '</div>';

	echo '</div>';
//$this->all_data
}







	function cab_metabox_create_group($group, $table_name, $period_id, $vragenlijst_id = false, $data_group_id = false) {

		//print_r($this->organisation_data['periodical']);

		// Get vragenlijst title
		$vragenlijst_title = "";
		if ($vragenlijst_id) {
			$vragenlijst_title = " - ".$this->cab_functions->get_vragenlijst_title($vragenlijst_id);
		}

		$return_html = '';
		$return_html .= "<h4>".$group['label'].$vragenlijst_title."</h4>";
	//	$return_html .= "<ul class='cab_fieldgroup cab_group_type_".array_values($group['fields'])[0]['type']."'>";

		foreach ($group['fields'] as $key => $field) {


			if (!$vragenlijst_id && isset($this->organisation_data['periodical'][$period_id]['data'][$table_name][0])) {
			 	$value = $this->organisation_data['periodical'][$period_id]['data'][$table_name][0][$key];
			} else if ($data_group_id) {
				$value = $this->organisation_data['periodical'][$period_id]['data'][$table_name][$data_group_id][$key];
			} else {
				$value = false;
			}

			// format the value if set in $organisation_data_field_formats
			if (isset($this->organisation_data_field_formats[$table_name])) {

				if (isset($this->organisation_data_field_formats[$table_name][$key])) {

					// if format is decimal, format the number
					if ($this->organisation_data_field_formats[$table_name][$key]['format'] == 'decimal') {

						$value = number_format( $value, 2 , "," , "."  );
					}

				}

			}

			$return_html .= $this->cab_metabox_create_input($key, $table_name, $field['label'], $period_id, $field['type'], $value, $vragenlijst_id, $field);
		}

		$return_html .= "</ul>";
		return $return_html;
	}



function cab_organisation_columns_head($columns) {

		$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Organisation' ),
		'status' => __( 'Status'),
		'bloementuin' => __( 'Bloementuin' ),
		'vragenlijst' => __( 'Vragenlijst'),
		'2' => __( '2011' ),
		'3' => __( '2013 begroot' ),
		'4' => __( '2013' ),
		'5' => __( '2014' ),
		'date' => __( 'Date' )
	);

	return $columns;
}

function cab_organisation_columns_content($column, $post_id) {



	global $post;

	switch( $column ) {

		case 'status':

			$categories = $this->cab_functions->get_status_categories();
			$organisation_status = get_field('organisation_status', $post->ID);
			if (is_numeric(get_field('organisation_status', $post->ID)) ) {
				echo $categories[$organisation_status];


			}
			//echo intval(get_field('organisation_status', $post->ID));
			// if (isset(var))
			// print_r($categories[get_field('organisation_status', $post->ID)]);
			//echo  get_field('organisation_status', $post->ID);

		break;

		/* If displaying the 'duration' column. */
		case 'vragenlijst' :

			if ($this->cab_functions->is_application_allowed($post_id,'vragenlijst')) {
				echo "Yes";
			} else {
				echo "No";
			}

			break;

		/* If displaying the 'genre' column. */
		case 'bloementuin' :

			if ($this->cab_functions->is_application_allowed($post_id,'bloementuin')) {
				echo "Yes";
			} else {
				echo "No";
			}

			break;

		case '2':
			//print_r($this->cab_functions->get_form_period_status($post_id, 2));
			echo $this->cab_functions->get_form_period_status($post_id, 2);
		break;

		case '3':
			echo $this->cab_functions->get_form_period_status($post_id, 3);
		break;

		case '4':
			echo $this->cab_functions->get_form_period_status($post_id, 4);
		break;

		case '5':
			echo $this->cab_functions->get_form_period_status($post_id, 5);
		break;

		/* Just break out of the switch statement for everything else. */
		default :
			break;
	}
}


function vragenlijst_column_orderby( $query ) {
	//print_r($query);
	//exit();
     if( ! is_admin() )
         return;

     $orderby = $query->get( 'orderby');

     if ('status' == $orderby) {
     	$query->set('meta_key','organisation_status');
 		$query->set('orderby','meta_value_num');
     }
    // if( 'event_date' == $orderby ) {
    //     $query->set('meta_key','_wr_event_date');
    //     $query->set('orderby','meta_value_num');
    // }
}


function cab_organisation_register_sortable( $columns )
{
	$columns['status'] = 'status';

	// $columns['vragenlijst'] = 'vragenlijst';
	// $columns['bloementuin'] = 'bloementuin';

	return $columns;
}


	// Collect content for the kernactiviteiten tab
function tab_content_kernactiviteiten($data) {

	// Gravity forms groups
	$gf_checkboxgroup_type_id = 14;
	$gf_checkboxgroup_discipline_id = 16;
	$gf_checkboxgroup_functie_id = 17;

	//
	$gf_id = 1;

	$return_html = "";
	$return_html .= $this->get_tab_content_kernactiviteiten_checkboxgroup($data['period_id'], 'keten', 'functie', $this->cab_functions->get_gform_field_data($gf_id,$gf_checkboxgroup_functie_id), $data['data']['functie'] );
	$return_html .= $this->get_tab_content_kernactiviteiten_checkboxgroup($data['period_id'], 'type', 'type', $this->cab_functions->get_gform_field_data($gf_id,$gf_checkboxgroup_type_id), $data['data']['type'] );
	$return_html .= $this->get_tab_content_kernactiviteiten_checkboxgroup($data['period_id'], 'discipline', 'sector', $this->cab_functions->get_gform_field_data($gf_id,$gf_checkboxgroup_discipline_id), $data['data']['sector'] );

	return $return_html;

}

// Generate a group of checkboxxes and returns the html
function get_tab_content_kernactiviteiten_checkboxgroup($period_id, $group, $label, $structure, $data) {

	$return_html = "<div class='checkbox_group'>";
	$return_html .= "<h4>".$label."</h4>";
	$return_html .= "<ul class='checkbox_group_list'>";

    $field_name = "data[periodical][".$period_id."][kernactiviteiten][".$group."][]";



	foreach ($structure['inputs'] as $key => $field) {

		if (in_array($structure['choices'][$key]['value'], $data)) {
			$is_checked = "checked";
		} else {
			$is_checked = "";
		}

		//print_r($gf_checkboxgroup_structure['choices'][$key]['value']);
		$return_html .= '<li class="clearfix"><input name="'.$field_name.'" type="checkbox" value="'.$structure['choices'][$key]['value'].'" id="choice_'.$group.'_'.$key.'" '.$is_checked.'><label for="choice_'.$group.'_'.$key.'"">'.$field['label'].'</label></li>';
		# code...


	}

	$return_html .= "</ul>";
	$return_html .= "</div>";

	return $return_html;
}


    function cab_metabox_create_input($name, $group, $label, $period = false, $type = 'text', $value = '', $vragenlijst_id = false, $field = false) {

    	$field_html = '';
    	$field_name = '';

    	if (!$period) {
    		$field_name = 'data['.$group.']['.$name.']';
    	} else if (!$vragenlijst_id) {
    		$field_name = 'data[periodical]['.$period.']['.$group.']['.$name.']';
    	} else {
    		$field_name = 'data[periodical]['.$period.']['.$group.']['.$vragenlijst_id .']['.$name.']';

    	}


    	if ($type == 'hidden') {

    		if ($name == 'aanv_vragenlijst_id' && !$value) {
    			$value = $vragenlijst_id;
    		}

    		$field_html .= '<input type="hidden" name="'.$field_name.'" id="'.$name.'" value="'.$value.'" /></li>';

    	} else if ($type == 'radio') {

    		// If there is a gfield_id given, get the structure from gravity forms
    		if (isset($field['gfield_id'])) {

    			// Get gravity forms field structure
    			$field_structure = $this->cab_functions->get_gform_field_data($this->cab_functions->get_gform_id(),$field['gfield_id']);

    			foreach ($field_structure['choices'] as $choice) {


    				// Check which radio button needs to be checked
    				if ($choice['value'] == $value) {
    					$is_checked = 'checked="checked"';
    				} else {
    					$is_checked = '';
    				}

  					$field_html .= '<li><input name="'.$field_name.'" type="radio" value="'.$choice['value'].'" '.$is_checked.' ><label >'.$choice['text'].'</label></li>';
    			}
    				//print_r($field_structure);

    		}
		//choices
    	} else {

    		if ($value == 'null') {
    			$value = '';
    		}
    		$field_html .= '<li><label for="'.$name.'">'.$label.'</label>';
    		$field_html .= '<input type="text" name="'.$field_name.'" id="'.$name.'" value="'.$value.'" /></li>';


    	}

    	return $field_html;

    }


	function cd_meta_box_save( $post_id )
	{

		global $post;
		global $wpdb;


		//print_r($_POST);
		// // Bail if we're doing an auto save
	    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

		// // // if our nonce isn't there, or we can't verify it, bail
	 	if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;

		// // if our current user can't edit this post, bail
	    if( !current_user_can( 'edit_post', $post_id ) ) return;





	    // If a email adres is given, create a new user
	    if (isset($_POST['add_user_email']) && $_POST['add_user_email'] != '' && isset($_POST['add_user_name']) && $_POST['add_user_name'] != '') {

	    	// Check if the username allready exists
			$user_name = $_POST['add_user_name'];
	    	$user_email = $_POST['add_user_email'];

	    	// Check if the username doesn't already exists
	    	if (!username_exists( $user_name )) {

				$random_password = wp_generate_password( $length=6, $include_standard_special_chars=false );

				$createUserResponse = wp_insert_user( array (
						'user_login' => sanitize_title( $user_name, $user_email ),
						'user_pass' => $random_password,
						'user_email' => $user_email,
						'role' => 'cab_organisation'
						)
				);

				if (is_wp_error($createUserResponse)) {
					$error_string = $createUserResponse->get_error_message();
					$_SESSION['my_admin_notices'] = '<div class="error"><p>'.$error_string.'</p></div>';
					return false;
				} else {
					$user_id = $createUserResponse;
				}

				// Add user to the post
				$_POST['fields']['field_51e66e085064e'] = $user_id;

				update_user_meta( $user_id, 'user-cab_organisatie-password', $random_password);

	    	} else {
	    		$_SESSION['my_admin_notices'] = '<div class="error"><p>The username already exists, try a different username.</p></div>';
	    	}

	    } else {
	    	// Get the user id
	    	$user_id = $this->cab_functions->get_user_id_by_organisation_id($post->ID);
	    }


	    // update the post data
	    if (isset($user_id)) {
    	    foreach ($_POST['data']['user_data'] as $key => $value) {
    	    	update_user_meta( $user_id, $key, $value);
    		}
	    }


		///update_user_meta( $user_id, 'user-cab_organisatie-geo', $value);






		// Save the data

		// remove the cached data
	    $this->cab_functions->remove_caches($post_id);

		// // Actual saving
		if (isset($_POST['data']['periodical'])) {



			$periodical_data = $_POST['data']['periodical'];




			//print_r($periodical_data);

		    // Walk through each period
		    foreach ($periodical_data as $period_id => $tables) {

		    	// firsts delete all the kernactiviteiten
		    	$this->cab_functions->delete_row("cab_organisatie_discipline", array('period_id' => $period_id, 'organisation_id' => $post->ID));
		    	$this->cab_functions->delete_row("cab_organisatie_keten", array('period_id' => $period_id, 'organisation_id' => $post->ID));
		    	$this->cab_functions->delete_row("cab_organisatie_type", array('period_id' => $period_id, 'organisation_id' => $post->ID));


		    	foreach ($tables as $table_name => $table_data) {



		    		// If data = kernactiviteiten
		    		if ($table_name == "kernactiviteiten") {

		    			foreach ($table_data as $key => $values) {
		    				// Remove all current items
		    				$this->cab_functions->delete_row("cab_organisatie_".$key, array('period_id' => $period_id, 'organisation_id' => $post->ID));

		    				// Add new items
		    				foreach ($values as $value) {
		    				 	$this->cab_functions->add_data("cab_organisatie_".$key, $post->ID, $period_id,array($key."_id" => $value));

		    				}

		    			}
		    		} else {

			    		// Is array reset checks if the first child element of the array is also an array
			    		if (is_array(reset($table_data))) {

			    			// Table contains vragenlijsten
			    			foreach ($table_data as $vragenlijst_id => $vragenlijst_data) {

			    				// walk through the table data and replace empty values with null
			    				foreach ($vragenlijst_data as $data_key => $data_value) {
			    					if ($data_value == '' && $data_key != 'id' && $data_key != 'organisation_id' ) {
			    						$vragenlijst_data[$data_key] = 'NULL';
			    					}
			    				}

			    				$id = $vragenlijst_data['id'];

			    				// if no $id this is new data so add extra details
			    				if (!$id) {
			    					$vragenlijst_data['organisation_id'] = $post_id;
			    					$vragenlijst_data['period_id'] = $period_id;
			    				}

			    			//	print_r($vragenlijst_data);
			    				// Remove the system fields, otherwise these will be overwritten in the database.
			    				unset($vragenlijst_data['id']);

			    				$this->update_data($id, $table_name, $vragenlijst_data);

			    			}


			    		} else {


			    			// walk through the table data and replace empty values with null
			    			foreach ($table_data as $data_key => $data_value) {
			    				if ($data_value == '' && $data_key != 'id' && $data_key != 'organisation_id' ) {
			    					$table_data[$data_key] = 'NULL';
			    				}
			    			}

			    		    // Single group
			    			$id = $table_data['id'];

			    			// if no $id this is new data so add extra details
			    			if (!$id) {
			    				$table_data['organisation_id'] = $post_id;
			    				$table_data['period_id'] = $period_id;
			    			}

			    			$this->update_data($id, $table_name, $table_data);

			    		}
		    		}
		    	}

		    }


		}





	}



// Receives an array with column names and values and saves these
function update_data($id = false, $table_name, $data = false) {
	global $wpdb;

	// walk through the data to check if there are any numerics, if so check if they have the right format for the databe
	foreach ($data as $key => $value) {

		$columns[$key] = $value;

		// if there is a format set for this table
		if (isset($this->organisation_data_field_formats[$table_name])) {

			if (isset($this->organisation_data_field_formats[$table_name][$key])) {
				$format = $this->organisation_data_field_formats[$table_name][$key]['format'];

				// if the format is set as decimal, try to convert from dutch to normalized
				if ($format == "decimal" && !is_numeric($value)) {

					/*// disabled because the target server is running php 2.9
					$fmt = new NumberFormatter( 'nl_NL', NumberFormatter::DECIMAL );
					$formattedValue = $fmt->parse($value);

					// update the columns array
					$columns[$key] = $formattedValue;*/


					// tmp
					$columns[$key] = str_replace(',', '.', $value);

				}

			}
		}

	}

	// Add a filter to replace the 'NULL' string with NULL because wordpres doesn't support null
	add_filter( 'query', 'wp_db_null_value' );

	// If has id update
	if ($id) {

		 $wpdb->update(
         	$wpdb->prefix.$table_name,
        		$columns,
         	array( 'id' => $id )
        );

	} else {

		// No id add new
	    $wpdb->insert(
	        $wpdb->prefix.$table_name,
			$columns
	    );

	}

	// Remove the filter again:
	remove_filter( 'query', 'wp_db_null_value' );

}

function update_subsidy($organisation_id, $amount) {
    global $wpdb;

    $wpdb->update(
        $wpdb->prefix.'cab_subsidy',
        array(
'amount' => $amount  // string
),
        array( 'organisation_id' => $organisation_id ));
}

function get_subsidy($organisation_id) {
    global $wpdb;

    $sql = "SELECT * FROM ".$wpdb->prefix."cab_subsidy WHERE organisation_id = ".$organisation_id;
    echo $sql;
    $subsidy = $wpdb->get_row($sql, OBJECT);
    return $subsidy;
}



}


$cab_admin = new cab_admin();




?>
