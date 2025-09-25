<?php
/**
 * @author      Elicus <hello@elicus.com>
 * @link        https://www.elicus.com/
 * @copyright   2023 Elicus Technologies Private Limited
 * @version     1.0.2
 */
class EL_GravityFormsStyler extends ET_Builder_Module {

	public $slug       = 'el_gravity_forms_styler';
	public $vb_support = 'on';

	protected $module_credits = array(
		'module_uri' => 'https://diviextended.com/product/gravity-forms-styler-for-divi/',
		'author'     => 'Elicus',
		'author_uri' => 'https://elicus.com/',
	);

	public function init() {
		$this->name             = esc_html__( 'Gravity Forms Styler', 'divi-extended-gravity-forms-styler' );
		$this->main_css_element = '%%order_class%%';

		if ( class_exists( 'GFForms' ) ) {
            wp_enqueue_style( 'gforms_reset_css' );
            wp_enqueue_style( 'gforms_formsmain_css' );
            wp_enqueue_style( 'gforms_ready_class_css' );
            wp_enqueue_style( 'gforms_browsers_css' );
            wp_enqueue_style( 'gform_layout' );
            wp_enqueue_style( 'gform_theme_ie11' );
            wp_enqueue_style( 'gform_basic' );
            wp_enqueue_style( 'gform_theme' );
            if ( is_rtl() ) {
                wp_enqueue_style( 'gforms_rtl_css' );
            }
        }
	}

	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'main_content' => esc_html__( 'Form', 'divi-extended-gravity-forms-styler' ),
				),
			),
			'advanced' => array(
				'toggles' => array(
					'text'            => esc_html__( 'Alignment', 'divi-extended-gravity-forms-styler' ),
					'title'           => esc_html__( 'Form Title', 'divi-extended-gravity-forms-styler' ),
					'description'     => esc_html__( 'Form Description', 'divi-extended-gravity-forms-styler' ),
					'labels'          => esc_html__( 'Labels', 'divi-extended-gravity-forms-styler' ),
					'input_fields'  => array(
						'title'    => esc_html__( 'Input Fields', 'divi-extended-gravity-forms-styler' ),
						'sub_toggles' => array(
                            'input' => array(
                                'name' => esc_html__( 'Input', 'divi-extended-gravity-forms-styler' ),
                            ),
                            'placeholder' => array(
                                'name' => esc_html__( 'Placeholder', 'divi-extended-gravity-forms-styler' ),
                            ),
                            'input_description' => array(
                                'name' => esc_html__( 'Description', 'divi-extended-gravity-forms-styler' ),
                            ), 
                        ),
                        'tabbed_subtoggles' => true,
					),
					'required'    => esc_html__( 'Required Text', 'divi-extended-gravity-forms-styler' ),
					'instructions'    => esc_html__( 'Field Instructions Text', 'divi-extended-gravity-forms-styler' ),
					'radio_fields'    => esc_html__( 'Radio Fields', 'divi-extended-gravity-forms-styler' ),
					'checkbox_fields' => esc_html__( 'Checkbox Fields', 'divi-extended-gravity-forms-styler' ),
					'dropdown_fields'  => array(
						'title'    => esc_html__( 'Dropdown Fields', 'divi-extended-gravity-forms-styler' ),
						'sub_toggles' => array(
                            'selected' => array(
                                'name' => esc_html__( 'Selected', 'divi-extended-gravity-forms-styler' ),
                            ),
                            'dropdown_option' => array(
                                'name' => esc_html__( 'Options', 'divi-extended-gravity-forms-styler' ),
                            ),
                        ),
                        'tabbed_subtoggles' => true,
					),
					'textarea_fields' => esc_html__( 'Textarea Fields', 'divi-extended-gravity-forms-styler' ),
					'upload_fields'   => esc_html__( 'Upload Fields', 'divi-extended-gravity-forms-styler' ),
					'button_fields'   => esc_html__( 'Next/Previous Buttons', 'divi-extended-gravity-forms-styler' ),
					'submit_button'   => esc_html__( 'Submit Button', 'divi-extended-gravity-forms-styler' ),
					'save_continue_button'    => esc_html__( 'Save and Continue Button', 'divi-extended-gravity-forms-styler' ),
					'success_error'  => array(
						'title'    => esc_html__( 'Success/Error Message', 'divi-extended-gravity-forms-styler' ),
						'sub_toggles' => array(
                            'success_message' => array(
                                'name' => esc_html__( 'Success', 'divi-extended-gravity-forms-styler' ),
                            ),
                            'error_message' => array(
                                'name' => esc_html__( 'Error', 'divi-extended-gravity-forms-styler' ),
                            ),
                        ),
                        'tabbed_subtoggles' => true,
					),
				),
			),
		);
	}

	public function get_advanced_fields_config() {

		$input_fields = array(
			$this->main_css_element . ' .el_gravity_forms_styler_wrapper form input[type="text"]',
			$this->main_css_element . ' .el_gravity_forms_styler_wrapper form input[type="email"]',
			$this->main_css_element . ' .el_gravity_forms_styler_wrapper form input[type="password"]',
			$this->main_css_element . ' .el_gravity_forms_styler_wrapper form input[type="tel"]',
			$this->main_css_element . ' .el_gravity_forms_styler_wrapper form input[type="url"]',
			$this->main_css_element . ' .el_gravity_forms_styler_wrapper form input[type="time"]',
			$this->main_css_element . ' .el_gravity_forms_styler_wrapper form input[type="week"]',
			$this->main_css_element . ' .el_gravity_forms_styler_wrapper form input[type="month"]',
			$this->main_css_element . ' .el_gravity_forms_styler_wrapper form input[type="datetime-local"]',
			$this->main_css_element . ' .el_gravity_forms_styler_wrapper form input[type="number"]',
			$this->main_css_element . ' .el_gravity_forms_styler_wrapper form input[type="date"]',
			$this->main_css_element . ' .el_gravity_forms_styler_wrapper form input[type="file"]', 
			$this->main_css_element . ' .el_gravity_forms_styler_wrapper form select',
			$this->main_css_element . ' .el_gravity_forms_styler_wrapper form textarea',
		);
		
		$description 			= $this->main_css_element . ' .el_gravity_forms_styler_wrapper .gform_description';
		$input_description      = $this->main_css_element . ' .el_gravity_forms_styler_wrapper form .gfield_description';

		$radio_field            		= $this->main_css_element . ' .el_gravity_forms_styler_wrapper form input[type="radio"]';
		$checkbox_field         		= $this->main_css_element . ' .el_gravity_forms_styler_wrapper form input[type="checkbox"]';
		$radio_checked_field    		= $this->main_css_element . ' .el_gravity_forms_styler_wrapper form input[type="radio"]:checked::before';
		$checkbox_checked_field 		= $this->main_css_element . ' .el_gravity_forms_styler_wrapper form input[type="checkbox"]:checked::before';
		$upload_field               	= $this->main_css_element . ' .el_gravity_forms_styler_wrapper form input[type="file"]';

		$button_field        = "{$this->main_css_element} .el_gravity_forms_styler_wrapper form .gform_previous_button.button, {$this->main_css_element} .el_gravity_forms_styler_wrapper form .gform_next_button.button";
		$submit_button_field = "{$this->main_css_element} .el_gravity_forms_styler_wrapper form input[type='submit'], {$this->main_css_element} .el_gravity_forms_styler_wrapper form button[type='submit'], {$this->main_css_element} .el_gravity_forms_styler_wrapper .form_saved_message_emailform input[type='submit']";
		$save_continue_button_field  = "{$this->main_css_element} .el_gravity_forms_styler_wrapper form .gform_save_link.button";
		$success = "{$this->main_css_element} .el_gravity_forms_styler_wrapper .gform_confirmation_wrapper .gform_confirmation_message";
		$error = "{$this->main_css_element} .el_gravity_forms_styler_wrapper form .gfield_validation_message, {$this->main_css_element} .el_gravity_forms_styler_wrapper .gform_validation_errors .gform_submission_error, {$this->main_css_element} .el_gravity_forms_styler_wrapper .gform_validation_errors";

		$input_fields_placeholder        = preg_filter( '/$/', '::placeholder', $input_fields );
		$input_fields_webkit_placeholder = preg_filter( '/$/', '::-webkit-input-placeholder', $input_fields );
		$input_fields_moz_placeholder    = preg_filter( '/$/', '::-moz-placeholder', $input_fields );
		$input_fields_ms_placeholder     = preg_filter( '/$/', '::-ms-input-placeholder', $input_fields );
		$input_fields_placeholder        = array_merge(
			$input_fields_webkit_placeholder,
			$input_fields_moz_placeholder,
			$input_fields_ms_placeholder,
			$input_fields_placeholder
		);

		$input_fields_focus = preg_filter( '/$/', ':focus', $input_fields );

		$textarea = "{$this->main_css_element} .el_gravity_forms_styler_wrapper form .gfield textarea.large, {$this->main_css_element} .el_gravity_forms_styler_wrapper form .gfield textarea.medium, {$this->main_css_element} .el_gravity_forms_styler_wrapper form .gfield textarea.small";
		$fieldset = "{$this->main_css_element} .el_gravity_forms_styler_wrapper form .gfield";

		return array(
			'fonts'               => array(
				'title'              => array(
					'label'           => esc_html__( 'Form Title', 'divi-extended-gravity-forms-styler' ),
					'font_size'       => array(
						'label'          => esc_html__( 'Title Text Font Size', 'divi-extended-gravity-forms-styler' ),
						'default'        => '16px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'line_height'     => array(
						'default'        => '1.5em',
						'range_settings' => array(
							'min'  => '0.1',
							'max'  => '10',
							'step' => '0.1',
						),
					),
					'letter_spacing'  => array(
						'default'        => '0px',
						'range_settings' => array(
							'min'  => '0',
							'max'  => '10',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'text_color'      => array(
						'label' => esc_html__( 'Title Text Color', 'divi-extended-gravity-forms-styler' ),
					),
					'hide_text_align' => true,
					'css'             => array(
						'main'  => "{$this->main_css_element} .el_gravity_forms_styler_wrapper .gform_title",
						'hover' => "{$this->main_css_element} .el_gravity_forms_styler_wrapper .gform_title:hover",
						'important' => 'all',
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'title',
				),
				'description'              => array(
					'label'           => esc_html__( 'Form Description', 'divi-extended-gravity-forms-styler' ),
					'font_size'       => array(
						'label'          => esc_html__( 'Description Text Font Size', 'divi-extended-gravity-forms-styler' ),
						'default'        => '16px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'line_height'     => array(
						'default'        => '1.5em',
						'range_settings' => array(
							'min'  => '0.1',
							'max'  => '10',
							'step' => '0.1',
						),
					),
					'letter_spacing'  => array(
						'default'        => '0px',
						'range_settings' => array(
							'min'  => '0',
							'max'  => '10',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'text_color'      => array(
						'label' => esc_html__( 'Description Text Color', 'divi-extended-gravity-forms-styler' ),
					),
					'hide_text_align' => true,
					'css'             => array(
						'main'  => "{$this->main_css_element} .el_gravity_forms_styler_wrapper .gform_description",
						'hover' => "{$this->main_css_element} .el_gravity_forms_styler_wrapper .gform_description:hover",
						'important' => 'all',
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'description',
				),
				'label'              => array(
					'label'           => esc_html__( 'Label Text', 'divi-extended-gravity-forms-styler' ),
					'font_size'       => array(
						'label'          => esc_html__( 'Label Text Font Size', 'divi-extended-gravity-forms-styler' ),
						'default'        => '16px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'line_height'     => array(
						'default'        => '1.5em',
						'range_settings' => array(
							'min'  => '0.1',
							'max'  => '10',
							'step' => '0.1',
						),
					),
					'letter_spacing'  => array(
						'default'        => '0px',
						'range_settings' => array(
							'min'  => '0',
							'max'  => '10',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'text_color'      => array(
						'label' => esc_html__( 'Label Text Color', 'divi-extended-gravity-forms-styler' ),
					),
					'hide_text_align' => true,
					'css'             => array(
						'main'  => "{$this->main_css_element} form label, {$this->main_css_element} form .gfield_label, {$this->main_css_element} form .gsection_title",
						'hover' => "{$this->main_css_element} form label:hover, {$this->main_css_element} form .gfield_label:hover, {$this->main_css_element} form .gsection_title:hover",
						'important' => 'all',
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'labels',
				),
				'required'              => array(
					'label'           => esc_html__( 'Required Text', 'divi-extended-gravity-forms-styler' ),
					'font_size'       => array(
						'label'          => esc_html__( 'Required Text Font Size', 'divi-extended-gravity-forms-styler' ),
						'default'        => '16px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'line_height'     => array(
						'default'        => '1.5em',
						'range_settings' => array(
							'min'  => '0.1',
							'max'  => '10',
							'step' => '0.1',
						),
					),
					'letter_spacing'  => array(
						'default'        => '0px',
						'range_settings' => array(
							'min'  => '0',
							'max'  => '10',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'text_color'      => array(
						'label' => esc_html__( 'Required Text Color', 'divi-extended-gravity-forms-styler' ),
					),
					'hide_text_align' => true,
					'css'             => array(
						'main'  => "{$this->main_css_element} .gfield_required_asterisk, {$this->main_css_element} .gform_required_legend, {$this->main_css_element} .gfield_required_text, {$this->main_css_element} .gfield_required_custom",
						'hover' => "{$this->main_css_element} form label:hover .gfield_required_asterisk, {$this->main_css_element} .gform_required_legend:hover, {$this->main_css_element} form label:hover .gfield_required_text, {$this->main_css_element} form label:hover .gfield_required_custom",
						'important' => 'all',
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'required',
				),
				'input'              => array(
					'label'           => esc_html__( 'Input Text', 'divi-extended-gravity-forms-styler' ),
					'font_size'       => array(
						'label'          => esc_html__( 'Input Text Font Size', 'divi-extended-gravity-forms-styler' ),
						'default'        => '16px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'line_height'     => array(
						'default'        => '1.5em',
						'range_settings' => array(
							'min'  => '0.1',
							'max'  => '10',
							'step' => '0.1',
						),
					),
					'letter_spacing'  => array(
						'default'        => '0px',
						'range_settings' => array(
							'min'  => '0',
							'max'  => '10',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'text_align'      => array(
						'label'   => esc_html__( 'Input Text Alignment', 'divi-extended-gravity-forms-styler' ),
						'default' => 'left',
					),
					'hide_text_color' => true,
					'css'             => array(
						'main' => implode( ', ', $input_fields ),
						'important' => 'all',
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'input_fields',
					'sub_toggle'	  => 'input',
				),
				'placeholder'        => array(
					'label'           => esc_html__( 'Placeholder Text', 'divi-extended-gravity-forms-styler' ),
					'font_size'       => array(
						'label'          => esc_html__( 'Placeholder Text Font Size', 'divi-extended-gravity-forms-styler' ),
						'default'        => '16px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'line_height'     => array(
						'default'        => '1.5em',
						'range_settings' => array(
							'min'  => '0.1',
							'max'  => '10',
							'step' => '0.1',
						),
					),
					'letter_spacing'  => array(
						'default'        => '0px',
						'range_settings' => array(
							'min'  => '0',
							'max'  => '10',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'text_align'      => array(
						'label'   => esc_html__( 'Placeholder Text Alignment', 'divi-extended-gravity-forms-styler' ),
						'default' => 'left',
					),
					'hide_text_color' => true,
					'css'             => array(
						'main'      => implode( ', ', $input_fields_placeholder ),
						'important' => 'all',
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'input_fields',
					'sub_toggle'	  => 'placeholder',
				),
				'input_description'              => array(
					'label'           => esc_html__( 'Input Description', 'divi-extended-gravity-forms-styler' ),
					'font_size'       => array(
						'label'          => esc_html__( 'Input Description Font Size', 'divi-extended-gravity-forms-styler' ),
						'default'        => '16px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'line_height'     => array(
						'default'        => '1.5em',
						'range_settings' => array(
							'min'  => '0.1',
							'max'  => '10',
							'step' => '0.1',
						),
					),
					'letter_spacing'  => array(
						'default'        => '0px',
						'range_settings' => array(
							'min'  => '0',
							'max'  => '10',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'text_align'      => array(
						'label'   => esc_html__( 'Input Description Alignment', 'divi-extended-gravity-forms-styler' ),
						'default' => 'left',
					),
					'css'             => array(
						'main' => $input_description,
						'important' => 'all',
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'input_fields',
					'sub_toggle'	  => 'input_description',
				),
				'instructions'              => array(
					'label'           => esc_html__( 'Field Instructions Text', 'divi-extended-gravity-forms-styler' ),
					'font_size'       => array(
						'label'          => esc_html__( 'Field Instructions Text Font Size', 'divi-extended-gravity-forms-styler' ),
						'default'        => '16px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'line_height'     => array(
						'default'        => '1.5em',
						'range_settings' => array(
							'min'  => '0.1',
							'max'  => '10',
							'step' => '0.1',
						),
					),
					'letter_spacing'  => array(
						'default'        => '0px',
						'range_settings' => array(
							'min'  => '0',
							'max'  => '10',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'text_color'      => array(
						'label' => esc_html__( 'Field Instructions Text Color', 'divi-extended-gravity-forms-styler' ),
					),
					'hide_text_align' => true,
					'css'             => array(
						'main'  => "{$this->main_css_element} form .warningTextareaInfo, {$this->main_css_element} form .charleft.ginput_counter, {$this->main_css_element} .form_saved_message p, {$this->main_css_element} form .gform_fileupload_rules, {$this->main_css_element} form .instruction, {$this->main_css_element} form .gsection_description",
						'hover' => "{$this->main_css_element} form .warningTextareaInfo:hover, {$this->main_css_element} .form_saved_message p:hover, {$this->main_css_element} form .gform_fileupload_rules:hover, {$this->main_css_element} form .instruction:hover, {$this->main_css_element} form .gsection_description:hover",
						'important' => 'all',
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'instructions',
				),
				'radio_label'        => array(
					'label'           => esc_html__( 'Radio Label Text', 'divi-extended-gravity-forms-styler' ),
					'font_size'       => array(
						'label'          => esc_html__( 'Radio Label Text Font Size', 'divi-extended-gravity-forms-styler' ),
						'default'        => '16px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'line_height'     => array(
						'default'        => '1.5em',
						'range_settings' => array(
							'min'  => '0.1',
							'max'  => '10',
							'step' => '0.1',
						),
					),
					'letter_spacing'  => array(
						'default'        => '0px',
						'range_settings' => array(
							'min'  => '0',
							'max'  => '10',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'hide_text_align' => true,
					'text_color'      => array(
						'label' => esc_html__( 'Radio Label Text Color', 'divi-extended-gravity-forms-styler' ),
					),
					'css'             => array(
						'main'  => "{$this->main_css_element} form .gfield_radio label",
						'hover' => "{$this->main_css_element} form .gfield_radio label:hover",
						'important' => 'all',
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'radio_fields',
				),
				'checkbox_label'     => array(
					'label'           => esc_html__( 'Checkbox Label Text', 'divi-extended-gravity-forms-styler' ),
					'font_size'       => array(
						'label'          => esc_html__( 'Checkbox Label Text Font Size', 'divi-extended-gravity-forms-styler' ),
						'default'        => '16px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'line_height'     => array(
						'default'        => '1.5em',
						'range_settings' => array(
							'min'  => '0.1',
							'max'  => '10',
							'step' => '0.1',
						),
					),
					'letter_spacing'  => array(
						'default'        => '0px',
						'range_settings' => array(
							'min'  => '0',
							'max'  => '10',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'hide_text_align' => true,
					'text_color'      => array(
						'label' => esc_html__( 'Checkbox Label Text Color', 'divi-extended-gravity-forms-styler' ),
					),
					'css'             => array(
						'main'  => "{$this->main_css_element} form .gfield_checkbox label",
						'hover' => "{$this->main_css_element} form .gfield_checkbox label:hover",
						'important' => 'all',
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'checkbox_fields',
				),
				'dropdown_selected'     => array(
					'label'           => esc_html__( 'Dropdown Selected Text', 'divi-extended-gravity-forms-styler' ),
					'font_size'       => array(
						'label'          => esc_html__( 'Dropdown Selected Text Font Size', 'divi-extended-gravity-forms-styler' ),
						'default'        => '16px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'line_height'     => array(
						'default'        => '1.5em',
						'range_settings' => array(
							'min'  => '0.1',
							'max'  => '10',
							'step' => '0.1',
						),
					),
					'letter_spacing'  => array(
						'default'        => '0px',
						'range_settings' => array(
							'min'  => '0',
							'max'  => '10',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'hide_text_align' => true,
					'text_color'      => array(
						'label' => esc_html__( 'Dropdown Selected Text Color', 'divi-extended-gravity-forms-styler' ),
					),
					'css'             => array(
						'main'  => "{$this->main_css_element} form select, {$this->main_css_element} form .gfield_select",
						'important' => 'all',
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'dropdown_fields',
					'sub_toggle'	  => 'selected',
				),
				'dropdown_option'        => array(
					'label'           => esc_html__( 'Option Text', 'divi-extended-gravity-forms-styler' ),
					'font_size'       => array(
						'label'          => esc_html__( 'Option Text Font Size', 'divi-extended-gravity-forms-styler' ),
						'default'        => '16px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'line_height'     => array(
						'default'        => '1.5em',
						'range_settings' => array(
							'min'  => '0.1',
							'max'  => '10',
							'step' => '0.1',
						),
					),
					'letter_spacing'  => array(
						'default'        => '0px',
						'range_settings' => array(
							'min'  => '0',
							'max'  => '10',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'text_align'      => array(
						'label'   => esc_html__( 'Option Text Alignment', 'divi-extended-gravity-forms-styler' ),
						'default' => 'left',
					),
					'text_color'      => array(
						'label' => esc_html__( 'Option Text Color', 'divi-extended-gravity-forms-styler' ),
					),
					'css'             => array(
						'main'      => "{$this->main_css_element} form select option, {$this->main_css_element} form .gfield_select option",
						'important' => 'all',
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'dropdown_fields',
					'sub_toggle'	  => 'dropdown_option',
				),
				'upload_text'        => array(
					'label'            => esc_html__( 'Upload Text', 'divi-extended-gravity-forms-styler' ),
					'font_size'        => array(
						'label'          => esc_html__( 'Upload Text Font Size', 'divi-extended-gravity-forms-styler' ),
						'default'        => '16px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'line_height'      => array(
						'default'        => '1.5em',
						'range_settings' => array(
							'min'  => '0.1',
							'max'  => '10',
							'step' => '0.1',
						),
					),
					'letter_spacing'   => array(
						'default'        => '0px',
						'range_settings' => array(
							'min'  => '0',
							'max'  => '10',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'hide_text_align'  => true,
					'hide_text_shadow' => true,
					'use_all_caps'     => false,
					'text_color'       => array(
						'label' => esc_html__( 'Upload Text Color', 'divi-extended-gravity-forms-styler' ),
					),
					'css'              => array(
						'main' => $upload_field,
						'important' => 'all',
					),
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'upload_fields',
				),
				'submit_button_text' => array(
					'label'           => esc_html__( 'Button Text', 'divi-extended-gravity-forms-styler' ),
					'font_size'       => array(
						'label'          => esc_html__( 'Button Text Font Size', 'divi-extended-gravity-forms-styler' ),
						'default'        => '16px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'line_height'     => array(
						'default'        => '1.5em',
						'range_settings' => array(
							'min'  => '0.1',
							'max'  => '10',
							'step' => '0.1',
						),
					),
					'letter_spacing'  => array(
						'default'        => '0px',
						'range_settings' => array(
							'min'  => '0',
							'max'  => '10',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'hide_text_align' => true,
					'text_color'      => array(
						'label' => esc_html__( 'Button Text Color', 'divi-extended-gravity-forms-styler' ),
					),
					'css'             => array(
						'main' => $submit_button_field,
						'important' => 'all',
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'submit_button',
				),
				'save_continue_button_text'  => array(
					'label'           => esc_html__( 'Button Text', 'divi-extended-gravity-forms-styler' ),
					'font_size'       => array(
						'label'          => esc_html__( 'Button Text Font Size', 'divi-extended-gravity-forms-styler' ),
						'default'        => '16px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'line_height'     => array(
						'default'        => '1.5em',
						'range_settings' => array(
							'min'  => '0.1',
							'max'  => '10',
							'step' => '0.1',
						),
					),
					'letter_spacing'  => array(
						'default'        => '0px',
						'range_settings' => array(
							'min'  => '0',
							'max'  => '10',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'hide_text_align' => true,
					'text_color'      => array(
						'label' => esc_html__( 'Button Text Color', 'divi-extended-gravity-forms-styler' ),
					),
					'css'             => array(
						'main' => $save_continue_button_field,
						'important' => 'all',
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'save_continue_button',
				),
				'success_message'              => array(
					'label'           => esc_html__( 'Success', 'divi-extended-gravity-forms-styler' ),
					'font_size'       => array(
						'label'          => esc_html__( 'Success Font Size', 'divi-extended-gravity-forms-styler' ),
						'default'        => '16px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'line_height'     => array(
						'default'        => '1.5em',
						'range_settings' => array(
							'min'  => '0.1',
							'max'  => '10',
							'step' => '0.1',
						),
					),
					'letter_spacing'  => array(
						'default'        => '0px',
						'range_settings' => array(
							'min'  => '0',
							'max'  => '10',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'text_align'      => array(
						'label'   => esc_html__( 'Success Alignment', 'divi-extended-gravity-forms-styler' ),
						'default' => 'left',
					),
					'css'             => array(
						'main' => $success,
						'important' => 'all',
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'success_error',
					'sub_toggle'	  => 'success_message',
				),
				'error_message'              => array(
					'label'           => esc_html__( 'Error', 'divi-extended-gravity-forms-styler' ),
					'font_size'       => array(
						'label'          => esc_html__( 'Error Font Size', 'divi-extended-gravity-forms-styler' ),
						'default'        => '16px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'line_height'     => array(
						'default'        => '1.5em',
						'range_settings' => array(
							'min'  => '0.1',
							'max'  => '10',
							'step' => '0.1',
						),
					),
					'letter_spacing'  => array(
						'default'        => '0px',
						'range_settings' => array(
							'min'  => '0',
							'max'  => '10',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'text_align'      => array(
						'label'   => esc_html__( 'Error Alignment', 'divi-extended-gravity-forms-styler' ),
						'default' => 'left',
					),
					'css'             => array(
						'main' => $error,
						'important' => 'all',
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'success_error',
					'sub_toggle'	  => 'error_message',
				),
				'button_text'        => array(
					'label'           => esc_html__( 'Button Text', 'divi-extended-gravity-forms-styler' ),
					'font_size'       => array(
						'label'          => esc_html__( 'Button Text Font Size', 'divi-extended-gravity-forms-styler' ),
						'default'        => '16px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'line_height'     => array(
						'default'        => '1.5em',
						'range_settings' => array(
							'min'  => '0.1',
							'max'  => '10',
							'step' => '0.1',
						),
					),
					'letter_spacing'  => array(
						'default'        => '0px',
						'range_settings' => array(
							'min'  => '0',
							'max'  => '10',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'hide_text_align' => true,
					'text_color'      => array(
						'label' => esc_html__( 'Button Text Color', 'divi-extended-gravity-forms-styler' ),
					),
					'css'             => array(
						'main' => $button_field,
						'important' => 'all',
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'button_fields',
				),
			),
			'box_shadow'          => array(
				'input'    => array(
					'label'       => esc_html__( 'Input Field Box Shadow', 'divi-extended-gravity-forms-styler' ),
					'css'         => array(
						'main' => implode( ', ', array_merge( $input_fields, $input_fields_focus ) ),
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'input_fields',
					'sub_toggle'	   => 'input',
				),
				'radio'    => array(
					'label'       => esc_html__( 'Radio Button Box Shadow', 'divi-extended-gravity-forms-styler' ),
					'css'         => array(
						'main' => "{$radio_field}, {$radio_field}:focus",
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'radio_fields',
				),
				'checkbox' => array(
					'label'       => esc_html__( 'Checkbox Box Shadow', 'divi-extended-gravity-forms-styler' ),
					'css'         => array(
						'main' => "{$checkbox_field}, {$checkbox_field}:focus",
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'checkbox_fields',
				),
				'submit_button' => array(
					'label' 		=> esc_html__( 'Submit Button Box Shadow', 'divi-extended-gravity-forms-styler' ),
					'css'          	=> array(
						'main'      => $submit_button_field,
						'important' => 'all',
					),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'submit_button',
				),
				'save_continue_button'  => array(
					'label' 		=> esc_html__( 'Save and Continue Button Box Shadow', 'divi-extended-gravity-forms-styler' ),
					'css'          	=> array(
						'main'      => $save_continue_button_field,
						'important' => 'all',
					),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'save_continue_button',
				),
				'button'        => array(
					'label' 		=> esc_html__( 'Button Box Shadow', 'divi-extended-gravity-forms-styler' ),
					'css'          	=> array(
						'main'      => $button_field,
						'important' => 'all',
					),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'button_fields',
				),
				'default'  => array(
					'css' => array(
						'main'      => $this->main_css_element,
						'important' => true,
					),
				),
			),
			'borders'             => array(
				'input'         => array(
					'label_prefix' => 'Input Fields',
					'defaults' => array(
				        'border_radii' => 'on||||',
				        'border_styles' => array(
				            'width' => '1px',
				            'color' => '#333333',
							'style' => 'solid',
				        ),
				    ),
					'css'          => array(
						'main'      => array(
							'border_radii'  => implode( ', ', array_merge( $input_fields, $input_fields_focus ) ),
							'border_styles' => implode( ', ', array_merge( $input_fields, $input_fields_focus ) ),
						),
						'important' => 'all',
					),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'input_fields',
					'sub_toggle'   => 'input',
				),
				'radio'         => array(
					'label_prefix'    => 'Radio Fields',
					'defaults'        => array(
						'border_radii'  => 'on||||',
						'border_styles' => array(
							'width' => '1px',
							'color' => '#333333',
							'style' => 'solid',
						),
					),
					'css'             => array(
						'main'      => array(
							'border_radii'  => "{$radio_field}, {$radio_field}:focus",
							'border_styles' => "{$radio_field}, {$radio_field}:focus",
						),
						'important' => 'all',
					),
					'depends_on'      => array( 'use_custom_radio' ),
					'depends_show_if' => 'on',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'radio_fields',
				),
				'checkbox'      => array(
					'label_prefix'    => 'Checkbox Fields',
					'defaults'        => array(
						'border_radii'  => 'on||||',
						'border_styles' => array(
							'width' => '1px',
							'color' => '#333333',
							'style' => 'solid',
						),
					),
					'css'             => array(
						'main'      => array(
							'border_radii'  => "{$checkbox_field}, {$checkbox_field}:focus",
							'border_styles' => "{$checkbox_field}, {$checkbox_field}:focus",
						),
						'important' => 'all',
					),
					'depends_on'      => array( 'use_custom_checkbox' ),
					'depends_show_if' => 'on',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'checkbox_fields',
				),
				'submit_button' => array(
					'label_prefix' => 'Submit Button',
					'css'          => array(
						'main'      => array(
							'border_radii'  => $submit_button_field,
							'border_styles' => $submit_button_field,
						),
						'important' => 'all',
					),
					'defaults' => array(
				        'border_radii' => 'on||||',
				        'border_styles' => array(
				            'width' => '1px',
				            'color' => '#333333',
							'style' => 'solid',
				        ),
				    ),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'submit_button',
				),
				'save_continue_button'  => array(
					'label_prefix' => 'Save and Continue Button',
					'css'          => array(
						'main'      => array(
							'border_radii'  => $save_continue_button_field,
							'border_styles' => $save_continue_button_field,
						),
						'important' => 'all',
					),
					'defaults' => array(
				        'border_radii' => 'on||||',
				        'border_styles' => array(
				            'width' => '1px',
				            'color' => '#333333',
							'style' => 'solid',
				        ),
				    ),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'save_continue_button',
				),
				'button'        => array(
					'label_prefix' => 'Button',
					'css'          => array(
						'main'      => array(
							'border_radii'  => $button_field,
							'border_styles' => $button_field,
						),
						'important' => 'all',
					),
					'defaults' => array(
				        'border_radii' => 'on||||',
				        'border_styles' => array(
				            'width' => '1px',
				            'color' => '#333333',
							'style' => 'solid',
				        ),
				    ),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'button_fields',
				),
				'errors'         => array(
					'label_prefix' => 'Errors',
					'defaults' => array(
				        'border_radii' => 'on||||',
				        'border_styles' => array(
				            'width' => '1px',
				            'color' => '#333333',
							'style' => 'solid',
				        ),
				    ),
					'css'          => array(
						'main'      => array(
							'border_radii'  => $error,
							'border_styles' => $error,
						),
						'important' => 'all',
					),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'success_error',
					'sub_toggle'   => 'error_message',
				),
				'default'       => array(
					'css' => array(
						'main'      => array(
							'border_radii'  => $this->main_css_element,
							'border_styles' => $this->main_css_element,
						),
						'important' => 'all',
					),
				),
			),
			'form_margin_padding' => array(
				'input'  => array(
					'margin_padding' => array(
						'css' => array(
							'margin'     => false,
							'padding'    => implode( ', ', $input_fields ),
							'important'  => 'all',
						),
					),
				),
				'upload' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => $upload_field,
							'important'  => 'all',
						),
					),
				),
				'submit' => array(
					'margin_padding' => array(
						'css' => array(
							'margin'     => $submit_button_field,
							'padding'    => $submit_button_field,
							'important'  => 'all',
						),
					),
				),
				'save_continue'  => array(
					'margin_padding' => array(
						'css' => array(
							'margin' 	 => $save_continue_button_field,
							'padding'    => $save_continue_button_field,
							'important'  => 'all',
						),
					),
				),
				'next_prev' => array(
					'margin_padding' => array(
						'css' => array(
							'margin'     => $button_field,
							'padding'    => $button_field,
							'important'  => 'all',
						),
					),
				),
				'success' => array(
					'margin_padding' => array(
						'css' => array(
							'margin'     => false,
							'padding'    => $success,
							'important'  => 'all',
						),
					),
				),
				'error' => array(
					'margin_padding' => array(
						'css' => array(
							'margin'     => false,
							'padding'    => "{$this->main_css_element} .el_gravity_forms_styler_wrapper form .gfield_validation_message, {$this->main_css_element} .el_gravity_forms_styler_wrapper .gform_validation_errors",
							'important'  => 'all',
						),
					),
				),
				'description'  => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => $description,
							'important'  => 'all',
						),
					),
				),				
				'fieldset'  => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => $fieldset,
							'important'  => 'all',
						),
					),
				),
			),
			'max_width'           => array(
				'extra' => array(
					'input' => array(
						'options'              => array(
							'width' => array(
								'label'          => esc_html__( 'Input Fields Width', 'divi-extended-gravity-forms-styler' ),
								'range_settings' => array(
									'min'  => 1,
									'max'  => 100,
									'step' => 1,
								),
								'hover'          => false,
								'fixed_unit'     => '%',
								'default_unit'   => '%',
								'default_tablet' => '',
								'default_phone'  => '',
								'tab_slug'       => 'advanced',
								'toggle_slug'    => 'input_fields',
								'sub_toggle'	 => 'input',
							),
						),
						'use_max_width'        => false,
						'use_module_alignment' => false,
						'css'                  => array(
							'main' => implode( ', ', $input_fields ),
							'important' => 'all',
						),
					),
				),
			),
			'height'              => array(
				'extra' => array(
					'input' => array(
						'options'        => array(
							'height' => array(
								'label'          => esc_html__( 'Input Fields Height', 'divi-extended-gravity-forms-styler' ),
								'range_settings' => array(
									'min'  => 1,
									'max'  => 100,
									'step' => 1,
								),
								'hover'          => false,
								'fixed_unit'     => 'px',
								'default_unit'   => 'px',
								'default'        => 'auto',
								'default_tablet' => '',
								'default_phone'  => '',
								'tab_slug'       => 'advanced',
								'toggle_slug'    => 'input_fields',
								'sub_toggle'	   => 'input',
							),
						),
						'use_max_height' => false,
						'use_min_height' => false,
						'css'            => array(
							'main' => implode( ', ', $input_fields ),
							'important' => 'all',
						),
					),
					'textarea' => array(
						'options'        => array(
							'height' => array(
								'label'          => esc_html__( 'Textarea Fields Height', 'divi-extended-gravity-forms-styler' ),
								'range_settings' => array(
									'min'  => 1,
									'max'  => 500,
									'step' => 1,
								),
								'hover'          => false,
								'fixed_unit'     => 'px',
								'default_unit'   => 'px',
								'default'        => '150px',
								'default_tablet' => '',
								'default_phone'  => '',
								'tab_slug'       => 'advanced',
								'toggle_slug'    => 'textarea_fields',
							),
						),
						'use_max_height' => false,
						'use_min_height' => false,
						'css'          	=> array(
							'main'      => $textarea,
							'important' => 'all',
						),
					),
				),
			),
			'background'          => array(
				'css' => array(
					'main' => $this->main_css_element,
					'important' => 'all',
				),
			),
			'margin_padding'      => array(
				'css' => array(
					'margin'    => $this->main_css_element,
					'padding'   => $this->main_css_element,
					'important' => 'all',
				),
			),
			'text'                => array(
				'css' => array(
					'text_orientation' => $this->main_css_element,
				),
			),
			'link_options'   => false,
		);
	}

	public function get_fields() {

		$et_accent_color = et_builder_accent_color();
		
		$gravity_forms = array( '0' => esc_html__( 'Select', 'divi-extended-gravity-forms-styler' ) );

		if ( class_exists( 'GFForms' ) ) {
			$forms	= RGFormsModel::get_forms();
			foreach ( $forms as $form => $value ) {
				$gravity_forms[ $value->id ] = $value->title;
			}
		}

		return array_merge( array(
				'gravity_form_id'              => array(
					'label'            => esc_html__( 'Select Form', 'divi-extended-gravity-forms-styler' ),
					'type'             => 'select',
					'option_category'  => 'configuration',
					'options'          => $gravity_forms,
					'computed_affects' => array(
						'__form',
					),
					'tab_slug'         => 'general',
					'toggle_slug'      => 'main_content',
					'description'    => esc_html__( 'Here you can select the form you have created earlier using Gravity Forms.', 'divi-extended-gravity-forms-styler' ),
				),
				'show_title' => array(
					'label'             => esc_html__( 'Show Title', 'divi-extended-gravity-forms-styler' ),
					'type'              => 'yes_no_button',
					'option_category'   => 'configuration',
					'options'           => array(
						'off' => esc_html__( 'Off', 'divi-extended-gravity-forms-styler' ),
						'on'  => esc_html__( 'On', 'divi-extended-gravity-forms-styler' ),
					),
					'default'           => 'off',
					'tab_slug'          => 'general',
					'toggle_slug'       => 'main_content',
					'description'      => esc_html__( 'Here you can choose whether or not to show form title.', 'divi-extended-gravity-forms-styler' ),
					'computed_affects' => array(
						'__form',
					),
				),
				'show_description' => array(
					'label'             => esc_html__( 'Show Description', 'divi-extended-gravity-forms-styler' ),
					'type'              => 'yes_no_button',
					'option_category'   => 'configuration',
					'options'           => array(
						'off' => esc_html__( 'Off', 'divi-extended-gravity-forms-styler' ),
						'on'  => esc_html__( 'On', 'divi-extended-gravity-forms-styler' ),
					),
					'default'           => 'off',
					'tab_slug'          => 'general',
					'toggle_slug'       => 'main_content',
					'description'      => esc_html__( 'Here you can choose whether or not to show form description.', 'divi-extended-gravity-forms-styler' ),
					'computed_affects' => array(
						'__form',
					),
				),
				'enable_ajax' => array(
					'label'             => esc_html__( 'Enable Ajax', 'divi-extended-gravity-forms-styler' ),
					'type'              => 'yes_no_button',
					'option_category'   => 'configuration',
					'options'           => array(
						'off' => esc_html__( 'Off', 'divi-extended-gravity-forms-styler' ),
						'on'  => esc_html__( 'On', 'divi-extended-gravity-forms-styler' ),
					),
					'default'           => 'off',
					'tab_slug'          => 'general',
					'toggle_slug'       => 'main_content',
					'description'      => esc_html__( 'Here you can choose whether or not to enable ajax.', 'divi-extended-gravity-forms-styler' ),
					'computed_affects' => array(
						'__form',
					),
				),
				'input_text_color'             => array(
					'label'          => esc_html__( 'Input Text Color', 'divi-extended-gravity-forms-styler' ),
					'type'           => 'color-alpha',
					'mobile_options' => true,
					'hover'          => 'tabs',
					'tab_slug'       => 'advanced',
					'toggle_slug'    => 'input_fields',
					'sub_toggle'	 => 'input',
					'description'    => esc_html__( 'Here you can select a custom color to be used for the input text of the contact form.', 'divi-extended-gravity-forms-styler' ),
				),
				'focus_input_text_color'       => array(
					'label'          => esc_html__( 'Focus Input Text Color', 'divi-extended-gravity-forms-styler' ),
					'type'           => 'color-alpha',
					'mobile_options' => true,
					'hover'          => 'tabs',
					'tab_slug'       => 'advanced',
					'toggle_slug'    => 'input_fields',
					'sub_toggle'	 => 'input',
					'description'    => esc_html__( 'Here you can select a custom color to be used for the input text when entering it.', 'divi-extended-gravity-forms-styler' ),
				),
				'placeholder_text_color'       => array(
					'label'          => esc_html__( 'Placeholder Text Color', 'divi-extended-gravity-forms-styler' ),
					'type'           => 'color-alpha',
					'mobile_options' => true,
					'hover'          => 'tabs',
					'tab_slug'       => 'advanced',
					'toggle_slug'    => 'input_fields',
					'sub_toggle'	 => 'placeholder',
					'description'    => esc_html__( 'Here you can select a custom color to be used for the placeholder text when field is empty.', 'divi-extended-gravity-forms-styler' ),
				),
				'focus_placeholder_text_color' => array(
					'label'          => esc_html__( 'Focus Placeholder Text Color', 'divi-extended-gravity-forms-styler' ),
					'type'           => 'color-alpha',
					'mobile_options' => true,
					'hover'          => 'tabs',
					'tab_slug'       => 'advanced',
					'toggle_slug'    => 'input_fields',
					'sub_toggle'	 => 'placeholder',
					'description'    => esc_html__( 'Here you can select a custom color to be used for the placeholder text when entering it.', 'divi-extended-gravity-forms-styler' ),
				),
				'input_bg_color'               => array(
					'label'          => esc_html__( 'Input Background', 'divi-extended-gravity-forms-styler' ),
					'type'           => 'color-alpha',
					'mobile_options' => true,
					'hover'          => 'tabs',
					'tab_slug'       => 'advanced',
					'toggle_slug'    => 'input_fields',
					'sub_toggle'	 => 'input',
					'description'    => esc_html__( 'Here you select a custom color to be used for the background of input fields when not selected.', 'divi-extended-gravity-forms-styler' ),
				),
				'input_focus_bg_color'         => array(
					'label'          => esc_html__( 'Focus Background', 'divi-extended-gravity-forms-styler' ),
					'type'           => 'color-alpha',
					'mobile_options' => true,
					'hover'          => 'tabs',
					'tab_slug'       => 'advanced',
					'toggle_slug'    => 'input_fields',
					'sub_toggle'	 => 'input',
					'description'    => esc_html__( 'Here you select a custom color to be used for the background of input fields when selected.', 'divi-extended-gravity-forms-styler' ),
				),
				'input_custom_padding'         => array(
					'label'            => esc_html__( 'Padding', 'divi-extended-gravity-forms-styler' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'default'          => '',
					'default_on_front' => '',
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'input_fields',
					'sub_toggle'	   => 'input',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-extended-gravity-forms-styler' ),
				),
				'use_custom_radio'             => array(
					'label'            => esc_html__( 'Use Custom Styling', 'divi-extended-gravity-forms-styler' ),
					'type'             => 'yes_no_button',
					'option_category'  => 'button',
					'options'          => array(
						'off' => esc_html__( 'No', 'divi-extended-gravity-forms-styler' ),
						'on'  => esc_html__( 'Yes', 'divi-extended-gravity-forms-styler' ),
					),
					'default'          => 'off',
					'default_on_front' => 'off',
					'affects'          => array(
						'radio_label',
						'radio_label_text_align',
						'radio_label_font',
						'radio_label_font_size',
						'radio_label_letter_spacing',
						'radio_label_line_height',
						'radio_label_text_color',
						'radio_label_text_shadow',
					),
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'radio_fields',
					'description'      => esc_html__( 'Here you can choose whether or not to custom style radio buttons.', 'divi-extended-gravity-forms-styler' ),
				),
				'radio_size'                   => array(
					'label'            => esc_html__( 'Radio Button Size', 'divi-extended-gravity-forms-styler' ),
					'type'             => 'range',
					'option_category'  => 'layout',
					'range_settings'   => array(
						'min'  => '0',
						'max'  => '100',
						'step' => '1',
					),
					'validate_unit'    => true,
					'fixed_unit'       => 'px',
					'fixed_range'      => true,
					'default'          => '15px',
					'default_on_front' => '15px',
					'show_if'          => array(
						'use_custom_radio' => 'on',
					),
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'radio_fields',
					'description'      => esc_html__( 'Move the slider or input the value to increase or decrease the size of radio buttons.', 'divi-extended-gravity-forms-styler' ),
				),
				'radio_bg_color'               => array(
					'label'          => esc_html__( 'Background', 'divi-extended-gravity-forms-styler' ),
					'type'           => 'color-alpha',
					'mobile_options' => true,
					'hover'          => 'tabs',
					'show_if'        => array(
						'use_custom_radio' => 'on',
					),
					'tab_slug'       => 'advanced',
					'toggle_slug'    => 'radio_fields',
					'description'    => esc_html__( 'Here you can select a custom color to be used for the background of radio buttons when not selected.', 'divi-extended-gravity-forms-styler' ),
				),
				'radio_checked_bg_color'       => array(
					'label'          => esc_html__( 'Checked Background', 'divi-extended-gravity-forms-styler' ),
					'type'           => 'color-alpha',
					'mobile_options' => true,
					'hover'          => 'tabs',
					'show_if'        => array(
						'use_custom_radio' => 'on',
					),
					'tab_slug'       => 'advanced',
					'toggle_slug'    => 'radio_fields',
					'description'    => esc_html__( 'Here you can select a custom color to be used for the background of radio button when selected.', 'divi-extended-gravity-forms-styler' ),
				),
				'use_custom_checkbox'          => array(
					'label'            => esc_html__( 'Use Custom Styling', 'divi-extended-gravity-forms-styler' ),
					'type'             => 'yes_no_button',
					'option_category'  => 'button',
					'options'          => array(
						'off' => esc_html__( 'No', 'divi-extended-gravity-forms-styler' ),
						'on'  => esc_html__( 'Yes', 'divi-extended-gravity-forms-styler' ),
					),
					'default'          => 'off',
					'default_on_front' => 'off',
					'affects'          => array(
						'checkbox_label',
						'checkbox_label_text_align',
						'checkbox_label_font',
						'checkbox_label_font_size',
						'checkbox_label_letter_spacing',
						'checkbox_label_line_height',
						'checkbox_label_text_color',
						'checkbox_label_text_shadow',
					),
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'checkbox_fields',
					'description'      => esc_html__( 'Here you can choose whether or not to custom style checkboxes.', 'divi-extended-gravity-forms-styler' ),
				),
				'checkbox_size'                => array(
					'label'            => esc_html__( 'Checkbox Size', 'divi-extended-gravity-forms-styler' ),
					'type'             => 'range',
					'option_category'  => 'layout',
					'range_settings'   => array(
						'min'  => '0',
						'max'  => '100',
						'step' => '1',
					),
					'validate_unit'    => true,
					'fixed_unit'       => 'px',
					'fixed_range'      => true,
					'default'          => '20px',
					'default_on_front' => '20px',
					'show_if'          => array(
						'use_custom_checkbox' => 'on',
					),
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'checkbox_fields',
					'description'      => esc_html__( 'Move the slider or input the value to increase or decrease the size of checkboxes.', 'divi-extended-gravity-forms-styler' ),
				),
				'checkbox_bg_color'            => array(
					'label'          => esc_html__( 'Background', 'divi-extended-gravity-forms-styler' ),
					'type'           => 'color-alpha',
					'mobile_options' => true,
					'hover'          => 'tabs',
					'show_if'        => array(
						'use_custom_checkbox' => 'on',
					),
					'tab_slug'       => 'advanced',
					'toggle_slug'    => 'checkbox_fields',
					'description'    => esc_html__( 'Here you can select a custom color to be used for the background of checkboxes when not selected.', 'divi-extended-gravity-forms-styler' ),
				),
				'checkbox_checked_bg_color'    => array(
					'label'          => esc_html__( 'Checked Background', 'divi-extended-gravity-forms-styler' ),
					'type'           => 'color-alpha',
					'mobile_options' => true,
					'hover'          => 'tabs',
					'show_if'        => array(
						'use_custom_checkbox' => 'on',
					),
					'tab_slug'       => 'advanced',
					'toggle_slug'    => 'checkbox_fields',
					'description'    => esc_html__( 'Here you can select a custom color to be used for the background of checkbox when selected.', 'divi-extended-gravity-forms-styler' ),
				),
				'checkbox_mark_color'            => array(
					'label'          => esc_html__( 'Check Mark Color', 'divi-extended-gravity-forms-styler' ),
					'type'           => 'color-alpha',
					'mobile_options' => true,
					'hover'          => 'tabs',
					'show_if'        => array(
						'use_custom_checkbox' => 'on',
					),
					'tab_slug'       => 'advanced',
					'toggle_slug'    => 'checkbox_fields',
					'description'    => esc_html__( 'Here you can select a custom color to be used for the checkmark of checkboxes when not selected.', 'divi-extended-gravity-forms-styler' ),
				),
				'upload_bg_color'              => array(
					'label'          => esc_html__( 'Upload Field Background', 'divi-extended-gravity-forms-styler' ),
					'type'           => 'color-alpha',
					'hover'          => 'tabs',
					'mobile_options' => true,
					'tab_slug'       => 'advanced',
					'toggle_slug'    => 'upload_fields',
					'description'    => esc_html__( 'Here you select a custom color to be used for the background of upload fields.', 'divi-extended-gravity-forms-styler' ),
				),
				'upload_custom_padding'        => array(
					'label'            => esc_html__( 'Padding', 'divi-extended-gravity-forms-styler' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'default'          => '',
					'default_on_front' => '',
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'upload_fields',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-extended-gravity-forms-styler' ),
				),
				'next_prev_custom_margin'        => array(
					'label'            => esc_html__( 'Margin', 'divi-extended-gravity-forms-styler' ),
					'type'             => 'custom_margin',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'default'          => '',
					'default_on_front' => '',
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'button_fields',
					'description'      => esc_html__( 'Margin adds extra space to the outside of the element, increasing the distance between the element and other items on the page.', 'divi-extended-gravity-forms-styler' ),
				),
				'next_prev_custom_padding'        => array(
					'label'            => esc_html__( 'Padding', 'divi-extended-gravity-forms-styler' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'default'          => '',
					'default_on_front' => '',
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'button_fields',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-extended-gravity-forms-styler' ),
				),
				'submit_custom_margin'        => array(
					'label'            => esc_html__( 'Margin', 'divi-extended-gravity-forms-styler' ),
					'type'             => 'custom_margin',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'default'          => '',
					'default_on_front' => '',
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'submit_button',
					'description'      => esc_html__( 'Margin adds extra space to the outside of the element, increasing the distance between the element and other items on the page.', 'divi-extended-gravity-forms-styler' ),
				),
				'submit_custom_padding'        => array(
					'label'            => esc_html__( 'Padding', 'divi-extended-gravity-forms-styler' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'default'          => '',
					'default_on_front' => '',
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'submit_button',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-extended-gravity-forms-styler' ),
				),
				'save_continue_custom_margin'        => array(
					'label'            => esc_html__( 'Margin', 'divi-extended-gravity-forms-styler' ),
					'type'             => 'custom_margin',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'default'          => '',
					'default_on_front' => '',
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'save_continue_button',
					'description'      => esc_html__( 'Margin adds extra space to the outside of the element, increasing the distance between the element and other items on the page.', 'divi-extended-gravity-forms-styler' ),
				),
				'save_continue_custom_padding'        => array(
					'label'            => esc_html__( 'Padding', 'divi-extended-gravity-forms-styler' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'default'          => '',
					'default_on_front' => '',
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'save_continue_button',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-extended-gravity-forms-styler' ),
				),
				'buttons_bg_color'             => array(
					'label'          	=> esc_html__( 'Background', 'divi-plus' ),
					'type'              => 'background-field',
					'base_name'         => 'buttons_bg',
					'context'           => 'buttons_bg_color',
					'option_category'   => 'button',
					'custom_color'      => true,
					'background_fields' => $this->generate_background_options( 'buttons_bg', 'button', 'advanced', 'button_fields', 'buttons_bg_color' ),
					'hover'             => 'tabs',
					'mobile_options'    => true,
					'tab_slug'       	=> 'advanced',
					'toggle_slug'    	=> 'button_fields',
					'description'    	=> esc_html__( 'Adjust the background style of the button fields by customizing the background color, gradient, and image.', 'divi-plus' ),
				),
				'save_continue_bg_color'               => array(
					'label'          	=> esc_html__( 'Background', 'divi-extended-gravity-forms-styler' ),
					'type'              => 'background-field',
					'base_name'         => 'save_continue_bg',
					'context'           => 'save_continue_bg_color',
					'option_category'   => 'button',
					'custom_color'      => true,
					'background_fields' => $this->generate_background_options( 'save_continue_bg', 'button', 'advanced', 'save_continue_button', 'save_continue_bg_color' ),
					'hover'             => 'tabs',
					'mobile_options'    => true,
					'tab_slug'       	=> 'advanced',
					'toggle_slug'    	=> 'save_continue_button',
					'description'    	=> esc_html__( 'Adjust the background style of the save and continue button by customizing the background color, gradient, and image.', 'divi-extended-gravity-forms-styler' ),
				),
				'save_continue_icon_color' => array(
					'label'          	 => esc_html__( 'Button Icon Color', 'divi-extended-gravity-forms-styler' ),
					'type'            	=> 'color-alpha',
					'hover'           	=> 'tabs',
					'mobile_options'  	=> true,
					'default'         	=> esc_attr( $et_accent_color ),
					'default_on_front'  => esc_attr( $et_accent_color ),
					'tab_slug'        	=> 'advanced',
					'toggle_slug'     	=> 'save_continue_button',
					'description'     	=> esc_html__( 'Here you can define a custom color for your icon.', 'divi-extended-gravity-forms-styler' ),
				),
				'submit_bg_color'              => array(
					'label'          	=> esc_html__( 'Background', 'divi-extended-gravity-forms-styler' ),
					'type'              => 'background-field',
					'base_name'         => 'submit_bg',
					'context'           => 'submit_bg_color',
					'option_category'   => 'button',
					'custom_color'      => true,
					'background_fields' => $this->generate_background_options( 'submit_bg', 'button', 'advanced', 'submit_button', 'submit_bg_color' ),
					'hover'             => 'tabs',
					'mobile_options'    => true,
					'tab_slug'       	=> 'advanced',
					'toggle_slug'    	=> 'submit_button',
					'description'    	=> esc_html__( 'Adjust the background style of the submit button by customizing the background color, gradient, and image.', 'divi-extended-gravity-forms-styler' ),
				),
				'success_custom_padding'        => array(
					'label'            => esc_html__( 'Padding', 'divi-extended-gravity-forms-styler' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'default'          => '',
					'default_on_front' => '',
					'tab_slug'         => 'advanced',
					'toggle_slug'     => 'success_error',
					'sub_toggle'	  => 'success_message',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-extended-gravity-forms-styler' ),
				),
				'success_bg_color'         => array(
					'label'          => esc_html__( 'Background', 'divi-extended-gravity-forms-styler' ),
					'type'           => 'color-alpha',
					'mobile_options' => true,
					'hover'          => 'tabs',
					'tab_slug'       	=> 'advanced',
					'toggle_slug'     => 'success_error',
					'sub_toggle'	  => 'success_message',
					'description'    => esc_html__( 'Here you can select a background color to be used for success message.', 'divi-extended-gravity-forms-styler' ),
				),
				'error_custom_padding'        => array(
					'label'            => esc_html__( 'Padding', 'divi-extended-gravity-forms-styler' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'default'          => '',
					'default_on_front' => '',
					'tab_slug'         => 'advanced',
					'toggle_slug'     => 'success_error',
					'sub_toggle'	  => 'error_message',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-extended-gravity-forms-styler' ),
				),
				'error_bg_color'            => array(
					'label'          => esc_html__( 'Background', 'divi-extended-gravity-forms-styler' ),
					'type'           => 'color-alpha',
					'mobile_options' => true,
					'hover'          => 'tabs',
					'tab_slug'       	=> 'advanced',
					'toggle_slug'     => 'success_error',
					'sub_toggle'	  => 'error_message',
					'description'    => esc_html__( 'Here you can select a background color to be used for error message.', 'divi-extended-gravity-forms-styler' ),
				),
				'description_custom_padding'        => array(
					'label'            => esc_html__( 'Form Description Padding', 'divi-extended-gravity-forms-styler' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'default'          => '',
					'default_on_front' => '',
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'description',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-extended-gravity-forms-styler' ),
				),
				'fieldset_custom_padding'        => array(
					'label'            => esc_html__( 'Fieldset Padding', 'divi-extended-gravity-forms-styler' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'default'          => '',
					'default_on_front' => '',
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'margin_padding',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-extended-gravity-forms-styler' ),
				),
				'__form'                       => array(
					'type'                => 'computed',
					'computed_callback'   => array( 'EL_GravityFormsStyler', 'get_form' ),
					'computed_depends_on' => array(
						'gravity_form_id',
						'show_title',
						'show_description',
						'enable_ajax'
						
					),
				),
			),
			$this->generate_background_options( 'buttons_bg', 'skip', 'advanced', 'button_fields', 'buttons_bg_color' ),
			$this->generate_background_options( 'save_continue_bg', 'skip', 'advanced', 'save_continue_button', 'save_continue_bg_color' ),
			$this->generate_background_options( 'submit_bg', 'skip', 'advanced', 'submit_button', 'submit_bg_color' )
		);
	}

	public static function get_form( $args = array(), $conditional_tags = array(), $current_page = array() ) {
		$defaults = array(
			'gravity_form_id'  => '0',
			'show_title'  	   => 'off',
			'show_description' => 'off',
			'enable_ajax' => 'off',
		);

		$args = wp_parse_args( $args, $defaults );
		
		if ( 'off' === $args['show_title'] ) {
			$args['show_title'] = 'false';
		} else {
			$args['show_title'] = 'true';
		}

		if ( 'off' === $args['show_description'] ) {
			$args['show_description'] = 'false';
		} else {
			$args['show_description'] = 'true';
		}
		
		if ( 'off' === $args['enable_ajax'] ) {
			$args['enable_ajax'] = 'false';
		} else {
			$args['enable_ajax'] = 'true';
		}

		if ( '0' !== $args['gravity_form_id'] ) {
			$shortcode = '[gravityform id="' . esc_attr( $args['gravity_form_id'] ) . '" title="' . esc_attr( $args['show_title'] ) . '" description="' . esc_attr( $args['show_description'] ) . '" ajax="' . esc_attr( $args['enable_ajax'] ) . '"]';
		}

		if ( isset( $shortcode ) ) {
			$output = do_shortcode( $shortcode );
		} else {
			return '';
		}

		return $output;
	}

	public function render( $attrs, $content = null, $render_slug ) {
		
		if( 'off' === $this->props['show_title'] ){
			$this->props['show_title'] = 'false';
		}else{
			$this->props['show_title'] = 'true';
		}
		if( 'off' === $this->props['show_description'] ){
			$this->props['show_description'] = 'false';
		}else{
			$this->props['show_description'] = 'true';
		}
		if( 'off' === $this->props['enable_ajax'] ){
			$this->props['enable_ajax'] = 'false';
		}else{
			$this->props['enable_ajax'] = 'true';
		}

		if ( '0' !== $this->props['gravity_form_id'] ) {
			$shortcode = '[gravityform id="' . esc_attr( $this->props['gravity_form_id'] ) . '" title="' . esc_attr( $this->props['show_title'] ) . '" description="' . esc_attr( $this->props['show_description'] ) . '" ajax="' . esc_attr( $this->props['enable_ajax'] ) . '"]';
		}

		if ( isset( $shortcode ) ) {
			$output = '<div class="el_gravity_forms_styler_wrapper">' . do_shortcode( $shortcode ) . '</div>';
		} else {
			return '';
		}

		$input_fields = array(
			$this->main_css_element . ' .el_gravity_forms_styler_wrapper form input[type="text"]',
			$this->main_css_element . ' .el_gravity_forms_styler_wrapper form input[type="email"]',
			$this->main_css_element . ' .el_gravity_forms_styler_wrapper form input[type="password"]',
			$this->main_css_element . ' .el_gravity_forms_styler_wrapper form input[type="tel"]',
			$this->main_css_element . ' .el_gravity_forms_styler_wrapper form input[type="url"]',
			$this->main_css_element . ' .el_gravity_forms_styler_wrapper form input[type="time"]',
			$this->main_css_element . ' .el_gravity_forms_styler_wrapper form input[type="week"]',
			$this->main_css_element . ' .el_gravity_forms_styler_wrapper form input[type="month"]',
			$this->main_css_element . ' .el_gravity_forms_styler_wrapper form input[type="datetime-local"]',
			$this->main_css_element . ' .el_gravity_forms_styler_wrapper form input[type="number"]',
			$this->main_css_element . ' .el_gravity_forms_styler_wrapper form input[type="date"]',
			$this->main_css_element . ' .el_gravity_forms_styler_wrapper form select',
			$this->main_css_element . ' .el_gravity_forms_styler_wrapper form textarea',
		);

		$input_fields_hover       = preg_filter( '/$/', ':hover', $input_fields );
		$input_fields_focus       = preg_filter( '/$/', ':focus', $input_fields );
		$input_fields_focus_hover = preg_filter( '/$/', ':hover', $input_fields_focus );
		
		$description 			= $this->main_css_element . '.el_gravity_forms_styler_wrapper .gform_description';
		$input_description        = $this->main_css_element . ' .el_gravity_forms_styler_wrapper form .gfield_description';
		$input_description_hover  = $this->main_css_element . ' .el_gravity_forms_styler_wrapper form .gfield_description:hover';
		
		$radio_field            		= $this->main_css_element . ' .el_gravity_forms_styler_wrapper form input[type="radio"]';
		$checkbox_field         		= $this->main_css_element . ' .el_gravity_forms_styler_wrapper form input[type="checkbox"]';
		$radio_checked_field    		= $this->main_css_element . ' .el_gravity_forms_styler_wrapper form input[type="radio"]:checked::before';
		$checkbox_checked_field 		= $this->main_css_element . ' .el_gravity_forms_styler_wrapper form input[type="checkbox"]:checked::before';

		$upload_field           = $this->main_css_element . ' .el_gravity_forms_styler_wrapper form input[type="file"]';

		$button_field        = "{$this->main_css_element} .el_gravity_forms_styler_wrapper form .gform_previous_button.button, {$this->main_css_element} .el_gravity_forms_styler_wrapper form .gform_next_button.button";
		$save_continue_button_field  = "{$this->main_css_element} .el_gravity_forms_styler_wrapper form .gform_save_link.button";
		$save_continue_icon  = "{$this->main_css_element} .el_gravity_forms_styler_wrapper form .gform_save_link.button svg path";
		$save_continue_icon_hover  = "{$this->main_css_element} .el_gravity_forms_styler_wrapper form .gform_save_link.button:hover svg path";
		$submit_button_field = "{$this->main_css_element} .el_gravity_forms_styler_wrapper form input[type='submit'], {$this->main_css_element} .el_gravity_forms_styler_wrapper form button[type='submit'], {$this->main_css_element} .el_gravity_forms_styler_wrapper .form_saved_message_emailform input[type='submit']";
		$button_field_hover        = "{$this->main_css_element} .el_gravity_forms_styler_wrapper form .gform_previous_button.button:hover, {$this->main_css_element} .el_gravity_forms_styler_wrapper form .gform_next_button.button:hover";
		$save_continue_button_field_hover  = "{$this->main_css_element} .el_gravity_forms_styler_wrapper form .gform_save_link.button:hover";
		$submit_button_field_hover = "{$this->main_css_element} .el_gravity_forms_styler_wrapper form input[type='submit']:hover, {$this->main_css_element} .el_gravity_forms_styler_wrapper form button[type='submit']:hover";
		$success = "{$this->main_css_element} .el_gravity_forms_styler_wrapper .gform_confirmation_wrapper .gform_confirmation_message";
		$error = "{$this->main_css_element} .el_gravity_forms_styler_wrapper form .gfield_validation_message, {$this->main_css_element} .el_gravity_forms_styler_wrapper .gform_validation_errors .gform_submission_error, {$this->main_css_element} .el_gravity_forms_styler_wrapper .gform_validation_errors";
		
		$input_fields_placeholder        = preg_filter( '/$/', '::placeholder', $input_fields );
		$input_fields_webkit_placeholder = preg_filter( '/$/', '::-webkit-input-placeholder', $input_fields );
		$input_fields_moz_placeholder    = preg_filter( '/$/', '::-moz-placeholder', $input_fields );
		$input_fields_ms_placeholder     = preg_filter( '/$/', '::-ms-input-placeholder', $input_fields );
		$input_fields_placeholder        = array_merge(
			$input_fields_webkit_placeholder,
			$input_fields_moz_placeholder,
			$input_fields_ms_placeholder,
			$input_fields_placeholder
		);

		$input_fields_hover_placeholder        = preg_filter( '/$/', '::placeholder', $input_fields_hover );
		$input_fields_hover_webkit_placeholder = preg_filter( '/$/', '::-webkit-input-placeholder', $input_fields_hover );
		$input_fields_hover_moz_placeholder    = preg_filter( '/$/', '::-moz-placeholder', $input_fields_hover );
		$input_fields_hover_ms_placeholder     = preg_filter( '/$/', '::-ms-input-placeholder', $input_fields_hover );
		$input_fields_hover_placeholder        = array_merge(
			$input_fields_hover_webkit_placeholder,
			$input_fields_hover_moz_placeholder,
			$input_fields_hover_ms_placeholder,
			$input_fields_hover_placeholder
		);

		$input_fields_focus_placeholder        = preg_filter( '/$/', '::placeholder', $input_fields_focus );
		$input_fields_focus_webkit_placeholder = preg_filter( '/$/', '::-webkit-input-placeholder', $input_fields_focus );
		$input_fields_focus_moz_placeholder    = preg_filter( '/$/', '::-moz-placeholder', $input_fields_focus );
		$input_fields_focus_ms_placeholder     = preg_filter( '/$/', '::-ms-input-placeholder', $input_fields_focus );
		$input_fields_focus_placeholder        = array_merge(
			$input_fields_focus_webkit_placeholder,
			$input_fields_focus_moz_placeholder,
			$input_fields_focus_ms_placeholder,
			$input_fields_focus_placeholder
		);

		$input_fields_focus_hover_placeholder        = preg_filter( '/$/', '::placeholder', $input_fields_focus_hover );
		$input_fields_focus_hover_webkit_placeholder = preg_filter( '/$/', '::-webkit-input-placeholder', $input_fields_focus_hover );
		$input_fields_focus_hover_moz_placeholder    = preg_filter( '/$/', '::-moz-placeholder', $input_fields_focus_hover );
		$input_fields_focus_hover_ms_placeholder     = preg_filter( '/$/', '::-ms-input-placeholder', $input_fields_focus_hover );
		$input_fields_focus_hover_placeholder        = array_merge(
			$input_fields_focus_hover_webkit_placeholder,
			$input_fields_focus_hover_moz_placeholder,
			$input_fields_focus_hover_ms_placeholder,
			$input_fields_focus_hover_placeholder
		);

		$textarea = "{$this->main_css_element} .el_gravity_forms_styler_wrapper form .gfield textarea.large, {$this->main_css_element} .el_gravity_forms_styler_wrapper form .gfield textarea.medium, {$this->main_css_element} .el_gravity_forms_styler_wrapper form .gfield textarea.small";
		$fieldset = "{$this->main_css_element} .el_gravity_forms_styler_wrapper form .gfield";

		/* Input Field Text Color */
		$input_text_colors = et_pb_responsive_options()->get_property_values( $this->props, 'input_text_color' );
		et_pb_responsive_options()->generate_responsive_css( $input_text_colors, implode( ', ', $input_fields ), 'color', $render_slug, '', 'color' );

		$input_text_color_hover = esc_html( $this->get_hover_value( 'input_text_color' ) );
		if ( $input_text_color_hover ) {
			self::set_style(
				$render_slug,
				array(
					'selector'    => implode( ', ', $input_fields_hover ),
					'declaration' => sprintf(
						'color: %s !important;',
						$input_text_color_hover
					),
				)
			);
		}

		/* Input Field Focus Text Color */
		$focus_input_text_colors = et_pb_responsive_options()->get_property_values( $this->props, 'focus_input_text_color' );
		et_pb_responsive_options()->generate_responsive_css( $focus_input_text_colors, implode( ', ', $input_fields_focus ), 'color', $render_slug, '', 'color' );

		$focus_input_text_color_hover = esc_html( $this->get_hover_value( 'focus_input_text_color' ) );
		if ( $focus_input_text_color_hover ) {
			self::set_style(
				$render_slug,
				array(
					'selector'    => implode( ', ', $input_fields_focus_hover ),
					'declaration' => sprintf(
						'color: %s !important;',
						$focus_input_text_color_hover
					),
				)
			);
		}

		/* Placeholder Text Color */
		$placeholder_text_colors = et_pb_responsive_options()->get_property_values( $this->props, 'placeholder_text_color' );
		et_pb_responsive_options()->generate_responsive_css( $placeholder_text_colors, implode( ', ', $input_fields_placeholder ), 'color', $render_slug, ' !important;', 'color' );

		$placeholder_text_color_hover = esc_html( $this->get_hover_value( 'placeholder_text_color' ) );
		if ( $placeholder_text_color_hover ) {
			self::set_style(
				$render_slug,
				array(
					'selector'    => implode( ', ', $input_fields_hover_placeholder ),
					'declaration' => sprintf(
						'color: %s !important;',
						$placeholder_text_color_hover
					),
				)
			);
		}

		/* Placeholder Focus Text Color */
		$focus_placeholder_text_colors = et_pb_responsive_options()->get_property_values( $this->props, 'focus_placeholder_text_color' );
		et_pb_responsive_options()->generate_responsive_css( $focus_placeholder_text_colors, implode( ', ', $input_fields_focus_placeholder ), 'color', $render_slug, ' !important;', 'color' );

		$focus_placeholder_text_color_hover = esc_html( $this->get_hover_value( 'focus_placeholder_text_color' ) );
		if ( $focus_placeholder_text_color_hover ) {
			self::set_style(
				$render_slug,
				array(
					'selector'    => implode( ', ', $input_fields_focus_hover_placeholder ),
					'declaration' => sprintf(
						'color: %s !important;',
						$focus_placeholder_text_color_hover
					),
				)
			);
		}

		/* Input Field Background Color */
		$input_bg_colors = et_pb_responsive_options()->get_property_values( $this->props, 'input_bg_color' );
		et_pb_responsive_options()->generate_responsive_css( $input_bg_colors, implode( ', ', $input_fields ), 'background-color', $render_slug, ' !important;', 'color' );

		$input_bg_color_hover = esc_html( $this->get_hover_value( 'input_bg_color' ) );
		if ( $input_bg_color_hover ) {
			self::set_style(
				$render_slug,
				array(
					'selector'    => implode( ', ', $input_fields_hover ),
					'declaration' => sprintf(
						'background-color: %s !important;',
						$input_bg_color_hover
					),
				)
			);
		}

		/* Input Field Focus Background Color */
		$input_focus_bg_colors = et_pb_responsive_options()->get_property_values( $this->props, 'input_focus_bg_color' );
		et_pb_responsive_options()->generate_responsive_css( $input_focus_bg_colors, implode( ', ', $input_fields_focus ), 'background-color', $render_slug, ' !important;', 'color' );

		$input_focus_bg_color_hover = esc_html( $this->get_hover_value( 'input_focus_bg_color' ) );
		if ( $input_focus_bg_color_hover ) {
			self::set_style(
				$render_slug,
				array(
					'selector'    => implode( ', ', $input_fields_focus_hover ),
					'declaration' => sprintf(
						'background-color: %s !important;',
						$input_focus_bg_color_hover
					),
				)
			);
		}

		if ( 'on' === $this->props['use_custom_checkbox'] ) {
			self::set_style(
				$render_slug,
				array(
					'selector'    => $checkbox_field,
					'declaration' => '-webkit-appearance: none; -moz-appearance: none; appearance: none; position: relative; border: 1px solid #333; vertical-align: middle; box-sizing: content-box;',
				)
			);

			self::set_style(
				$render_slug,
				array(
					'selector'    => $checkbox_checked_field,
					'declaration' => 'position: absolute; top: 0; left: 0; font-family: ETModules; content: "N"; line-height: 1;',
				)
			);

			$size_property  = array( 'width', 'height' );
			$checkbox_sizes = et_pb_responsive_options()->get_property_values( $this->props, 'checkbox_size' );
			et_pb_responsive_options()->generate_responsive_css( $checkbox_sizes, $checkbox_field, $size_property, $render_slug, '', 'range' );
			et_pb_responsive_options()->generate_responsive_css( $checkbox_sizes, $checkbox_checked_field, 'font-size', $render_slug, '', 'range' );

			$checkbox_bg_colors = et_pb_responsive_options()->get_property_values( $this->props, 'checkbox_bg_color' );
			et_pb_responsive_options()->generate_responsive_css( $checkbox_bg_colors, $checkbox_field, 'background-color', $render_slug, ' !important;', 'type' );

			$checkbox_checked_bg_colors = et_pb_responsive_options()->get_property_values( $this->props, 'checkbox_checked_bg_color' );
			et_pb_responsive_options()->generate_responsive_css( $checkbox_checked_bg_colors, $checkbox_field . ':checked', 'background-color', $render_slug, ' !important;', 'type' );

			$checkbox_mark_colors = et_pb_responsive_options()->get_property_values( $this->props, 'checkbox_mark_color' );
			et_pb_responsive_options()->generate_responsive_css( $checkbox_mark_colors, $checkbox_field, 'color', $render_slug, '!important;', 'type' );

			$checkbox_bg_color_hover = esc_html( $this->get_hover_value( 'checkbox_bg_color' ) );
			if ( $checkbox_bg_color_hover ) {
				self::set_style(
					$render_slug,
					array(
						'selector'    => $checkbox_field . ':hover',
						'declaration' => sprintf(
							'background-color: %s !important;',
							$checkbox_bg_color_hover
						),
					)
				);
			}

			$checkbox_checked_bg_color_hover = esc_html( $this->get_hover_value( 'checkbox_checked_bg_color' ) );
			if ( $checkbox_checked_bg_color_hover ) {
				self::set_style(
					$render_slug,
					array(
						'selector'    => $checkbox_field . ':checked:hover',
						'declaration' => sprintf(
							'background-color: %s !important;',
							$checkbox_checked_bg_color_hover
						),
					)
				);
			}
		}

		if ( 'on' === $this->props['use_custom_radio'] ) {
			self::set_style(
				$render_slug,
				array(
					'selector'    => $radio_field,
					'declaration' => '-webkit-appearance: none; -moz-appearance: none; appearance: none; position: relative; border: 1px solid #333; vertical-align: middle; box-sizing: content-box;',
				)
			);

			self::set_style(
				$render_slug,
				array(
					'selector'    => $radio_checked_field,
					'declaration' => 'position: absolute; top: 3px; left: 3px; background: #000; width: calc(100% - 6px); height: calc(100% - 6px); border-radius: inherit; content: "";',
				)
			);

			$size_property = array( 'width', 'height' );
			$radio_sizes   = et_pb_responsive_options()->get_property_values( $this->props, 'radio_size' );
			et_pb_responsive_options()->generate_responsive_css( $radio_sizes, $radio_field, $size_property, $render_slug, '', 'range' );

			$radio_bg_colors = et_pb_responsive_options()->get_property_values( $this->props, 'radio_bg_color' );
			et_pb_responsive_options()->generate_responsive_css( $radio_bg_colors, $radio_field, 'background-color', $render_slug, ' !important;', 'type' );

			$radio_checked_bg_colors = et_pb_responsive_options()->get_property_values( $this->props, 'radio_checked_bg_color' );
			et_pb_responsive_options()->generate_responsive_css( $radio_checked_bg_colors, $radio_field . ':checked:before', 'background-color', $render_slug, ' !important;', 'type' );

			$radio_bg_color_hover = esc_html( $this->get_hover_value( 'radio_bg_color' ) );
			if ( $radio_bg_color_hover ) {
				self::set_style(
					$render_slug,
					array(
						'selector'    => $radio_field . ':hover',
						'declaration' => sprintf(
							'background-color: %s !important;',
							$radio_bg_color_hover
						),
					)
				);
			}

			$radio_checked_bg_color_hover = esc_html( $this->get_hover_value( 'radio_checked_bg_color' ) );
			if ( $radio_checked_bg_color_hover ) {
				self::set_style(
					$render_slug,
					array(
						'selector'    => $radio_field . ':checked:hover',
						'declaration' => sprintf(
							'background-color: %s !important;',
							$radio_checked_bg_color_hover
						),
					)
				);
			}
		}

		/* Upload Button */
		$upload_bg_colors = et_pb_responsive_options()->get_property_values( $this->props, 'upload_bg_color' );
		et_pb_responsive_options()->generate_responsive_css( $upload_bg_colors, $upload_field, 'background-color', $render_slug, ' !important;', 'color' );
		$upload_bg_color_hover = esc_html( $this->get_hover_value( 'upload_bg_color' ) );
		if ( $upload_bg_color_hover ) {
			self::set_style(
				$render_slug,
				array(
					'selector'    => $upload_field . ':hover',
					'declaration' => sprintf(
						'background-color: %s !important;',
						$upload_bg_color_hover
					),
				)
			);
		}

		/* Save and Continue Button */
		$save_continue_icon_color     	= et_pb_responsive_options()->get_property_values( $this->props, 'save_continue_icon_color' );
		et_pb_responsive_options()->generate_responsive_css( $save_continue_icon_color, $save_continue_icon, 'fill', $render_slug, '', 'color' );
		$save_continue_icon_color_hover    = $this->get_hover_value( 'save_continue_icon_color' );
        if ( $save_continue_icon_color_hover ) {
            self::set_style( $render_slug, array(
                'selector'    => $save_continue_icon_hover,
                'declaration' => sprintf(
                    'fill: %1$s;',
                    esc_attr( $save_continue_icon_color_hover )
                ),
            ) );
        }

		/* Success Background Color */
		$success_colors = et_pb_responsive_options()->get_property_values( $this->props, 'success_bg_color' );
		et_pb_responsive_options()->generate_responsive_css( $success_colors, $success, 'background-color', $render_slug, ' !important;', 'color' );
		/* Error Background Color */
		$error_colors = et_pb_responsive_options()->get_property_values( $this->props, 'error_bg_color' );
		et_pb_responsive_options()->generate_responsive_css( $error_colors, $error, 'background-color', $render_slug, ' !important;', 'color' );
		
		$args = array(
			'render_slug'	=> $render_slug,
			'props'			=> $this->props,
			'fields'		=> $this->fields_unprocessed,
			'module'		=> $this,
			'backgrounds' 	=> array(
				'buttons_bg' => array(
					'normal' => $button_field,
					'hover' => $button_field_hover,
	 			),
	 			'save_continue_bg' => array(
	 				'normal' => $save_continue_button_field,
	 				'hover' => $save_continue_button_field_hover,
	 			),
	 			'submit_bg' => array(
	 				'normal' => $submit_button_field,
	 				'hover' => $submit_button_field_hover,
	 			),
			),
		);

		EL_GravityFormsStylerForDiviHelper::process_background( $args );
		$fields = array( 'form_margin_padding' );
		EL_GravityFormsStylerForDiviHelper::process_advanced_margin_padding_css( $this, $render_slug, $this->margin_padding, $fields );

		$file = et_is_builder_plugin_active() ? 'style-dbp' : 'style';
        wp_enqueue_style( 'el-gravity-forms-styler-style', ELICUS_GRAVITY_FORMS_STYLER_PATH . 'includes/modules/GravityFormsStyler/' . $file . '.min.css', array(), '1.0.0' );

		return $output;
	}

}
if ( class_exists( 'GFForms' ) ) {
	new EL_GravityFormsStyler();
}
