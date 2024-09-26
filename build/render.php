<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

// Check if the 'type' attribute is set and default to 'new' if it's not
$type = isset($attributes['type']) ? $attributes['type'] : 'new';

// Display a message indicating the type (for debugging purposes)
echo '<p>Currently displaying ' . esc_html($type) . ' domains.</p>';

// $url = "http://localhost:10033/wp-json/wstr/v1/domains/?type=new";
// $ch = curl_init();
// curl_setopt($ch, CURLOPT_URL, $url);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// curl_setopt($ch, CURLOPT_ENCODING, ""); // this will handle gzip content
// $result = curl_exec($ch);
// curl_close($ch);
// var_dump($result);

// Fetch data from the API based on the selected type
$response = wp_remote_get("http://localhost:10033/wp-json/wstr/v1/domains/?type=$type");

if (is_wp_error($response)) {
	return '<p>' . esc_html__('Failed to fetch domains.', 'card-block') . '</p>';
}

$domains = json_decode(wp_remote_retrieve_body($response), true);
if (empty($domains)) {
	return '<p>' . esc_html__('No domains found.', 'card-block') . '</p>';
}

// Initialize output
$output = '';


if ($type === 'recents') {
	// Initialize Swiper container and wrapper
	$output .= '<div class="swiper-container ws-container">';
	$output .= '<div class="swiper-wrapper ws-cards-container-wrapper ws_cards_xl">';
	foreach ($domains as $domain) {
		$title = esc_html($domain['title']);
		// $image_url = esc_url($domain['featured_image']);
		$default_image_url = home_url('/wp-content/themes/assets/images/alternate-domain.png');
		// Check if the featured image exists; if not, use the fallback image
		$image_url = !empty($domain['featured_image']) ? esc_url($domain['featured_image']) : esc_url($default_image_url);
		// $currency = esc_html($domain['currency']);
		// $regular_price = esc_html($domain['regular_price']);
		// $sale_price = esc_html($domain['sale_price']);
		$discount_percent = esc_html($domain['precentage_discount']);
		$term_exist = isset($domain['term_exist']) ? (bool) $domain['term_exist'] : true; // Default to true if not set

		// Define the logo URL
		$logo = !empty($domain['logo']) ? esc_url($domain['logo']) : '';

		// Determine which image to show if logo is not present
		$display_image = !empty($logo) ? $logo : $image_url;

		// HTML structure for premium domains (or any other type)
		$output .= '<div class="ws-cards-container swiper-slide">';

		// Conditionally display the diamond icon based on term_exist
		if ($term_exist) {
			$output .= '<div class="premium_icon"><img src="/wp-content/plugins/card-block/images/diamond.png" alt="Diamond Icon" /></div>';
		}

		// Hover charts
		$output .= '<div class="ws_card_hover_charts ws_flex">';
		$output .= '<div class="circular-progress page-trust">';
		$output .= '<div class="progress-text">';
		$output .= '<div role="progressbar" aria-valuenow="' . (int) esc_attr($domain['pa']) . '" aria-valuemin="0" aria-valuemax="100" style="--value:' . (int) esc_attr($domain['pa']) . '"></div>';
		$output .= '</div>'; // Close progress-text
		$output .= '<div class="progress-title">';
		$output .= '<h6>Page Trust</h6>';
		$output .= '</div>'; // Close progress-title
		$output .= '</div>'; // Close circular-progress

		$output .= '<div class="circular-progress domain-trust">';
		$output .= '<div class="progress-text">';
		$output .= '<div role="progressbar" aria-valuenow="' . (int) esc_attr($domain['da']) . '" aria-valuemin="0" aria-valuemax="100" style="--value:' . (int) esc_attr($domain['da']) . '"></div>';
		$output .= '</div>'; // Close progress-text
		$output .= '<div class="progress-title">';
		$output .= '<h6>Domain Trust</h6>';
		$output .= '</div>'; // Close progress-title
		$output .= '</div>'; // Close circular-progress
		$output .= '</div>';

		$output .= '<div class="ws-card-img">';
		$output .= '<img src="' . $image_url . '" alt="' . $title . '" />';
		$output .= '</div>';
		$output .= '<div class="ws-card-contents ws-flex">';
		if ((int) $discount_percent > 0) {
			$output .= '<div class="ws_discount_percent"> -' . $discount_percent . '%</div>';
		}
		$output .= '<img src="' . $display_image . '" alt="' . $title . '" title="' . $title . '" class="card_logo_img"/>';
		$output .= '<span class="ws-card-inner-contents">';
		$output .= '<h5><a href="' . esc_url($domain['permalink']) . '">' . $title . '</a></h5>';
		// $output .= '<div class="ws_card_price_wrapper ws_flex gap_10">';
		// $output .= '<p class="regular_price">' . $currency . $regular_price . '</p>';
		// $output .= '<p class="sale_price">' . $currency . $sale_price . '</p>';
		// $output .= '</div>';
		$output .= get_wstr_price($domain['id']);
		$output .= '</span>';
		$output .= '<div class="ws-card-likes">';
		$output .= '<h6><span>2k</span><i class="fa-solid fa-heart"></i></h6>'; // Example placeholder
		$output .= '</div>';
		$output .= '</div>'; // Close ws-card-contents
		$output .= '</div>'; // Close ws-cards-container
	}
	$output .= '</div>';
	$output .= '</div>';
} elseif ($type === 'trending') {
	$output .= '<div class="ws_trending_cards ws_home_trending_cards">';
	$output .= '<div class="ws-cards-container-wrapper ws_cards_xl">';
	foreach ($domains as $domain) {

		$title = esc_html($domain['title']);
		$default_image_url = home_url('/wp-content/themes/assets/images/alternate-domain.png');
		// Check if the featured image exists; if not, use the fallback image
		$image_url = !empty($domain['featured_image']) ? esc_url($domain['featured_image']) : esc_url($default_image_url);
		$currency = esc_html($domain['currency']);
		$regular_price = esc_html($domain['regular_price']);
		$sale_price = esc_html($domain['sale_price']);
		$discount_percent = esc_html($domain['precentage_discount']);
		$term_exist = isset($domain['term_exist']) ? (bool) $domain['term_exist'] : true; // Default to true if not set

		// Define the logo URL
		$logo = !empty($domain['logo']) ? esc_url($domain['logo']) : '';
		$display_image = !empty($logo) ? $logo : $image_url;
		$output .= '<div class="ws-card-contents">';
		if ((int) $discount_percent > 0) {
			$output .= '<div class="ws_discount_percent"> -' . $discount_percent . '%</div>';
		}
		$output .= '<div class="ws_home_trending_contents">';
		$output .= '<img src="' . $display_image . '" alt="' . $title . '" title="' . $title . '" class="card_logo_img"/>';
		$output .= '<span class="ws-card-inner-contents">';
		$output .= '<h5><a href="' . esc_url($domain['permalink']) . '">' . $title . '</a></h5>';
		$output .= '<div class="ws_card_price_wrapper ws_flex gap_10">';


		// Regular Price
		// $regular_price = get_wstr_regular_price(get_the_ID());
		// $output .= '<p class="regular_price">' . get_wstr_currency() . get_wstr_regular_price(get_the_ID());
		// '</p>';
		$output .= get_wstr_price($domain['id']);

		// Sale Price
		// $sale_price = get_wstr_sale_price(get_the_ID());
		// $output .= '<p class="sale_price">' . get_wstr_currency() . $get_wstr_regular_price(get_the_ID());
		// '</p>';


		$output .= '</div>';
		$output .= '</span>';
		$output .= '<div class="ws-card-likes">';
		$output .= '<h6><span>2k</span><i class="fa-solid fa-heart"></i></h6>'; // Example placeholder
		$output .= '</div>';
		$output .= '</div>';
		$output .= '<div class="ws_card_hover_charts ws_flex">';
		$output .= '<div class="circular-progress page-trust">';
		$output .= '<div class="progress-text">';
		$output .= '<div role="progressbar" aria-valuenow="' . (int) esc_attr($domain['pa']) . '" aria-valuemin="0" aria-valuemax="100" style="--value:' . (int) esc_attr($domain['pa']) . '"></div>';
		$output .= '</div>'; // Close progress-text
		$output .= '<div class="progress-title">';
		$output .= '<h6>Page Trust</h6>';
		$output .= '</div>'; // Close progress-title
		$output .= '</div>'; // Close circular-progress

		$output .= '<div class="circular-progress domain-trust">';
		$output .= '<div class="progress-text">';
		$output .= '<div role="progressbar" aria-valuenow="' . (int) esc_attr($domain['da']) . '" aria-valuemin="0" aria-valuemax="100" style="--value:' . (int) esc_attr($domain['da']) . '"></div>';
		$output .= '</div>'; // Close progress-text
		$output .= '<div class="progress-title">';
		$output .= '<h6>Domain Trust</h6>';
		$output .= '</div>'; // Close progress-title
		$output .= '</div>'; // Close circular-progress
		$output .= '</div>';
		$output .= '</div>'; // Close ws-card-contents


	}
	$output .= '</div>';
	$output .= '</div>';
} else {
	// var_dump($domains);
	// Generate dynamic HTML based on the selected type
	$output .= '<div class="ws-container new">';
	$output .= '<div class="ws-cards-container-wrapper ws_cards_xl">';

	foreach ($domains as $domain) {
		$title = esc_html($domain['title']);
		$default_image_url = home_url('/wp-content/themes/assets/images/alternate-domain.png');
		// Check if the featured image exists; if not, use the fallback image
		$image_url = !empty($domain['featured_image']) ? esc_url($domain['featured_image']) : esc_url($default_image_url);
		// $currency = esc_html($domain['currency']);
		// $regular_price = esc_html($domain['regular_price']);
		// $sale_price = esc_html($domain['sale_price']);
		$discount_percent = esc_html($domain['precentage_discount']);
		$term_exist = isset($domain['term_exist']) ? (bool) $domain['term_exist'] : true; // Default to true if not set

		// Define the logo URL
		$logo = !empty($domain['logo']) ? esc_url($domain['logo']) : '';

		// Determine which image to show if logo is not present
		$display_image = !empty($logo) ? $logo : $image_url;

		// HTML structure for premium domains (or any other type)
		$output .= '<div class="ws-cards-container">';

		// Conditionally display the diamond icon based on term_exist
		if ($term_exist) {
			$output .= '<div class="premium_icon"><img src="/wp-content/plugins/card-block/images/diamond.png" alt="Diamond Icon" /></div>';
		}

		// Hover charts
		$output .= '<div class="ws_card_hover_charts ws_flex">';
		$output .= '<div class="circular-progress page-trust">';
		$output .= '<div class="progress-text">';
		$output .= '<div class="arpan"></div>';
		$output .= '<div role="progressbar" aria-valuenow="' . (int) esc_attr($domain['pa']) . '" aria-valuemin="0" aria-valuemax="100" style="--value:' . (int) esc_attr($domain['pa']) . '"></div>';

		$output .= '</div>'; // Close progress-text
		$output .= '<div class="progress-title">';
		$output .= '<h6>Page Trust</h6>';
		$output .= '</div>'; // Close progress-title
		$output .= '</div>'; // Close circular-progress

		$output .= '<div class="circular-progress domain-trust">';
		$output .= '<div class="progress-text">';
		$output .= '<div role="progressbar" aria-valuenow="' . (int) esc_attr($domain['da']) . '" aria-valuemin="0" aria-valuemax="100" style="--value:' . (int) esc_attr($domain['da']) . '"></div>';
		$output .= '</div>'; // Close progress-text
		$output .= '<div class="progress-title">';
		$output .= '<h6>Domain Trust</h6>';
		$output .= '</div>'; // Close progress-title
		$output .= '</div>'; // Close circular-progress
		$output .= '</div>';

		$output .= '<div class="ws-card-img">';
		$output .= '<img src="' . $image_url . '" alt="' . esc_attr($title) . '" />';
		$output .= '</div>';
		$output .= '<div class="ws-card-contents ws-flex">';
		if ((int) $discount_percent > 0) {
			$output .= '<div class="ws_discount_percent"> -' . $discount_percent . '%</div>';
		}
		$output .= '<img src="' . $display_image . '" alt="' . $title . '" title="' . $title . '" class="card_logo_img"/>';
		$output .= '<span class="ws-card-inner-contents">';
		$output .= '<h5><a href="' . esc_url($domain['permalink']) . '">' . $title . '</a></h5>';
		// $output .= '<div class="ws_card_price_wrapper ws_flex gap_10">';
		// $output .= '<p class="regular_price">' . $currency . $regular_price . '</p>';
		// $output .= '<p class="sale_price">' . $currency . $sale_price . '</p>';
		// $output .= '</div>';
		$output .= get_wstr_price($domain['id']);
		$output .= '</span>';
		$output .= '<div class="ws-card-likes">';
		$output .= '<h6><span>2k</span><i class="fa-solid fa-heart"></i></h6>'; // Example placeholder
		$output .= '</div>';
		$output .= '</div>'; // Close ws-card-contents
		$output .= '</div>'; // Close ws-cards-container
	}

	$output .= '</div>'; // Close ws-cards-container-wrapper
	$output .= '</div>'; // Close ws-container
}

// Output the HTML
echo $output;
