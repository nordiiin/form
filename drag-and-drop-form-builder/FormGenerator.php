<?php

namespace dragNDropFormGenerator;

use phpformbuilder\Form;

/**
 * Class FormGenerator
 *
 * @version 0.1
 * @author Gilles Migliori - gilles.migliori@gmail.com
 *
 */

class FormGenerator
{

    public $json_form;
    public $icon_font_url = '';
    private $json_form_sections;
    private $autoloaded_plugins = array('bootstrap-select', 'bootstrap-input-spinner', 'colorpicker', 'intl-tel-input', 'ladda', 'select2');

    private $current_dir;

    /*
    array
    0 =>
        'id' => string
        'componentType' => string 'input'
        'component' =>
            object(stdClass)
            'componentType' => string 'input'
            'helper'        => string
            'plugins'       =>
                array
                0 =>
                    object(stdClass)
                    'objectName'   => string 'Autocomplete'
                    'pluginName'   => string 'autocomplete'
                    'selector'     => string
                    'jsConfig'     => string 'default'
                    'replacements' =>
                        array
                        0 =>
                            object(stdClass)
                            'availableTags' => string '"value 1", "value 2", "add other values ..."'
                    'dataAttributes' => string
            'attr'        => array
            'clazz'       => string
            'index'       => int
            'label'       => string
            'name'        => string
            'placeholder' => string
            'type'        => string
            'value'       => string
            'width'       => string
    */

    // special data
    private $db_fields = array();

    private $hasCaptcha = false;
    private $captchaFieldname = '';

    private $has_recaptcha = false;
    private $recaptcha_private_key = '';

    private $has_hcaptcha = false;
    private $hcaptcha_secret_key = '';

    private $has_jquery_plugins = false;
    private $frameworks_without_jquery = ['bs5', 'bulma', 'tailwind', 'uikit'];

    private $fileuploaders = [];

    private $is_modal = false;
    private $is_popover = false;
    private $popover_data_attributes = '';

    private $current_cols       = '4-8';
    private $currently_centered = false;

    private $has_email_fields = false;
    private $email_field_names = array();

    private $output_preview;
    private $php_form;
    private $php_form_code = array(
        'components'     => array(),
        'global_plugins' => '',
        'head'           => '',
        'if_posted'      => '',
        'main'           => '',
        'message'        => '',
        'scripts'        => '',
        'start_form'     => '',
        'start'          => ''
    );

    private $error_msg = '';

    public function __construct($json_string, $output_preview = true)
    {
        $this->current_dir = $this->getCurrentDir();
        $this->output_preview = $output_preview;

        $json = json_decode($json_string);
        $json_error = $this->getLastJsonError();
        if (!empty($json_error)) {
            $this->error_msg = $json_error;
            $this->buildErrorMsg($this->error_msg);
        } else {
            $this->json_form = $json->userForm;
            $this->json_form_sections = $json->formSections;
            $this->icon_font_url = $this->getIconFont();
            $this->getSpecialData();

            $this->createForm($this->json_form);

            foreach ($this->json_form->plugins as $plugin) {
                $this->addPlugin($plugin, true);
            }

            // Group components depending on their width
            // and the width of the following component
            $this->getSectionGroups();

            foreach ($this->json_form_sections as $section) {
                $this->buildSection($section);
            }

            $this->buildCodeParts();
        }
    }

    public function outputCode()
    {
        $output = '';
        $code_part_index = 1;
        $numbered_class = 'd-inline-block px-3 py-1 rounded-circle bg-info text-info-100 me-3';
        if ($this->json_form->ajax !== 'true') {
            $output .= '<h5 class="fw-light"><span class="' . $numbered_class . '">' . $code_part_index . '</span>Add the following code at the very beginning of your file</h5>';
        } else {
            $output .= '<h5 class="fw-light"><span class="' . $numbered_class . ' mb-3">' . $code_part_index . '</span>Create a <span class="badge bg-secondary fw-normal px-2 py-1">/ajax-forms</span> directory at the root of your project and place a file named <span class="badge bg-secondary fw-normal px-2 py-1">' . $this->json_form->id . '.php</span> in it.<br>Then save the code of the form below in this file</h5>';
        }

        $output .= '<pre><code class="language-php">';
        $output .= $this->php_form_code['main'];
        $output .= '?&gt;</code></pre>';

        $code_part_index++;

        $output .= '<hr class="mt-4">';
        if ($this->json_form->ajax === 'true') {
            $output .= '<h5 class="fw-light"><span class="' . $numbered_class . '">' . $code_part_index . '</span>Add the following code in your page to display the form</h5>';
            $output .= '<pre><code class="language-php">';
            $output .= '&lt;div id="' . $this->json_form->id . '-loader"&gt;&lt;/div&gt;';
            $output .= '</code></pre>';
        } else {
            $output .= '<h5 class="fw-light"><span class="' . $numbered_class . '">' . $code_part_index . '</span>Add the following code between &lt;head&gt;&lt;/head&gt;</h5>';
            $output .= '<pre><code class="language-php">';
            $output .= $this->php_form_code['head'];
            $output .= '</code></pre>';

            $code_part_index++;

            $output .= '<hr class="mt-4">';
            $output .= '<h5 class="fw-light"><span class="' . $numbered_class . '">' . $code_part_index . '</span>Add the following code in your page to display the form</h5>';
            $output .= '<pre><code class="language-php">';
            $output .= $this->php_form_code['render'];
            $output .= '</code></pre>';
        }

        $code_part_index++;

        $requiresjQuery = $this->has_jquery_plugins;
        if (!in_array($this->json_form->framework, $this->frameworks_without_jquery)) {
            $requiresjQuery = true;
        }

        $output .= '<hr class="mt-4">';
        $output .= '<h5 class="fw-light"><span class="' . $numbered_class . '">' . $code_part_index . '</span>Add the following code just before &lt;/body&gt;';
        if ($requiresjQuery) {
            $output .= '<sup class="text-danger ms-2">*</sup>';
        }
        $output .= '</h5>';
        $output .= '<pre><code class="language-php">';
        $output .= $this->php_form_code['scripts'];
        $output .= '</code></pre>';
        if ($requiresjQuery) {
            $output .= '<p><sup class="text-danger me-2">*</sup><span class="text-secondary">jQuery script must have already been added before.</span></p>';
        }

        echo $output;
    }

    public function outputPageCode()
    {
        $output = '';
        if ($this->json_form->ajax === 'true') {
            $output .= '<div class="alert alert-info"><p>Forms loaded with Ajax use 2 files - refer to the <em>Form code</em> tab</p><p class="mb-0">It is therefore not possible to display a complete one-page code here</p></div>';
        } else {
            $page_code = htmlspecialchars(file_get_contents('sample-pages/' . $this->json_form->framework . '.html'));

            $find = array("`\{form-php\}[\r\n]+`", "`\{form-head\}[\r\n]+`", "`\{form\}`", "`\{form-js\}`");

            $head_code = $this->reindentCode($this->php_form_code['head'], 4);
            $render_code = $this->reindentCode($this->php_form_code['render'], 16);
            $scripts_code = $this->reindentCode($this->php_form_code['scripts'], 4);

            $replace = array($this->php_form_code['main'] . "?&gt;\n", $head_code, $render_code, $scripts_code);
            $page_code = preg_replace($find, $replace, $page_code);
            $output .= '<pre><code class="language-php">' . $page_code . '</code></pre>';
        }

        echo $output;
    }

    public function outputPreview()
    {
        if ($this->is_modal) {
            $btn_class = $this->getTriggerButtonClass();
            echo '<button data-micromodal-trigger="modal-' . $this->json_form->id . '" class="' . $btn_class . '">Open the modal form</button>';
        } elseif ($this->is_popover) {
            $btn_class = $this->getTriggerButtonClass();
            echo '<button data-popover-trigger="' . $this->json_form->id . '"' . $this->popover_data_attributes . ' class="' . $btn_class . '">Open the popover form</button>';
        }
        $this->php_form->render();
    }

    public function printJsCode()
    {
        $js_code = '';
        if ($this->has_jquery_plugins && in_array($this->json_form->framework, $this->frameworks_without_jquery)) {
            $js_code .= '<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>';
        }
        $js_code .= $this->php_form->printJsCode(false, false);
        // base_url for tinymce in preview
        echo str_replace(array('location.protocol', 'location.host'), array('window.parent.location.protocol', 'window.parent.location.host'), $js_code);
    }

    private function addAttribute($new_attr_name, $new_attr_value, $comp_attr)
    {
        // create new attr object then push it to the component attributes
        $new_attr = new \stdClass();
        $new_attr->name = $new_attr_name;
        $new_attr->value = $new_attr_value;
        $comp_attr[] = $new_attr;

        return $comp_attr;
    }

    private function addBtn($comp)
    {
        $attrArray = $this->mergeAttributes($comp);
        $attr = $this->getAttributes($attrArray);
        $label = $this->addBtnIcon($comp->icon, $comp->iconPosition, $comp->label);
        if ($this->output_preview) {
            if ($comp->center === 'true') {
                $this->php_form->centerContent();
            }
            $this->php_form->addBtn($comp->type, $comp->name, $comp->value, $label, $attr);
            if ($comp->center === 'true') {
                $this->php_form->centerContent(false);
            }
        }
        if ($comp->center === 'true') {
            $this->php_form_code['components'][] = "\$form->centerContent();\n";
        }
        $this->php_form_code['components'][] = "\$form->addBtn('$comp->type', '$comp->name', '$comp->value', '" . $this->sanitize($label) . "', '" . $this->sanitize($attr) . "');\n";
        if ($comp->center === 'true') {
            $this->php_form_code['components'][] = "\$form->centerContent(false);\n";
        }
    }

    private function addBtngroup($comp)
    {
        foreach ($comp->plugins as $plugin) {
            if ($plugin->pluginName == 'ladda') {
                foreach ($comp->buttons as $btn) {
                    foreach ($plugin->dataAttributes as $attr) {
                        $btn->attr = $this->addAttribute('data-' . $attr->name, $attr->value, $btn->attr);
                    }
                }
            }
        }
        foreach ($comp->buttons as $btn) {
            $attrArray = $this->mergeAttributes($btn);
            $attr = $this->getAttributes($attrArray);
            $label = $this->addBtnIcon($btn->icon, $btn->iconPosition, $btn->label);
            if ($this->output_preview) {
                $this->php_form->addBtn($btn->type, $btn->name, $btn->value, $label, $attr, $comp->name);
            }
            $this->php_form_code['components'][] = "\$form->addBtn('$btn->type', '$btn->name', '$btn->value', '" . $this->sanitize($label) . "', '" . $this->sanitize($attr) . "', '$comp->name');\n";
        }
        if ($this->output_preview) {
            if ($comp->center === 'true') {
                $this->php_form->centerContent();
            }
            $this->php_form->printBtnGroup($comp->name);
            if ($comp->center === 'true') {
                $this->php_form->centerContent(false);
            }
        }
        if ($comp->center === 'true') {
            $this->php_form_code['components'][] = "\$form->centerContent();\n";
        }
        $this->php_form_code['components'][] = "\$form->printBtnGroup('$comp->name');\n";
        if ($comp->center === 'true') {
            $this->php_form_code['components'][] = "\$form->centerContent(false);\n";
        }
    }

    private function addBtnIcon($icon, $iconPosition, $label)
    {
        if (!empty($icon)) {
            $icon_html = '<i class="' . $icon . '" aria-hidden="true"></i>';
            if ($iconPosition === 'before') {
                $label = $icon_html . ' ' . $label;
            } else {
                $label .= ' ' . $icon_html;
            }
        }

        return $label;
    }

    private function addCheckboxGroup($comp)
    {
        $php_code = '';

        $inline = false;
        if (filter_var($comp->inline, FILTER_VALIDATE_BOOLEAN)) {
            $inline = true;
        }

        // helper
        $this->addHelper($comp->helper, $comp->name);

        $attrArray = $this->mergeAttributes($comp);
        $attr = $this->getAttributes($attrArray);

        foreach ($comp->checkboxes as $chk) {
            $chk_attr = array();

            if ($comp->value === $chk->value) {
                $chk_attr[] = 'checked';
            }
            if (isset($chk->disabled) && $chk->disabled === 'true') {
                $chk_attr[] = 'disabled';
            }
            foreach ($chk->attr as $attrib) {
                if (!empty($attrib->value)) {
                    $chk_attr[] = $attrib->name . '=' . $attrib->value;
                }
            }
            $chk_attr = implode(', ', $chk_attr);
            if ($this->output_preview) {
                $this->php_form->addCheckbox($comp->name, $chk->text, $chk->value, $chk_attr);
            }
            $php_code .= "\$form->addCheckbox('$comp->name', '" . $this->sanitize($chk->text) . "', '$chk->value', '" . $this->sanitize($chk_attr) . "');\n";
        }
        if ($this->output_preview) {
            if ($comp->center === 'true') {
                $this->php_form->centerContent();
            }
            $this->php_form->printCheckboxGroup($comp->name, $comp->label, $inline, $attr);
            if ($comp->center === 'true') {
                $this->php_form->centerContent(false);
            }
        }
        if ($comp->center === 'true') {
            $php_code .= "\$form->centerContent();\n";
        }
        $php_code .= "\$form->printCheckboxGroup('$comp->name', '" . $this->sanitize($comp->label) . "', $comp->inline, '" . $this->sanitize($attr) . "');\n";
        if ($comp->center === 'true') {
            $php_code .= "\$form->centerContent(false);\n";
        }
        $this->php_form_code['components'][] = $php_code;
        foreach ($comp->plugins as $plugin) {
            if (!in_array($plugin->pluginName, $this->autoloaded_plugins)) {
                $this->addPlugin($plugin);
            }
        }
    }

    /**
     * addGroupInputs
     *
     * @param  array $group = ['fieldname-1', 'firldname-2']
     * @return void
     */
    private function addGroupInputs($group)
    {
        if ($this->output_preview) {
            if (isset($group[2])) {
                $this->php_form->groupElements($group[0], $group[1], $group[2]);
            } else {
                $this->php_form->groupElements($group[0], $group[1]);
            }
        }
        if (isset($group[2])) {
            $this->php_form_code['components'][] = "\$form->groupElements('$group[0]', '$group[1]', '$group[2]');\n";
        } else {
            $this->php_form_code['components'][] = "\$form->groupElements('$group[0]', '$group[1]');\n";
        }
    }

    private function addFileuploader($comp)
    {
        $this->has_jquery_plugins = true;
        $this->fileuploaders[] = [
            'index'      => $comp->index,
            'fieldname'  => $comp->name,
            'upload_dir' => '../../../../..' . $comp->uploadDir
        ];
        $this->addHelper($comp->helper, $comp->name);
        if (empty($comp->extensions)) {
            $extensions = null;
        } else {
            $extensions = "['" . preg_replace('`([\s]?),([\s]?)`', "', '", $comp->extensions) . "']";
        }
        if ($this->output_preview) {
            $fileUpload_config = array(
                'upload_dir'    => '../../../../..' . $comp->uploadDir,
                'limit'         => $comp->limit,
                'file_max_size' => $comp->fileMaxSize,
                'debug'         => true
            );
            if ($extensions !== null) {
                $fileUpload_config['extensions']  = $extensions;
            }
            if ($comp->xml === 'image-upload') {
                $fileUpload_config['thumbnails']  = $comp->thumbnails;
                $fileUpload_config['editor']      = $comp->editor;
                $fileUpload_config['widthImg']    = $comp->widthImg;
                $fileUpload_config['heightImg']   = $comp->heightImg;
                $fileUpload_config['crop']        = $comp->crop;
            }
            $alert = Form::buildAlert('Information: The file upload is just simulated in the preview. No file is really uploaded.', $this->json_form->framework, 'info');
            $this->php_form->addHtml($alert);
            $this->php_form->addFileUpload($comp->name, '', $comp->label, '', $fileUpload_config);
        }

        $phpfcode = [];

        $phpfcode[] = "
// Prefill upload with existing file
\$current_file = ''; // default empty

\$current_file_path = '../../../../..$comp->uploadDir';

/* INSTRUCTIONS:
    If you get a filename from your database or anywhere
    and want to prefill the uploader with this file,
    replace \"filename.ext\" with your filename variable in the line below.
*/
\$current_file_name = 'filename.ext';

if (file_exists(\$current_file_path . \$current_file_name)) {
    \$current_file_size = filesize(\$current_file_path . \$current_file_name);
    \$current_file_type = mime_content_type(\$current_file_path . \$current_file_name);
    \$current_file = array(
        'name' => \$current_file_name,
        'size' => \$current_file_size,
        'type' => \$current_file_type,
        'file' => \$current_file_path . \$current_file_name, // url of the file
        'data' => array(
            'listProps' => array(
                'file' => \$current_file_name
            )
        )
    );
}\n\n";
        $phpfcode[] = "\$fileUpload_config = array(
    'upload_dir'    => '../../../../..$comp->uploadDir',
    'limit'         => $comp->limit,
    'file_max_size' => $comp->fileMaxSize,";
        if ($extensions !== null) {
            $phpfcode[] = "'extensions'    => $extensions,";
        }
        if ($comp->xml === 'image-upload') {
            $phpfcode[] = "
    'thumbnails'    => $comp->thumbnails,
    'editor'        => $comp->editor,
    'widthImg'      => $comp->widthImg,
    'heightImg'     => $comp->heightImg,
    'crop'          => $comp->crop,";
        }
        $phpfcode[] = "\n    'debug'         => true\n);\n";

        $phpfcode[] = "\$form->addFileUpload('$comp->name', '', '" . $this->sanitize($comp->label) . "', '', \$fileUpload_config, \$current_file);\n";

        $this->php_form_code['components'][] = implode('', $phpfcode);
    }

    private function addHelper($helper, $name)
    {
        if (!empty($helper)) {
            if ($this->output_preview) {
                $this->php_form->addHelper($helper, $name);
            }
            $this->php_form_code['components'][] = "\$form->addHelper('" . $this->sanitize($helper) . "', '$name');\n";
        }
    }

    private function addIcon($icon, $iconPosition, $name)
    {
        if (!empty($icon)) {
            if ($this->json_form->framework === 'foundation') {
                $icon = 'input-group-label ' . $icon;
            } elseif ($this->json_form->framework === 'uikit') {
                $icon = 'uk-form-icon ' . $icon;
            }
            $icon_html = '<i class="' . $icon . '" aria-label="hidden"></i>';
            if (strpos($icon, 'material') > -1) {
                $ic = explode(' ', $icon);
                if (isset($ic[2])) {
                    $icon_class = $ic[0];
                    $icon_text = $ic[1];
                } else {
                    $icon_class = $ic[0] . ' ' . $ic[1];
                    $icon_text = $ic[2];
                }
                $icon_html = '<i class="' . $icon_class . '" aria-label="hidden">' . $icon_text . '</i>';
            }
            if ($this->output_preview) {
                $this->php_form->addIcon($name, $icon_html, $iconPosition);
            }
            $this->php_form_code['components'][] = "\$form->addIcon( '$name', '" . $this->sanitize($icon_html) . "', '$iconPosition');\n";
        }
    }

    private function addHtml($comp)
    {
        if ($this->output_preview) {
            $this->php_form->addHtml($comp->value);
        }
        $this->php_form_code['components'][] = "\$form->addHtml('" . $this->sanitize($comp->value) . "');\n";
    }

    private function addInput($comp)
    {
        foreach ($comp->plugins as $plugin) {
            if ($plugin->pluginName == 'colorpicker') {
                $comp->attr = $this->addAttribute('data-colorpicker', 'true', $comp->attr);
            }
        }
        $attrArray = $this->mergeAttributes($comp);
        $attr = $this->getAttributes($attrArray);
        $this->addIcon($comp->icon, $comp->iconPosition, $comp->name);
        $this->addHelper($comp->helper, $comp->name);
        if ($this->output_preview) {
            $this->php_form->addInput($comp->type, $comp->name, $comp->value, $comp->label, $attr);
        }
        $this->php_form_code['components'][] = "\$form->addInput('$comp->type', '$comp->name', '$comp->value', '" . $this->sanitize($comp->label) . "', '" . $this->sanitize($attr) . "');\n";
        foreach ($comp->plugins as $plugin) {
            if (!in_array($plugin->pluginName, $this->autoloaded_plugins)) {
                $this->addPlugin($plugin);
            }
        }
    }

    private function addParagraph($comp)
    {
        $class = '';
        if (!empty($comp->clazz)) {
            $class = ' class="' . $comp->clazz . '"';
        }
        if ($this->output_preview) {
            $this->php_form->addHtml('<p' . $class . '>' . $comp->value . '</p>');
        }
        $this->php_form_code['components'][] = "\$form->addHtml('" . $this->sanitize('<p' . $class . '>' . $comp->value . '</p>') . "');\n";
    }

    private function addPlugin($plugin, $global = false)
    {
        $replacements = array();
        $replacements_code_array = array();
        foreach ($plugin->replacements as $key => $repl) {
            $replacements["$key"] = $repl;
            $replacements_code_array[] = "'$key' => '$repl'";
        }
        // array('availableTags' => '"value 1","value 2","add other values ..."')

        if (empty($replacements)) {
            if ($this->output_preview) {
                if ($plugin->pluginName === 'modal') {
                    $modal_options = [
                        'title'       => $plugin->title,
                        'title-class' => $plugin->titleClass,
                        'title-tag'   => $plugin->titleTag,
                        'animation'   => $plugin->animation,
                        'blur'        => $plugin->blur
                    ];
                    $this->php_form->modal($modal_options);
                } elseif ($plugin->pluginName === 'pretty-checkbox') {
                    $plain = '';
                    if ($plugin->plain === 'true') {
                        $plain = 'plain';
                    }
                    $pretty_checkbox_options = [
                        'checkboxStyle'  => $plugin->checkboxStyle,
                        'radioStyle'     => $plugin->radioStyle,
                        'fill'           => $plugin->fill,
                        'plain'          => $plain,
                        'size'           => $plugin->size,
                        'animations'     => $plugin->animations
                    ];
                    $this->php_form->addPlugin($plugin->pluginName, '#' . $this->json_form->id, $plugin->jsConfig, $pretty_checkbox_options);
                } elseif ($plugin->pluginName === 'popover') {
                    $this->php_form->popover();
                } else {
                    $this->php_form->addPlugin($plugin->pluginName, $plugin->selector, $plugin->jsConfig);
                }
            }

            if ($plugin->pluginName === 'formvalidation') {
                $form_code = "\$form->addPlugin('$plugin->pluginName', '$plugin->selector');\n";
            } elseif ($plugin->pluginName === 'modal') {
                $this->is_modal = true;
                $fcode = [
                    "\$modal_options = [",
                    "    'title'       => '$plugin->title',",
                    "    'title-class' => '$plugin->titleClass',",
                    "    'title-tag'   => '$plugin->titleTag',",
                    "    'animation'   => '$plugin->animation',",
                    "    'blur'        => '$plugin->blur'",
                    "];",
                    "\$form->modal(\$modal_options);"
                ];
                $form_code = implode("\n", $fcode);
            } elseif ($plugin->pluginName === 'pretty-checkbox') {
                $plain = '';
                if ($plugin->plain === 'true') {
                    $plain = 'plain';
                }
                $fcode = [
                    "\$pretty_checkbox_options = [",
                    "    'checkboxStyle'  => '$plugin->checkboxStyle',",
                    "    'radioStyle'     => '$plugin->radioStyle',",
                    "    'fill'           => '$plugin->fill',",
                    "    'plain'          => '$plain',",
                    "    'size'           => '$plugin->size',",
                    "    'animations'     => '$plugin->animations'",
                    "];",
                    "\$form->addPlugin('pretty-checkbox', '#" . $this->json_form->id . "', '" . $plugin->jsConfig . "', \$pretty_checkbox_options);"
                ];
                $form_code = implode("\n", $fcode);
            } elseif ($plugin->pluginName === 'popover') {
                $this->is_popover = true;
                $this->popover_data_attributes = '';
                foreach ($plugin->dataAttributes as $attr) {
                    if (!empty($attr->value)) {
                        $this->popover_data_attributes .= ' data-' . $attr->name . '="' . $attr->value . '"';
                    }
                }
                $form_code = "\$form->popover();\n";
            } else {
                $form_code = "\$form->addPlugin('$plugin->pluginName', '$plugin->selector', '$plugin->jsConfig');\n";
            }
        } else {
            if ($this->output_preview) {
                $this->php_form->addPlugin($plugin->pluginName, $plugin->selector, $plugin->jsConfig, $replacements);
            }

            $replacements_code = implode(', ', $replacements_code_array);
            $form_code = "\$form->addPlugin('$plugin->pluginName', '$plugin->selector', '$plugin->jsConfig', array($replacements_code));\n";
        }

        if ($global) {
            $this->php_form_code['global_plugins'] .= $form_code;
        } else {
            $this->php_form_code['components'][] = $form_code;
        }
    }

    private function addRadioGroup($comp)
    {
        $php_code = '';

        $inline = false;
        if (filter_var($comp->inline, FILTER_VALIDATE_BOOLEAN)) {
            $inline = true;
        }

        // helper
        $this->addHelper($comp->helper, $comp->name);

        $attrArray = $this->mergeAttributes($comp);
        $attr = $this->getAttributes($attrArray);

        foreach ($comp->radioButtons as $rad) {
            $rad_attr = array();

            if ($comp->value === $rad->value) {
                $rad_attr[] = 'checked';
            }
            if ($rad->disabled === 'true') {
                $rad_attr[] = 'disabled';
            }
            foreach ($rad->attr as $attrib) {
                if (!empty($attrib->value)) {
                    $rad_attr[] = $attrib->name . '=' . $attrib->value;
                }
            }
            $rad_attr = implode(', ', $rad_attr);
            if ($this->output_preview) {
                $this->php_form->addRadio($comp->name, $rad->text, $rad->value, $rad_attr);
            }
            $php_code .= "\$form->addRadio('$comp->name', '" . $this->sanitize($rad->text) . "', '$rad->value', '" . $this->sanitize($rad_attr) . "');\n";
        }
        if ($this->output_preview) {
            if ($comp->center === 'true') {
                $this->php_form->centerContent();
            }
            $this->php_form->printRadioGroup($comp->name, $comp->label, $inline, $attr);
            if ($comp->center === 'true') {
                $this->php_form->centerContent(false);
            }
        }
        if ($comp->center === 'true') {
            $php_code .= "\$form->centerContent();\n";
        }
        $php_code .= "\$form->printRadioGroup('$comp->name', '" . $this->sanitize($comp->label) . "', $comp->inline, '" . $this->sanitize($attr) . "');\n";
        if ($comp->center === 'true') {
            $php_code .= "\$form->centerContent(false);\n";
        }
        $this->php_form_code['components'][] = $php_code;
        foreach ($comp->plugins as $plugin) {
            if (!in_array($plugin->pluginName, $this->autoloaded_plugins)) {
                $this->addPlugin($plugin);
            }
        }
    }

    private function addRecaptcha($comp)
    {
        if ($this->output_preview) {
            $this->php_form->addRecaptchaV3($comp->publickey);
        }
        if (empty($comp->publickey)) {
            $comp->publickey = 'RECAPTCHA_PUBLIC_KEY_HERE';
        }
        $this->php_form_code['components'][] = "\$form->addRecaptchaV3('$comp->publickey');\n";
    }

    private function addSelect($comp)
    {
        $php_code             = '';
        $has_bootstrap_select = false;
        $has_select2          = false;
        foreach ($comp->plugins as $plugin) {
            if ($plugin->pluginName == 'bootstrap-select') {
                $comp->attr = $this->addAttribute('class', 'selectpicker', $comp->attr);
                $has_bootstrap_select = true;
            } elseif ($plugin->pluginName == 'select2') {
                $comp->attr = $this->addAttribute('class', 'select2', $comp->attr);
                $has_select2          = true;
            } elseif ($plugin->pluginName == 'slimselect') {
                $has_slimselect = true;
            }
        }

        // helper
        $this->addHelper($comp->helper, $comp->name);

        // placeholder
        if (!empty($comp->placeholder)) {
            if ($has_bootstrap_select) {
                $comp->attr = $this->addAttribute('title', $comp->placeholder, $comp->attr);
            } elseif ($has_select2) {
                $comp->attr = $this->addAttribute('data-placeholder', $comp->placeholder, $comp->attr);
            } elseif ($has_slimselect) {
                $php_code .= "\$form->addOption('$comp->name', '', '$comp->placeholder', '', 'disabled, data-placeholder=true');\n";
            } else {
                $php_code .= "\$form->addOption('$comp->name', '', '$comp->placeholder', '', 'disabled, selected');\n";
            }
            foreach ($comp->attr as $index => $attr) {
                if ($attr->name === 'placeholder') {
                    // placeholder has been added to the form, we delete the attribute & reindex
                    unset($comp->attr[$index]);
                    $comp->attr = array_values($comp->attr);
                }
            }
        }
        $attrArray = $this->mergeAttributes($comp);
        $attr = $this->getAttributes($attrArray);
        $is_multiple = false;
        if (!empty($attr) && strpos('multiple=true', $attr) !== false) {
            $is_multiple = true;
        }
        if (!empty($comp->placeholder) && $has_bootstrap_select !== true) {
            if ($this->output_preview) {
                $op_attr = 'disabled, selected';
                if ($has_slimselect) {
                    $op_attr = 'data-placeholder=true';
                }
                $this->php_form->addOption($comp->name, '', $comp->placeholder, '', $op_attr);
            }
            $php_code .= "\$form->addOption('$comp->name', '', '$comp->placeholder', '', 'disabled, selected');\n";
        }
        if ($is_multiple !== true) {
            foreach ($comp->selectOptions as $option) {
                $opt_attr = [];

                if ($comp->value === $option->value) {
                    $opt_attr[] = 'selected';
                }
                $opt_attr = implode(', ', $opt_attr);
                if ($this->output_preview) {
                    $this->php_form->addOption($comp->name, $option->value, $option->text, $option->groupname, $opt_attr);
                }
                $php_code .= "\$form->addOption('$comp->name', '$option->value', '" . $this->sanitize($option->text) . "', '$option->groupname', '" . $this->sanitize($opt_attr) . "');\n";
            }
        } else {
            $select_array_values = array_map('trim', explode(',', $comp->value));
            foreach ($comp->selectOptions as $option) {
                $opt_attr = [];

                if (in_array($option->value, $select_array_values)) {
                    $opt_attr[] = 'selected';
                }
                $opt_attr = implode(', ', $opt_attr);
                if ($this->output_preview) {
                    $this->php_form->addOption($comp->name, $option->value, $option->text, $option->groupname, $opt_attr);
                }
                $php_code .= "\$form->addOption('$comp->name', '$option->value', '" . $this->sanitize($option->text) . "', '$option->groupname', '" . $this->sanitize($opt_attr) . "');\n";
            }
        }
        if ($this->output_preview) {
            $this->php_form->addSelect($comp->name, $comp->label, $attr);
        }
        $php_code .= "\$form->addSelect('$comp->name', '" . $this->sanitize($comp->label) . "', '" . $this->sanitize($attr) . "');\n";
        $this->php_form_code['components'][] = $php_code;
        foreach ($comp->plugins as $plugin) {
            if (!in_array($plugin->pluginName, $this->autoloaded_plugins)) {
                $this->addPlugin($plugin);
            }
        }
    }

    private function addSetCols($section)
    {
        $comp = $section->component;
        if (isset($comp->label) && isset($comp->width)) {
            $cw = $comp->width;
            if (empty($comp->label)) {
                $cols = array(
                    '100%' => '0-12',
                    '66%'  => '0-8',
                    '50%'  => '0-6',
                    '33%'  => '0-4'
                );
            } else {
                $cols = array(
                    '100%' => '4-8',
                    '66%'  => '3-5',
                    '50%'  => '2-4',
                    '33%'  => '2-2'
                );
            }
            $form_cols = $cols[$cw];
            if ($form_cols !== $this->current_cols) {
                $new_cols = explode('-', $form_cols);
                if ($this->output_preview) {
                    $this->php_form->setCols($new_cols[0], $new_cols[1]);
                }
                $this->php_form_code['components'][] = "\$form->setCols($new_cols[0], $new_cols[1]);\n";

                $this->current_cols = $form_cols;
            }
            $this->currently_centered = false;
        } elseif (($section->componentType == 'buttongroup' || $section->componentType == 'button') && $comp->center === 'true' && !$this->currently_centered) {
            if ($this->output_preview) {
                $this->php_form->setCols(0, 12);
                $this->php_form->centerContent();
            }
            $this->php_form_code['components'][] = "\$form->setCols(0, 12);\n";
            $this->php_form_code['components'][] = "\$form->centerContent();\n";

            $this->current_cols = '0-12';
            $this->currently_centered = true;
        }
    }

    private function addTextarea($comp)
    {
        foreach ($comp->plugins as $plugin) {
            if ($plugin->pluginName == 'tinymce') {
                $comp->attr = $this->addAttribute('class', 'tinymce', $comp->attr);
            }
        }
        $attrArray = $this->mergeAttributes($comp);
        $attr = $this->getAttributes($attrArray);
        $this->addHelper($comp->helper, $comp->name);
        if ($this->output_preview) {
            $this->php_form->addTextarea($comp->name, $comp->value, $comp->label, $attr);
        }
        $this->php_form_code['components'][] = "\$form->addTextarea('$comp->name', '$comp->value', '$comp->label', '$attr');\n";
        foreach ($comp->plugins as $plugin) {
            if (!in_array($plugin->pluginName, $this->autoloaded_plugins)) {
                $this->addPlugin($plugin);
            }
        }
    }

    private function addHcaptcha($comp)
    {
        if ($this->output_preview) {
            if ($comp->center === 'true') {
                $this->php_form->centerContent();
            }
            $this->php_form->addHcaptcha($comp->sitekey, 'data-theme=' . $comp->theme . ', data-size=' . $comp->size);
            if ($comp->center === 'true') {
                $this->php_form->centerContent(false);
            }
        }
        if (empty($comp->sitekey)) {
            $comp->sitekey = 'HCAPTCHA_SITE_KEY_HERE';
        }
        if ($comp->center === 'true') {
            $this->php_form_code['components'][] = "\$form->centerContent();\n";
        }
        $this->php_form_code['components'][] = "\$form->addHcaptcha('$comp->sitekey', 'data-size=$comp->size, data-theme=$comp->theme');\n";
        if ($comp->center === 'true') {
            $this->php_form_code['components'][] = "\$form->centerContent(false);\n";
        }
    }

    private function addHeading($comp)
    {
        $attr = '';
        if (!empty($comp->clazz)) {
            $attr = 'class=' . $comp->clazz;
        }
        if ($this->output_preview) {
            $this->php_form->addHeading($comp->value, $comp->type, $attr);
        }
        $this->php_form_code['components'][] = "\$form->addHeading('" . $this->sanitize($comp->value) . "', '" . $comp->type . "', '" . $attr . "');\n";
    }

    private function arrayKeyLast($array)
    {
        if (!is_array($array) || empty($array)) {
            return null;
        }

        return array_keys($array)[count($array) - 1];
    }

    private function buildCodeParts()
    {
        /* Start
        -------------------------------------------------- */

        $start_1 = array(
            '&lt;?php',
            'use phpformbuilder\Form;',
            'use phpformbuilder\Validator\Validator;'
        );
        if (!empty($this->fileuploaders)) {
            $start_1[] = 'use fileuploader\server\FileUploader;';
        }
        $start_2 = array();
        if ($this->json_form->aftervalidation === 'db-insert' || $this->json_form->aftervalidation === 'db-update' || $this->json_form->aftervalidation === 'db-delete') {
            $start_2 = array('use phpformbuilder\database\DB;');
        }
        $start_3 = array(
            '',
            '/* =============================================',
            '    start session and include form class',
            '============================================= */',
            '',
            'session_start();',
            'include_once rtrim($_SERVER[\'DOCUMENT_ROOT\'], DIRECTORY_SEPARATOR) . \'' . $this->current_dir . 'Form.php\';',
            ''
        );
        if (!empty($this->fileuploaders)) {
            $start_3[] = '// include the fileuploader';
            $start_3[] = '';
            $start_3[] = 'include_once rtrim($_SERVER[\'DOCUMENT_ROOT\'], DIRECTORY_SEPARATOR) . \'' . $this->current_dir . 'plugins/fileuploader/server/class.fileuploader.php\';';
            $start_3[] = '';
        }

        $start = array_merge($start_1, $start_2, $start_3);
        $this->php_form_code['start'] = implode("\n", $start);

        /* if_posted
        -------------------------------------------------- */

        $if_posted_1 = array(
            '',
            '/* =============================================',
            '    validation if posted',
            '============================================= */',
            '',
            'if ($_SERVER["REQUEST_METHOD"] == "POST" && Form::testToken(\'' . $this->json_form->id . '\')) {',
            '    // create validator & auto-validate required fields',
            '    $validator = Form::validate(\'' . $this->json_form->id . '\');'
        );
        $if_posted_2 = array();
        if ($this->has_email_fields) {
            $if_posted_2 = array(
                '',
                '    // additional validation'
            );
            foreach ($this->email_field_names as $field_name) {
                $if_posted_2[] = '    $validator->email()->validate(\'' . $field_name . '\');';
            }
        }
        $if_posted_3 = array();
        if ($this->has_recaptcha) {
            if (empty($this->recaptcha_private_key)) {
                $this->recaptcha_private_key = 'RECAPTCHA_PRIVATE_KEY_HERE';
            }
            $if_posted_3 = array(
                '',
                '    // recaptcha validation',
                '    $validator->recaptcha(\'' . $this->recaptcha_private_key . '\', \'Recaptcha Error\')->validate(\'g-recaptcha-response\');'
            );
        } elseif ($this->has_hcaptcha) {
            if (empty($this->hcaptcha_secret_key)) {
                $this->hcaptcha_secret_key = 'HCAPTCHA_SECRET_KEY_HERE';
            }
            $if_posted_3 = array(
                '',
                '    // hcaptcha validation',
                '    $validator->hcaptcha(\'' . $this->hcaptcha_secret_key . '\', \'Captcha Error\')->validate(\'h-captcha-response\');'
            );
        }
        $if_posted_4 = array();
        if ($this->hasCaptcha) {
            $if_posted_4 = array(
                '',
                '    // captcha validation',
                '    $validator->captcha()->validate(\'' . $this->captchaFieldname . '\');'
            );
        }
        $if_posted_5 = array(
            '',
            '    // check for errors',
            '    if ($validator->hasErrors()) {',
            '        $_SESSION[\'errors\'][\'' . $this->json_form->id . '\'] = $validator->getAllErrors();',
            '    } else {'
        );

        $if_posted_6 = array();
        if (!empty($this->fileuploaders)) {
            $if_posted_6[] = "        \$uploaded_files = [];";
            foreach ($this->fileuploaders as $fuploader) {
                $if_posted_6[] = "        if (isset(\$_POST['" . $fuploader['fieldname'] . "']) && !empty(\$_POST['" . $fuploader['fieldname'] . "'])) {";
                $if_posted_6[] = "            \$posted_file = FileUploader::getPostedFiles(\$_POST['" . $fuploader['fieldname'] . "']);";
                $if_posted_6[] = "            \$uploaded_files['" . $fuploader['fieldname'] . "'] = [";
                $if_posted_6[] = "                'upload_dir' => '" . rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR) . $this->current_dir . 'plugins/fileuploader/default/php/' . $fuploader['upload_dir'] . "',";
                $if_posted_6[] = "                'filename' => \$posted_file[0]['file']";
                $if_posted_6[] = "            ];";
                $if_posted_6[] = "        }";
            }
        }

        $if_posted_7 = array();
        if ($this->json_form->aftervalidation === 'send-email') {
            // Email sending
            if (!empty($this->fileuploaders)) {
                $if_posted_7[] = '        /* Send email with attached file(s) */';
                $if_posted_7[] = '        $attachments = array();';
                $if_posted_7[] = '        foreach ($uploaded_files as $f) {';
                $if_posted_7[] = "            \$attachments[] = \$f['upload_dir'] . \$f['filename'];";
                $if_posted_7[] = '        }';
                $if_posted_7[] = '        $attachments = implode(\', \', $attachments);';
            } else {
                $if_posted_7[] = '        // send email';
            }
            $if_posted_7[] = '        $email_config = array(';
            $if_posted_7[] = '            \'sender_email\'    => \'' . $this->json_form->senderEmail . '\',';
            $if_posted_7[] = '            \'recipient_email\' => \'' . $this->json_form->recipientEmail . '\',';
            $if_posted_7[] = '            \'subject\'         => \'' . $this->json_form->subject . '\',';
            if (!empty($this->json_form->senderName)) {
                $if_posted_7[] = '            \'sender_name\'     => \'' . $this->json_form->senderName . '\',';
            }
            if (!empty($this->json_form->replyToEmail)) {
                $if_posted_7[] = '            \'reply_to_email\'  => \'' . $this->json_form->replyToEmail . '\',';
            }
            if (!empty($this->json_form->sentMessage)) {
                $if_posted_7[] = '            \'sent_message\'    => \'' . $this->sanitize($this->json_form->sentMessage) . '\',';
            }
            if (!empty($this->fileuploaders)) {
                $if_posted_7[] = '            \'attachments\'    =>  $attachments,';
            }
            $filter_values = [$this->json_form->id];
            if (!empty($this->fileuploaders)) {
                foreach ($this->fileuploaders as $fuploader) {
                    $filter_values[] = $fuploader['fieldname'];
                    $filter_values[] = 'uploader-' . $fuploader['fieldname'];
                }
            }
            $filter_values = implode(', ', $filter_values);
            $if_posted_7[] = '            \'filter_values\'   => \'' . $filter_values . '\'';
            $if_posted_7[] = '        );';
            $if_posted_7[] = '        $sent_message = Form::sendMail($email_config);';

            $message = array(
                'if (isset($sent_message)) {',
                '    echo $sent_message;',
                '}'
            );
            $this->php_form_code['message'] = implode("\n", $message);
        } elseif ($this->json_form->aftervalidation === 'db-insert' || $this->json_form->aftervalidation === 'db-update' || $this->json_form->aftervalidation === 'db-delete') {
            $message = array(
                'if (isset($msg)) {',
                '    echo $msg;',
                '}'
            );
            $this->php_form_code['message'] = implode("\n", $message);
            // DB insert
            $if_posted_7 = array(
                '        include_once rtrim($_SERVER[\'DOCUMENT_ROOT\'], DIRECTORY_SEPARATOR) . \'' . $this->current_dir . 'database/db-connect.php\';',
                '        include_once rtrim($_SERVER[\'DOCUMENT_ROOT\'], DIRECTORY_SEPARATOR) . \'' . $this->current_dir . 'database/DB.php\';',
                '',
                '        $db = new DB();'
            );

            if ($this->json_form->aftervalidation === 'db-insert') {
                $if_posted_7[] = '        // Insert a new record';
                $if_posted_7[] = '        $values = array(';
                foreach ($this->db_fields as $key => $db_field) {
                    if ($db_field['component_name'] === $this->json_form->dbPrimary) {
                        continue;
                    } elseif ($db_field['multiple']) {
                        $temp = '            \'' . $db_field['component_name'] . '\' => json_encode($_POST[\'' . $db_field['component_name'] . '\']),';
                    } else {
                        if ($db_field['component_type'] !== 'fileuploader') {
                            $temp = '            \'' . $db_field['component_name'] . '\' => $_POST[\'' . $db_field['component_name'] . '\'],';
                        } else {
                            $temp = '            \'' . $db_field['component_name'] . '\' => $uploaded_files[\'' . $db_field['component_name'] . '\'][\'filename\'],';
                        }
                    }
                    if ($key === $this->arrayKeyLast($this->db_fields)) {
                        $temp = \rtrim($temp, ',');
                    }
                    $if_posted_7[] = $temp;
                }
                $if_posted_7[] = '        );';
                $if_posted_7[] = '        if (!$db->insert(\'' . $this->json_form->dbTable . '\', $values)) {';
                $if_posted_7[] = '            $msg = Form::buildAlert($db->error(), \'' . $this->json_form->framework . '\', \'danger\');';
                $if_posted_7[] = '        } else {';
                $if_posted_7[] = '            $id  = $db->getLastInsertId();';
                $if_posted_7[] = '            $msg = Form::buildAlert(\'1 Record inserted ; id = #\' . $id, \'' . $this->json_form->framework . '\', \'success\');';
                $if_posted_7[] = '        }';
            } elseif ($this->json_form->aftervalidation === 'db-update') {
                $if_posted_7[] = '        // Update an existing record';
                $filter_found = false;
                foreach ($this->db_fields as $db_field) {
                    if ($db_field['component_name'] === $this->json_form->dbFilter) {
                        $if_posted_7[] = '        $where  = array(\'' . $db_field['component_name'] . '\' => $_POST[\'' . $db_field['component_name'] . '\']);';
                        $filter_found = true;
                    }
                }
                if (!$filter_found) {
                    echo Form::buildAlert('<p><strong>Your form will not work because you didn\'t set any value to filter the Database update records.</strong><br>Open the <em>Main Settings</em>, go to <em>Form Action</em> and enter a value in <em>Filter field name</em>.<br>The value must be the field name that will be used in the SQL WHERE clause to update your records and your form must have a field with the same name.</p>', $this->php_form->framework, 'warning');
                }
                $if_posted_7[] = '        $update = array(';
                foreach ($this->db_fields as $key => $db_field) {
                    if ($db_field['component_name'] !== $this->json_form->dbFilter) {
                        if ($db_field['multiple']) {
                            $temp = '        \'' . $db_field['component_name'] . '\'] => json_encode($_POST[\'' . $db_field['component_name'] . '\']),';
                        } else {
                            $temp = '        \'' . $db_field['component_name'] . '\' => $_POST[\'' . $db_field['component_name'] . '\'],';
                        }
                        if ($key === $this->arrayKeyLast($this->db_fields)) {
                            $temp = \rtrim($temp, ',');
                        }
                        $if_posted_7[] = $temp;
                    }
                }
                $if_posted_7[] = '        );';
                $if_posted_7[] = '        if (!$db->update(\'' . $this->json_form->dbTable . '\', $update, $where)) {';
                $if_posted_7[] = '            $msg = Form::buildAlert($db->error(), \'' . $this->json_form->framework . '\', \'danger\');';
                $if_posted_7[] = '        } else {';
                $if_posted_7[] = '            $msg = Form::buildAlert(\'Database updated successfully\', \'' . $this->json_form->framework . '\', \'success\');';
                $if_posted_7[] = '        }';
            } elseif ($this->json_form->aftervalidation === 'db-delete') {
                foreach ($this->db_fields as $db_field) {
                    if ($db_field['component_name'] === $this->json_form->dbFilter) {
                        $if_posted_7[] = '        $where = array(\'' . $db_field['component_name'] . '\' => $_POST[\'' . $db_field['component_name'] . '\']);';
                    }
                }
                $if_posted_7[] = '        if (!$db->delete(\'' . $this->json_form->dbTable . '\', $where)) {';
                $if_posted_7[] = '            $msg = Form::buildAlert($db->error(), \'' . $this->json_form->framework . '\', \'danger\');';
                $if_posted_7[] = '        } else {';
                $if_posted_7[] = '            $msg = Form::buildAlert(\'1 record deleted\', \'' . $this->json_form->framework . '\', \'success\');';
                $if_posted_7[] = '        }';
            }
        }

        $if_posted_7[] = '        // clear the form';
        $if_posted_7[] = '        Form::clear(\'' . $this->json_form->id . '\');';
        if (!empty($this->json_form->redirectUrl)) {
            $if_posted_7[] = '        // redirect after success';
            $if_posted_7[] = '        header(\'Location:' . $this->json_form->redirectUrl . '\');';
            $if_posted_7[] = '        exit;';
        }
        $if_posted_7[] = '    }';
        $if_posted_7[] = '}';
        $if_posted_7[] = '';

        $if_posted = array_merge($if_posted_1, $if_posted_2, $if_posted_3, $if_posted_4, $if_posted_5, $if_posted_6, $if_posted_7);
        $this->php_form_code['if_posted'] = implode("\n", $if_posted);

        /* head
        -------------------------------------------------- */

        if ($this->json_form->ajax !== 'true') {
            $this->php_form_code['head'] = '';
            if (!empty($this->icon_font_url)) {
                $icon_font = $this->json_form->iconFont;
                $this->php_form_code['head'] .= "&lt;!-- $icon_font --&gt;\n\n&lt;link rel=\"stylesheet\" href=\"$this->icon_font_url\"&gt;\n\n";
            }
            $this->php_form_code['head'] .= "&lt;?php \$form->printIncludes('css'); ?&gt;\n";
        }

        /* render
        -------------------------------------------------- */

        $render = array();
        if ($this->is_modal) {
            $btn_class = $this->getTriggerButtonClass();
            $render[] = '&lt;button data-micromodal-trigger="modal-' . $this->json_form->id . '" class="' . $btn_class . '"&gt;Open the modal form&lt;/button>';
        } elseif ($this->is_popover) {
            $btn_class = $this->getTriggerButtonClass();
            $render[] = '&lt;button data-popover-trigger="' . $this->json_form->id . '"' . $this->popover_data_attributes . ' class="' . $btn_class . '"&gt;Open the popover form&lt;/button&gt;';
        }
        if ($this->json_form->ajax !== 'true') {
            $render[] = '&lt;?php';
        } else {
            $render[] = '';
        }
        if (!empty($this->php_form_code['message'])) {
            $render[] = $this->php_form_code['message'];
        }
        if ($this->json_form->ajax === 'true') {
            $render[] = '';
        }
        $render[] = '$form->render();';
        if ($this->json_form->ajax !== 'true') {
            $render[] = '?&gt;';
        } else {
            $render[] = '';
        }

        $this->php_form_code['render'] = implode("\n", $render);

        /* scripts
        -------------------------------------------------- */

        if ($this->json_form->ajax !== 'true') {
            $scripts = [];
            if ($this->has_jquery_plugins && in_array($this->json_form->framework, $this->frameworks_without_jquery)) {
                $scripts[] = '&lt;script src=&quot;https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js&quot; integrity=&quot;sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==&quot; crossorigin=&quot;anonymous&quot; referrerpolicy=&quot;no-referrer&quot;&gt;&lt;/script&gt;';
            }
            $scripts[] = '&lt;?php';
            $scripts[] = '$form->printIncludes(\'js\');';
            $scripts[] = '$form->printJsCode();';
            $scripts[] = '?&gt;';
            if ($this->json_form->framework === 'material' || $this->json_form->framework === 'bs4-material') {
                $scripts[] = '&lt;script&gt;';
                $scripts[] = '$(document).ready(function() {';
                $scripts[] = '    $(\'select:not(.selectpicker):not(.select2)\').formSelect();';
                $scripts[] = '});';
                $scripts[] = '&lt;/script&gt;';
            }
            $this->php_form_code['scripts'] = implode("\n", $scripts);
        } else {
            if (!$this->output_preview) {
                $this->php_form = new Form($this->json_form->id);
                $this->php_form->setPluginsUrl();
            }
            $scripts = array(
                '&lt;!-- Ajax form loader --&gt;',
                '',
                '&lt;!-- Change the src url below if necessary --&gt;',
                '&lt;script src=&quot;' . $this->php_form->plugins_url . 'ajax-data-loader/ajax-data-loader.min.js&quot;&gt;&lt;/script&gt;',
                '',
                '&lt;script&gt;',
                '    // --- SETUP YOUR FORM(S) BELOW IN THE &quot;ajaxForms&quot; VARIABLE ---',
                '    var ajaxForms = [',
                '        {',
                '            formId: \'' . $this->json_form->id . '\',',
                '            container: \'#' . $this->json_form->id . '-loader\',',
                '            url: \'/ajax-forms/' . $this->json_form->id . '.php\'',
                '        }',
                '    ];',
                '',
                '    // --- NO NEED TO CHANGE ANYTHING AFTER THIS LINE ---',
                '    // --- COPY/PASTE THE FOLLOWING CODE IN THE HTML FILE THAT WILL LOAD THE FORM ---',
                '',
                '    document.addEventListener(&apos;DOMContentLoaded&apos;, function() {',
                '        ajaxForms.forEach(function(currentForm) {',
                '            const $formContainer = document.querySelector(currentForm.container);',
                '            if (typeof($formContainer.dataset.ajaxForm) === &apos;undefined&apos;) {',
                '                fetch(currentForm.url)',
                '                .then((response) =&gt; {',
                '                    return response.text()',
                '                })',
                '                .then((data) =&gt; {',
                '                    $formContainer.innerHTML = &apos;&apos;;',
                '                    $formContainer.dataset.ajaxForm = currentForm;',
                '                    $formContainer.dataset.ajaxFormId = currentForm.formId;',
                '                    loadData(data, currentForm.container);',
                '                }).catch((error) =&gt; {',
                '                    console.log(error);',
                '                });',
                '            }',
                '        });',
                '    });',
                '&lt;/script&gt;',
            );
            $this->php_form_code['scripts'] = implode("\n", $scripts);
        }

        /* Main form code
        -------------------------------------------------- */

        $main_code = '';
        $main_code .= $this->php_form_code['start'];
        $main_code .= $this->php_form_code['if_posted'];
        $main_code .= $this->php_form_code['start_form'];
        foreach ($this->php_form_code['components'] as $comp) {
            $main_code .= $comp;
        }
        $main_code .= $this->php_form_code['global_plugins'];
        if ($this->json_form->ajax === 'true') {
            $main_code .= $this->php_form_code['render'];
        }
        $this->php_form_code['main'] = $main_code;
    }

    /**
     * display error message if
     *     - iCheck used with material
     * @param string $msg
     */
    private function buildErrorMsg($msg)
    {
        $this->error_msg .= '<div style="line-height:30px;border-radius:5px;border-bottom:1px solid #ac2925;background-color: #c9302c;margin:10px auto;"><p style="color:#fff;text-align:center;font-size:16px;margin:0">' . $msg . '</p></div>';
    }

    private function buildSection($section)
    {
        if ($this->json_form->layout == 'horizontal') {
            $this->addSetCols($section);

            if ($section->group_inputs !== false) {
                $this->addGroupInputs($section->group_inputs);
            }
        }
        switch ($section->componentType) {
            case 'button':
                $this->addBtn($section->component);
                break;

            case 'buttongroup':
                $this->addBtngroup($section->component);
                break;

            case 'checkbox':
                $this->addCheckboxGroup($section->component);
                break;

            case 'dependent':
                $this->startDependent($section->component);
                break;

            case 'dependentend':
                $this->endDependent();
                break;

            case 'fileuploader':
                $this->addFileuploader($section->component);
                break;

            case 'hcaptcha':
                $this->addHcaptcha($section->component);
                break;

            case 'heading':
                $this->addHeading($section->component);
                break;

            case 'html':
                $this->addHtml($section->component);
                break;

            case 'input':
                $this->addInput($section->component);
                break;

            case 'paragraph':
                $this->addParagraph($section->component);
                break;

            case 'radio':
                $this->addRadioGroup($section->component);
                break;

            case 'recaptcha':
                $this->addRecaptcha($section->component);
                break;

            case 'select':
                $this->addSelect($section->component);
                break;

            case 'textarea':
                $this->addTextarea($section->component);
                break;

            default:
                # code...
                break;
        }
    }

    private function createForm($json_form)
    {
        $form_framework = $json_form->framework;
        $is_bs4_material = false;
        if ($form_framework === 'bs4-material') {
            $form_framework = 'material';
            $is_bs4_material = true;
        }
        foreach ($json_form->plugins as $pl) {
            if ($pl->pluginName === 'formvalidation') {
                if (empty($json_form->attr)) {
                    $json_form->attr = 'data-fv-no-icon=true';
                } else {
                    $json_form->attr .= ', data-fv-no-icon=true';
                }
            }
            if ($pl->isjQuery) {
                $this->has_jquery_plugins = true;
            }
        }
        if ($this->output_preview) {
            $this->php_form = new Form($json_form->id, $json_form->layout, $json_form->attr, $form_framework);
            if ($is_bs4_material) {
                $this->php_form->addPlugin('materialize', '#' . $json_form->id);
            }
            $this->php_form->setAction('#', false);
            $this->php_form->useLoadJs();
            $this->php_form->setMode('development');
        }
        $this->php_form_code['start_form'] = "\n/* ==================================================\n    The Form\n ================================================== */\n\n";
        $this->php_form_code['start_form'] .= "\$form = new Form('$json_form->id', '$json_form->layout', '$json_form->attr', '$form_framework');\n";
        if ($this->json_form->ajax === 'true') {
            $this->php_form_code['start_form'] .= "// enable Ajax loading\n\$form->setOptions(['ajax' => true]);\n\n";
        }
        if ($is_bs4_material) {
            $this->php_form_code['start_form'] .= "\$form->addPlugin('materialize', '#$json_form->id');\n";
        }
        $this->php_form_code['start_form'] .= "// \$form->setMode('development');\n";
    }

    private function endDependent()
    {
        if ($this->output_preview) {
            $this->php_form->endDependentFields();
        }
        $this->php_form_code['components'][] = "\$form->endDependentFields();\n";
    }

    private function getAttributes($attrArray)
    {
        $tempArray = array();
        foreach ($attrArray as $attr) {
            if (!empty($attr->value) && !is_bool($attr->value)) {
                $tempArray[] = $attr->name . '=' . str_replace(',', '\,', $attr->value);
            } else {
                $tempArray[] = $attr->name;
            }
        }
        $attr = implode(',', $tempArray);

        return $attr;
    }

    /**
     * getCurrentDir
     *
     * @return current_dir root-relative dir to phpformbuilder with starting & ending DIRECTORY_SEPARATOR
     */
    private function getCurrentDir()
    {
        $phpformbuilder_path = realpath('../../phpformbuilder');

        $document_root = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $_SERVER['DOCUMENT_ROOT']);
        $phpformbuilder_path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $phpformbuilder_path);

        $cur_dir = DIRECTORY_SEPARATOR . ltrim(str_replace($document_root, '', $phpformbuilder_path), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        // var_dump($cur_dir);

        return str_replace(DIRECTORY_SEPARATOR, '/', $cur_dir);
    }

    /**
     * getIconFont
     * The fonts URLs are listed in src/ts/defaultConfig.ts
     *
     * @return link to the font stylesheet || ''
     */
    private function getIconFont()
    {
        $uip_css_url = str_replace('ajax/preview.php', 'lib/universal-icon-picker/assets/stylesheets/', $_SERVER['SCRIPT_NAME']);
        $iconFonts = array(
            'font-awesome'           => '//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css',
            'font-awesome-solid'           => '//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css',
            'font-awesome-regular'           => '//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css',
            'font-awesome-brands'           => '//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css',
            'material-icons-filled'  => '//fonts.googleapis.com/css2?family=Material+Icons',
            'material-icons-outlined'  => '//fonts.googleapis.com/css2?family=Material+Icons+Outlined',
            'material-icons-round'  => '//fonts.googleapis.com/css2?family=Material+Icons+Round',
            'material-icons-sharp'  => '//fonts.googleapis.com/css2?family=Material+Icons+Sharp',
            'material-icons-two-tone'  => '//fonts.googleapis.com/css2?family=Material+Icons+Two+Tone',
            'bootstrap-icons'        => '//cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css',
            'elegant-icons'          => $uip_css_url . 'elegant-icons.min.css',
            'feather-icons'          => $uip_css_url . 'feather-icons.min.css',
            'foundation-icons'       => $uip_css_url . 'foundation-icons.min.css',
            'happy-icons'            => $uip_css_url . 'happy-icons.min.css',
            'icomoon'                => $uip_css_url . 'icomoon.min.css',
            'open-iconic'            => $uip_css_url . 'open-iconic.min.css',
            'tabler-icons'           => $uip_css_url . 'tabler-icons.min.css',
            'weather-icons'          => $uip_css_url . 'weather-icons.min.css',
            'zondicons'              => $uip_css_url . 'zondicons.min.css'
        );
        if (array_key_exists($this->json_form->iconFont, $iconFonts)) {
            $icf = $this->json_form->iconFont;
            return $iconFonts[$icf];
        }
        return '';
    }

    private function getLastJsonError()
    {
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return '';
                break;
            case JSON_ERROR_DEPTH:
                $err_msg = 'JSON Error - Maximum stack depth exceeded';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $err_msg = 'JSON Error - Underflow or the modes mismatch';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $err_msg = 'JSON Error - Unexpected control character found';
                break;
            case JSON_ERROR_SYNTAX:
                $err_msg = 'JSON Error - Syntax error, malformed JSON';
                break;
            case JSON_ERROR_UTF8:
                $err_msg = 'JSON Error - Malformed UTF-8 characters, possibly incorrectly encoded';
                break;
            default:
                $err_msg = 'JSON Error - Unknown error';
                break;
        }

        return $err_msg;
    }

    private function getSectionGroups()
    {
        $json_form_sections_count = count($this->json_form_sections);
        $grouppable_component_types = array('checkbox', 'input', 'radio', 'select', 'textarea');
        $group_started = false;
        for ($i = 0; $i < $json_form_sections_count; $i++) {
            $current_section               = $this->json_form_sections[$i];
            $current_section->group_inputs = false;
            if (isset($this->json_form_sections[$i + 1]) && $group_started === false) {
                $next_section = $this->json_form_sections[$i + 1];
                if (in_array($current_section->componentType, $grouppable_component_types) && in_array($next_section->componentType, $grouppable_component_types)) {
                    $current_component_width = intval(str_replace('%', '', $current_section->component->width));
                    $next_component_width = intval(str_replace('%', '', $next_section->component->width));
                    if ($current_component_width + $next_component_width <= 100) {
                        $current_section->group_inputs = array($current_section->component->name, $next_section->component->name);
                        $group_started = true;
                        // group 3rd component
                        if (isset($this->json_form_sections[$i + 2])) {
                            $third_section = $this->json_form_sections[$i + 2];
                            if (in_array($third_section->componentType, $grouppable_component_types)) {
                                $third_component_width = intval(str_replace('%', '', $third_section->component->width));
                                if ($current_component_width + $next_component_width + $third_component_width <= 100) {
                                    $current_section->group_inputs[] = $third_section->component->name;
                                }
                            }
                        }
                    }
                }
            } else {
                $group_started = false;
            }
        }
    }

    private function getSpecialData()
    {
        $add_to_dbfields = array('checkbox', 'input', 'radio', 'select', 'textarea', 'fileuploader');

        foreach ($this->json_form_sections as $section) {
            if (in_array($section->componentType, $add_to_dbfields)) {
                $multiple = false;
                if ($section->componentType === 'checkbox' || ($section->componentType === 'select' && in_array('multiple', $section->component->attr))) {
                    $multiple = true;
                }
                $db_field = array(
                    'component_type' => $section->componentType,
                    'component_name' => $section->component->name,
                    'multiple'       => $multiple
                );
                $this->db_fields[] = $db_field;
            }
            if ($section->componentType === 'recaptcha') {
                $this->has_recaptcha = true;
                $this->recaptcha_private_key = $section->component->privatekey;
            } elseif ($section->componentType === 'hcaptcha') {
                $this->has_hcaptcha = true;
                $this->hcaptcha_secret_key = $section->component->secretkey;
            } elseif ($section->componentType === 'input') {
                if ($section->component->type === 'email') {
                    $this->has_email_fields = true;
                    $this->email_field_names[] = $section->component->name;
                }
            }
            if (isset($section->component->plugins)) {
                foreach ($section->component->plugins as $plugin) {
                    if ($plugin->pluginName === 'captcha') {
                        $this->hasCaptcha = true;
                        $this->captchaFieldname = $section->component->name;
                    }
                    if ($plugin->isjQuery) {
                        $this->has_jquery_plugins = true;
                    }
                }
            }
        }
    }

    private function getTriggerButtonClass()
    {
        $btn_class = [
            'bs4'         => 'btn btn-primary',
            'bs5'         => 'btn btn-primary',
            'bulma'       => 'button is-primary',
            'foundation'  => 'button primary',
            'material'    => 'btn waves-effect waves-light',
            'tailwind'    => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-r-lg text-sm px-5 py-2.5 text-center mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800',
            'uikit'       => 'uk-button uk-button-primary'
        ];
        $framework = $this->json_form->framework;

        return $btn_class[$framework];
    }

    private function mergeAttributes($component)
    {
        if (!empty($component->plugins)) {
            foreach ($component->plugins as $plugin) {
                if ($plugin->dataAttributes !== null) {
                    foreach ($plugin->dataAttributes as $attr) {
                        if ($attr !== null && isset($attr->value) && $attr->value !== null) {
                            // create new attr object then push it to the component attributes
                            $plugin_attr = new \stdClass();
                            if ($plugin->pluginName !== 'bootstrap-input-spinner') {
                                $plugin_attr->name = 'data-' . $attr->name;
                            } else {
                                if ($attr->name === 'min' || $attr->name === 'max' || $attr->name === 'step') {
                                    $plugin_attr->name = $attr->name;
                                } else {
                                    $plugin_attr->name = 'data-' . $attr->name;
                                }
                            }
                            $plugin_attr->value = $attr->value;
                            $component->attr[] = $plugin_attr;
                        }
                    }
                }
            }
        }
        return $component->attr;
    }

    private function reindentCode($codepart, $spaces)
    {
        $replacement = '';
        for ($i = 0; $i < $spaces; $i++) {
            $replacement .= ' ';
        }
        return rtrim(preg_replace("`[\n]`", "\n" . $replacement, $codepart)) . "\n";
    }

    private function sanitize($html)
    {
        return htmlspecialchars(str_replace(array("\\", "'"), array("\\\\", "\'"), $html));
    }

    private function startDependent($component)
    {
        if ($this->output_preview) {
            $this->php_form->startDependentFields($component->name, $component->value);
        }
        $this->php_form_code['components'][] = "\$form->startDependentFields('$component->name', '" . $this->sanitize($component->value) . "');\n";
    }
}
