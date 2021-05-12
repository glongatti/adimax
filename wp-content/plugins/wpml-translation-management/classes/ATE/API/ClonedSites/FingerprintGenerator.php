<?php

namespace WPML\TM\ATE\ClonedSites;

class FingerprintGenerator {
	const SITE_FINGERPRINT_HEADER = 'SITE-FINGERPRINT';
	const NEW_SITE_FINGERPRINT_HEADER = 'NEW-SITE-FINGERPRINT';

	public function getSiteFingerprint() {
		$siteFingerprint = [
			'wp_url' => defined('ATE_CLONED_SITE_URL') ? ATE_CLONED_SITE_URL : site_url(),
		];

		return json_encode( $siteFingerprint );
	}
}