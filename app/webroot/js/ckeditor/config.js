/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.language = 'es';
	config.toolbar = 'MyToolbar';
	config.removePlugins = 'elementspath';

	config.toolbar_MyToolbar =
	[
		{ name: 'basicstyles', items : [ 'Bold','Italic' ] },
		{ name: 'paragraph', groups: [ 'list', 'align' ], items: [ 'NumberedList', 'BulletedList', '-', 'Indent', 'Outdent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
		{ name: 'links', items : [ 'Link','Unlink' ] },
		{ name: 'styles', items : ['Format'] },
		{ name: 'insert', items : ['Table'] },
		{ name: 'clipboard', items : ['PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] },
		['ShowBlocks', '-', 'RemoveFormat', '-', 'Source']
	];

	config.forcePasteAsPlainText = true;

	config.format_tags = 'p;h2;h3;h4';

	config.language = 'es';
};
