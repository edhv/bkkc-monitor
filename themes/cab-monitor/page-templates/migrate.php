<?php
/*
Template Name: Migrate
*/


	global $wpdb;
	global $cab_functions;
	global $cab_form;


// get all entries
$entries = GFAPI::get_entries(1, array(), null, array('offset' => 0, 'page_size' => 400));

echo "nr of entries: ".count($entries)."<br/>";


$entryPostTable = [];
$entriesArray = [];

// walk through the entries
foreach ($entries as $entry) {

	// build up array with entries
	$entriesArray[$entry['id']] = $entry;


	//echo " - entry(".$entry['id'].") ".$entry[1]."<br/>";

	//
	$userId = $entry['created_by'];
	$periodId = $entry['created_by'];

	if (!$userId) {
		echo $entry['id']." has no user id <br/>";
	}

	//echo "  userId( ".$userId." )<br/>";

	// perform a query based on the user id stored in the form
	$posts = get_posts(array(
		'numberposts'	=> -1,
		'post_type'		=> 'cab_organisation',
		'meta_query'	=> array(
			array(
				'key'	  	=> 'gekoppelde_gebruiker',
				'value'	  	=> $userId,
				'compare' 	=> '='
			),
		),
	));


	// if 1 organisation is returned
	if (count($posts) !== 1) {
		echo "Query on user returns none or more than 1 organisation <br/>";
	} else {
		$entryPostTable[ $entry['id'] ] = $posts[0]->ID;
	}

	//print_r($posts);

	# code...
}

// walk through the entryPostTable
foreach ($entryPostTable as $entryId => $postId) {
	migrateEntry( $entriesArray[$entryId], $postId);
	# code...
}




// does the migration
function migrateEntry( $entry, $postId ) {

	global $cab_functions;
	global $cab_form;

	$post = get_post($postId);

	$entryPeriod = $entry[181];
	$entryCategories = explode(",", $entry[19]);

	echo $entry[1]." - ".$post->post_title."<br/>";
	echo "OrganisationId( ".$postId." ) EntryId( ".$entry['id']." )<br/>";
	//print_r($entry);
	echo $entryPeriod;

	//$target_organisations = $cab_functions->get_all_organisations();
	$organisationData = $cab_functions->get_organisation_data( $postId );

	// check if the period exists
	if (!isset($organisationData['periodical'][$entryPeriod])) {
		echo "period not available ".$entryPeriod." <br/>";
		return;
	} 

	echo "<hr/>";


	$organisationDataPeriod = $organisationData['periodical'][$entryPeriod];


	// migrates sections
	migrateSection( $postId, 'subsidy', 'cab_subsidy', $entry, $organisationDataPeriod['data']['cab_subsidy'] );
	migrateSection( $postId, 'eigen_inkomsten', 'cab_eigen_inkomsten', $entry, $organisationDataPeriod['data']['cab_eigen_inkomsten'] );
	migrateSection( $postId, 'organisatie', 'cab_organisatie', $entry, $organisationDataPeriod['data']['cab_organisatie'] );
	migrateSection( $postId, 'omzet', 'cab_omzet', $entry, $organisationDataPeriod['data']['cab_omzet'] );
	migrateSection( $postId, 'scholing', 'cab_scholing', $entry, $organisationDataPeriod['data']['cab_scholing'] );
	migrateSection( $postId, 'marketing', 'cab_marketing', $entry, $organisationDataPeriod['data']['cab_marketing'] );
	migrateSection( $postId, 'media', 'cab_media', $entry, $organisationDataPeriod['data']['cab_media'] );


	/*
	migrateAdditionalSection( 1, 'activiteiten', $entry, $organisationDataPeriod['data']['cab_activiteiten'] );
	migrateAdditionalSection( 1, 'nevenactiviteiten', $entry, $organisationDataPeriod['data']['cab_nevenactiviteiten'] );
	migrateAdditionalSection( 1, 'bezoekers', $entry, $organisationDataPeriod['data']['cab_bezoekers'] );


	migrateAdditionalSection( 2, 'activiteiten', $entry, $organisationDataPeriod['data']['cab_activiteiten'] );
	migrateAdditionalSection( 2, 'nevenactiviteiten', $entry, $organisationDataPeriod['data']['cab_nevenactiviteiten'] );
	migrateAdditionalSection( 2, 'bezoekers', $entry, $organisationDataPeriod['data']['cab_bezoekers'] );
	

	migrateAdditionalSection( 3, 'activiteiten', $entry, $organisationDataPeriod['data']['cab_activiteiten'] );
	migrateAdditionalSection( 3, 'vertoningen', $entry, $organisationDataPeriod['data']['cab_vertoningen'] );
	

	migrateAdditionalSection( 4, 'activiteiten', $entry, $organisationDataPeriod['data']['cab_activiteiten'] );
	migrateAdditionalSection( 4, 'nevenactiviteiten', $entry, $organisationDataPeriod['data']['cab_nevenactiviteiten'] );
	migrateAdditionalSection( 4, 'bezoekers', $entry, $organisationDataPeriod['data']['cab_bezoekers'] );
*/

	foreach ($entryCategories as $key => $categorie) {

		switch ($categorie) {
			case '1':
				migrateAdditionalSection( 1, 'activiteiten', $entry, $organisationDataPeriod['data']['cab_activiteiten'] );
				migrateAdditionalSection( 1, 'nevenactiviteiten', $entry, $organisationDataPeriod['data']['cab_nevenactiviteiten'] );
				migrateAdditionalSection( 1, 'bezoekers', $entry, $organisationDataPeriod['data']['cab_bezoekers'] );
				break;
			case '2':
				migrateAdditionalSection( 2, 'activiteiten', $entry, $organisationDataPeriod['data']['cab_activiteiten'] );
				migrateAdditionalSection( 2, 'nevenactiviteiten', $entry, $organisationDataPeriod['data']['cab_nevenactiviteiten'] );
				migrateAdditionalSection( 2, 'bezoekers', $entry, $organisationDataPeriod['data']['cab_bezoekers'] );
				break;
			case '3':
				migrateAdditionalSection( 3, 'activiteiten', $entry, $organisationDataPeriod['data']['cab_activiteiten'] );
				migrateAdditionalSection( 3, 'vertoningen', $entry, $organisationDataPeriod['data']['cab_vertoningen'] );
				break;
			case '4':
				migrateAdditionalSection( 4, 'activiteiten', $entry, $organisationDataPeriod['data']['cab_activiteiten'] );
				migrateAdditionalSection( 4, 'nevenactiviteiten', $entry, $organisationDataPeriod['data']['cab_nevenactiviteiten'] );
				migrateAdditionalSection( 4, 'bezoekers', $entry, $organisationDataPeriod['data']['cab_bezoekers'] );
				break;
			case '5':
				migrateAdditionalSection( 5, 'activiteiten', $entry, $organisationDataPeriod['data']['cab_activiteiten'] );
				migrateAdditionalSection( 5, 'nevenactiviteiten', $entry, $organisationDataPeriod['data']['cab_nevenactiviteiten'] );
				migrateAdditionalSection( 5, 'bezoekers', $entry, $organisationDataPeriod['data']['cab_bezoekers'] );
				break;
			case '6':
				migrateAdditionalSection( 6, 'activiteiten', $entry, $organisationDataPeriod['data']['cab_activiteiten'] );
				migrateAdditionalSection( 6, 'spreiding', $entry, $organisationDataPeriod['data']['cab_spreiding'] );
				migrateAdditionalSection( 6, 'nevenactiviteiten', $entry, $organisationDataPeriod['data']['cab_nevenactiviteiten'] );
				migrateAdditionalSection( 6, 'bezoekers', $entry, $organisationDataPeriod['data']['cab_bezoekers'] );
				break;
			case '7':
				migrateAdditionalSection( 7, 'activiteiten', $entry, $organisationDataPeriod['data']['cab_activiteiten'] );
				migrateAdditionalSection( 7, 'nevenactiviteiten', $entry, $organisationDataPeriod['data']['cab_nevenactiviteiten'] );
				migrateAdditionalSection( 7, 'bezoekers', $entry, $organisationDataPeriod['data']['cab_bezoekers'] );
				break;
			
		}
		# code...
	}

	echo "<br/>";
	echo "<br/>";
	echo "<br/>";
	//migrateAdditionalSection( 'aanv_1_nevenactiviteiten', $entry, $organisationDataPeriod['data']['cab_nevenactiviteiten'][0] );
	//migrateAdditionalSection( 'aanv_1_bezoekers', $entry, $organisationDataPeriod['data']['cab_bezoekers'][0] );

	//migrateAdditionalSection( 'aanv_2_activiteiten', $entry, $organisationDataPeriod['data']['cab_activiteiten'][0] );
	//migrateAdditionalSection( 'aanv_1_nevenactiviteiten', $entry, $organisationDataPeriod['data']['cab_nevenactiviteiten'][0] );
	//migrateAdditionalSection( 'aanv_1_bezoekers', $entry, $organisationDataPeriod['data']['cab_bezoekers'][0] );

	/*
	cab_activiteiten
	cab_bezoekers
	cab_eigen_inkomsten
	cab_marketing
	cab_media
	cab_nevenactiviteiten
	cab_omzet
	cab_organisatie
	cab_scholing
	cab_spreiding
	cab_subsidy
	cab_vertoningen

	 */

	//print_r($organisationDataPeriod);
	
	/*echo "<hr/>";
	$cab_functions->show_array_as_table( $organisationDataPeriod['data']['cab_subsidy']);

	$subsidyData = $organisationDataPeriod['data']['cab_subsidy'][0];

	$sectionFields = $cab_form->get_field_list_by_section('subsidy');

	print_r($sectionFields);

	*/

	// migrate
	// foreach ($subsidyData as $key => $value) {
	// 	echo $key."<br/>";
	// 	$formFieldId = $cab_form->get_field_id_by_db_field( 'subsidy', $key );
	// 	echo $formFieldId;
	// 	# code...
	// }


	//print_r($organisationData);

}


function migrateSection( $organisationId, $section, $table, $entry, $data ) {
	global $cab_functions;
	global $cab_form;

	$entryPeriod = $entry[181];
	$structure = $cab_form->get_field_list_by_section( $section );
	$migratedData = array();
	$updatedData = array();

	echo "<strong>".$section."</strong><br/>";

	foreach ($structure as $key => $value) {

		if ( substr($key ,0,1) == '_') {
			continue;
		}
		

		//
		$entryFieldId = $value['gform-field-id'];
		$entryValue = $entry[$entryFieldId];
		//$storedDataValue = $data[$key];

		if (isset($data[0])) {
			$storedDataValue = $data[0][$key];
		} else {
			$storedDataValue = '';
		}
		// migrated data
		$migratedValue = $storedDataValue;


		// if stored data is 0 and the entry has no data
		// this should be NULL
		if ( ( $storedDataValue == '0' || $storedDataValue == '' ) && $entryValue == '') {
			$migratedValue = 'NULL';
		} else if ( $storedDataValue == '' && $entryValue != '') {
			$migratedValue = $entryValue;
		}

		$migratedData[$key] = $migratedValue;

		echo "key( ".$key." ) data( ".$storedDataValue." ) - entry( ".$entryValue." ) - migrated( ".$migratedData[$key]." ) <br/>";
		//print_r($value);

		if ($storedDataValue != $migratedData[$key]) {
			$updatedData[$key] = $migratedData[$key];
		}

		# code...
	}


	// update the database
	if (isset($data[0])) {
		store_auto($data[0]['id'], $table, $organisationId, $entryPeriod, $updatedData);
	}
	echo "<hr/>";

};


function migrateAdditionalSection( $additionalNr, $section, $entry, $data ) {

	global $cab_functions;
	global $cab_form;
	echo "<strong>".$section."</strong><br/>";
	$structureGroup = $cab_form->get_field_list_by_section( 'aanv_'.$additionalNr );

	$structure = $structureGroup['_aanv_'.$additionalNr.'_'.$section];
	$migratedData = array();

	foreach ($structure['data'] as $key => $value) {

		//
		$entryFieldId = $value['gform-field-id'];

		//
		$entryValue = $entry[$entryFieldId];

		if (isset($data[0])) {
			$storedDataValue = $data[0][$key];
		} else {
			$storedDataValue = '';
		}

		// migrated data
		$migratedValue = $storedDataValue;


		// if stored data is 0 and the entry has no data
		// this should be NULL
		if ( ( $storedDataValue == '0' || $storedDataValue == '' ) && $entryValue == '') {
		 	$migratedValue = 'NULL';
		} else if ( $storedDataValue == '' && $entryValue != '') {
			$migratedValue = $entryValue;
		}

		$migratedData[$key] = $migratedValue;

		echo "key( ".$key." ) data( ".$storedDataValue." ) - entry( ".$entryValue." ) - migrated( ".$migratedData[$key]." ) <br/>";
		$updatedData = array();

		if ($storedDataValue != $migratedData[$key]) {
			$updatedData[$key] = $migratedData[$key];
		}

		if (isset($data[0])) {
			store_auto($data[0]['id'], $structure['table'], $data[0]['organisation_id'], $data[0]['period_id'], $updatedData);
		}
		# code...
	}
}




function store_aanv($rowId, $table, $organisationId, $period, $updatedData) {

}

/* Database update */
function store_auto($rowId, $table, $organisationId, $period, $updatedData) {

	global $wpdb;
	global $cab_functions;

	$wpdb->show_errors = true;
	
	if (count($updatedData) <= 0 ) {
		return;
	}


	// add period
	$updatedData['period_id'] = $period;

	$queryStr = "UPDATE `".$wpdb->prefix . $table ."` SET ";

	foreach ($updatedData as $key => $value) {

		$sanitizedValue = $value;

		if ($sanitizedValue !== 'NULL') {
			$queryStr .= "`".$key."` = '".$sanitizedValue."', "; 
		} else {
			$queryStr .= "`".$key."` = ".$sanitizedValue.", "; 
		}
	}

	$queryStr .= "`organisation_id` = '".$organisationId."' WHERE `id` = '".$rowId."'";

	echo $queryStr;
	// perform query
	//$wpdb->query($queryStr);
	

}


//print_r($entryPostTable);


?>