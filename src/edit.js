import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { SelectControl } from '@wordpress/components';
import { useEffect, useState } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import './editor.scss';

export default function Edit({ attributes, setAttributes }) {
	const { type } = attributes;
	const [domains, setDomains] = useState([]);
	const [loading, setLoading] = useState(true);
	const [error, setError] = useState(null);

	useEffect(() => {
		const fetchDomains = async () => {
			setLoading(true);
			const apiUrl = `http://localhost:10038/wp-json/wstr/v1/domains/?type=${type}`;
			console.log('Fetching data from:', apiUrl);

			try {
				const response = await fetch(apiUrl);
				if (!response.ok) {
					throw new Error('Network response was not ok.');
				}
				const data = await response.json();
				console.log('Data received from API:', data);

				if (!Array.isArray(data)) {
					throw new Error('Expected an array of domains.');
				}

				const domainData = data.map(domain => ({
					title: domain.title,
					description: domain.price, // Use 'price' as description
					image: domain.featured_image,
				}));

				console.log('Processed domain data:', domainData);
				setDomains(domainData);
			} catch (error) {
				setError(error.message);
			} finally {
				setLoading(false);
			}
		};

		fetchDomains();
	}, [type]);

	const blockProps = useBlockProps();

	return (
		<div {...blockProps}>
			<InspectorControls>
				<SelectControl
					label={__('Select Domain Type', 'card-block')}
					value={type}
					options={[
						{ label: __('New', 'card-block'), value: 'new' },
						{ label: __('Premium', 'card-block'), value: 'premium' },
						{ label: __('Recently Sold', 'card-block'), value: 'recents' },
					]}
					onChange={(newType) => setAttributes({ type: newType })}
				/>
			</InspectorControls>

			{loading && <p>{__('Loading domains...', 'card-block')}</p>}
			{error && <p>{error}</p>}
			{!loading && !error && domains.length > 0 && (
				<div className="ws-container">
					<div className="ws-cards-container-wrapper ws_cards_xl">
						{domains.map((domain, index) => {
							const displayImage = domain.logo ? domain.logo : domain.image; // Handle logo or fallback to image
							return (
								<div className="ws-cards-container" key={index}>
									{/* Conditionally display the diamond icon if term exists */}
									{domain.term_exist && (
										<div className="premium_icon">
											<img src="/wp-content/plugins/card-block/images/diamond.png" alt="Diamond Icon" />
										</div>
									)}

									{/* Hover Charts for Page Trust and Domain Trust */}
									<div className="ws_card_hover_charts ws_flex">
										<div className="circular-progress page-trust">
											<div className="progress-text">
												<div role="progressbar" aria-valuenow={domain.pa} aria-valuemin="0" aria-valuemax="100" style={{ "--value": domain.pa }}></div>
											</div>
											<div className="progress-title">
												<h6>Page Trust</h6>
											</div>
										</div>
										<div className="circular-progress domain-trust">
											<div className="progress-text">
												<div role="progressbar" aria-valuenow={domain.da} aria-valuemin="0" aria-valuemax="100" style={{ "--value": domain.da }}></div>
											</div>
											<div className="progress-title">
												<h6>Domain Trust</h6>
											</div>
										</div>
									</div>

									{/* Domain Image */}
									<div className="ws-card-img">
										<img src={domain.image} alt={domain.title} />
									</div>

									{/* Card Contents */}
									<div className="ws-card-contents ws-flex">
										{domain.discount_percent > 0 && (
											<div className="ws_discount_percent">-{domain.discount_percent}%</div>
										)}
										<img src={displayImage} alt={domain.title} title={domain.title} className="card_logo_img" />
										<span className="ws-card-inner-contents">
											<h5><a href={domain.permalink}>{domain.title}</a></h5>
											<div className="ws_card_price_wrapper ws_flex gap_10">
												<p className="regular_price">{domain.currency}{domain.regular_price}</p>
												<p className="sale_price">{domain.currency}{domain.sale_price}</p>
											</div>
										</span>
										<div className="ws-card-likes">
											<h6><span>2k</span><i className="fa-solid fa-heart"></i></h6> {/* Placeholder likes */}
										</div>
									</div>
								</div>
							);
						})}
					</div>
				</div>
			)}
		</div>
	);
}
