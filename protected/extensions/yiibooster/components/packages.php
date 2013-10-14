<?php
/**
 * Built-in client script packages.
 *
 * Please see {@link CClientScript::packages} for explanation of the structure
 * of the returned array.
 *
 * @author Ruslan Fadeev <fadeevr@gmail.com>
 *
 * @var Bootstrap $this
 */
return array(

	'font-awesome' => array(
		'baseUrl' => $this->enableCdn ? '//netdna.bootstrapcdn.com/font-awesome/3.2.1/' : $this->getAssetsUrl().'/font-awesome/',
		'css' => array($this->minify ? 'css/font-awesome.min.css' : 'css/font-awesome.css'),
        'depends' => array('defaultLayout')
	),
	'font-awesome-ie7' => array(
		'baseUrl' => $this->enableCdn ? '//netdna.bootstrapcdn.com/font-awesome/3.2.1/' : $this->getAssetsUrl().'/font-awesome/',
		'css' => array($this->minify ? 'css/font-awesome-ie7.min.css' : 'css/font-awesome-ie7.css'),
        'depends' => array('defaultLayout')
	),
	'bootstrap.js' => array(
		'baseUrl' => $this->enableCdn ? '//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/' : $this->getAssetsUrl() . '/bootstrap/',
		'js' => array($this->minify ? 'js/bootstrap.min.js' : 'js/bootstrap.js'),
		'depends' => array('jquery', 'jqui-tb-noconflict','defaultLayout'),
	),
	'bootstrap-yii' => array(
		'baseUrl' => $this->getAssetsUrl(),
		'css' => array('css/bootstrap-yii.css'),
        'depends' => array('defaultLayout')
	),
	'jquery-css' => array(
		'baseUrl' => $this->getAssetsUrl(),
		'css' => array('css/jquery-ui-bootstrap.css'),
        'depends' => array('defaultLayout')
	),
	'bootbox' => array(
		'baseUrl' => $this->getAssetsUrl() . '/bootbox/',
		'js' => array($this->minify ? 'bootbox.min.js' : 'bootbox.js'),
        'depends' => array('defaultLayout')
	),
	'notify' => array(
		'baseUrl' => $this->getAssetsUrl() . '/notify/',
		'js' => array($this->minify ? 'notify.min.js' : 'notify.js'),
        'depends' => array('defaultLayout')
	),
	'jqui-tb-noconflict' => array(
		'baseUrl' => $this->getAssetsUrl(),
		'js' => array('js/jqui-tb-noconflict.js'),
		'depends' => array('jquery', 'jquery.ui','defaultLayout') // we don't have any other choice to reliably prevent conflicts with jQueryUI than to forcefully include it before Bootstrap and the script preventing conflicts
	),

	//widgets start
	'datepicker' => array(
		'depends' => array('jquery','defaultLayout'),
		'baseUrl' => $this->enableCdn ? '//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.1.3/' : $this->getAssetsUrl(),
		'css' => array($this->minify ? 'css/bootstrap-datepicker.min.css' : 'css/bootstrap-datepicker.css'),
		'js' => array($this->minify ? 'js/bootstrap-datepicker.min.js' : 'js/bootstrap-datepicker.js'),
        'depends' => array('defaultLayout')
	),
	'datetimepicker' => array(
		'depends' => array('jquery','defaultLayout'),
		'baseUrl' => $this->getAssetsUrl() . '/bootstrap-datetimepicker/', // Not in CDN yet
		'css' => array($this->minify ? 'css/bootstrap-datetimepicker.css' : 'css/bootstrap-datetimepicker.css'),
		'js' => array($this->minify ? 'js/bootstrap-datetimepicker.min.js' : 'js/bootstrap-datetimepicker.js'),
        'depends' => array('defaultLayout')
	),
	'date' => array(
		'baseUrl' => $this->enableCdn ? '//cdnjs.cloudflare.com/ajax/libs/datejs/1.0/' : $this->getAssetsUrl() . '/js/',
		'js' => array('date.min.js'),
        'depends' => array('defaultLayout')
	),
	'x-editable' => array(
		'baseUrl' => $this->getAssetsUrl() . '/bootstrap-editable/',
		'css' => array('css/bootstrap-editable.css'),
		'js' => array($this->minify ? 'js/bootstrap-editable.min.js' : 'js/bootstrap-editable.js'),
		'depends' => array('jquery','defaultLayout')
	),
	'moment' => array(
		'baseUrl' => $this->getAssetsUrl(),
		'js' => array('js/moment.min.js'),
        'depends' => array('defaultLayout')
	),
	'picker' => array(
		'baseUrl' => $this->getAssetsUrl() . '/picker',
		'js' => array('bootstrap.picker.js'),
		'css' => array('bootstrap.picker.css'),
		'depends' => array('bootstrap.js','defaultLayout')
	),
	'bootstrap.wizard' => array(
		'baseUrl' => $this->getAssetsUrl() . '/bootstrap-wizard',
		'js' => array($this->minify ? 'jquery.bootstrap.wizard.min.js' : 'jquery.bootstrap.wizard.js'),
        'depends' => array('defaultLayout')
	),

	'ajax-cache' => array(
		'baseUrl' => $this->getAssetsUrl() . '/ajax-cache',
		'js' => array('jquery.ajax.cache.js'),
        'depends' => array('defaultLayout')
	),
	'jqote2' => array(
		'baseUrl' => $this->getAssetsUrl() . '/jqote2',
		'js' => array('jquery.jqote2.min.js'),
        'depends' => array('defaultLayout')
	),
	'json-grid-view' => array(
		'baseUrl' => $this->getAssetsUrl() . '/json-grid-view',
		'js' => array('jquery.json.yiigridview.js'),
		'depends' => array('jquery', 'jqote2', 'ajax-cache','defaultLayout')
	),

	'redactor' => array(
		'baseUrl' => $this->getAssetsUrl() . '/redactor',
		'js' => array($this->minify ? 'redactor.min.js' : 'redactor.js'),
		'css' => array('redactor.css'),
		'depends' => array('jquery','defaultLayout')
	),

	'passfield' => array(
		'depends' => array('jquery','defaultLayout'),
		'baseUrl' => $this->getAssetsUrl() . '/bootstrap-passfield/', // Not in CDN yet
		'css' => array($this->minify ? 'css/passfield.min.css' : 'css/passfield.min.css'),
		'js' => array($this->minify ? 'js/passfield.min.js' : 'js/passfield.min.js'),
        'depends' => array('defaultLayout')
	),

	'timepicker' => array(
		'baseUrl' => $this->getAssetsUrl() . '/bootstrap-timepicker',
		'js' => array($this->minify ? 'js/bootstrap-timepicker.min.js' : 'js/bootstrap-timepicker.js'),
		'css' => array($this->minify ? 'css/bootstrap-timepicker.min.css' : 'css/bootstrap-timepicker.css'),
		'depends' => array('bootstrap.js','defaultLayout')
	),

);
