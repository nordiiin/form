<!doctype html>
<html lang="en">
<!--=====================================
=       PHP Form Builder Website        =
======================================-->

<head>
    <?php
    $meta = array(
        'title'       => 'Online Drag and Drop Form Builder',
        'description' => 'Drag and drop the components and retrieve the code. This online Form Builder is the easiest way to create web forms.',
        'canonical'   => 'https://www.phpformbuilder.pro/drag-n-drop-form-builder/index.html',
        'screenshot'  => 'drag-n-drop-form-builder.png'
    );
    include_once '../documentation/inc/page-head.php';

    // Slider
    $slider = array(
        array(
            'img'     => 'drag-field.png',
            'caption' => 'Drag & drop the form elements from the left panel to create the fields'
        ),
        array(
            'img'     => 'component-settings.png',
            'caption' => 'Click any component in the main panel to view & live-edit its settings in the Component Settings panel'
        ),
        array(
            'img'     => 'radio-buttons.png',
            'caption' => 'Add / Edit / Remove Radio buttons or Checkboxes from the Component Settings panel'
        ),
        array(
            'img'     => 'dependent-fields.png',
            'caption' => 'Drag & drop the \'Start/End Condition\' from the left panel to add conditional logic'
        ),
        array(
            'img'     => 'dependent-fields-settings.png',
            'caption' => 'Choose conditions under which the fields will be displayed or hidden'
        ),
        array(
            'img'     => 'tinymce.png',
            'caption' => 'Enable and configure each plugin from the Component Settings panel'
        ),
        array(
            'img'     => 'title.png',
            'caption' => 'Drag & drop to add & customize Title (h1-h6), Paragraph or custom HTML'
        ),
        array(
            'img'     => 'element-plugins.png',
            'caption' => 'Different plugins are available depending on the selected field type'
        ),
        array(
            'img'     => 'datepicker-settings.png',
            'caption' => 'Different options are available depending on the selected plugin'
        ),
        array(
            'img'     => 'drag-n-drop-to-reorder.png',
            'caption' => 'Drag and drop the elements to reorder them at any time'
        ),
        array(
            'img'     => 'preview.png',
            'caption' => 'Click the \'preview\' button for a live preview at any time'
        ),
        array(
            'img'     => 'preview-datepicker.png',
            'caption' => 'Plugins and all features are available during previewing'
        ),
        array(
            'img'     => 'form-settings.png',
            'caption' => 'Click the \'Main Settings\' button to choose your preferred framework & others various settings'
        ),
        array(
            'img'     => 'ajax-loading.png',
            'caption' => 'Enable loading with Ajax if you want to - it allows to load your form in any HTML page without PHP'
        ),
        array(
            'img'     => 'form-action.png',
            'caption' => 'Configure the sending of posted data by email or saving in your database'
        ),
        array(
            'img' => 'email-settings.png',
            'caption'     => 'Setting up email sending'
        ),
        array(
            'img'     => 'form-plugins.png',
            'caption' => 'Some plugins are available in the global configuration of the form'
        ),
        array(
            'img'     => 'form-code.png',
            'caption' => 'Click the \'Get Code\' button, copy/paste in your page'
        ),
        array(
            'img'     => 'page-code.png',
            'caption' => 'The complete page code is also available'
        ),
        array(
            'img'     => 'load-save.png',
            'caption' => 'Load / Save your forms in JSON format at any time'
        )
    );
    ?>
    <meta name="msvalidate.01" content="E3AF40C058E0C40A855426AF92C86F46" />
    <style>
        @media all and (min-width: 768px){ .fancybox-thumbs{ top: auto !important; width: auto !important; bottom: 0 !important; left: 0 !important; right: 0 !important; height: 95px !important; padding: 10px 10px 5px 10px !important; box-sizing: border-box !important; background: rgba(0, 0, 0, 0.3) !important;} .fancybox-show-thumbs .fancybox-inner{ right: 0 !important; bottom: 95px !important;}}
        @font-face{font-display:swap;font-family:Roboto;font-style:normal;font-weight:300;src:local("Roboto Light"),local("Roboto-Light"),url('https://www.phpformbuilder.pro/documentation/assets/fonts/roboto-v18-latin-300.woff2') format("woff2"),url('https://www.phpformbuilder.pro/documentation/assets/fonts/roboto-v18-latin-300.woff') format("woff")}@font-face{font-display:swap;font-family:Roboto;font-style:normal;font-weight:400;src:local("Roboto"),local("Roboto-Regular"),url('https://www.phpformbuilder.pro/documentation/assets/fonts/roboto-v18-latin-regular.woff2') format("woff2"),url('https://www.phpformbuilder.pro/documentation/assets/fonts/roboto-v18-latin-regular.woff') format("woff")}@font-face{font-display:swap;font-family:Roboto;font-style:normal;font-weight:500;src:local("Roboto Medium"),local("Roboto-Medium"),url('https://www.phpformbuilder.pro/documentation/assets/fonts/roboto-v18-latin-500.woff2') format("woff2"),url('https://www.phpformbuilder.pro/documentation/assets/fonts/roboto-v18-latin-500.woff') format("woff")}@font-face{font-display:swap;font-family:Roboto Condensed;font-style:normal;font-weight:400;src:local("Roboto Condensed"),local("RobotoCondensed-Regular"),url('https://www.phpformbuilder.pro/documentation/assets/fonts/roboto-condensed-v16-latin-regular.woff2') format("woff2"),url('https://www.phpformbuilder.pro/documentation/assets/fonts/roboto-condensed-v16-latin-regular.woff') format("woff")}@font-face{font-display:swap;font-family:bootstrap-icons;src:url('https://www.phpformbuilder.pro/documentation/assets/fonts/bootstrap-icons.woff2') format("woff2"),url('https://www.phpformbuilder.pro/documentation/assets/fonts/bootstrap-icons.woff') format("woff")}@font-face{font-family:icomoon;src:url('https://www.phpformbuilder.pro/drag-n-drop-form-builder/assets/fonts/icomoon.eot?wwups2');src:url('https://www.phpformbuilder.pro/drag-n-drop-form-builder/assets/fonts/icomoon.eot?wwups2#iefix') format('embedded-opentype'),url('https://www.phpformbuilder.pro/drag-n-drop-form-builder/assets/fonts/icomoon.ttf?wwups2') format('truetype'),url('https://www.phpformbuilder.pro/drag-n-drop-form-builder/assets/fonts/icomoon.woff?wwups2') format('woff'),url('https://www.phpformbuilder.pro/drag-n-drop-form-builder/assets/fonts/icomoon.svg?wwups2#icomoon') format('svg');font-weight:400;font-style:normal;font-display:block}:root{--bs-blue:#0e73cc;--bs-red:#fc4848;--bs-yellow:#ffc107;--bs-gray:#8c8476;--bs-gray-dark:#38352f;--bs-gray-100:#f6f6f5;--bs-gray-200:#eae8e5;--bs-gray-300:#d4d1cc;--bs-gray-400:#bfbab2;--bs-gray-500:#a9a398;--bs-gray-600:#7f7a72;--bs-gray-700:#55524c;--bs-gray-800:#2a2926;--bs-gray-900:#191817;--bs-primary:#0e73cc;--bs-secondary:#7f7a72;--bs-success:#0f9e7b;--bs-info:#00c2db;--bs-pink:#e6006f;--bs-warning:#ffc107;--bs-danger:#fc4848;--bs-light:#f6f6f5;--bs-dark:#191817;--bs-primary-rgb:14,115,204;--bs-secondary-rgb:127,122,114;--bs-success-rgb:15,158,123;--bs-info-rgb:0,194,219;--bs-pink-rgb:230,0,111;--bs-warning-rgb:255,193,7;--bs-danger-rgb:252,72,72;--bs-light-rgb:246,246,245;--bs-dark-rgb:25,24,23;--bs-white-rgb:255,255,255;--bs-black-rgb:0,0,0;--bs-body-color-rgb:42,45,45;--bs-body-bg-rgb:255,255,255;--bs-font-sans-serif:Roboto,-apple-system,BlinkMacSystemFont,"Segoe UI","Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";--bs-font-monospace:Consolas,Monaco,Andale Mono,Ubuntu Mono,monospace;--bs-gradient:linear-gradient(180deg,hsla(0,0%,100%,.15),hsla(0,0%,100%,0));--bs-body-font-family:Roboto,-apple-system,BlinkMacSystemFont,Segoe UI,Helvetica Neue,Arial,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol;--bs-body-font-size:0.9375rem;--bs-body-font-weight:400;--bs-body-line-height:1.5;--bs-body-color:#2a2d2d;--bs-body-bg:#fff}*,:after,:before{box-sizing:border-box}@media (prefers-reduced-motion:no-preference){:root{scroll-behavior:smooth}}body{-webkit-text-size-adjust:100%;background-color:var(--bs-body-bg);color:var(--bs-body-color);font-family:var(--bs-body-font-family);font-size:var(--bs-body-font-size);font-weight:var(--bs-body-font-weight);line-height:var(--bs-body-line-height);margin:0;text-align:var(--bs-body-text-align)}hr{background-color:currentColor;border:0;color:inherit;margin:1rem 0;opacity:.25}hr:not([size]){height:1px}.h4,.h6,h1,h5{font-weight:500;line-height:1.2;margin-bottom:.5rem;margin-top:0}h1{font-size:calc(1.375rem + 1.5vw)}.h4{font-size:calc(1.26563rem + .1875vw)}@media (min-width:1200px){h1{font-size:2.5rem}.h4{font-size:1.40625rem}}h5{font-size:1.171875rem}.h6{font-size:.9375rem}p{margin-bottom:1rem;margin-top:0}ul{padding-left:2rem}ul{margin-bottom:1rem;margin-top:0}.small,small{font-size:.875em}a{color:#0e73cc;text-decoration:underline}img{vertical-align:middle}button{border-radius:0}button,input{font-family:inherit;font-size:inherit;line-height:inherit;margin:0}button{text-transform:none}[type=button],button{-webkit-appearance:button}::-moz-focus-inner{border-style:none;padding:0}fieldset{border:0;margin:0;min-width:0;padding:0}legend{float:left;font-size:calc(1.275rem + .3vw);line-height:inherit;margin-bottom:.5rem;padding:0;width:100%}legend+*{clear:left}::-webkit-datetime-edit-day-field,::-webkit-datetime-edit-fields-wrapper,::-webkit-datetime-edit-hour-field,::-webkit-datetime-edit-minute,::-webkit-datetime-edit-month-field,::-webkit-datetime-edit-text,::-webkit-datetime-edit-year-field{padding:0}::-webkit-inner-spin-button{height:auto}::-webkit-search-decoration{-webkit-appearance:none}::-webkit-color-swatch-wrapper{padding:0}::file-selector-button{font:inherit}::-webkit-file-upload-button{-webkit-appearance:button;font:inherit}.alert{border:1px solid transparent;border-radius:0;margin-bottom:1rem;padding:1rem;position:relative}.alert-heading{color:inherit}.alert-info{background-color:#ccf3f8;border-color:#b3edf4;color:#007483}.btn{background-color:transparent;border:1px solid transparent;border-radius:0;color:#2a2d2d;display:inline-block;font-size:.9375rem;font-weight:400;line-height:1.5;padding:.375rem .75rem;text-align:center;text-decoration:none;vertical-align:middle}.btn-primary{background-color:#0e73cc;border-color:#0e73cc;box-shadow:inset 0 1px 0 hsla(0,0%,100%,.15),0 1px 1px rgba(0,0,0,.075);color:#fff}.btn-secondary{background-color:#7f7a72;border-color:#7f7a72;box-shadow:inset 0 1px 0 hsla(0,0%,100%,.15),0 1px 1px rgba(0,0,0,.075);color:#000}.btn-warning{background-color:#ffc107;border-color:#ffc107;box-shadow:inset 0 1px 0 hsla(0,0%,100%,.15),0 1px 1px rgba(0,0,0,.075);color:#000}.btn-link{color:#0e73cc;font-weight:400;text-decoration:underline}.card{word-wrap:break-word;background-clip:border-box;background-color:#fff;border:1px solid rgba(0,0,0,.125);border-radius:0;display:flex;flex-direction:column;min-width:0;position:relative}.card-body{flex:1 1 auto;padding:1rem}.card-header{background-color:rgba(0,0,0,.03);border-bottom:1px solid rgba(0,0,0,.125);margin-bottom:0;padding:.5rem 1rem}.card-header:first-child{border-radius:0}.btn-close{background:url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%232a2d2d'%3E%3Cpath d='M.293.293a1 1 0 0 1 1.414 0L8 6.586 14.293.293a1 1 0 1 1 1.414 1.414L9.414 8l6.293 6.293a1 1 0 0 1-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 0 1-1.414-1.414L6.586 8 .293 1.707a1 1 0 0 1 0-1.414z'/%3E%3C/svg%3E") 50%/1em auto no-repeat;border:0;border-radius:.25rem;box-sizing:content-box;color:#2a2d2d;height:1em;opacity:.5;padding:.25em;width:1em}.container,.container-fluid{margin-left:auto;margin-right:auto;padding-left:var(--bs-gutter-x,.75rem);padding-right:var(--bs-gutter-x,.75rem);width:100%}@media (min-width:576px){.container{max-width:540px}}@media (min-width:768px){.container{max-width:720px}}@media (min-width:992px){.container{max-width:960px}}@media (min-width:1200px){legend{font-size:1.5rem}.container{max-width:1140px}}.dropdown-toggle{white-space:nowrap}.dropdown-toggle:after{border-bottom:0;border-left:.3em solid transparent;border-right:.3em solid transparent;border-top:.3em solid;content:"";display:inline-block;margin-left:.255em;vertical-align:.255em}.dropdown-menu{background-clip:padding-box;background-color:#fff;border:1px solid rgba(0,0,0,.15);border-radius:.25rem;box-shadow:0 .5rem 1rem rgba(0,0,0,.15);color:#2a2d2d;display:none;font-size:.9375rem;list-style:none;margin:0;min-width:10rem;padding:.5rem 0;position:absolute;text-align:left;z-index:1000}.dropdown-divider{border-top:1px solid rgba(0,0,0,.15);height:0;margin:.5rem 0;overflow:hidden}.dropdown-item{background-color:transparent;border:0;clear:both;color:#191817;display:block;font-weight:400;padding:.25rem 1rem;text-align:inherit;text-decoration:none;white-space:nowrap;width:100%}.dropdown-header{color:#7f7a72;display:block;font-size:.8203125rem;margin-bottom:0;padding:.5rem 1rem;white-space:nowrap}.row{--bs-gutter-x:1.5rem;--bs-gutter-y:0;display:flex;flex-wrap:wrap;margin-left:calc(var(--bs-gutter-x)*-.5);margin-right:calc(var(--bs-gutter-x)*-.5);margin-top:calc(var(--bs-gutter-y)*-1)}.row>*{flex-shrink:0;margin-top:var(--bs-gutter-y);max-width:100%;padding-left:calc(var(--bs-gutter-x)*.5);padding-right:calc(var(--bs-gutter-x)*.5);width:100%}.col{flex:1 0 0%}@media (min-width:768px){.col-md-2{flex:0 0 auto;width:16.66666667%}.col-md-3{flex:0 0 auto;width:25%}.col-md-4{flex:0 0 auto;width:33.33333333%}.col-md-8{flex:0 0 auto;width:66.66666667%}}@media (min-width:992px){.col-lg-3{flex:0 0 auto;width:25%}}.nav{display:flex;flex-wrap:wrap;list-style:none;margin-bottom:0;padding-left:0}.nav-link{color:#0e73cc;display:block;padding:.5rem 1rem;text-decoration:none}.nav-tabs{border-bottom:1px solid #d4d1cc}.nav-tabs .nav-link{background:0 0;border:1px solid transparent;border-top-left-radius:.25rem;border-top-right-radius:.25rem;margin-bottom:-1px}.nav-tabs .nav-link.active{background-color:#fff;border-color:#d4d1cc #d4d1cc #fff;color:#55524c}.tab-content>.tab-pane{display:none}.tab-content>.active{display:block}.navbar{align-items:center;display:flex;flex-wrap:wrap;justify-content:space-between;padding-bottom:.5rem;padding-top:.5rem;position:relative}.navbar>.container-fluid{align-items:center;display:flex;flex-wrap:inherit;justify-content:space-between}.navbar-brand{font-size:1.171875rem;margin-right:1rem;padding-bottom:0;padding-top:0;text-decoration:none;white-space:nowrap}.navbar-nav{display:flex;flex-direction:column;list-style:none;margin-bottom:0;padding-left:0}.navbar-nav .nav-link{padding-left:0;padding-right:0}.navbar-collapse{align-items:center;flex-basis:100%;flex-grow:1}
        .navbar-toggler{background-color:transparent;border:1px solid transparent;border-radius:0;font-size:1.171875rem;line-height:1;padding:.25rem .75rem}.navbar-toggler-icon{background-position:50%;background-repeat:no-repeat;background-size:100%;display:inline-block;height:1.5em;vertical-align:middle;width:1.5em}@media (min-width:992px){.navbar-expand-lg{flex-wrap:nowrap;justify-content:flex-start}.navbar-expand-lg .navbar-nav{flex-direction:row}.navbar-expand-lg .navbar-nav .nav-link{padding-left:1rem;padding-right:1rem}.navbar-expand-lg .navbar-collapse{display:flex!important;flex-basis:auto}.navbar-expand-lg .navbar-toggler{display:none}}.navbar-dark .navbar-brand{color:#fff}.navbar-dark .navbar-nav .nav-link{color:hsla(0,0%,100%,.65)}.navbar-dark .navbar-nav .nav-link.active{color:#fff}.navbar-dark .navbar-toggler{border-color:hsla(0,0%,100%,.1);color:hsla(0,0%,100%,.65)}.navbar-dark .navbar-toggler-icon{background-image:url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3E%3Cpath stroke='rgba(255, 255, 255, 0.65)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E")}.fade:not(.show){opacity:0}.collapse:not(.show){display:none}.fixed-top{top:0}.fixed-top{left:0;position:fixed;right:0;z-index:1030}.visually-hidden{clip:rect(0,0,0,0)!important;border:0!important;height:1px!important;margin:-1px!important;overflow:hidden!important;padding:0!important;position:absolute!important;white-space:nowrap!important;width:1px!important}.overflow-hidden{overflow:hidden!important}.d-block{display:block!important}.d-flex{display:flex!important}.d-none{display:none!important}.border-bottom{border-bottom:1px solid #d4d1cc!important}.border-secondary{border-color:#7f7a72!important}.w-50{width:50%!important}.w-100{width:100%!important}.h-100{height:100%!important}.flex-column{flex-direction:column!important}.flex-wrap{flex-wrap:wrap!important}.justify-content-start{justify-content:flex-start!important}.justify-content-end{justify-content:flex-end!important}.justify-content-center{justify-content:center!important}.justify-content-between{justify-content:space-between!important}.align-items-center{align-items:center!important}.align-items-stretch{align-items:stretch!important}.mx-0{margin-left:0!important;margin-right:0!important}.mx-1{margin-left:.25rem!important;margin-right:.25rem!important}.mx-5{margin-left:3rem!important;margin-right:3rem!important}.mx-auto{margin-left:auto!important;margin-right:auto!important}.mt-4{margin-top:1.5rem!important}.me-2{margin-right:.5rem!important}.me-3{margin-right:1rem!important}.me-4{margin-right:1.5rem!important}.mb-0{margin-bottom:0!important}.mb-1{margin-bottom:.25rem!important}.mb-3{margin-bottom:1rem!important}.mb-4{margin-bottom:1.5rem!important}.mb-5{margin-bottom:3rem!important}.ms-2{margin-left:.5rem!important}.ms-auto{margin-left:auto!important}.p-3{padding:1rem!important}.px-0{padding-left:0!important;padding-right:0!important}.px-1{padding-left:.25rem!important;padding-right:.25rem!important}.py-2{padding-bottom:.5rem!important;padding-top:.5rem!important}.pe-2{padding-right:.5rem!important}.pb-2{padding-bottom:.5rem!important}.ps-4{padding-left:1.5rem!important}.fs-4{font-size:calc(1.26563rem + .1875vw)!important}.fw-light{font-weight:300!important}.text-center{text-align:center!important}.text-decoration-none{text-decoration:none!important}.text-uppercase{text-transform:uppercase!important}.text-secondary{--bs-text-opacity:1;color:rgba(var(--bs-secondary-rgb),var(--bs-text-opacity))!important}.text-white{--bs-text-opacity:1;color:rgba(var(--bs-white-rgb),var(--bs-text-opacity))!important}.text-muted{--bs-text-opacity:1;color:#7f7a72!important}.bg-dark{--bs-bg-opacity:1;background-color:rgba(var(--bs-dark-rgb),var(--bs-bg-opacity))!important}.bg-opacity-75{--bs-bg-opacity:0.75}@media (min-width:768px){.d-md-flex{display:flex!important}}.alert{border:none;position:relative}.alert :first-child{margin-top:0}.alert p{margin-bottom:.5em}.alert p:last-child{margin-bottom:0}#website-navbar{box-shadow:0 .5rem 1rem rgba(0,0,0,.15);font-family:Roboto Condensed}#website-navbar .navbar-nav{align-items:stretch;display:flex;flex-wrap:nowrap;margin-top:1rem;width:100%}#website-navbar .navbar-nav .nav-item{align-items:stretch;flex-grow:1;justify-content:center;line-height:1.25rem}#website-navbar .navbar-nav .nav-link{align-items:center;display:flex;flex-direction:column;font-size:.875rem;justify-content:center;padding:.5rem 1rem;text-align:center;text-transform:uppercase}#website-navbar .navbar-nav .nav-link.active{background-color:#55524c;text-decoration:none}:target{scroll-margin-top:100px}html{font-family:Roboto,-apple-system,BlinkMacSystemFont,Segoe UI,Helvetica Neue,Arial,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol;min-height:100%;position:relative}@media screen and (prefers-reduced-motion:reduce){html{overflow-anchor:none;scroll-behavior:auto}}body{counter-reset:section}.h4,.h6,h1,h5{font-family:Roboto,-apple-system,BlinkMacSystemFont,Segoe UI,Helvetica Neue,Arial,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol}h1{color:#0e73cc;line-height:.9;margin-bottom:2.5rem}h1:first-letter{font-size:2em}h1 small{font-size:1.3125rem;line-height:1;margin-left:.75rem}.h4,h5{color:#55524c;font-weight:300!important}.h4,.h6,h5{margin-bottom:1rem}a{text-decoration:none}.bi:before,[class*=" bi-"]:before{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;display:inline-block;font-family:bootstrap-icons!important;font-style:normal;font-variant:normal;font-weight:400!important;line-height:1;text-transform:none;vertical-align:-.125em}.bi-info-circle-fill:before{content:"\f430"}.bi-play-circle-fill:before{content:"\f4f2"}.list-group{border-radius:.25rem;display:flex;flex-direction:column;margin-bottom:0;padding-left:0}.list-group-item{background-color:#fff;border:1px solid rgba(0,0,0,.125);color:#191817;display:block;padding:.5rem 1rem;position:relative;text-decoration:none}.list-group-item:first-child{border-top-left-radius:inherit;border-top-right-radius:inherit}.list-group-item+.list-group-item{border-top-width:0}.list-group-horizontal{flex-direction:row}.list-group-horizontal>.list-group-item:first-child{border-bottom-left-radius:.25rem;border-top-right-radius:0}.list-group-horizontal>.list-group-item+.list-group-item{border-left-width:0;border-top-width:1px}.modal{display:none;height:100%;left:0;outline:0;overflow-x:hidden;overflow-y:auto;position:fixed;top:0;width:100%;z-index:1055}.modal-dialog{margin:.5rem;position:relative;width:auto}.modal.fade .modal-dialog{transform:translateY(-50px)}.modal-dialog-centered{align-items:center;display:flex;min-height:calc(100% - 1rem)}.modal-content{background-clip:padding-box;background-color:#fff;border:1px solid rgba(0,0,0,.2);border-radius:.3rem;box-shadow:0 .125rem .25rem rgba(0,0,0,.075);display:flex;flex-direction:column;outline:0;position:relative;width:100%}.modal-header{align-items:center;border-bottom:1px solid #d4d1cc;border-top-left-radius:calc(.3rem - 1px);border-top-right-radius:calc(.3rem - 1px);display:flex;flex-shrink:0;justify-content:space-between;padding:1rem}.modal-header .btn-close{margin:-.5rem -.5rem -.5rem auto;padding:.5rem}.modal-title{line-height:1.5;margin-bottom:0}.modal-body{flex:1 1 auto;padding:1rem;position:relative}.modal-footer{align-items:center;border-bottom-left-radius:calc(.3rem - 1px);border-bottom-right-radius:calc(.3rem - 1px);border-top:1px solid #d4d1cc;display:flex;flex-shrink:0;flex-wrap:wrap;justify-content:flex-end;padding:.75rem}.modal-footer>*{margin:.25rem}@media (min-width:576px){.modal-dialog{margin:1.75rem auto;max-width:500px}.modal-dialog-centered{min-height:calc(100% - 3.5rem)}.modal-content{box-shadow:0 .5rem 1rem rgba(0,0,0,.15)}}@media (min-width:992px){#website-navbar{box-shadow:0 2px 1px rgba(0,0,0,.12),0 1px 1px rgba(0,0,0,.24)}#website-navbar .navbar-nav{margin-top:0}#website-navbar .navbar-nav .nav-link{font-size:.8125rem;height:100%;padding-left:.75rem;padding-right:.75rem}#website-navbar .navbar-brand{font-size:1.0625rem;margin-bottom:0}.modal-lg,.modal-xl{max-width:800px}}@media (min-width:1200px){.fs-4{font-size:1.40625rem!important}.modal-xl{max-width:1140px}}.dropdown-toggle:not(.sidebar-toggler):after{background-repeat:no-repeat;border:none;content:" ";display:block;height:14px;line-height:1.40625rem;margin:0;position:absolute;right:1rem;top:calc(50% - 7px);transform:rotate(0);width:7px}.dropdown-toggle{padding-right:2.5rem!important;position:relative}.dropdown-toggle.dropdown-light:after{background-image:url("data:image/svg+xml;charset=utf-8,%3Csvg aria-hidden='true' data-prefix='fas' data-icon='angle-right' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 256 512' class='svg-inline--fa fa-angle-right fa-w-8 fa-2x'%3E%3Cpath fill='%23f6f6f5' d='m224.3 273-136 136c-9.4 9.4-24.6 9.4-33.9 0l-22.6-22.6c-9.4-9.4-9.4-24.6 0-33.9l96.4-96.4-96.4-96.4c-9.4-9.4-9.4-24.6 0-33.9L54.3 103c9.4-9.4 24.6-9.4 33.9 0l136 136c9.5 9.4 9.5 24.6.1 34z'/%3E%3C/svg%3E")}#form-generator-container{max-width:1140px}.list-group-item{border-radius:.25rem!important;border-width:1px!important;margin:5px 0!important;width:100%}@media (min-width:768px){.list-group-item{margin:5px 1.5%!important;width:47%}}#fg-form-wrapper{align-content:flex-start;min-height:65vh;width:100%}.btn-primary{background-color:#0e73cc;color:#fff;text-decoration:none}.btn-secondary{background-color:#55524c;color:#fff;text-decoration:none}.list-unstyled-item{list-style-type:none!important}#preview-modal{overflow-y:hidden!important}.modal-xl{max-width:96%!important}.modal-xl .modal-content{min-height:95vh!important}.modal-xl .modal-content .modal-body{height:100%}.nowrap{white-space:nowrap}[class*=" icon-"],[class^=icon-]{font-family:icomoon!important;font-style:normal;font-weight:400;font-variant:normal;text-transform:none;line-height:1;color:#2a2d2d;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}[class*=" icon-"].icon-lg,[class^=icon-].icon-lg{font-size:2.5em}.icon-bubbles:before{content:"\e914"}.icon-upload1:before{content:"\e904"}.icon-eye:before{content:"\e906"}.icon-download1:before{content:"\e907"}.icon-recaptcha:before{content:"\e919"}.icon-btn-group:before{content:"\e908"}.icon-button:before{content:"\e916"}.icon-checkbox-checked:before{content:"\e918"}.icon-radio-checked:before{content:"\e909"}.icon-input:before{content:"\e90a"}.icon-select:before{content:"\e90b"}.icon-textarea:before{content:"\e90c"}.icon-download:before{content:"\e91b"}.icon-upload:before{content:"\e91c"}.icon-bars:before{content:"\e901"}.icon-tools:before{content:"\e902"}.icon-hcaptcha:before{content:"\e91d";color:#7b7b7b}
    </style>
    <?php require_once '../documentation/inc/css-includes.php'; ?>
    <link rel="stylesheet" href="https://www.phpformbuilder.pro/documentation/assets/stylesheets/drag-and-drop.min.css">

    <link rel="stylesheet" href="assets/css/icomoon.min.css">
    <meta name="wot-verification" content="f1ae0987ae03a7a50b55" />
</head>

<body style="padding-top:76px;">

    <!-- Main navbar -->

    <nav id="website-navbar" class="navbar navbar-dark bg-dark navbar-expand-lg fixed-top">

        <div class="container-fluid px-0">
            <a class="navbar-brand me-3" href="/index.html"><img src="/documentation/assets/images/phpformbuilder-logo.png" width="60" height="60" class="me-3" alt="PHP Form Builder" title="PHP Form Builder">PHP Form Builder</a>
            <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navcol-1"><span class="visually-hidden">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>

            <div class="collapse navbar-collapse" id="navcol-1">

                <ul class="nav navbar-nav ms-auto">

                    <!-- https://www.phpformbuilder.pro navbar -->

                    <li class="nav-item" role="presentation"><a class="nav-link" href="../index.php">Home</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link active" href="index.php">Drag &amp; drop Form Builder</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="../documentation/quick-start-guide.php">Quick Start Guide</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="../templates/index.php">Form Templates</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="../documentation/javascript-plugins.php">Javascript Plugins</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="../documentation/code-samples.php">Code Samples</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="../documentation/class-doc.php">Class Doc.</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="../documentation/functions-reference.php">Functions Reference</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="../documentation/help-center.php">Help Center</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <h1 class="text-center fw-light mb-3">Drag &amp; drop Form Builder<br><small class="text-muted">The easiest way to create professional web forms</small></h1>
    <div class="d-flex justify-content-center mb-4">
        <a href="#instructions" class="btn btn-link me-4"><i class="bi bi-info-circle-fill me-2"></i>Instructions for use</a>
        <a href="#tutorial" class="btn btn-link"><i class="bi bi-play-circle-fill me-2"></i>Tutorial in images</a>
    </div>
    <div id="form-generator-container" class="container-fluid">
        <div class="row mx-0 mb-1 overflow-hidden">
            <div id="ui-icon-bars-left-column" class="col-md-2 mb-1 d-none d-md-flex align-items-center justify-content-start">
                <a href="#" class="d-block p-3 text-decoration-none"><i class="icon-bars text-secondary fs-4"></i></a>
            </div>
            <div class="col-md-8 d-flex justify-content-between mb-1">
                <button class="btn nowrap w-100 h-100 btn-primary dropdown-toggle dropdown-light" type="button" id="load-save-btn" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="pe-2">Load / Save</span></button>
                <div class="dropdown-menu font-weight-normal" aria-labelledby="load-save-btn">
                    <p class="dropdown-header h6">Disk</p>
                    <a id="load-json-from-disk-btn" class="dropdown-item d-flex justify-content-between" href="#" data-bs-toggle="modal" data-bs-target="#load-json-from-disk-modal"><span>Load from disk</span> <i class="icon-upload1 text-muted ps-4"></i></a>
                    <a id="save-json-to-disk-btn" class="dropdown-item d-flex justify-content-between" href="#"><span>Save on disk</span> <i class="icon-download1 text-muted ps-4"></i></a>
                    <div class="dropdown-divider"></div>
                    <p class="dropdown-header h6">Server</p>
                    <a id="load-json-from-server-btn" class="dropdown-item d-flex justify-content-between" href="#" data-bs-toggle="modal" data-bs-target="#load-json-from-server-modal"><span>Load from server</span> <i class="icon-upload text-muted ps-4"></i></a>
                    <a id="save-json-on-server-btn" class="dropdown-item d-flex justify-content-between" href="#" data-bs-toggle="modal" data-bs-target="#save-json-on-server-modal"><span>Save on server</span> <i class="icon-download text-muted ps-4"></i></a>
                </div>
                <button id="main-settings-btn" class="btn nowrap w-100 h-100 mx-1 btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#main-settings-modal">Main settings <i class="icon-tools ms-2 text-white"></i></button>
                <div class="text-right">
                    <a id="preview-btn" href="#" class="btn w-100 mb-1 nowrap btn-warning" data-bs-toggle="modal" data-bs-target="#preview-modal">Preview<i class="icon-eye ms-2"></i></a>
                    <a id="get-code-btn" href="#" class="btn w-100 nowrap btn-warning" data-bs-toggle="modal" data-bs-target="#get-code-modal">Get code<i class="icon-upload ms-2"></i></a>
                </div>
            </div>
            <div id="ui-icon-bars-right-column" class="col-md-2 mb-1 d-none d-md-flex align-items-center justify-content-end">
                <a href="#" class="d-block p-3 text-decoration-none"><i class="icon-bars text-secondary fs-4"></i></a>
            </div>
        </div>
        <div id="app" class="row overflow-hidden mx-0">

            <!--=====================================
        =              Left column              =
        ======================================-->

            <aside id="ui-left-column" class="col-md-3 mb-3" aria-label="Components">
                <div class="card border-secondary h-100">
                    <div class="card-header text-white bg-dark bg-opacity-75 text-uppercase small">Components</div>
                    <div class="card-body small">

                        <ul id="sidebar-components" class="list-group list-group-horizontal text-secondary align-items-stretch flex-wrap">
                            <li class="list-group-item d-flex flex-column justify-content-center align-items-center" data-component="input">
                                <i class="icon-input icon-lg text-secondary aria-hidden"></i><span class="text-gray">Input</span>
                            </li>
                            <li class="list-group-item d-flex flex-column justify-content-center align-items-center" data-component="textarea">
                                <i class="icon-textarea icon-lg text-secondary aria-hidden"></i><span class="text-gray">Textarea</span>
                            </li>
                            <li class="list-group-item d-flex flex-column justify-content-center align-items-center" data-component="select">
                                <i class="icon-select icon-lg text-secondary aria-hidden"></i><span class="text-gray">Select</span>
                            </li>
                            <li class="list-group-item d-flex flex-column justify-content-center align-items-center" data-component="radio">
                                <i class="icon-radio-checked icon-lg text-secondary aria-hidden"></i><span class="text-gray">Radio</span>
                            </li>
                            <li class="list-group-item d-flex flex-column justify-content-center align-items-center" data-component="checkbox">
                                <i class="icon-checkbox-checked icon-lg text-secondary aria-hidden"></i><span class="text-gray">Checkbox</span>
                            </li>
                            <li class="list-group-item d-flex flex-column justify-content-center align-items-center" data-component="fileuploader">
                                <i class="icon-upload icon-lg text-secondary aria-hidden"></i><span class="text-gray">Fileupload</span>
                            </li>
                            <li class="list-group-item d-flex flex-column justify-content-center align-items-center" data-component="hcaptcha">
                                <i class="icon-hcaptcha icon-lg text-secondary aria-hidden"></i><span class="text-gray">Hcaptcha</span>
                            </li>
                            <li class="list-group-item d-flex flex-column justify-content-center align-items-center" data-component="recaptcha">
                                <i class="icon-recaptcha icon-lg text-secondary aria-hidden"></i><span class="text-gray">Recaptcha</span>
                            </li>
                            <li class="list-unstyled-item w-100">

                                <hr class="mx-5">
                            </li>
                            <li class="list-group-item d-flex flex-column justify-content-center align-items-center" data-component="button">
                                <i class="icon-button icon-lg text-secondary aria-hidden"></i><span class="text-gray">Button</span>
                            </li>
                            <li class="list-group-item d-flex flex-column justify-content-center align-items-center" data-component="buttongroup">
                                <i class="icon-btn-group icon-lg text-secondary aria-hidden"></i><span class="text-gray nowrap">Btn group</span>
                            </li>
                            <li class="list-unstyled-item w-100">

                                <hr class="mx-5">
                            </li>
                            <li class="list-group-item d-flex flex-column justify-content-center align-items-center nowrap" data-component="dependent">
                                <i class="icon-bubbles icon-lg text-secondary aria-hidden"></i><span class="text-gray">Start<br>Condition</span>
                            </li>
                            <li class="list-group-item d-flex flex-column justify-content-center align-items-center nowrap" data-component="dependentend">
                                <i class="icon-bubbles icon-lg text-secondary aria-hidden"></i><span class="text-gray">End<br>Condition</span>
                            </li>
                            <li class="list-unstyled-item w-100">

                                <hr class="mx-5">
                            </li>
                            <li class="list-group-item d-flex flex-column justify-content-center align-items-center" data-component="heading">
                                <i class="icon-title icon-lg text-secondary aria-hidden"></i><span class="text-muted">Title</span>
                            </li>
                            <li class="list-group-item d-flex flex-column justify-content-center align-items-center" data-component="paragraph">
                                <i class="icon-short_text icon-lg text-secondary aria-hidden"></i><span class="text-gray">Paragraph</span>
                            </li>
                            <li class="list-group-item d-flex flex-column justify-content-center align-items-center" data-component="html">
                                <i class="icon-html-five icon-lg text-secondary aria-hidden"></i><span class="text-gray">HTML</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </aside>

            <!--=====================================
        =              Main Content             =
        ======================================-->

            <main class="col mb-3">
                <div class="card border-secondary h-100">
                    <div class="card-header text-white bg-dark bg-opacity-75 text-uppercase small">Drop components here to build your form</div>
                    <div class="card-body px-1">
                        <div id="fg-form-wrapper"></div>
                    </div>
                </div>
            </main>

            <!--=====================================
        =              Right column             =
        ======================================-->

            <aside id="ui-right-column" class="col-md-4 col-lg-3 mb-3" aria-label="Component settings">
                <div class="card border-secondary h-100">
                    <div class="card-header text-white bg-dark bg-opacity-75 text-uppercase small">Component settings</div>
                    <div class="card-body">
                        <div id="components-settings"></div>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <!--=============================================
    =                   Website                   =
    =============================================-->

    <main class="container mt-6">

        <section>

            <h2 id="instructions">Instructions for use</h2>

            <h3>Overview<br><small class="text-secondary">Create your form in 3 easy steps</small></h3>
            <ol class="list-group list-group-horizontal mb-5">
                <li class="list-group-item fw-bold py-3 nowrap"><span class="d-inline-block px-2 rounded-circle bg-warning me-2">1</span>Add components</li>
                <li class="list-group-item fw-bold py-3 nowrap"><span class="d-inline-block px-2 rounded-circle bg-warning me-2">2</span>Configure your form</li>
                <li class="list-group-item fw-bold py-3 nowrap"><span class="d-inline-block px-2 rounded-circle bg-warning me-2">3</span>Get the code</li>
            </ol>
            <p class="lead">This online Drag &amp; drop Form Builder is suitable for any type of use.</p>
            <p>You can create your forms with the online tool as well as from your PHP Form Builder local copy.</p>
            <p>Create and configure your forms by simple drag and drop.<br>
                The generated forms can be easily integrated into any HTML or PHP page.</p>
            <p>Any type of integration is possible whatever the CMS or Framework used: Wordpress, Joomla, Drupal, Prestashop, Bootstrap, Material Design, Foundation, ...</p>
            <p>Creating your forms with this online drag-and-drop form builder does not require any knowledge. Its use is simple and intuitive, this is the easiest way to generate any Web form.</p>
            <p>Nevertheless here are some explanations:</p>

            <hr class="my-5">

            <h3>Adding and configuring fields and HTML content</h3>

            <p>Drag the components from the left panel to the center panel.</p>

            <p>Click a component in the center panel to display its properties in the right panel.</p>

            <p>The right panel allows you to choose the properties of the selected item: field name, default value, placeholder, is it a required field or not, etc...</p>

            <p>The available options are different depending on the selected component. For example, you can add and configure the options of a select dropdown, add and configure radio buttons or checkboxes.</p>

            <p>The right panel is generally divided into two or three tabs - one of which is used to add Javascript Plugins.</p>

            <p>Different Javascript Plugins are available, again depending on the component chosen.
                Each plugin provides its own configuration options.</p>

            <hr class="my-5">

            <h3>Main Settings</h3>

            <p>Here you have access to the form parameters: name, framework used (Bootstrap 3/4, Material Design, ...), layout, ... etc.</p>

            <p>The "<em>Form Action</em>" tab lets you define the parameters for sending an email, saving the values in your database, and possibly a redirection.</p>

            <p>The "<em>Form Plugins</em>" tab is where you can configure Javascript Plugins enabled at the form level. Mainly Checkbox and Radio Buttons plugins.</p>

            <p>The "<em>Ajax loading</em>" tab allows you to enable the loading of the form in Ajax. This is particularly useful if you want to insert your form in an HTML page (which does not accept PHP).<br>
                In this case, the HTML page will call the form in Ajax. The PHP form is saved in a separate file.</p>

            <hr class="my-5">

            <h3>Preview</h3>

            <p>The preview button enables you to open a modal at any time and see what your form looks like.</p>
            <p>The plugins are activated in the preview window.</p>

            <hr class="my-5">

            <h3>Get Code</h3>

            <p>Click to build the form and retrieve the code. It's easy: just copy and paste following the instructions and your form is ready.</p>

            <hr class="my-5">

            <h3>Advanced features</h3>

            <p>The online drag-and-drop form builder is designed to easily create your forms.</p>
            <p>PHP Form Builder offers many advanced features, some of which are not available in drag-and-drop.</p>
            <p>Once your form is generated, you can easily use PHP Form Builder functions to customize any field and add more advanced features.</p>

            <hr class="my-5">

            <h3 id="tutorial">Tutorial in images</h3>
            <?php
            $output = '<div class="d-flex flex-column">';
            foreach ($slider as $sl) {
                $output .= '<a href="../assets/images/drag-n-drop/' . $sl['img'] . '" data-fancybox="gallery" data-caption="' . $sl['caption'] . '"> <img src="../assets/images/drag-n-drop/thumbs/' . $sl['img'] . '" class="img-thumbnail me-4 mb-1" width="100" height="88" alt="' . $sl['caption'] . '" /><span class="text-secondary text-decoration-none">' . $sl['caption'] . '</span></a>';
            }
            $output .= '</div>';
            echo $output;
            ?>
        </section>
    </main>

    <!--============  End of Website  =============-->

    <!--=====================================
    =                Modals                 =
    ======================================-->

    <!-- load JSON from disk -->
    <div id="load-json-from-disk-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="load-json-from-disk-btn" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <h5 class="modal-title">Upload your form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <input id="json-file-disk-input" style="display: none" type="file" accept=".json">
                    <button class="btn btn-primary" onclick="document.getElementById('json-file-disk-input').click();">Browse</button>
                    <div id="json-file-disk-output"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cancel</button>
                    <button id="json-file-disk-load-btn" type="button" class="btn btn-primary">Load <i class="icon-upload1 text-white ms-2"></i></button>
                </div>
            </div>
        </div>
    </div>

    <!-- load JSON from server -->
    <div id="load-json-from-server-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="load-json-from-server-btn" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <h5 class="modal-title">Upload your form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div id="demo-server-delete-disabled"></div>
                    <div id="json-file-server-output"></div>
                    <div id="json-forms-file-tree-wrapper">
                        <div class="ft-tree"></div>
                        <div class="ft-explorer"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cancel</button>
                    <button id="json-file-server-load-btn" type="button" class="btn btn-primary">Load <i class="icon-upload text-white ms-2"></i></button>
                </div>
            </div>
        </div>
    </div>

    <!-- save JSON on server -->
    <div id="save-json-on-server-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="save-json-on-server-btn" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <h5 class="modal-title">Save your form on server</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div id="demo-server-save-disabled"></div>
                    <div id="save-on-server-file-tree-wrapper">
                        <div class="ft-tree"></div>
                        <div class="ft-explorer"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cancel</button>
                    <button id="json-file-server-save-btn" type="button" class="btn btn-primary">Save <i class="icon-download text-white ms-2"></i></button>
                </div>
            </div>
        </div>
    </div>

    <!-- main settings -->
    <div id="main-settings-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="main-settings-btn" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" id="user-form-settings">
                <div class="modal-header">

                    <h5 class="modal-title">Main Settings</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">

                        <div class="row justify-content-center">

                            <ul class="col-md-8 nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link text-secondary active" id="nav-tab-main-settings-main" data-bs-toggle="tab" href="#tab-main-settings-main" role="tab" aria-controls="tab-main-settings-main" aria-selected="true">Form settings</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-secondary" id="nav-tab-main-settings-action" data-bs-toggle="tab" href="#tab-main-settings-action" role="tab" aria-controls="tab-main-settings-action" aria-selected="false">Form action</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-secondary" id="nav-tab-main-settings-plugins" data-bs-toggle="tab" href="#tab-main-settings-plugins" role="tab" aria-controls="tab-main-settings-plugins" aria-selected="false">Form plugins</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-secondary" id="nav-tab-main-settings-ajax" data-bs-toggle="tab" href="#tab-main-settings-ajax" role="tab" aria-controls="tab-main-settings-ajax" aria-selected="false">Ajax loading</a>
                                </li>
                            </ul>
                        </div>
                        <div class="row justify-content-center">
                            <p class="col-md-8 small text-right text-muted py-2 mb-4">All the changes are registered in real time.</p>
                        </div>
                    </div>
                    <div class="container">
                        <div class="row justify-content-center tab-content">
                            <div id="tab-main-settings-main" class="col-md-8 tab-pane fade show active" role="tabpanel" aria-labelledby="nav-tab-main-settings-main">

                                <section id="user-form-settings-main"></section>
                            </div>
                            <div id="tab-main-settings-action" class="col-md-8 tab-pane fade" role="tabpanel" aria-labelledby="nav-tab-main-settings-action">

                                <section id="user-form-settings-action"></section>
                                <div id="collapsible-form-actions" class="accordion mt-4">
                                    <fieldset id="send-email" class="collapse" data-parent="#collapsible-form-actions">
                                        <legend class="h4 pb-2 border-bottom border-bottom-gray fw-light">Send email</legend>

                                        <section id="user-form-settings-sendmail"></section>
                                    </fieldset>
                                    <fieldset id="db-insert" class="collapse" data-parent="#collapsible-form-actions">
                                        <legend class="h4 pb-2 border-bottom border-bottom-gray fw-light">Database insert</legend>

                                        <section id="user-form-settings-db-insert"></section>
                                    </fieldset>
                                    <fieldset id="db-update" class="collapse" data-parent="#collapsible-form-actions">
                                        <legend class="h4 pb-2 border-bottom border-bottom-gray fw-light">Database update</legend>

                                        <section id="user-form-settings-db-update"></section>
                                    </fieldset>
                                    <fieldset id="db-delete" class="collapse" data-parent="#collapsible-form-actions">
                                        <legend class="h4 pb-2 border-bottom border-bottom-gray fw-light">Database delete</legend>

                                        <section id="user-form-settings-db-delete"></section>
                                    </fieldset>
                                </div>
                            </div>
                            <div id="tab-main-settings-plugins" class="col-md-8 tab-pane fade" role="tabpanel" aria-labelledby="nav-tab-main-settings-plugins">

                                <section id="user-form-settings-plugins"></section>
                            </div>
                            <div id="tab-main-settings-ajax" class="col-md-8 tab-pane fade" role="tabpanel" aria-labelledby="nav-tab-main-settings-ajax">
                                <div class="alert alert-info text-center mb-5">

                                    <h5 class="alert-heading">If you use a CMS, enable Ajax loading</h5>

                                    <hr>
                                    <p>Ajax loading allows you to insert the form into your HTML page without PHP code.</p>
                                    <p class="mb-0">The form is saved in an separate PHP file, called by the Ajax script.</p>
                                </div>

                                <section id="user-form-settings-ajax" class="text-center w-50 mx-auto"></section>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- preview -->
    <div id="preview-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="preview-btn" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">

                    <h5 class="modal-title">Form Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- get code -->
    <div id="get-code-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="get-code-btn" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">

                    <h5 class="modal-title">Form Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- errors -->
    <div id="errors-modal" class="modal fade" tabindex="-1" role="dialog" data-show="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">

                    <h5 class="modal-title fw-light">Your form has errors</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/javascripts/loadjs.min.js"></script>
    <script src="assets/javascripts/bundle.min.js"></script>
    <!--=============================================
    =                   Website                   =
    =============================================-->

    <script>
        loadjs.ready('core', function() {
            loadjs(['../lib/fancybox/jquery.fancybox.min.css', '../lib/fancybox/jquery.fancybox.min.js'], 'fancybox');

            loadjs.ready('fancybox', function() {
                $('[data-fancybox="gallery"]').fancybox({
                    buttons: [
                        "share",
                        "slideShow",
                        "thumbs",
                        "close"
                    ],
                    thumbs: {
                        autoStart: true,
                        axis: 'x'
                    }
                });
            });
        });
    </script>

    <!--============  End of Website  =============-->
</body>

</html>
