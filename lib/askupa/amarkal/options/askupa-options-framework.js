/**
 * package:		Twitter Feed
 * version:		1.2
 * author:		Askupa Software <contact@askupasoftware.com>
 * link:		http://products.askupasoftware.com/twitter-feed/
 * facing:		custom
 * depends:		{jquery}
 */

jQuery(document).ready(function ($) {
	var ofw = new OptionsFramework();
	ofw.init();
});

var $ = jQuery;

/**
 * 
 * @returns {OptionsFramework}
 */
function OptionsFramework() {}

/**
 * 
 * @returns {undefined}
 */
OptionsFramework.prototype.init = function() {
	
	this.settings_changed = false;

	this.sections = $('input#sections').val().split(',');
	this.fields = $('.askupa-field');
	this.form = $('#askupa-form');	for(var i = 0; i < this.sections.length; i++)
		$('div#' + this.sections[i] + '-section').hide();	var current_section = $('input#askupa-current-section').val();
	if(current_section === '')
		this.show_section(this.sections[0]);
	else this.show_section(current_section);
	
	this.bind_sidebar_events();
	this.bind_field_events();
	this.bind_footer_events();
	this.show_submit_feedback();
	this.init_sliders();
	this.init_switches();
};

/**
 * 
 * @param {type} item
 * @returns {unresolved}
 */
OptionsFramework.prototype.show_section = function(item) {	if(this.activeItem === item)
		return;	$('div#' + item + '-section').show();
	$('li#' + item + '-menu-item').addClass('active');	$('div#' + this.activeItem + '-section').hide();
	$('li#' + this.activeItem + '-menu-item').removeClass('active');	this.activeItem = item;	$('input#askupa-current-section').val(item);
};

/**
 * 
 * @returns {undefined}
 */
OptionsFramework.prototype.bind_sidebar_events = function() {
	for(var i = 0; i < this.sections.length; i++) {
		var section = this.sections[i];
		var ofw = this;
		var el = 'li#' + section + '-menu-item > a';		$(el).click((function(val) {
			return function() {
				 ofw.show_section(val);
			};
        })(section));		var margin = parseInt($(el).find('i').css('margin-right'));
		$(el).hover(
			function(e) {
				$(this).find('i').stop().animate(
					{'margin-right' : margin + 8},
					250
				);
			},
			function(e) {
				$(this).find('i').stop().animate(
					{'margin-right' : margin},
					250
				);
			}
		);
	}
};

/**
 * 
 * @returns {undefined}
 */
OptionsFramework.prototype.bind_field_events = function() {
	for(var i = 0; i < this.fields.length; i++) {
		var field = $(this.fields[i]);
		var message = 'One or more fields have been changed, you should save your progress';
		var ofw = this;
		field.change(function() {			if($(this).hasClass('askupa-ignore'))
				return;
			
			if(!ofw.settings_changed) {
				ofw.show_feedback(message, 'neutral');
				ofw.settings_changed = true;
			}			if(this.type === 'checkbox') {
				if(this.checked)
					this.value = 1;
				else this.value = 0;
			}
		});
	}
};

/**
 * 
 * @returns {undefined}
 */
OptionsFramework.prototype.bind_footer_events = function() {	$('#askupa-reset-section, #askupa-reset-all').click(function(el) {		message = 'Are you sure you want to ';
		if(this.value === 'Reset Section')
			message += 'reset this section?';
		if(this.value === 'Reset All')
			message += 'reset all sections?';		var submit = confirm(message);
		if(!submit)
			el.preventDefault();
	});
};

/**
 * 
 * @returns {undefined}
 */
OptionsFramework.prototype.init_sliders = function() {
	$('.ui-slider').each(function() {
		var el = $(this);
		var input = el.find('input');
		el.slider({
			value: parseFloat(el.attr('data-value')),
			min: parseFloat(el.attr('data-min')),
			max: parseFloat(el.attr('data-max')),
			step: parseFloat(el.attr('data-step')),
			disabled: parseInt(el.attr('data-disabled')),
			slide: function( event, ui ) {				$(input).val( ui.value ).trigger('change');
			}
		});
	});
};

/**
 * Initiate Switches
 * Initiate all switch elements in the document
 */
OptionsFramework.prototype.init_switches = function() {
	$('.askupa-switch').each(function() {
		var on = $(this).find('.label-on.switch-label');
		var off = $(this).find('.label-off.switch-label');
		var input = $(this).find('input');
		var value = parseInt($(input).val());
		var disabled = $(this).attr('data-disabled');		if(value)
			$(on).addClass('active');
		else $(off).addClass('active');		if(disabled) return;		$(on).click(function() {
			$(this).addClass('active');
			$(off).removeClass('active');
			$(input).val(1).trigger('change');
		});		$(off).click(function() {
			$(this).addClass('active');
			$(on).removeClass('active');
			$(input).val(0).trigger('change');
		});
	});
};

/**
 * 
 * @returns {undefined}
 */
OptionsFramework.prototype.show_submit_feedback = function() {	var feedback = $.parseJSON($('#askupa-submit-feedback').val());
	if(feedback !== null)
		for(var i = 0; i < feedback.length; i++)
			this.show_feedback(feedback[i]['message'], feedback[i]['type']);
};

/**
 * 
 * @param {type} message
 * @param {type} type
 * @returns {undefined}
 */
OptionsFramework.prototype.show_feedback = function(message, type) {
	var button = $('<i></i>').addClass('fa fa-times');
	var el = $('<div></div>').addClass('askupa-feedback ' + type).html(message).append(button);
	$('div#askupa-feedback-wrapper').append(el);
	
	$(el).click(function() {
		$(this).slideUp(300,function() {
			$(this).remove();
		});
	});
};