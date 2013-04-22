var BabelFish = (function () {
	var phrases = {
		'CALENDAR': '@@{CALENDAR}',
		'CONFIRMACTION': '@@{CONFIRMACTION}',
		'TABSLOADING': '@@{TABSLOADING}',
		'SHADOWBOX_OF': '@@{SHADOWBOX_OF}',
		'SHADOWBOX_LOADING': '@@{SHADOWBOX_LOADING}',
		'SHADOWBOX_CANCEL': '@@{SHADOWBOX_CANCEL}',
		'SHADOWBOX_NEXT': '@@{SHADOWBOX_NEXT}',
		'SHADOWBOX_PREVIOUS': '@@{SHADOWBOX_PREVIOUS}',
		'SHADOWBOX_PLAY': '@@{SHADOWBOX_PLAY}',
		'SHADOWBOX_PAUSE': '@@{SHADOWBOX_PAUSE}',
		'SHADOWBOX_CLOSE': '@@{SHADOWBOX_CLOSE}',
		'SHADOWBOX_ERROR_SINGLE': '@@{SHADOWBOX_ERROR_SINGLE}',
		'SHADOWBOX_ERROR_SHARED': '@@{SHADOWBOX_ERROR_SHARED}',
		'SHADOWBOX_ERROR_EITHER': '@@{SHADOWBOX_ERROR_EITHER}',
		'RATING_0': '@@{RATING_0}',
		'RATING_1': '@@{RATING_1}',
		'RATING_2': '@@{RATING_2}',
		'RATING_3': '@@{RATING_3}',
		'RATING_4': '@@{RATING_4}',
		'RATING_5': '@@{RATING_5}',
		'RATING_IS_DONE': '@@{RATING_IS_DONE}',
		'RATING_DISABLED': '@@{RATING_DISABLED}'
	};
	return function (phrase_id) {
		return phrases[phrase_id] || ('Untranslated: ' + phrase_id);
	};
}());
var Assets = {
	Image : function (filename) {
		return '%%{}' + filename;
	}
};
var GLOBALS = {
	Language : '@@{}',
	URLS : {
		'Dropbox-Refresh' : '&&{Dock/Dropbox/Get}',
		'Dropbox-Remove' : '&&{Dock/Dropbox/Remove}',
		'Babelfish-Update' : '&&{System/Babelfish/Update}',
		'Babelfish-Load' : '&&{System/Babelfish/Load}',
		'Babelfish-Tell' : '&&{System/Babelfish/Tell}',
		'Page-Load' : '&&{System/Pages/Load}'
	}
};
