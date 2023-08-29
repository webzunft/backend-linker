document.addEventListener('DOMContentLoaded', function() {
	var backendLinks = document.querySelectorAll('.wz-backend-link-placeholder');

	backendLinks.forEach(function(link) {
		// Get the current URL
		var currentUrl = window.location.href;
		var currentUrlObject;

		try {
			currentUrlObject = new URL(currentUrl);
		} catch (error) {
			// If the current URL is invalid, don't proceed
			return;
		}

		// If the current URL doesn't contain the desired string, don't proceed
		if ( ! currentUrlObject.search.includes( BackendLinkerConfig.urlStringMatch ) ) {
			return;
		}

		try {
			// get a test URL or the referrer URL
			var referrerUrlObject = new URL( currentUrlObject.searchParams.get('wbl-source') || document.referrer );
		} catch (error) {
			// If the referrer URL is invalid, don't proceed
			return;
		}

		backendLinks.forEach(function(link) {
			var endpoint = link.dataset.linkGoal;
			var newLink = document.createElement('a');
			var backendURL = referrerUrlObject.protocol + '//' + referrerUrlObject.hostname;
			newLink.href = backendURL + '/' + endpoint;
			newLink.text = link.textContent;
			link.replaceWith(newLink);

			// Show the footnote next to the link
			var footnote = newLink.nextSibling;
			if (footnote && footnote.classList.contains('wz-backend-link-footnote')) {
				footnote.style.visibility = 'visible';
			}

			// Show the footnote hint in the footer
			document.getElementById('wz-backend-linker-footnote').style.display = 'block';
		});
	});
});
