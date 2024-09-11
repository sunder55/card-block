/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps } from '@wordpress/block-editor';
import { useEffect, useState } from '@wordpress/element';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

export default function Edit() {
	const [domains, setDomains] = useState([]);

	useEffect(() => {
		fetch('http://localhost:10038/wp-json/wp/v2/domain')
			.then(response => response.json())
			.then(data => {
				const domainDataPromises = data.map(domain => {
					const mediaUrl = domain._links['wp:featuredmedia']?.[0]?.href;

					if (mediaUrl) {
						return fetch(mediaUrl)
							.then(response => response.json())
							.then(mediaData => ({
								title: domain.title.rendered,
								description: domain.content.rendered,
								image: mediaData.source_url,
								// Add any other fields you need, such as price and likes
							}));
					} else {
						return {
							title: domain.title.rendered,
							description: domain.content.rendered,
							image: '', // Placeholder if no image available
							// Add any other fields you need, such as price and likes
						};
					}
				});

				Promise.all(domainDataPromises)
					.then(fetchedData => setDomains(fetchedData))
					.catch(error => console.error('Error fetching domain data:', error));
			})
			.catch(error => console.error('Error fetching data:', error));
	}, []);

	return (
		<div {...useBlockProps()}>
			{domains.length > 0 ? (
				domains.map((domain, index) => (
					<div class="ws-container">
						<div class="ws-cards-container-wrapper ws_cards_xl">
							<div className="ws-cards-container" key={index}>
								<img src={domain.image} alt={domain.title} className="domain_card_diamond" />
								<div className="ws-card-img">
									<img src={domain.image} alt={domain.title} />
								</div>
								<div className="ws-card-contents ws-flex">
									<img src={domain.image} alt={domain.title} title={domain.title} />
									<span className="ws-card-inner-contents">
										<h5>{domain.title}</h5>
										<h6 dangerouslySetInnerHTML={{ __html: domain.description }}></h6>
										{/* Add other fields such as price here */}
									</span>
									<div className="ws-card-likes">
										{/* Add likes here */}
										<h6>2k<i className="fa-solid fa-heart"></i></h6>
									</div>
								</div>
							</div>
						</div>
					</div>
				))
			) : (
				<p>{__('Loading domains...', 'card-block')}</p>
			)}
		</div>
	);
}
