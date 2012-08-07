/* Use this script if you need to support IE 7 and IE 6. */

window.onload = function() {
	function addIcon(el, entity) {
		var html = el.innerHTML;
		el.innerHTML = '<span style="font-family: \'icomoon\'">' + entity + '</span>' + html;
	}
	var icons = {
			'icon-music' : '&#x21;',
			'icon-feed' : '&#x22;',
			'icon-mail' : '&#x23;',
			'icon-search' : '&#x24;',
			'icon-loading' : '&#x25;',
			'icon-download' : '&#x26;',
			'icon-link' : '&#x27;',
			'icon-out' : '&#x2c;',
			'icon-share' : '&#x2d;',
			'icon-facebook' : '&#x2e;',
			'icon-twitter' : '&#x2f;',
			'icon-android' : '&#x30;',
			'icon-apple' : '&#x31;',
			'icon-windows' : '&#x32;',
			'icon-search-2' : '&#x33;',
			'icon-creative-commons' : '&#x34;',
			'icon-checkmark' : '&#x35;',
			'icon-cancel' : '&#x36;',
			'icon-clock' : '&#x37;',
			'icon-plus' : '&#x28;',
			'icon-plus-alt' : '&#x29;',
			'icon-plus-2' : '&#x2a;'
		},
		els = document.getElementsByTagName('*'),
		i, attr, html, c, el;
	for (i = 0; i < els.length; i += 1) {
		el = els[i];
		attr = el.getAttribute('data-icon');
		if (attr) {
			addIcon(el, attr);
		}
		c = el.className;
		c = c.match(/icon-[^s'"]+/);
		if (c) {
			addIcon(el, icons[c[0]]);
		}
	}
};