<?php
// phpcs:disable PSR1.Files.SideEffects

namespace phpformbuilder;

require_once('traits/Elements.php');
require_once('traits/Internal.php');
require_once('traits/Plugins.php');
require_once('traits/Rendering.php');
require_once('traits/StaticFunctions.php');
require_once('traits/Utilities.php');

/**
 * Form Class
 *
 * @version 5.0.5
 * @author Gilles Migliori - gilles.migliori@gmail.com
 *
 */

class Form
{
    use traits\Elements;
    use traits\Internal;
    use traits\Plugins;
    use traits\Rendering;
    use traits\StaticFunctions;
    use traits\Utilities;

    /* general */

    public $form_ID                = '';
    protected $form_attr           = '';
    protected $action              = '';
    protected $add_get_vars        = true;
    public static $instances;

    /*  bs4_options, bs5_options, bulma_options, foundation_options, material_options, tailwind_options, uikit_options :
    *   wrappers and classes styled with Bootstrap 4/5, Bulma, foundation XY Grid (Foundation > 6.3), Material Design, Tailwind, UIkit
    *   each option can be individually updated with $form->setOptions();
    */

    protected $bs4_options = array(
        'elementsWrapper'              => '<div class="form-group"></div>',
        'formHorizontalClass'          => 'form-horizontal',
        'formVerticalClass'            => '',
        'checkboxWrapper'              => '<div class="form-check"></div>',
        'radioWrapper'                 => '<div class="form-check"></div>',
        'helperWrapper'                => '<small class="form-text text-muted"></small>',
        'buttonWrapper'                => '<div class="form-group"></div>',
        'wrapElementsIntoLabels'       => false,
        'wrapCheckboxesIntoLabels'     => false,
        'wrapRadiobtnsIntoLabels'      => false,
        'elementsClass'                => 'form-control',
        'wrapperErrorClass'            => '',
        'elementsErrorClass'           => 'is-invalid',
        'textErrorClass'               => 'invalid-feedback w-100 d-block',
        'verticalLabelWrapper'         => false,
        'verticalLabelClass'           => 'form-control-label',
        'verticalCheckboxLabelClass'   => 'form-check-label',
        'verticalRadioLabelClass'      => 'form-check-label',
        'horizontalLabelWrapper'       => false,
        'horizontalLabelClass'         => 'col-form-label',
        'horizontalLabelCol'           => 'col-sm-4',
        'horizontalOffsetCol'          => 'offset-sm-4',
        'horizontalElementCol'         => 'col-sm-8',
        'inlineCheckboxLabelClass'     => 'form-check-label',
        'inlineRadioLabelClass'        => 'form-check-label',
        'inlineCheckboxWrapper'        => '<div class="form-check form-check-inline"></div>',
        'inlineRadioWrapper'           => '<div class="form-check form-check-inline"></div>',
        'iconBeforeWrapper'            => '<div class="input-group-prepend"><span class="input-group-text"></span></div>',
        'iconAfterWrapper'             => '<div class="input-group-append"><span class="input-group-text"></span></div>',
        'btnGroupClass'                => 'btn-group',
        'requiredMark'                 => '<sup class="text-danger">* </sup>',
        'openDomReady'                 => 'jQuery(document).ready(function($) {',
        'closeDomReady'                => '});'
    );

    protected $bs5_options = array(
        'formHorizontalClass'          => 'form-horizontal',
        'formVerticalClass'            => '',
        'elementsWrapper'              => '<div class="bs5-form-stacked-element mb-3"></div>',
        'checkboxWrapper'              => '<div class="form-check"></div>',
        'radioWrapper'                 => '<div class="form-check"></div>',
        'helperWrapper'                => '<span class="form-text"></span>',
        'buttonWrapper'                => '<div class="mb-3"></div>',
        'wrapElementsIntoLabels'       => false,
        'wrapCheckboxesIntoLabels'     => false,
        'wrapRadiobtnsIntoLabels'      => false,
        'elementsClass'                => 'form-control',
        'wrapperErrorClass'            => '',
        'elementsErrorClass'           => 'is-invalid',
        'textErrorClass'               => 'invalid-feedback w-100 d-block',
        'verticalLabelWrapper'         => false,
        'verticalLabelClass'           => 'form-label',
        'verticalCheckboxLabelClass'   => 'form-label',
        'verticalRadioLabelClass'      => 'form-label',
        'horizontalLabelWrapper'       => false,
        'horizontalLabelClass'         => 'col-form-label',
        'horizontalLabelCol'           => 'col-sm-4',
        'horizontalOffsetCol'          => 'col-sm-offset-4',
        'horizontalElementCol'         => 'col-sm-8',
        'inlineCheckboxLabelClass'     => 'form-check-label',
        'inlineRadioLabelClass'        => 'form-check-label',
        'inlineCheckboxWrapper'        => '<div class="form-check form-check-inline"></div>',
        'inlineRadioWrapper'           => '<div class="form-check form-check-inline"></div>',
        'iconBeforeWrapper'            => '<div class="input-group-text"></div>',
        'iconAfterWrapper'             => '<div class="input-group-text"></div>',
        'btnGroupClass'                => 'btn-group',
        'requiredMark'                 => '<sup class="text-danger">* </sup>',
        'openDomReady'                 => 'document.addEventListener(\'DOMContentLoaded\', function(event) {',
        'closeDomReady'                => '});'
    );

    protected $bulma_options = array(
        'formHorizontalClass'          => '',
        'formVerticalClass'            => '',
        'elementsWrapper'              => '<div class="field"></div>',
        'checkboxWrapper'              => '<div class="control"></div>',
        'radioWrapper'                 => '<div class="control"></div>',
        'helperWrapper'                => '<span class="help"></span>',
        'buttonWrapper'                => '<div class="field"></div>',
        'wrapElementsIntoLabels'       => false,
        'wrapCheckboxesIntoLabels'     => true,
        'wrapRadiobtnsIntoLabels'      => true,
        'elementsClass'                => '',
        'wrapperErrorClass'            => '',
        'elementsErrorClass'           => 'is-danger',
        'textErrorClass'               => 'help is-danger',
        'verticalLabelWrapper'         => false,
        'verticalLabelClass'           => 'label',
        'verticalCheckboxLabelClass'   => 'checkbox',
        'verticalRadioLabelClass'      => 'radio',
        'horizontalLabelWrapper'       => true,
        'horizontalLabelClass'         => 'label',
        'horizontalLabelCol'           => 'column is-4',
        'horizontalOffsetCol'          => 'is-offset-4',
        'horizontalElementCol'         => 'column',
        'inlineCheckboxLabelClass'     => 'checkbox',
        'inlineRadioLabelClass'        => 'radio',
        'inlineCheckboxWrapper'        => '<div class="control"></div>',
        'inlineRadioWrapper'           => '<div class="control"></div>',
        'iconBeforeWrapper'            => '<span class="icon is-small is-left"></span>',
        'iconAfterWrapper'             => '<span class="icon is-small is-right"></span>',
        'btnGroupClass'                => 'field btn-group has-addons',
        'requiredMark'                 => '<sup class="has-text-danger">* </sup>',
        'openDomReady'                 => 'document.addEventListener(\'DOMContentLoaded\', function(event) {',
        'closeDomReady'                => '});'
    );

    protected $foundation_options = array(
        'formHorizontalClass'          => 'form-horizontal',
        'formVerticalClass'            => '',
        'elementsWrapper'              => '<div class="grid-x grid-padding-x"></div>',
        'checkboxWrapper'              => '<div class="foundation-checkbox"></div>',
        'radioWrapper'                 => '<div class="foundation-radio"></div>',
        'helperWrapper'                => '<p class="help-text"></p>',
        'buttonWrapper'                => '<div class="grid-x grid-padding-x"></div>',
        'wrapElementsIntoLabels'       => false,
        'wrapCheckboxesIntoLabels'     => false,
        'wrapRadiobtnsIntoLabels'      => false,
        'elementsClass'                => '',
        'wrapperErrorClass'            => '',
        'elementsErrorClass'           => 'is-invalid-input',
        'textErrorClass'               => 'form-error is-visible',
        'verticalLabelWrapper'         => true,
        'verticalLabelClass'           => 'small-12 cell',
        'verticalCheckboxLabelClass'   => '',
        'verticalRadioLabelClass'      => '',
        'horizontalLabelWrapper'       => true,
        'horizontalLabelClass'         => '',
        'horizontalLabelCol'           => 'small-12 medium-4 cell',
        'horizontalOffsetCol'          => 'medium-offset-4',
        'horizontalElementCol'         => 'small-12 medium-8 cell',
        'inlineCheckboxLabelClass'     => 'checkbox-inline',
        'inlineRadioLabelClass'        => 'radio-inline',
        'inlineCheckboxWrapper'        => '<div class="checkbox-inline"></div>',
        'inlineRadioWrapper'           => '<div class="radio-inline"></div>',
        'iconBeforeWrapper'            => '',
        'iconAfterWrapper'             => '',
        'btnGroupClass'                => 'button-group',
        'requiredMark'                 => '<sup style="color:red">* </sup>',
        'openDomReady'                 => 'jQuery(document).ready(function($) {',
        'closeDomReady'                => '});'
    );

    protected $material_options = array(
        'formHorizontalClass'          => 'form-horizontal',
        'formVerticalClass'            => '',
        'elementsWrapper'              => '<div class="row form-group"></div>',
        'checkboxWrapper'              => '<div class="checkbox"></div>',
        'radioWrapper'                 => '<div class="radio"></div>',
        'helperWrapper'                => '<small class="form-text text-muted"></small>',
        'buttonWrapper'                => '<div class="form-group"></div>',
        'wrapElementsIntoLabels'       => false,
        'wrapCheckboxesIntoLabels'     => true,
        'wrapRadiobtnsIntoLabels'      => true,
        'elementsClass'                => 'form-control',
        'wrapperErrorClass'            => '',
        'elementsErrorClass'           => 'is-invalid',
        'textErrorClass'               => 'invalid-feedback red-text text-darken-2',
        'verticalLabelWrapper'         => false,
        'verticalLabelClass'           => '',
        'verticalCheckboxLabelClass'   => '',
        'verticalRadioLabelClass'      => '',
        'horizontalLabelWrapper'       => false,
        'horizontalLabelClass'         => '',
        'horizontalLabelCol'           => 'col s4',
        'horizontalOffsetCol'          => 'offset-s4',
        'horizontalElementCol'         => 'input-field col s8',
        'inlineCheckboxLabelClass'     => 'checkbox-inline',
        'inlineRadioLabelClass'        => 'radio-inline',
        'inlineCheckboxWrapper'        => '',
        'inlineRadioWrapper'           => '',
        'iconBeforeWrapper'            => '',
        'iconAfterWrapper'             => '',
        'btnGroupClass'                => 'btn-group',
        'requiredMark'                 => '<sup class="text-danger">* </sup>',
        'openDomReady'                 => 'document.addEventListener(\'DOMContentLoaded\', function(event) {',
        'closeDomReady'                => '});'
    );

    protected $tailwind_options = array(
        'formHorizontalClass'          => 'form-horizontal',
        'formVerticalClass'            => '',
        'elementsWrapper'              => '<div class="grid grid-cols-1 mb-7"></div>',
        'checkboxWrapper'              => '<div class="grid grid-cols-1 mb-2 whitespace-nowrap"></div>',
        'radioWrapper'                 => '<div class="grid grid-cols-1 mb-2 whitespace-nowrap"></div>',
        'helperWrapper'                => '<p class="text-sm text-gray-700"></p>',
        'buttonWrapper'                => '<div class="grid grid-cols-1 mb-7"></div>',
        'wrapElementsIntoLabels'       => false,
        'wrapCheckboxesIntoLabels'     => false,
        'wrapRadiobtnsIntoLabels'      => false,
        'elementsClass'                => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
        'wrapperErrorClass'            => '',
        'elementsErrorClass'           => 'is-invalid-input',
        'textErrorClass'               => 'absolute text-sm text-red-600',
        'verticalLabelWrapper'         => false,
        'verticalLabelClass'           => 'block mb-2',
        'verticalCheckboxLabelClass'   => 'block ml-2',
        'verticalRadioLabelClass'      => 'block ml-2',
        'horizontalLabelWrapper'       => true,
        'horizontalLabelClass'         => 'block py-2',
        'horizontalLabelCol'           => 'col-span-4',
        'horizontalOffsetCol'          => 'col-start-4',
        'horizontalElementCol'         => 'col-span-8 relative',
        'inlineCheckboxLabelClass'     => 'checkbox-inline',
        'inlineRadioLabelClass'        => 'radio-inline inline-block ml-2 text-sm font-medium text-gray-900 dark:text-gray-300',
        'inlineCheckboxWrapper'        => '<span class="flex-initial flex-nowrap items-center mb-4 mr-6"></span>',
        'inlineRadioWrapper'           => '<span class="flex-initial flex-nowrap items-center mb-4 mr-6"></span>',
        'iconAfterWrapper'             => '<div class="icon is-right flex absolute inset-y-0 right-0 items-center pr-3 pointer-events-none z-10"></div>',
        'iconBeforeWrapper'            => '<div class="flex absolute icon is-left inset-y-0 left-0 items-center pl-3 pointer-events-none z-10"></div>',
        'btnGroupClass'                => 'inline-flex rounded-md shadow-sm',
        'requiredMark'                 => '<sup class="text-red-400">* </sup>',
        'openDomReady'                 => 'document.addEventListener(\'DOMContentLoaded\', function(event) {',
        'closeDomReady'                => '});'
    );

    protected $uikit_options = array(
        'formHorizontalClass'          => 'form-horizontal',
        'formVerticalClass'            => 'uk-form-stacked',
        'elementsWrapper'              => '<div class="uk-form-stacked-element uk-margin"></div>',
        'checkboxWrapper'              => '<div class="uk-form-stacked-element uk-margin"></div>',
        'radioWrapper'                 => '<div class="uk-form-stacked-element uk-margin"></div>',
        'helperWrapper'                => '<p class="uk-text-muted uk-margin-remove"></p>',
        'buttonWrapper'                => '<div class="uk-form-stacked-element uk-margin"></div>',
        'wrapElementsIntoLabels'       => false,
        'wrapCheckboxesIntoLabels'     => false,
        'wrapRadiobtnsIntoLabels'      => false,
        'elementsClass'                => '',
        'wrapperErrorClass'            => '',
        'elementsErrorClass'           => 'uk-form-danger',
        'textErrorClass'               => 'uk-text-danger uk-margin-remove-top',
        'verticalLabelWrapper'         => false,
        'verticalLabelClass'           => 'uk-form-label',
        'verticalCheckboxLabelClass'   => 'uk-form-label',
        'verticalRadioLabelClass'      => 'uk-form-label',
        'horizontalLabelWrapper'       => false,
        'horizontalLabelClass'         => 'uk-form-label',
        'horizontalLabelCol'           => 'uk-width-1-3@s',
        'horizontalOffsetCol'          => '',
        'horizontalElementCol'         => 'uk-form-controls uk-width-2-3@s',
        'inlineCheckboxLabelClass'     => 'uk-form-label',
        'inlineRadioLabelClass'        => 'uk-form-label',
        'inlineCheckboxWrapper'        => '<span class="uk-flex-inline uk-flex-middle uk-margin-right uk-margin-small-bottom uk-text-nowrap"></span>',
        'inlineRadioWrapper'           => '<span class="uk-flex-inline uk-flex-middle uk-margin-right uk-margin-small-bottom uk-text-nowrap"></span>',
        'iconBeforeWrapper'            => '',
        'iconAfterWrapper'             => '',
        'btnGroupClass'                => 'uk-button-group',
        'requiredMark'                 => '<sup class="uk-text-danger">* </sup>',
        'openDomReady'                 => 'document.addEventListener(\'DOMContentLoaded\', function(event) {',
        'closeDomReady'                => '});'
    );

    protected $shared_options = [
        'ajax'           => false,
        'centerContent'  => [
            'center' => false,
            'stack'  => false
        ],
        'deferScripts'  => true,
        'loadJsBundle'  => '',
        'useLoadJs'     => false
    ];

    /* error fields + messages */

    protected $errors   = array();
    protected $error_fields = array();

    /* layout */

    protected $layout; /* horizontal | vertical */

    /* init (no need to change anything here) */

    protected $checkbox                   = array();
    protected $checkbox_end_wrapper       = '';
    protected $checkbox_start_wrapper     = '';
    protected $current_dependent_data     = array();
    protected $elements_end_wrapper       = '';
    protected $elements_start_wrapper     = '';
    protected $end_fieldset               = '';
    protected $form_start_wrapper         = '';
    protected $form_end_wrapper           = '';
    protected $fileuploader_count         = 0;
    protected $fields_with_addons         = [];
    protected $fields_with_helpers        = [];
    protected $fields_with_icons          = [];
    public $framework                     = '';
    protected $group_name                 = array();
    protected $has_hcaptcha_error         = false;
    protected $hcaptcha_error_text        = '';
    protected $hasAccordion               = false;
    protected $hasDependentField          = false;
    protected $has_file                   = false;
    protected $has_recaptcha_error        = false;
    protected $helper_end_wrapper         = '';
    protected $helper_start_wrapper       = '';
    protected $hidden_fields              = '';
    protected $html_element_content       = array(); // ex : $this->html_element_content[$element_name][$pos][]  = $html
    protected $icon_after_end_wrapper     = '';
    protected $icon_after_start_wrapper   = '';
    protected $icon_before_end_wrapper    = '';
    protected $icon_before_start_wrapper  = '';
    protected $input_grouped              = array();
    protected $input_wrapper              = array();
    protected $method                     = 'POST';
    protected $option                     = array();
    protected $optiongroup_ID             = array();
    protected $plugins_path               = '';
    public $plugins_url                   = '';
    protected $radio                      = array();
    protected $radio_end_wrapper          = '';
    protected $radio_start_wrapper        = '';
    protected $recaptcha_js_callback      = '';
    protected $recaptcha_error_text       = '';
    protected $token                      = '';
    protected $txt                        = '';
    public $error_msg                     = '';
    public $html                          = '';

    /* plugins */

    protected $js_plugins         = array();

    protected $css_includes       = array();
    protected $js_includes        = array();
    protected $js_code            = '';
    protected $has_popover        = false;
    protected $popover_js_code    = '';

    /**
     * Defines the layout (horizontal | vertical).
     * Default is 'horizontal'
     * Clears values from the PHP session if self::clear has been called before
     * Catches posted errors
     * Adds hidden field with the form ID
     * Sets elements wrappers
     *
     * @param string $form_ID   The ID of the form
     * @param string $layout    (Optional) Can be 'horizontal' or 'vertical'
     * @param string $attr      (Optional) Can be any HTML input attribute or js event EXCEPT class
     *                          (class is defined in layout param).
     *                          attributes must be listed separated with commas.
     *                          Example : novalidate,onclick=alert(\'clicked\');
     * @param string $framework (Optional) bs4 | bs5 | bulma | foundation | material | tailwind | uikit
     *                          (Bootstrap 4, Bootstrap 5, Bulma, Foundation, Material design, Tailwind, UIkit
     * @return $this
     */
    public function __construct($form_ID, $layout = 'horizontal', $attr = '', $framework = 'bs5')
    {
        if (Form::$instances === null) {
            Form::$instances = array(
                'css_files' => array(),
                'js_files' => array()
            );
        }
        $this->action      = '';
        $this->form_attr   = $attr;
        $this->form_ID     = $form_ID;
        $this->framework   = $framework;
        $this->layout      = $layout;
        $this->mode        = 'production';
        $this->token       = $this->generateToken();
        $this->setPluginsUrl();

        // check registration
        if ($this->checkRegistration() !== true) {
            $msg = 'Your copy of PHP Form Builder is NOT authorized.<br><a href="https://www.phpformbuilder.pro/index.html#license-registration" title="About PHP Form Builder License" style="color:#fff;text-decoration:underline;">About PHP Form Builder License</a>';
            $this->buildErrorMsg($msg);
        }

        // check if the server's PHP SESSION parameters are correct
        if (isset($_POST[$form_ID]) && !isset($_SESSION[$form_ID]) && $this->mode === 'development') {
            $this->buildErrorMsg('PHP SESSION ERROR<br>You have an error in your PHP SESSION settings. Please refer to the help center here:<br><a href="https://www.phpformbuilder.pro/documentation/help-center.php#php-session-settings-error" style="color:#fff">https://www.phpformbuilder.pro/documentation/help-center.php#php-session-settings-error</a>.');
        }

        // set framework options
        if ($framework == 'bs4') {
            $this->options = array_merge($this->bs4_options, $this->shared_options);
            if ($layout == 'horizontal') {
                $this->options['elementsWrapper']       = '<div class="form-group row"></div>';
                $this->options['checkboxWrapper']       = '<div class="form-check"></div>';
                $this->options['radioWrapper']          = '<div class="form-check"></div>';
                $this->options['buttonWrapper']         = '<div class="form-group row"></div>';
            }
        } elseif ($framework == 'bs5') {
            $this->options = array_merge($this->bs5_options, $this->shared_options);
            if ($layout == 'horizontal') {
                $this->options['elementsWrapper']       = '<div class="row mb-3"></div>';
                $this->options['checkboxWrapper']       = '<div class="form-check"></div>';
                $this->options['radioWrapper']          = '<div class="form-check"></div>';
                $this->options['buttonWrapper']         = '<div class="row"></div>';
            }
        } elseif ($framework == 'bulma') {
            $this->options = array_merge($this->bulma_options, $this->shared_options);
            if ($layout == 'horizontal') {
                $this->options['elementsWrapper']       = '<div class="field is-horizontal"></div>';
                $this->options['buttonWrapper']         = '<div class="field is-horizontal"></div>';
            }
        } elseif ($framework == 'foundation') {
            $this->options = array_merge($this->foundation_options, $this->shared_options);
            if ($layout !== 'horizontal') {
                $this->setOptions(
                    array(
                        'wrapElementsIntoLabels' => true,
                        'horizontalElementCol'   => 'small-12 cell'
                    )
                );
            }
        } elseif ($framework == 'material') {
            $this->options = array_merge($this->material_options, $this->shared_options);
            if ($layout !== 'horizontal') {
                $this->setOptions(array('elementsWrapper' => '<div class="row"><div class="input-field col s12"></div></div>'));
            }
        } elseif ($framework == 'tailwind') {
            $this->options = array_merge($this->tailwind_options, $this->shared_options);
            if ($layout == 'horizontal') {
                $this->options['elementsWrapper']       = '<div class="grid grid-cols-12 gap-4 mb-7"></div>';
                $this->options['checkboxWrapper']       = '<div class="flex items-center my-2 whitespace-nowrap"></div>';
                $this->options['radioWrapper']          = '<div class="flex items-center my-2 whitespace-nowrap"></div>';
                $this->options['buttonWrapper']         = '<div class="grid grid-cols-12 gap-4 mb-7"></div>';
            }
        } elseif ($framework == 'uikit') {
            $this->options = array_merge($this->uikit_options, $this->shared_options);
            if ($layout == 'horizontal') {
                $this->options['elementsWrapper']       = '<div class="uk-grid uk-flex-right uk-width-1-1"></div>';
                $this->options['checkboxWrapper']       = '<div class="uk-flex mb-2"></div>';
                $this->options['radioWrapper']          = '<div class="uk-flex mb-2"></div>';
                $this->options['buttonWrapper']         = '<div class="uk-grid uk-flex-right"></div>';
            }
        }
        if (!isset($_SESSION['clear_form'][$form_ID])) {
            $_SESSION['clear_form'][$form_ID] = false;
        } elseif ($_SESSION['clear_form'][$form_ID] === true) {
            $_SESSION['clear_form'][$form_ID] = false; // reset after clearing
        } elseif (isset($_POST[$form_ID])) {
            self::registerValues($form_ID);
        }
        if (isset($_SESSION['errors'][$form_ID])) {
            $this->registerErrors();
            unset($_SESSION['errors'][$form_ID]);
        }
        $this->checkbox_end_wrapper          = $this->getElementWrapper($this->options['checkboxWrapper'], 'end');
        $this->checkbox_start_wrapper        = $this->getElementWrapper($this->options['checkboxWrapper'], 'start');
        $this->elements_end_wrapper          = $this->getElementWrapper($this->options['elementsWrapper'], 'end');
        $this->elements_start_wrapper        = $this->getElementWrapper($this->options['elementsWrapper'], 'start');
        $this->helper_end_wrapper            = $this->getElementWrapper($this->options['helperWrapper'], 'end');
        $this->helper_start_wrapper          = $this->getElementWrapper($this->options['helperWrapper'], 'start');
        $this->icon_after_end_wrapper   = $this->getElementWrapper($this->options['iconAfterWrapper'], 'end');
        $this->icon_after_start_wrapper = $this->getElementWrapper($this->options['iconAfterWrapper'], 'start');
        $this->icon_before_end_wrapper   = $this->getElementWrapper($this->options['iconBeforeWrapper'], 'end');
        $this->icon_before_start_wrapper = $this->getElementWrapper($this->options['iconBeforeWrapper'], 'start');
        $this->inline_checkbox_end_wrapper   = $this->getElementWrapper($this->options['inlineCheckboxWrapper'], 'end');
        $this->inline_checkbox_start_wrapper = $this->getElementWrapper($this->options['inlineCheckboxWrapper'], 'start');
        $this->inline_radio_end_wrapper      = $this->getElementWrapper($this->options['inlineRadioWrapper'], 'end');
        $this->inline_radio_start_wrapper    = $this->getElementWrapper($this->options['inlineRadioWrapper'], 'start');
        $this->radio_end_wrapper             = $this->getElementWrapper($this->options['radioWrapper'], 'end');
        $this->radio_start_wrapper           = $this->getElementWrapper($this->options['radioWrapper'], 'start');
        $this->button_start_wrapper   = $this->getElementWrapper($this->options['buttonWrapper'], 'start');
        $this->button_end_wrapper     = $this->getElementWrapper($this->options['buttonWrapper'], 'end');
        $this->addInput('hidden', $form_ID . '-token', $this->token);
        $this->addInput('hidden', $form_ID, true);

        $registered_frameworks = ['bs4', 'bs5', 'bulma', 'foundation', 'material', 'tailwind', 'uikit'];
        if (in_array($this->framework, $registered_frameworks)) {
            $this->addPlugin('frameworks/' . $this->framework, '#' . $form_ID);

            // register the framework to get the sendMail() sent_message html alert code after posting
            // if the form uses the materialize plugin, $_SESSION['phpfb_framework'] will switch to 'material-bootstrap'
            $_SESSION['phpfb_framework'] = $this->framework;
        }

        // accordion plugin
        if (strpos($attr, 'data-accordion') !== false) {
            $this->hasAccordion = true;
            $this->addPlugin('accordion', '#' . $form_ID);
        }

        return $this;
    }

    /**
     * set sending method
     * @param string $method POST|GET
     * @return $this
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    public function setLayout($layout)
    {
        $this->layout = $layout;

        return $this;
    }

    /**
     * set the form mode to 'development' or 'production'
     * in production mode, all the plugins dependencies are combined and compressed in a single css or js file.
     * the css | js files are saved in plugins/min/css and plugins/min/js folders.
     * these 2 folders have to be wrirable (chmod 0755+)
     * @param string $mode 'development' | 'production'
     * @return $this
     */
    public function setMode($mode)
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * Redefines form action
     *
     * @param boolean $add_get_vars (Optional) If $add_get_vars is set to false,
     *                              url vars will be removed from destination page.
     *                              Example : www.myUrl.php?var=value => www.myUrl.php
     *
     * @return $this
     */
    public function setAction($url, $add_get_vars = true)
    {
        $this->action = $url;
        $this->add_get_vars = $add_get_vars;

        return $this;
    }

    /**
     * Sets form layout options to match your framework
     *
     * @param array $user_options (Optional) An associative array containing the
     *                            options names as keys and values as data.
     * @return $this
     */
    public function setOptions($user_options = array())
    {
        $formClassOptions = [
            'ajax', 'btnGroupClass', 'buttonWrapper', 'centerContent', 'checkboxWrapper', 'closeDomReady', 'deferScripts', 'elementsClass', 'elementsErrorClass', 'elementsWrapper', 'formHorizontalClass', 'formVerticalClass', 'helperWrapper', 'horizontalElementCol', 'horizontalLabelClass', 'horizontalLabelCol', 'horizontalLabelWrapper', 'horizontalOffsetCol', 'iconAfterWrapper', 'iconBeforeWrapper', 'inlineCheckboxLabelClass', 'inlineCheckboxWrapper', 'inlineRadioLabelClass', 'inlineRadioWrapper', 'loadJsBundle', 'openDomReady', 'radioWrapper', 'requiredMark', 'textErrorClass', 'useLoadJs', 'verticalCheckboxLabelClass', 'verticalLabelClass', 'verticalLabelWrapper', 'verticalRadioLabelClass', 'wrapCheckboxesIntoLabels', 'wrapElementsIntoLabels', 'wrapperErrorClass', 'wrapRadiobtnsIntoLabels'
        ];
        foreach ($user_options as $key => $value) {
            if (in_array($key, $formClassOptions)) {
                $this->options[$key] = $value;

                /* redefining starting & ending wrappers */

                if ($key == 'ajax' & $value === true) {
                    // disable defered scripts & replace domready with a 'loadAjaxForm' CustomEvent if ajax form
                    $this->options['deferScripts']  = false;
                    $this->options['openDomReady']  = 'document.addEventListener(\'loadAjaxForm' . $this->form_ID . '\', function (e) {';
                } elseif ($key == 'elementsWrapper') {
                    $this->elements_start_wrapper = $this->getElementWrapper($this->options['elementsWrapper'], 'start');
                    $this->elements_end_wrapper   = $this->getElementWrapper($this->options['elementsWrapper'], 'end');
                } elseif ($key == 'checkboxWrapper') {
                    $this->checkbox_start_wrapper = $this->getElementWrapper($this->options['checkboxWrapper'], 'start');
                    $this->checkbox_end_wrapper   = $this->getElementWrapper($this->options['checkboxWrapper'], 'end');
                } elseif ($key == 'iconAfterWrapper') {
                    $this->icon_after_start_wrapper = $this->getElementWrapper($this->options['iconAfterWrapper'], 'start');
                    $this->icon_after_end_wrapper   = $this->getElementWrapper($this->options['iconAfterWrapper'], 'end');
                } elseif ($key == 'iconBeforeWrapper') {
                    $this->icon_before_start_wrapper = $this->getElementWrapper($this->options['iconBeforeWrapper'], 'start');
                    $this->icon_before_end_wrapper   = $this->getElementWrapper($this->options['iconBeforeWrapper'], 'end');
                } elseif ($key == 'inlineCheckboxWrapper') {
                    $this->inline_checkbox_start_wrapper = $this->getElementWrapper($this->options['inlineCheckboxWrapper'], 'start');
                    $this->inline_checkbox_end_wrapper   = $this->getElementWrapper($this->options['inlineCheckboxWrapper'], 'end');
                } elseif ($key == 'inlineRadioWrapper') {
                    $this->inline_radio_start_wrapper = $this->getElementWrapper($this->options['inlineRadioWrapper'], 'start');
                    $this->inline_radio_end_wrapper   = $this->getElementWrapper($this->options['inlineRadioWrapper'], 'end');
                } elseif ($key == 'helperWrapper') {
                    $this->helper_start_wrapper = $this->getElementWrapper($this->options['helperWrapper'], 'start');
                    $this->helper_end_wrapper   = $this->getElementWrapper($this->options['helperWrapper'], 'end');
                } elseif ($key == 'radioWrapper') {
                    $this->radio_start_wrapper = $this->getElementWrapper($this->options['radioWrapper'], 'start');
                    $this->radio_end_wrapper   = $this->getElementWrapper($this->options['radioWrapper'], 'end');
                } elseif ($key == 'buttonWrapper') {
                    $this->button_start_wrapper = $this->getElementWrapper($this->options['buttonWrapper'], 'start');
                    $this->button_end_wrapper   = $this->getElementWrapper($this->options['buttonWrapper'], 'end');
                }
            }
        }

        return $this;
    }

    /**
     * load scripts with loadJS
     * https://github.com/muicss/loadjs
     * @param  string $bundle   optional loadjs bundle name to wait for
     * @return void
     */
    public function useLoadJs($bundle = '')
    {
        $this->setOptions(array('useLoadJs' => true, 'loadJsBundle' => $bundle));
    }

    /**
     * get plugins folder url from Form.php path + DOCUMENT_ROOT path
     * plugins_url will be the complete url to plugins dir
     * i.e. http(s)://www.your-site.com[/subfolder(s)]/phpformbuilder/plugins/
     *
     * @param string $forced_url    optional URL
     */
    public function setPluginsUrl($forced_url = '')
    {
        // reliable document_root (https://gist.github.com/jpsirois/424055)
        $script_name     = str_replace(DIRECTORY_SEPARATOR, '/', $_SERVER['SCRIPT_NAME']);
        $script_filename = str_replace(DIRECTORY_SEPARATOR, '/', $_SERVER['SCRIPT_FILENAME']);
        $root_path       = str_replace($script_name, '', $script_filename);
        if (!empty($forced_url)) {
            $this->plugins_url = $forced_url;
        } elseif (empty($this->plugins_url)) {
            $form_class_path    = dirname(__FILE__);
            $this->plugins_path = $form_class_path . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR;

            // reliable document_root with symlinks resolved
            $info = new \SplFileInfo($root_path);
            $real_root_path = $info->getRealPath();

            // sanitize directory separator
            $form_class_path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $form_class_path);
            $real_root_path  = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $real_root_path);

            $this->plugins_url = (((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/' . ltrim(str_replace(array($real_root_path, DIRECTORY_SEPARATOR), array('', '/'), $this->plugins_path), '/');
        }
    }
}
