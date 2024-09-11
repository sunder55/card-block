<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
?>
<p <?php echo get_block_wrapper_attributes(); ?>></p>
<?php
// Fetch data from the new API endpoint
$response = wp_remote_get('http://localhost:10033/wp-json/wstr/v1/domains/?type=premium');
if (is_wp_error($response)) {
	return '<p>' . esc_html__('Failed to fetch domains.', 'card-block') . '</p>';
}

$domains = json_decode(wp_remote_retrieve_body($response), true);
var_dump($domains);
if (empty($domains)) {
	return '<p>' . esc_html__('No domains found.', 'card-block') . '</p>';
}

// Generate dynamic HTML
$output = '<div class="ws-container">';
$output .= '<div class="ws-cards-container-wrapper ws_cards_xl">';

foreach ($domains as $domain) {
	$title = esc_html($domain['title']);
	$description = ''; // Assuming there's no separate description field, otherwise adjust as needed
	$price = wp_kses_post(strip_tags($domain['price'])); // Clean up the price field
	$image_url = esc_url($domain['image']);
	$permalink = esc_url($domain['permalink']);
	$progress = 20;
	$output .= '<div class="ws-cards-container">';
	// $output .= '<img src="path/to/diamond.png" alt="premium" class="domain_card_diamond" />'; // Update path as needed


	// hover charts
	$output .= '<div class="ws_card_hover_charts ws_flex">';
	// page trust
// Add the circular progress bar with progress set to 20
	$output .= '<div class="circular-progress page-trust">';
	$output .= '<svg viewBox="0 0 36 36" class="circular-chart">';
	$output .= '<path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />';
	$output .= '<path class="circle" stroke-dasharray="20, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />';
	$output .= '</svg>';

	// Add the h2 element inside the circle
	$output .= '<div class="progress-text">';
	$output .= '<h2>' . $progress . '<br><span> of 100</span></h2>';
	$output .= '</div>'; // Close progress-text
	$output .= '<div class="progress-title">';
	$output .= '<h6>Page Trust</h6>';
	$output .= '</div>'; // Close progress-text
	$output .= '</div>'; // Close circular-progress

	// second circlular domain trust
// Add the circular progress bar with progress set to 20
	$output .= '<div class="circular-progress domain-trust">';
	$output .= '<svg viewBox="0 0 36 36" class="circular-chart">';
	$output .= '<path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />';
	$output .= '<path class="circle" stroke-dasharray="20, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />';
	$output .= '</svg>';

	// Add the h2 element inside the circle
	$output .= '<div class="progress-text">';
	$output .= '<h2>' . $progress . '<br><span> of 100</span></h2>';
	$output .= '</div>'; // Close progress-text
	$output .= '<div class="progress-title">';
	$output .= '<h6>Domain Trust</h6>';
	$output .= '</div>'; // Close progress-text
	$output .= '</div>'; // Close circular-progress
	$output .= '</div>';




	$output .= '<div class="ws-card-img">';
	$output .= '<img src="' . $image_url . '" alt="' . $title . '" />';
	$output .= '</div>';
	$output .= '<div class="ws-card-contents ws-flex">';
	// $output .= '<img src="' . $image_url . '" alt="' . $title . '" title="' . $title . '" />'; // Assuming logo image is same as domain image, adjust if different
	$output .= '<span class="ws-card-inner-contents">';
	$output .= '<h5><a href="' . $permalink . '">' . $title . '</a></h5>';
	// $output .= '<h6>' . $description . '</h6>'; // This field is empty in the provided data
	$output .= '<h6 class="ws-card-price">' . $price . '</h6>';
	$output .= '</span>';
	$output .= '<div class="ws-card-likes">';
	$output .= '<h6><span>2k</span><i class="fa-solid fa-heart"></i></h6>'; // Example placeholder
	$output .= '</div>';
	$output .= '</div>'; // Close ws-card-contents
	$output .= '</div>'; // Close ws-cards-container
}

$output .= '</div>'; // Close ws-cards-container-wrapper
$output .= '</div>'; // Close ws-container

echo $output;
?>