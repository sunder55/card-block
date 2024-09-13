<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
?>
<p <?php echo get_block_wrapper_attributes(); ?>></p>
<?php
// $option = isset($attributes['option']) ? $attributes['option'] : 'new';
// Fetch data from the new API endpoint
$response = wp_remote_get('http://localhost:10033/wp-json/wstr/v1/domains/?type=premium');
if (is_wp_error($response)) {
	return '<p>' . esc_html__('Failed to fetch domains.', 'card-block') . '</p>';
}

$domains = json_decode(wp_remote_retrieve_body($response), true);
if (empty($domains)) {
	return '<p>' . esc_html__('No domains found.', 'card-block') . '</p>';
}

// Generate dynamic HTML
$output = '<div class="ws-container">';
$output .= '<div class="ws-cards-container-wrapper ws_cards_xl">';

foreach ($domains as $domain) {
	$title = esc_html($domain['title']);
	$description = ''; // Assuming there's no separate description field, otherwise adjust as needed
	// $price = wp_kses_post(strip_tags($domain['price'])); // Clean up the price field
	$image_url = esc_url($domain['featured_image']);
	$permalink = esc_url($domain['permalink']);
	$currency = esc_html($domain['currency']);
	$regular_price = esc_html($domain['regular_price']);
	$sale_price = esc_html($domain['sale_price']);
	$discount_percent = esc_html($domain['percentage_discount']);
	// $progress = 20;
	$page_trust = esc_html($domain['pa']);
	// $stroke_dasharray = esc_attr($page_trust . ', ' . (100 - $page_trust)); // Proper escaping for attributes

	$domain_trust = esc_html($domain['da']);

	// Define the logo URL
	$logo = !empty($domain['logo']) ? esc_url($domain['logo']) : '';

	// Determine which image to show if logo is not present
	$display_image = !empty($logo) ? $logo : $image_url;

	$output .= '<div class="ws-cards-container">';
	// $output .= '<img src="path/to/diamond.png" alt="premium" class="domain_card_diamond" />'; // Update path as needed

	// hover charts
	$output .= '<div class="ws_card_hover_charts ws_flex">';
	// page trust
	// Add the circular progress bar with progress set to 20
	$output .= '<div class="circular-progress page-trust">';
	// $output .= '<svg viewBox="0 0 36 36" class="circular-chart">';
	// $output .= '<path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />';
	// $output .= '<path class="circle" stroke-dasharray="' . $stroke_dasharray . '" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />';
	// $output .= '</svg>';
	// Add the h2 element inside the circle
	$output .= '<div class="progress-text">';
	// if (!empty($page_trust))
	$output .= '<div role="progressbar" aria-valuenow="' . $page_trust . '" aria-valuemin="0" aria-valuemax="100" style="--value:' . $page_trust . '"></div>';
	// $output .= '<span>of 100</span>';
	$output .= '</div>'; // Close progress-text
	$output .= '<div class="progress-title">';
	$output .= '<h6>Page Trust</h6>';
	$output .= '</div>'; // Close progress-title
	$output .= '</div>'; // Close circular-progress

	// second circular domain trust
	// Add the circular progress bar with progress set to 20
	$output .= '<div class="circular-progress domain-trust">';
	// $output .= '<svg viewBox="0 0 36 36" class="circular-chart">';
	// $output .= '<path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />';
	// $output .= '<path class="circle" stroke-dasharray="20, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />';
	// $output .= '</svg>';

	// Add the h2 element inside the circle
	$output .= '<div class="progress-text">';
	// $output .= '<h2>' . $progress . '<br><span> of 100</span></h2>';
	// if (!empty($domain_trust))
	$output .= '<div role="progressbar" aria-valuenow="' . $domain_trust . '" aria-valuemin="0" aria-valuemax="100" style="--value:' . $domain_trust . '"></div>';
	$output .= '</div>'; // Close progress-text
	$output .= '<div class="progress-title">';
	$output .= '<h6>Domain Trust</h6>';
	$output .= '</div>'; // Close progress-title
	$output .= '</div>'; // Close circular-progress
	$output .= '</div>';

	$output .= '<div class="ws-card-img">';
	$output .= '<div class="premium_icon"><img src="/wp-content/plugins/card-block/images/diamond.png" alt="Diamond Icon" /></div>';

	$output .= '<img src="' . $image_url . '" alt="' . $title . '" />';
	$output .= '</div>';
	$output .= '<div class="ws-card-contents ws-flex">';
	if ((int) $discount_percent > 0) {
		$output .= '<div class="ws_discount_percent"> -' . $discount_percent . '%</div>';
	}
	$output .= '<img src="' . $display_image . '" alt="' . $title . '" title="' . $title . '" class="card_logo_img"/>';
	$output .= '<span class="ws-card-inner-contents">';
	$output .= '<h5><a href="' . $permalink . '">' . $title . '</a></h5>';
	// $output .= '<h6>' . $description . '</h6>'; // This field is empty in the provided data
	$output .= '<div class = "ws_card_price_wrapper ws_flex gap_10">';
	$output .= '<p class = "regular_price">' . $currency;
	$output .= $regular_price . '</p>';
	$output .= '<p class = "sale_price">' . $currency;
	$output .= $sale_price . '</p>';
	$output .= '</div>';
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