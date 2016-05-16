(function (window, document) {

	var ownerShortcodeAd = GINGERBEARD_SHORTCODE_ADS.owner_shortcode_ad,
		ownerShortcodeWeight = GINGERBEARD_SHORTCODE_ADS.owner_shortcode_weight,
		authorShortcodeAd = GINGERBEARD_SHORTCODE_ADS.author_shortcode_ad,

	var shortcodeAds = [ ownerShortcodeAd, authorShortcodeAd ],
		shortcodeAdWeights = [ ownerShortcodeWeight, 10-ownerShortcodeWeight ];

	shortcodeAdsSplit = new Array();

	var shortcodeCount = 0,

		while(shortcodeCount<shortcodeAds.length) {
			for(i=0; i<shortcodeAdWeights[shortcodeCount]; i++ )
				shortcodeAdsSplit[shortcodeAdsSplit.length]=shortcodeAds[shortcodeCount]
			shortcodeCount++
		}

})(window, document);