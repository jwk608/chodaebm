// Init scripts
jQuery(document).ready(function(){
	"use strict";
	
	// Settings and constants
	PASTORE_CHURCH_STORAGE['shortcodes_delimiter'] = ',';		// Delimiter for multiple values
	PASTORE_CHURCH_STORAGE['shortcodes_popup'] = null;		// Popup with current shortcode settings
	PASTORE_CHURCH_STORAGE['shortcodes_current_idx'] = '';	// Current shortcode's index
	PASTORE_CHURCH_STORAGE['shortcodes_tab_clone_tab'] = '<li id="pastore_church_shortcodes_tab_{id}" data-id="{id}"><a href="#pastore_church_shortcodes_tab_{id}_content"><span class="iconadmin-{icon}"></span>{title}</a></li>';
	PASTORE_CHURCH_STORAGE['shortcodes_tab_clone_content'] = '';

	// Shortcode selector - "change" event handler - add selected shortcode in editor
	jQuery('body').on('change', ".sc_selector", function() {
		"use strict";
		PASTORE_CHURCH_STORAGE['shortcodes_current_idx'] = jQuery(this).find(":selected").val();
		if (PASTORE_CHURCH_STORAGE['shortcodes_current_idx'] == '') return;
		var sc = pastore_church_clone_object(PASTORE_CHURCH_SHORTCODES_DATA[PASTORE_CHURCH_STORAGE['shortcodes_current_idx']]);
		var hdr = sc.title;
		var content = "";
		try {
			content = tinyMCE.activeEditor ? tinyMCE.activeEditor.selection.getContent({format : 'raw'}) : jQuery('#wp-content-editor-container textarea').selection();
		} catch(e) {};
		if (content) {
			for (var i in sc.params) {
				if (i == '_content_') {
					sc.params[i].value = content;
					break;
				}
			}
		}
		var html = (!pastore_church_empty(sc.desc) ? '<p>'+sc.desc+'</p>' : '')
			+ pastore_church_shortcodes_prepare_layout(sc);


		// Show Dialog popup
		PASTORE_CHURCH_STORAGE['shortcodes_popup'] = pastore_church_message_dialog(html, hdr,
			function(popup) {
				"use strict";
				pastore_church_options_init(popup);
				popup.find('.pastore_church_options_tab_content').css({
					maxHeight: jQuery(window).height() - 300 + 'px',
					overflow: 'auto'
				});
			},
			function(btn, popup) {
				"use strict";
				if (btn != 1) return;
				var sc = pastore_church_shortcodes_get_code(PASTORE_CHURCH_STORAGE['shortcodes_popup']);
				if (tinyMCE.activeEditor) {
					if ( !tinyMCE.activeEditor.isHidden() )
						tinyMCE.activeEditor.execCommand( 'mceInsertContent', false, sc );
					//else if (typeof wpActiveEditor != 'undefined' && wpActiveEditor != '') {
					//	document.getElementById( wpActiveEditor ).value += sc;
					else
						send_to_editor(sc);
				} else
					send_to_editor(sc);
			});

		// Set first item active
		jQuery(this).get(0).options[0].selected = true;

		// Add new child tab
		PASTORE_CHURCH_STORAGE['shortcodes_popup'].find('.pastore_church_shortcodes_tab').on('tabsbeforeactivate', function (e, ui) {
			if (ui.newTab.data('id')=='add') {
				pastore_church_shortcodes_add_tab(ui.newTab);
				e.stopImmediatePropagation();
				e.preventDefault();
				return false;
			}
		});

		// Delete child tab
		PASTORE_CHURCH_STORAGE['shortcodes_popup'].find('.pastore_church_shortcodes_tab > ul').on('click', '> li+li > a > span', function (e) {
			var tab = jQuery(this).parents('li');
			var idx = tab.data('id');
			if (parseInt(idx) > 1) {
				if (tab.hasClass('ui-state-active')) {
					tab.prev().find('a').trigger('click');
				}
				tab.parents('.pastore_church_shortcodes_tab').find('.pastore_church_options_tab_content').eq(idx).remove();
				tab.remove();
				e.preventDefault();
				return false;
			}
		});

		return false;
	});

});



// Return result code
//------------------------------------------------------------------------------------------
function pastore_church_shortcodes_get_code(popup) {
	PASTORE_CHURCH_STORAGE['sc_custom'] = '';
	
	var sc_name = PASTORE_CHURCH_STORAGE['shortcodes_current_idx'];
	var sc = PASTORE_CHURCH_SHORTCODES_DATA[sc_name];
	var tabs = popup.find('.pastore_church_shortcodes_tab > ul > li');
	var decor = !pastore_church_isset(sc.decorate) || sc.decorate;
	var rez = '[' + sc_name + pastore_church_shortcodes_get_code_from_tab(popup.find('#pastore_church_shortcodes_tab_0_content').eq(0)) + ']'
			// + (decor ? '\n' : '')
			;
	if (pastore_church_isset(sc.children)) {
		if (PASTORE_CHURCH_STORAGE['sc_custom']!='no') {
			var decor2 = !pastore_church_isset(sc.children.decorate) || sc.children.decorate;
			for (var i=0; i<tabs.length; i++) {
				var tab = tabs.eq(i);
				var idx = tab.data('id');
				if (isNaN(idx) || parseInt(idx) < 1) continue;
				var content = popup.find('#pastore_church_shortcodes_tab_' + idx + '_content').eq(0);
				rez += (decor2 ? '\n\t' : '') + '[' + sc.children.name + pastore_church_shortcodes_get_code_from_tab(content) + ']';	// + (decor2 ? '\n' : '');
				if (pastore_church_isset(sc.children.container) && sc.children.container) {
					if (content.find('[data-param="_content_"]').length > 0) {
						rez += 
							//(decor2 ? '\t\t' : '') + 
							content.find('[data-param="_content_"]').val()
							// + (decor2 ? '\n' : '')
							;
					}
					rez += 
						//(decor2 ? '\t' : '') + 
						'[/' + sc.children.name + ']'
						// + (decor ? '\n' : '')
						;
				}
			}
		}
	} else if (pastore_church_isset(sc.container) && sc.container && popup.find('#pastore_church_shortcodes_tab_0_content [data-param="_content_"]').length > 0) {
		rez += 
			//(decor ? '\t' : '') + 
			popup.find('#pastore_church_shortcodes_tab_0_content [data-param="_content_"]').val()
			// + (decor ? '\n' : '')
			;
	}
	if (pastore_church_isset(sc.container) && sc.container || pastore_church_isset(sc.children))
		rez += 
			(pastore_church_isset(sc.children) && decor && PASTORE_CHURCH_STORAGE['sc_custom']!='no' ? '\n' : '')
			+ '[/' + sc_name + ']'
			 //+ (decor ? '\n' : '')
			 ;
	return rez;
}

// Collect all parameters from tab into string
function pastore_church_shortcodes_get_code_from_tab(tab) {
	var rez = ''
	var mainTab = tab.attr('id').indexOf('tab_0') > 0;
	tab.find('[data-param]').each(function () {
		var field = jQuery(this);
		var param = field.data('param');
		if (!field.parents('.pastore_church_options_field').hasClass('pastore_church_options_no_use') && param.substr(0, 1)!='_' && !pastore_church_empty(field.val()) && field.val()!='none' && (field.attr('type') != 'checkbox' || field.get(0).checked)) {
			rez += ' '+param+'="'+pastore_church_shortcodes_prepare_value(field.val())+'"';
		}
		// On main tab detect param "custom"
		if (mainTab && param=='custom') {
			PASTORE_CHURCH_STORAGE['sc_custom'] = field.val();
		}
	});
	// Get additional params for general tab from items tabs
	if (PASTORE_CHURCH_STORAGE['sc_custom']!='no' && mainTab) {
		var sc = PASTORE_CHURCH_SHORTCODES_DATA[PASTORE_CHURCH_STORAGE['shortcodes_current_idx']];
		var sc_name = PASTORE_CHURCH_STORAGE['shortcodes_current_idx'];
		if (sc_name == 'trx_columns' || sc_name == 'trx_skills' || sc_name == 'trx_team' || sc_name == 'trx_price_table') {	// Determine "count" parameter
			var cnt = 0;
			tab.siblings('div').each(function() {
				var item_tab = jQuery(this);
				var merge = parseInt(item_tab.find('[data-param="span"]').val());
				cnt += !isNaN(merge) && merge > 0 ? merge : 1;
			});
			rez += ' count="'+cnt+'"';
		}
	}
	return rez;
}


// Shortcode parameters builder
//-------------------------------------------------------------------------------------------

// Prepare layout from shortcode object (array)
function pastore_church_shortcodes_prepare_layout(field) {
	"use strict";
	// Make params cloneable
	field['params'] = [field['params']];
	if (!pastore_church_empty(field.children)) {
		field.children['params'] = [field.children['params']];
	}
	// Prepare output
	var output = '<div class="pastore_church_shortcodes_body pastore_church_options_body"><form>';
	output += pastore_church_shortcodes_show_tabs(field);
	output += pastore_church_shortcodes_show_field(field, 0);
	if (!pastore_church_empty(field.children)) {
		PASTORE_CHURCH_STORAGE['shortcodes_tab_clone_content'] = pastore_church_shortcodes_show_field(field.children, 1);
		output += PASTORE_CHURCH_STORAGE['shortcodes_tab_clone_content'];
	}
	output += '</div></form></div>';
	return output;
}



// Show tabs
function pastore_church_shortcodes_show_tabs(field) {
	"use strict";
	// html output
	var output = '<div class="pastore_church_shortcodes_tab pastore_church_options_container pastore_church_options_tab">'
		+ '<ul>'
		+ PASTORE_CHURCH_STORAGE['shortcodes_tab_clone_tab'].replace(/{id}/g, 0).replace('{icon}', 'cog').replace('{title}', 'General');
	if (pastore_church_isset(field.children)) {
		for (var i=0; i<field.children.params.length; i++)
			output += PASTORE_CHURCH_STORAGE['shortcodes_tab_clone_tab'].replace(/{id}/g, i+1).replace('{icon}', 'cancel').replace('{title}', field.children.title + ' ' + (i+1));
		output += PASTORE_CHURCH_STORAGE['shortcodes_tab_clone_tab'].replace(/{id}/g, 'add').replace('{icon}', 'list-add').replace('{title}', '');
	}
	output += '</ul>';
	return output;
}

// Add new tab
function pastore_church_shortcodes_add_tab(tab) {
	"use strict";
	var idx = 0;
	tab.siblings().each(function () {
		"use strict";
		var i = parseInt(jQuery(this).data('id'));
		if (i > idx) idx = i;
	});
	idx++;
	tab.before( PASTORE_CHURCH_STORAGE['shortcodes_tab_clone_tab'].replace(/{id}/g, idx).replace('{icon}', 'cancel').replace('{title}', PASTORE_CHURCH_SHORTCODES_DATA[PASTORE_CHURCH_STORAGE['shortcodes_current_idx']].children.title + ' ' + idx) );
	tab.parents('.pastore_church_shortcodes_tab').append(PASTORE_CHURCH_STORAGE['shortcodes_tab_clone_content'].replace(/tab_1_/g, 'tab_' + idx + '_'));
	tab.parents('.pastore_church_shortcodes_tab').tabs('refresh');
	pastore_church_options_init(tab.parents('.pastore_church_shortcodes_tab').find('.pastore_church_options_tab_content').eq(idx));
	tab.prev().find('a').trigger('click');
}



// Show one field layout
function pastore_church_shortcodes_show_field(field, tab_idx) {
	"use strict";
	
	// html output
	var output = '';

	// Parse field params
	for (var clone_num in field['params']) {
		var tab_id = 'tab_' + (parseInt(tab_idx) + parseInt(clone_num));
		output += '<div id="pastore_church_shortcodes_' + tab_id + '_content" class="pastore_church_options_content pastore_church_options_tab_content">';

		for (var param_num in field['params'][clone_num]) {
			
			var param = field['params'][clone_num][param_num];
			var id = tab_id + '_' + param_num;
	
			// Divider after field
			var divider = pastore_church_isset(param['divider']) && param['divider'] ? ' pastore_church_options_divider' : '';
		
			// Setup default parameters
			if (param['type']=='media') {
				if (!pastore_church_isset(param['before'])) param['before'] = {};
				param['before'] = pastore_church_merge_objects({
						'title': 'Choose image',
						'action': 'media_upload',
						'type': 'image',
						'multiple': false,
						'sizes': false,
						'linked_field': '',
						'captions': { 	
							'choose': 'Choose image',
							'update': 'Select image'
							}
					}, param['before']);
				if (!pastore_church_isset(param['after'])) param['after'] = {};
				param['after'] = pastore_church_merge_objects({
						'icon': 'iconadmin-cancel',
						'action': 'media_reset'
					}, param['after']);
			}
			if (param['type']=='color' && (PASTORE_CHURCH_STORAGE['shortcodes_cp']=='tiny' || (pastore_church_isset(param['style']) && param['style']!='wp'))) {
				if (!pastore_church_isset(param['after'])) param['after'] = {};
				param['after'] = pastore_church_merge_objects({
						'icon': 'iconadmin-cancel',
						'action': 'color_reset'
					}, param['after']);
			}
		
			// Buttons before and after field
			var before = '', after = '', buttons_classes = '', rez, rez2, i, key, opt;
			
			if (pastore_church_isset(param['before'])) {
				rez = pastore_church_shortcodes_action_button(param['before'], 'before');
				before = rez[0];
				buttons_classes += rez[1];
			}
			if (pastore_church_isset(param['after'])) {
				rez = pastore_church_shortcodes_action_button(param['after'], 'after');
				after = rez[0];
				buttons_classes += rez[1];
			}
			if (pastore_church_in_array(param['type'], ['list', 'select', 'fonts']) || (param['type']=='socials' && (pastore_church_empty(param['style']) || param['style']=='icons'))) {
				buttons_classes += ' pastore_church_options_button_after_small';
			}

			if (param['type'] != 'hidden') {
				output += '<div class="pastore_church_options_field'
					+ ' pastore_church_options_field_' + (pastore_church_in_array(param['type'], ['list','fonts']) ? 'select' : param['type'])
					+ (pastore_church_in_array(param['type'], ['media', 'fonts', 'list', 'select', 'socials', 'date', 'time']) ? ' pastore_church_options_field_text'  : '')
					+ (param['type']=='socials' && !pastore_church_empty(param['style']) && param['style']=='images' ? ' pastore_church_options_field_images'  : '')
					+ (param['type']=='socials' && (pastore_church_empty(param['style']) || param['style']=='icons') ? ' pastore_church_options_field_icons'  : '')
					+ (pastore_church_isset(param['dir']) && param['dir']=='vertical' ? ' pastore_church_options_vertical' : '')
					+ (!pastore_church_empty(param['multiple']) ? ' pastore_church_options_multiple' : '')
					+ (pastore_church_isset(param['size']) ? ' pastore_church_options_size_'+param['size'] : '')
					+ (pastore_church_isset(param['class']) ? ' ' + param['class'] : '')
					+ divider 
					+ '">' 
					+ "\n"
					+ '<label class="pastore_church_options_field_label" for="' + id + '">' + param['title']
					+ '</label>'
					+ "\n"
					+ '<div class="pastore_church_options_field_content'
					+ buttons_classes
					+ '">'
					+ "\n";
			}
			
			if (!pastore_church_isset(param['value'])) {
				param['value'] = '';
			}
			

			switch ( param['type'] ) {
	
			case 'hidden':
				output += '<input class="pastore_church_options_input pastore_church_options_input_hidden" name="' + id + '" id="' + id + '" type="hidden" value="' + pastore_church_shortcodes_prepare_value(param['value']) + '" data-param="' + pastore_church_shortcodes_prepare_value(param_num) + '" />';
			break;

			case 'date':
				if (pastore_church_isset(param['style']) && param['style']=='inline') {
					output += '<div class="pastore_church_options_input_date"'
						+ ' id="' + id + '_calendar"'
						+ ' data-format="' + (!pastore_church_empty(param['format']) ? param['format'] : 'yy-mm-dd') + '"'
						+ ' data-months="' + (!pastore_church_empty(param['months']) ? max(1, min(3, param['months'])) : 1) + '"'
						+ ' data-linked-field="' + (!pastore_church_empty(data['linked_field']) ? data['linked_field'] : id) + '"'
						+ '></div>'
						+ '<input id="' + id + '"'
							+ ' name="' + id + '"'
							+ ' type="hidden"'
							+ ' value="' + pastore_church_shortcodes_prepare_value(param['value']) + '"'
							+ ' data-param="' + pastore_church_shortcodes_prepare_value(param_num) + '"'
							+ (!pastore_church_empty(param['action']) ? ' onchange="pastore_church_options_action_'+param['action']+'(this);return false;"' : '')
							+ ' />';
				} else {
					output += '<input class="pastore_church_options_input pastore_church_options_input_date' + (!pastore_church_empty(param['mask']) ? ' pastore_church_options_input_masked' : '') + '"'
						+ ' name="' + id + '"'
						+ ' id="' + id + '"'
						+ ' type="text"'
						+ ' value="' + pastore_church_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-format="' + (!pastore_church_empty(param['format']) ? param['format'] : 'yy-mm-dd') + '"'
						+ ' data-months="' + (!pastore_church_empty(param['months']) ? max(1, min(3, param['months'])) : 1) + '"'
						+ ' data-param="' + pastore_church_shortcodes_prepare_value(param_num) + '"'
						+ (!pastore_church_empty(param['action']) ? ' onchange="pastore_church_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />'
						+ before 
						+ after;
				}
			break;

			case 'text':
				output += '<input class="pastore_church_options_input pastore_church_options_input_text' + (!pastore_church_empty(param['mask']) ? ' pastore_church_options_input_masked' : '') + '"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' type="text"'
					+ ' value="' + pastore_church_shortcodes_prepare_value(param['value']) + '"'
					+ (!pastore_church_empty(param['mask']) ? ' data-mask="'+param['mask']+'"' : '') 
					+ ' data-param="' + pastore_church_shortcodes_prepare_value(param_num) + '"'
					+ (!pastore_church_empty(param['action']) ? ' onchange="pastore_church_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />'
				+ before 
				+ after;
			break;
		
			case 'textarea':
				var cols = pastore_church_isset(param['cols']) && param['cols'] > 10 ? param['cols'] : '40';
				var rows = pastore_church_isset(param['rows']) && param['rows'] > 1 ? param['rows'] : '8';
				output += '<textarea class="pastore_church_options_input pastore_church_options_input_textarea"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' cols="' + cols + '"'
					+ ' rows="' + rows + '"'
					+ ' data-param="' + pastore_church_shortcodes_prepare_value(param_num) + '"'
					+ (!pastore_church_empty(param['action']) ? ' onchange="pastore_church_options_action_'+param['action']+'(this);return false;"' : '')
					+ '>'
					+ param['value']
					+ '</textarea>';
			break;

			case 'spinner':
				output += '<input class="pastore_church_options_input pastore_church_options_input_spinner' + (!pastore_church_empty(param['mask']) ? ' pastore_church_options_input_masked' : '') + '"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' type="text"'
					+ ' value="' + pastore_church_shortcodes_prepare_value(param['value']) + '"' 
					+ (!pastore_church_empty(param['mask']) ? ' data-mask="'+param['mask']+'"' : '') 
					+ (pastore_church_isset(param['min']) ? ' data-min="'+param['min']+'"' : '') 
					+ (pastore_church_isset(param['max']) ? ' data-max="'+param['max']+'"' : '') 
					+ (!pastore_church_empty(param['step']) ? ' data-step="'+param['step']+'"' : '') 
					+ ' data-param="' + pastore_church_shortcodes_prepare_value(param_num) + '"'
					+ (!pastore_church_empty(param['action']) ? ' onchange="pastore_church_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />' 
					+ '<span class="pastore_church_options_arrows"><span class="pastore_church_options_arrow_up iconadmin-up-dir"></span><span class="pastore_church_options_arrow_down iconadmin-down-dir"></span></span>';
			break;

			case 'tags':
				var tags = param['value'].split(PASTORE_CHURCH_STORAGE['shortcodes_delimiter']);
				if (tags.length > 0) {
					for (i=0; i<tags.length; i++) {
						if (pastore_church_empty(tags[i])) continue;
						output += '<span class="pastore_church_options_tag iconadmin-cancel">' + tags[i] + '</span>';
					}
				}
				output += '<input class="pastore_church_options_input_tags"'
					+ ' type="text"'
					+ ' value=""'
					+ ' />'
					+ '<input name="' + id + '"'
						+ ' type="hidden"'
						+ ' value="' + pastore_church_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-param="' + pastore_church_shortcodes_prepare_value(param_num) + '"'
						+ (!pastore_church_empty(param['action']) ? ' onchange="pastore_church_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />';
			break;
		
			case "checkbox": 
				output += '<input type="checkbox" class="pastore_church_options_input pastore_church_options_input_checkbox"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' value="true"' 
					+ (param['value'] == 'true' ? ' checked="checked"' : '') 
					+ (!pastore_church_empty(param['disabled']) ? ' readonly="readonly"' : '') 
					+ ' data-param="' + pastore_church_shortcodes_prepare_value(param_num) + '"'
					+ (!pastore_church_empty(param['action']) ? ' onchange="pastore_church_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />'
					+ '<label for="' + id + '" class="' + (!pastore_church_empty(param['disabled']) ? 'pastore_church_options_state_disabled' : '') + (param['value']=='true' ? ' pastore_church_options_state_checked' : '') + '"><span class="pastore_church_options_input_checkbox_image iconadmin-check"></span>' + (!pastore_church_empty(param['label']) ? param['label'] : param['title']) + '</label>';
			break;
		
			case "radio":
				for (key in param['options']) { 
					output += '<span class="pastore_church_options_radioitem"><input class="pastore_church_options_input pastore_church_options_input_radio" type="radio"'
						+ ' name="' + id + '"'
						+ ' value="' + pastore_church_shortcodes_prepare_value(key) + '"'
						+ ' data-value="' + pastore_church_shortcodes_prepare_value(key) + '"'
						+ (param['value'] == key ? ' checked="checked"' : '') 
						+ ' id="' + id + '_' + key + '"'
						+ ' />'
						+ '<label for="' + id + '_' + key + '"' + (param['value'] == key ? ' class="pastore_church_options_state_checked"' : '') + '><span class="pastore_church_options_input_radio_image iconadmin-circle-empty' + (param['value'] == key ? ' iconadmin-dot-circled' : '') + '"></span>' + param['options'][key] + '</label></span>';
				}
				output += '<input type="hidden"'
						+ ' value="' + pastore_church_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-param="' + pastore_church_shortcodes_prepare_value(param_num) + '"'
						+ (!pastore_church_empty(param['action']) ? ' onchange="pastore_church_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />';

			break;
		
			case "switch":
				opt = [];
				i = 0;
				for (key in param['options']) {
					opt[i++] = {'key': key, 'title': param['options'][key]};
					if (i==2) break;
				}
				output += '<input name="' + id + '"'
					+ ' type="hidden"'
					+ ' value="' + pastore_church_shortcodes_prepare_value(pastore_church_empty(param['value']) ? opt[0]['key'] : param['value']) + '"'
					+ ' data-param="' + pastore_church_shortcodes_prepare_value(param_num) + '"'
					+ (!pastore_church_empty(param['action']) ? ' onchange="pastore_church_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />'
					+ '<span class="pastore_church_options_switch' + (param['value']==opt[1]['key'] ? ' pastore_church_options_state_off' : '') + '"><span class="pastore_church_options_switch_inner iconadmin-circle"><span class="pastore_church_options_switch_val1" data-value="' + opt[0]['key'] + '">' + opt[0]['title'] + '</span><span class="pastore_church_options_switch_val2" data-value="' + opt[1]['key'] + '">' + opt[1]['title'] + '</span></span></span>';
			break;

			case 'media':
				output += '<input class="pastore_church_options_input pastore_church_options_input_text pastore_church_options_input_media"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' type="text"'
					+ ' value="' + pastore_church_shortcodes_prepare_value(param['value']) + '"'
					+ (!pastore_church_isset(param['readonly']) || param['readonly'] ? ' readonly="readonly"' : '')
					+ ' data-param="' + pastore_church_shortcodes_prepare_value(param_num) + '"'
					+ (!pastore_church_empty(param['action']) ? ' onchange="pastore_church_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />'
					+ before 
					+ after;
				if (!pastore_church_empty(param['value'])) {
					var fname = pastore_church_get_file_name(param['value']);
					var fext  = pastore_church_get_file_ext(param['value']);
					output += '<a class="pastore_church_options_image_preview" rel="prettyPhoto" target="_blank" href="' + param['value'] + '">' + (fext!='' && pastore_church_in_list('jpg,png,gif', fext, ',') ? '<img src="'+param['value']+'" alt="" />' : '<span>'+fname+'</span>') + '</a>';
				}
			break;
		
			case 'button':
				rez = pastore_church_shortcodes_action_button(param, 'button');
				output += rez[0];
			break;

			case 'range':
				output += '<div class="pastore_church_options_input_range" data-step="'+(!pastore_church_empty(param['step']) ? param['step'] : 1) + '">'
					+ '<span class="pastore_church_options_range_scale"><span class="pastore_church_options_range_scale_filled"></span></span>';
				if (param['value'].toString().indexOf(PASTORE_CHURCH_STORAGE['shortcodes_delimiter']) == -1)
					param['value'] = Math.min(param['max'], Math.max(param['min'], param['value']));
				var sliders = param['value'].toString().split(PASTORE_CHURCH_STORAGE['shortcodes_delimiter']);
				for (i=0; i<sliders.length; i++) {
					output += '<span class="pastore_church_options_range_slider"><span class="pastore_church_options_range_slider_value">' + sliders[i] + '</span><span class="pastore_church_options_range_slider_button"></span></span>';
				}
				output += '<span class="pastore_church_options_range_min">' + param['min'] + '</span><span class="pastore_church_options_range_max">' + param['max'] + '</span>'
					+ '<input name="' + id + '"'
						+ ' type="hidden"'
						+ ' value="' + pastore_church_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-param="' + pastore_church_shortcodes_prepare_value(param_num) + '"'
						+ (!pastore_church_empty(param['action']) ? ' onchange="pastore_church_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />'
					+ '</div>';			
			break;
		
			case "checklist":
				for (key in param['options']) { 
					output += '<span class="pastore_church_options_listitem'
						+ (pastore_church_in_list(param['value'], key, PASTORE_CHURCH_STORAGE['shortcodes_delimiter']) ? ' pastore_church_options_state_checked' : '') + '"'
						+ ' data-value="' + pastore_church_shortcodes_prepare_value(key) + '"'
						+ '>'
						+ param['options'][key]
						+ '</span>';
				}
				output += '<input name="' + id + '"'
					+ ' type="hidden"'
					+ ' value="' + pastore_church_shortcodes_prepare_value(param['value']) + '"'
					+ ' data-param="' + pastore_church_shortcodes_prepare_value(param_num) + '"'
					+ (!pastore_church_empty(param['action']) ? ' onchange="pastore_church_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />';
			break;
		
			case 'fonts':
				for (key in param['options']) {
					param['options'][key] = key;
				}
			case 'list':
			case 'select':
				if (!pastore_church_isset(param['options']) && !pastore_church_empty(param['from']) && !pastore_church_empty(param['to'])) {
					param['options'] = [];
					for (i = param['from']; i <= param['to']; i+=(!pastore_church_empty(param['step']) ? param['step'] : 1)) {
						param['options'][i] = i;
					}
				}
				rez = pastore_church_shortcodes_menu_list(param);
				if (pastore_church_empty(param['style']) || param['style']=='select') {
					output += '<input class="pastore_church_options_input pastore_church_options_input_select" type="text" value="' + pastore_church_shortcodes_prepare_value(rez[1]) + '"'
						+ ' readonly="readonly"'
						//+ (!pastore_church_empty(param['mask']) ? ' data-mask="'+param['mask']+'"' : '') 
						+ ' />'
						+ '<span class="pastore_church_options_field_after pastore_church_options_with_action iconadmin-down-open" onchange="pastore_church_options_action_show_menu(this);return false;"></span>';
				}
				output += rez[0]
					+ '<input name="' + id + '"'
						+ ' type="hidden"'
						+ ' value="' + pastore_church_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-param="' + pastore_church_shortcodes_prepare_value(param_num) + '"'
						+ (!pastore_church_empty(param['action']) ? ' onchange="pastore_church_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />';
			break;

			case 'images':
				rez = pastore_church_shortcodes_menu_list(param);
				if (pastore_church_empty(param['style']) || param['style']=='select') {
					output += '<div class="pastore_church_options_caption_image iconadmin-down-open">'
						//+'<img src="' + rez[1] + '" alt="" />'
						+'<span style="background-image: url(' + rez[1] + ')"></span>'
						+'</div>';
				}
				output += rez[0]
					+ '<input name="' + id + '"'
						+ ' type="hidden"'
						+ ' value="' + pastore_church_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-param="' + pastore_church_shortcodes_prepare_value(param_num) + '"'
						+ (!pastore_church_empty(param['action']) ? ' onchange="pastore_church_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />';
			break;
		
			case 'icons':
				rez = pastore_church_shortcodes_menu_list(param);
				if (pastore_church_empty(param['style']) || param['style']=='select') {
					output += '<div class="pastore_church_options_caption_icon iconadmin-down-open"><span class="' + rez[1] + '"></span></div>';
				}
				output += rez[0]
					+ '<input name="' + id + '"'
						+ ' type="hidden"'
						+ ' value="' + pastore_church_shortcodes_prepare_value(param['value']) + '"'
						+ ' data-param="' + pastore_church_shortcodes_prepare_value(param_num) + '"'
						+ (!pastore_church_empty(param['action']) ? ' onchange="pastore_church_options_action_'+param['action']+'(this);return false;"' : '')
						+ ' />';
			break;

			case 'socials':
				if (!pastore_church_is_object(param['value'])) param['value'] = {'url': '', 'icon': ''};
				rez = pastore_church_shortcodes_menu_list(param);
				if (pastore_church_empty(param['style']) || param['style']=='icons') {
					rez2 = pastore_church_shortcodes_action_button({
						'action': pastore_church_empty(param['style']) || param['style']=='icons' ? 'select_icon' : '',
						'icon': (pastore_church_empty(param['style']) || param['style']=='icons') && !pastore_church_empty(param['value']['icon']) ? param['value']['icon'] : 'iconadmin-users'
						}, 'after');
				} else
					rez2 = ['', ''];
				output += '<input class="pastore_church_options_input pastore_church_options_input_text pastore_church_options_input_socials' 
					+ (!pastore_church_empty(param['mask']) ? ' pastore_church_options_input_masked' : '') + '"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' type="text" value="' + pastore_church_shortcodes_prepare_value(param['value']['url']) + '"' 
					+ (!pastore_church_empty(param['mask']) ? ' data-mask="'+param['mask']+'"' : '') 
					+ ' data-param="' + pastore_church_shortcodes_prepare_value(param_num) + '"'
					+ (!pastore_church_empty(param['action']) ? ' onchange="pastore_church_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />'
					+ rez2[0];
				if (!pastore_church_empty(param['style']) && param['style']=='images') {
					output += '<div class="pastore_church_options_caption_image iconadmin-down-open">'
						//+'<img src="' + rez[1] + '" alt="" />'
						+'<span style="background-image: url(' + rez[1] + ')"></span>'
						+'</div>';
				}
				output += rez[0]
					+ '<input name="' + id + '_icon' + '" type="hidden" value="' + pastore_church_shortcodes_prepare_value(param['value']['icon']) + '" />';
			break;

			case "color":
				var cp_style = pastore_church_isset(param['style']) ? param['style'] : PASTORE_CHURCH_STORAGE['shortcodes_cp'];
				output += '<input class="pastore_church_options_input pastore_church_options_input_color pastore_church_options_input_color_'+cp_style +'"'
					+ ' name="' + id + '"'
					+ ' id="' + id + '"'
					+ ' data-param="' + pastore_church_shortcodes_prepare_value(param_num) + '"'
					+ ' type="text"'
					+ ' value="' + pastore_church_shortcodes_prepare_value(param['value']) + '"'
					+ (!pastore_church_empty(param['action']) ? ' onchange="pastore_church_options_action_'+param['action']+'(this);return false;"' : '')
					+ ' />'
					+ before;
				if (cp_style=='custom')
					output += '<span class="pastore_church_options_input_colorpicker iColorPicker"></span>';
				else if (cp_style=='tiny')
					output += after;
			break;   
	
			}

			if (param['type'] != 'hidden') {
				output += '</div>';
				if (!pastore_church_empty(param['desc']))
					output += '<div class="pastore_church_options_desc">' + param['desc'] + '</div>' + "\n";
				output += '</div>' + "\n";
			}

		}

		output += '</div>';
	}

	
	return output;
}



// Return menu items list (menu, images or icons)
function pastore_church_shortcodes_menu_list(field) {
	"use strict";
	if (field['type'] == 'socials') field['value'] = field['value']['icon'];
	var list = '<div class="pastore_church_options_input_menu ' + (pastore_church_empty(field['style']) ? '' : ' pastore_church_options_input_menu_' + field['style']) + '">';
	var caption = '';
	for (var key in field['options']) {
		var value = field['options'][key];
		if (pastore_church_in_array(field['type'], ['list', 'icons', 'socials'])) key = value;
		var selected = '';
		if (pastore_church_in_list(field['value'], key, PASTORE_CHURCH_STORAGE['shortcodes_delimiter'])) {
			caption = value;
			selected = ' pastore_church_options_state_checked';
		}
		list += '<span class="pastore_church_options_menuitem' 
			+ selected 
			+ '" data-value="' + pastore_church_shortcodes_prepare_value(key) + '"'
			+ '>';
		if (pastore_church_in_array(field['type'], ['list', 'select', 'fonts']))
			list += value;
		else if (field['type'] == 'icons' || (field['type'] == 'socials' && field['style'] == 'icons'))
			list += '<span class="' + value + '"></span>';
		else if (field['type'] == 'images' || (field['type'] == 'socials' && field['style'] == 'images'))
			//list += '<img src="' + value + '" data-icon="' + key + '" alt="" class="pastore_church_options_input_image" />';
			list += '<span style="background-image:url(' + value + ')" data-src="' + value + '" data-icon="' + key + '" class="pastore_church_options_input_image"></span>';
		list += '</span>';
	}
	list += '</div>';
	return [list, caption];
}



// Return action button
function pastore_church_shortcodes_action_button(data, type) {
	"use strict";
	var class_name = ' pastore_church_options_button_' + type + (pastore_church_empty(data['title']) ? ' pastore_church_options_button_'+type+'_small' : '');
	var output = '<span class="' 
				+ (type == 'button' ? 'pastore_church_options_input_button'  : 'pastore_church_options_field_'+type)
				+ (!pastore_church_empty(data['action']) ? ' pastore_church_options_with_action' : '')
				+ (!pastore_church_empty(data['icon']) ? ' '+data['icon'] : '')
				+ '"'
				+ (!pastore_church_empty(data['icon']) && !pastore_church_empty(data['title']) ? ' title="'+pastore_church_shortcodes_prepare_value(data['title'])+'"' : '')
				+ (!pastore_church_empty(data['action']) ? ' onclick="pastore_church_options_action_'+data['action']+'(this);return false;"' : '')
				+ (!pastore_church_empty(data['type']) ? ' data-type="'+data['type']+'"' : '')
				+ (!pastore_church_empty(data['multiple']) ? ' data-multiple="'+data['multiple']+'"' : '')
				+ (!pastore_church_empty(data['sizes']) ? ' data-sizes="'+data['sizes']+'"' : '')
				+ (!pastore_church_empty(data['linked_field']) ? ' data-linked-field="'+data['linked_field']+'"' : '')
				+ (!pastore_church_empty(data['captions']) && !pastore_church_empty(data['captions']['choose']) ? ' data-caption-choose="'+pastore_church_shortcodes_prepare_value(data['captions']['choose'])+'"' : '')
				+ (!pastore_church_empty(data['captions']) && !pastore_church_empty(data['captions']['update']) ? ' data-caption-update="'+pastore_church_shortcodes_prepare_value(data['captions']['update'])+'"' : '')
				+ '>'
				+ (type == 'button' || (pastore_church_empty(data['icon']) && !pastore_church_empty(data['title'])) ? data['title'] : '')
				+ '</span>';
	return [output, class_name];
}

// Prepare string to insert as parameter's value
function pastore_church_shortcodes_prepare_value(val) {
	return typeof val == 'string' ? val.replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/'/g, '&#039;').replace(/</g, '&lt;').replace(/>/g, '&gt;') : val;
}
