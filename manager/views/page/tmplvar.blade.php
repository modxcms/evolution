@extends('manager::template.page')
@section('content')
    <?php /** @var EvolutionCMS\Models\SiteTmplvar $data */ ?>
    @push('scripts.top')
        <script>

            /*
            * Set methods for props
            * */
            var props = {
                currentParams: {},
                f: '',
                first: true,
                showParameters: function (ctrl) {
                    var c, p, df, cp;
                    var ar, label, value, key, dt, defaultVal, tr;

                    var valueElement = document.getElementById('properties');
                    if (!valueElement) {
                        return;
                    }

                    this.currentParams = {}; // reset;

                    if (ctrl && ctrl.form) {
                        this.f = ctrl.form;
                    } else {
                        this.f = document.forms['mutate'];
                        if (!this.f) {
                            return;
                        }
                    }

                    tr = document.getElementById('displaypropsrow');

                    // check if codemirror is used
                    var t, td, desc;
                    this.currentParams = JSON.parse(valueElement.value);

                    t = '<table width="100%" class="displayparams grid"><thead><tr><td>{{ ManagerTheme::getLexicon('parameter') }}</td><td>{{ ManagerTheme::getLexicon('value') }}</td><td style="text-align:right;white-space:nowrap">{{ ManagerTheme::getLexicon('set_default') }} </td></tr></thead>';

                    try {
                        var type, options, found, info, sd;
                        var ll, ls, sets = [], lv, arrValue, split;

                        for (var key in this.currentParams) {

                            if (key === 'internal' || this.currentParams[key][0]['label'] == undefined) {
                                return;
                            }

                            cp = this.currentParams[key][0];
                            type = cp['type'];
                            value = cp['value'];
                            defaultVal = cp['default'];
                            label = cp['label'] != undefined ? cp['label'] : key;
                            desc = cp['desc'] + '';
                            options = cp['options'] != undefined ? cp['options'] : '';

                            ll = [];
                            ls = [];
                            if (options.indexOf('==') > -1) {
                                // option-format: label==value||label==value
                                sets = options.split('||');
                                for (i = 0; i < sets.length; i++) {
                                    split = sets[i].split('==');
                                    ll[i] = split[0];
                                    ls[i] = split[1] != undefined ? split[1] : split[0];
                                }
                            } else {
                                // option-format: value,value
                                ls = options.split(',');
                                ll = ls;
                            }

                            key   = key.replace(/\"/g, '&quot;');
                            value = value.replace(/\"/g, '&quot;');

                            switch (type) {
                                case 'int':
                                    c = '<input type="text" name="prop_' + key + '" value="' + value + '" onchange="props.setParameter(\'' + key + '\',\'' + type + '\',this)" />';
                                    break;
                                case 'menu':
                                    c = '<select name="prop_' + key + '" onchange="props.setParameter(\'' + key + '\',\'' + type + '\',this)">';
                                    if (this.currentParams[key] === options) {
                                        this.currentParams[key] = ls[0];
                                    } // use first list item as default
                                    for (i = 0; i < ls.length; i++) {
                                        c += '<option value="' + ls[i] + '"' + ((ls[i] === value) ? ' selected="selected"' : '') + '>' + ll[i] + '</option>';
                                    }
                                    c += '</select>';
                                    break;
                                case 'list':
                                    if (this.currentParams[key] === options) {
                                        this.currentParams[key] = ls[0];
                                    } // use first list item as default
                                    c = '<select name="prop_' + key + '" onchange="props.setParameter(\'' + key + '\',\'' + type + '\',this)">';
                                    for (i = 0; i < ls.length; i++) {
                                        c += '<option value="' + ls[i] + '"' + ((ls[i] === value) ? ' selected="selected"' : '') + '>' + ll[i] + '</option>';
                                    }
                                    c += '</select>';
                                    break;
                                case 'list-multi':
                                    // value = typeof ar[3] !== 'undefined' ? (ar[3] + '').replace(/^\s|\s$/, "") : '';
                                    arrValue = value.split(',');
                                    if (this.currentParams[key] === options) {
                                        this.currentParams[key] = ls[0];
                                    } // use first list item as default
                                    c = '<select name="prop_' + key + '" size="' + ls.length + '" multiple="multiple" onchange="props.setParameter(\'' + key + '\',\'' + type + '\',this)">';
                                    for (i = 0; i < ls.length; i++) {
                                        if (arrValue.length) {
                                            found = false;
                                            for (j = 0; j < arrValue.length; j++) {
                                                if (ls[i] === arrValue[j]) {
                                                    found = true;
                                                }
                                            }
                                            if (found === true) {
                                                c += '<option value="' + ls[i] + '" selected="selected">' + ll[i] + '</option>';
                                            } else {
                                                c += '<option value="' + ls[i] + '">' + ll[i] + '</option>';
                                            }
                                        } else {
                                            c += '<option value="' + ls[i] + '">' + ll[i] + '</option>';
                                        }
                                    }
                                    c += '</select>';
                                    break;
                                case 'checkbox':
                                    lv = (value + '').split(',');
                                    c = '';
                                    for (i = 0; i < ls.length; i++) {
                                        c += '<label><input type="checkbox" name="prop_' + key + '[]" value="' + ls[i] + '"' + ((contains(lv, ls[i]) === true) ? ' checked="checked"' : '') + ' onchange="props.setParameter(\'' + key + '\',\'' + type + '\',this)" /> ' + ll[i] + '</label>&nbsp;';
                                    }
                                    break;
                                case 'radio':
                                    c = '';
                                    for (i = 0; i < ls.length; i++) {
                                        c += '<label><input type="radio" name="prop_' + key + '" value="' + ls[i] + '"' + ((ls[i] === value) ? ' checked="checked"' : '') + ' onchange="props.setParameter(\'' + key + '\',\'' + type + '\',this)" /> ' + ll[i] + '</label>&nbsp;';
                                    }
                                    break;
                                case 'textarea':
                                    c = '<textarea name="prop_' + key + '" rows="4" onchange="props.setParameter(\'' + key + '\',\'' + type + '\',this)">' + value + '</textarea>';
                                    break;
                                default:  // string
                                    c = '<input type="text" name="prop_' + key + '" value="' + value + '" onchange="props.setParameter(\'' + key + '\',\'' + type + '\',this)" />';
                                    break;
                            }

                            info = '';
                            info += desc ? '<br/><small>' + desc + '</small>' : '';
                            sd = defaultVal != undefined ? '<a title="{{ ManagerTheme::getLexicon('set_default') }}" href="javascript:;" class="btn btn-primary" onclick="props.setDefaultParam(\'' + key + '\',1);return false;"><i class="{{ $_style['icon_refresh'] }}"></i></a>' : '';

                            t += '<tr><td class="labelCell" width="20%"><span class="paramLabel">' + label + '</span><span class="paramDesc">' + info + '</span></td><td class="inputCell relative" width="74%">' + c + '</td><td style="text-align: center">' + sd + '</td></tr>';
                        }

                        t += '</table>';

                    } catch (e) {
                        t = e + '\n\n' + props;
                    }

                    td = document.getElementById('displayprops');
                    td.innerHTML = t;
                    tr.style.display = '';
                    if (JSON.stringify(this.currentParams) === '{}') return;

                    this.implodeParameters();
                },
                setParameter: function (key, dt, ctrl) {
                    var v, arrValues, cboxes = [];
                    if (!ctrl) {
                        return null;
                    }
                    switch (dt) {
                        case 'int':
                            ctrl.value = parseInt(ctrl.value);
                            if (isNaN(ctrl.value)) {
                                ctrl.value = 0;
                            }
                            v = ctrl.value;
                            break;
                        case 'menu':
                        case 'list':
                            v = ctrl.options[ctrl.selectedIndex].value;
                            break;
                        case 'list-multi':
                            arrValues = [];
                            for (var i = 0; i < ctrl.options.length; i++) {
                                if (ctrl.options[i].selected) {
                                    arrValues.push(ctrl.options[i].value);
                                }
                            }
                            v = arrValues.toString();
                            break;
                        case 'checkbox':
                            arrValues = [];
                            cboxes = document.getElementsByName(ctrl.name);
                            for (var i = 0; i < cboxes.length; i++) {
                                if (cboxes[i].checked) {
                                    arrValues.push(cboxes[i].value);
                                }
                            }
                            v = arrValues.toString();
                            break;
                        default:
                            v = ctrl.value + '';
                            break;
                    }
                    this.currentParams[key][0]['value'] = v;
                    this.implodeParameters();
                },
                setDefaultParam: function (key, show) {
                    if (typeof this.currentParams[key][0]['default'] != 'undefined') {
                        this.currentParams[key][0]['value'] = this.currentParams[key][0]['default'];
                        if (show) {
                            this.implodeParameters();
                            this.showParameters();
                        }
                    }
                },
                setDefaults: function () {
                    var keys = Object.keys(this.currentParams);
                    var last = keys[keys.length - 1], show;
                    Object.keys(this.currentParams).forEach(function(key) {
                        show = key === last ? 1 : 0;
                        props.setDefaultParam(key, show);
                    });
                },
                IsJsonString: function (str) {
                    try {
                        JSON.parse(str);
                    } catch (e) {
                        return false;
                    }
                    return true;
                },
                implodeParameters: function () {
                    var stringified = JSON.stringify(this.currentParams, null, 2);
                    this.f.properties.innerHTML = stringified;
                    if (this.first) {
                        documentDirty = false;
                        this.first = false;
                    }
                }
            };

          function check_toggle(target)
          {
            var el = document.getElementsByName(target + '[]');
            var count = el.length;
            for (var i = 0; i < count; i++) {
              el[i].checked = !el[i].checked;
            }
          };

          function check_none(target)
          {
            var el = document.getElementsByName(target + '[]');
            var count = el.length;
            for (var i = 0; i < count; i++) {
              el[i].checked = false;
            }
          };

          function check_all(target)
          {
            var el = document.getElementsByName(target + '[]');
            var count = el.length;
            for (var i = 0; i < count; i++) {
              el[i].checked = true;
            }
          };

          var actions = {
            save: function() {
              documentDirty = false;
              form_save = true;
              document.mutate.save.click();
              saveWait('mutate');
            },
            duplicate: function() {
              if (confirm("{{ ManagerTheme::getLexicon('confirm_duplicate_record') }}") === true) {
                documentDirty = false;
                document.location.href = "index.php?id={{ $data->getKey() }}&a=304";
              }
            },
            delete: function() {
              if (confirm("{{ ManagerTheme::getLexicon('confirm_delete_tmplvars') }}") === true) {
                documentDirty = false;
                document.location.href = 'index.php?id=' + document.mutate.id.value + '&a=303';
              }
            },
            cancel: function() {
              documentDirty = false;
              document.location.href = 'index.php?a={{ $origin }}@if(!empty($originId))&id={{ $originId}}@endif&tab=1';
            }
          };

          // Widget Parameters
          var widgetParams = {};          // name = description;datatype;default or list values - datatype: int, string, list : separated by comma (,)
          widgetParams['date'] = '&format=Date Format;string;%A %d, %B %Y &default=If no value, use current date;list;Yes,No;No';
          widgetParams['string'] = '&format=String Format;list;Upper Case,Lower Case,Sentence Case,Capitalize';
          widgetParams['delim'] = '&format=Delimiter;string;,';
          widgetParams['hyperlink'] = '&text=Display Text;string; &title=Title;string; &class=Class;string &style=Style;string &target=Target;string &attrib=Attributes;string';
          widgetParams['htmltag'] = '&tagname=Tag Name;string;div &tagid=Tag ID;string &class=Class;string &style=Style;string &attrib=Attributes;string';
          widgetParams['viewport'] = '&vpid=ID/Name;string &width=Width;string;100 &height=Height;string;100 &borsize=Border Size;int;1 &sbar=Scrollbars;list;,Auto,Yes,No &asize=Auto Size;list;,Yes,No &aheight=Auto Height;list;,Yes,No &awidth=Auto Width;list;,Yes,No &stretch=Stretch To Fit;list;,Yes,No &class=Class;string &style=Style;string &attrib=Attributes;string';
          widgetParams['datagrid'] = '&cols=Column Names;string &flds=Field Names;string &cwidth=Column Widths;string &calign=Column Alignments;string &ccolor=Column Colors;string &ctype=Column Types;string &cpad=Cell Padding;int;1 &cspace=Cell Spacing;int;1 &rowid=Row ID Field;string &rgf=Row Group Field;string &rgstyle = Row Group Style;string &rgclass = Row Group Class;string &rowsel=Row Select;string &rhigh=Row Hightlight;string; &psize=Page Size;int;100 &ploc=Pager Location;list;top-right,top-left,bottom-left,bottom-right,both-right,both-left; &pclass=Pager Class;string &pstyle=Pager Style;string &head=Header Text;string &foot=Footer Text;string &tblc=Grid Class;string &tbls=Grid Style;string &itmc=Item Class;string &itms=Item Style;string &aitmc=Alt Item Class;string &aitms=Alt Item Style;string &chdrc=Column Header Class;string &chdrs=Column Header Style;string;&egmsg=Empty message;string;No records found;';
          widgetParams['richtext'] = '&w=Width;string;100% &h=Height;string;300px &edt=Editor;list;{!! get_by_key($events, 'OnRichTextEditorRegister') !!}';
          widgetParams['image'] = '&alttext=Alternate Text;string &hspace=H Space;int &vspace=V Space;int &borsize=Border Size;int &align=Align;list;none,baseline,top,middle,bottom,texttop,absmiddle,absbottom,left,right &name=Name;string &class=Class;string &id=ID;string &style=Style;string &attrib=Attributes;string';
          widgetParams['custom_widget'] = '&output=Output;textarea;[+value+]';

          // Current Params
          var currentParams = {};
          var lastdf, lastmod = {};

          function showParameters(ctrl)
          {
            var c, p, df, cp;
            var ar, desc, value, key, dt;

            currentParams = {}; // reset;

            if (ctrl && ctrl.form) {
              f = ctrl.form;
            } else {
              f = document.forms['mutate'];
              if (!f) return;
              ctrl = f.display;
            }
            cp = f.params.value.split('&'); // load current setting once

            // get display format
            df = lastdf = ctrl.options[ctrl.selectedIndex].value;

            // load last modified param values
            if (lastmod[df]) cp = lastmod[df].split('&');
            for (p = 0; p < cp.length; p++) {
              cp[p] = (cp[p] + '').replace(/^\s|\s$/, ''); // trim
              ar = cp[p].split('=');
              currentParams[ar[0]] = ar[1];
            }

            // setup parameters
            var tr = document.getElementById('displayparamrow'), t, td,
                dp = (widgetParams[df]) ? widgetParams[df].split('&') : '';
            if (!dp) {
              tr.style.display = 'none';
            } else {
              t = '<table class="displayparams"><thead><tr><td width="50%">{{ ManagerTheme::getLexicon('parameter') }}</td><td width="50%">{{ ManagerTheme::getLexicon('value') }}</td></tr></thead>';
              for (p = 0; p < dp.length; p++) {
                dp[p] = (dp[p] + '').replace(/^\s|\s$/, ''); // trim
                ar = dp[p].split('=');
                key = ar[0];     // param
                ar = (ar[1] + '').split(';');
                desc = ar[0];   // description
                dt = ar[1];     // data type
                value = decode((currentParams[key]) ? currentParams[key] : (dt === 'list') ? ar[3] : (ar[2]) ? ar[2] : '');
                if (value !== currentParams[key]) currentParams[key] = value;
                value = (value + '').replace(/^\s|\s$/, ''); // trim
                value = value.replace(/\"/g, '&quot;'); // replace double quotes with &quot;
                if (dt) {
                  switch (dt) {
                    case 'int':
                    case 'float':
                      c = '<input type="text" name="prop_' + key + '" value="' + value + '" size="30" onchange="setParameter(\'' + key + '\',\'' + dt + '\',this)" />';
                      break;
                    case 'list':
                      c = '<select name="prop_' + key + '" onchange="setParameter(\'' + key + '\',\'' + dt + '\',this)">';
                      var ls = (ar[2] + '').split(',');
                      if (!currentParams[key] || currentParams[key] === 'undefined') {
                        currentParams[key] = ls[0]; // use first list item as default
                      }
                      for (var i = 0; i < ls.length; i++) {
                        c += '<option value="' + ls[i] + '"' + ((ls[i] === value) ? ' selected="selected"' : '') + '>' + ls[i] + '</option>';
                      }
                      c += '</select>';
                      break;
                    case 'textarea':
                      c = '<textarea class="inputBox phptextarea" name="prop_' + key + '" cols="25" style="width:220px;" onchange="setParameter(\'' + key + '\',\'' + dt + '\',this)" >' + value + '</textarea>';
                      break;
                    default:  // string
                      c = '<input type="text" name="prop_' + key + '" value="' + value + '" size="30" onchange="setParameter(\'' + key + '\',\'' + dt + '\',this)" />';
                      break;
                  }
                  t += '<tr><td bgcolor="#FFFFFF" width="50%">' + desc + '</td><td bgcolor="#FFFFFF" width="50%">' + c + '</td></tr>';
                }
                ;
              }
              t += '</table>';
              td = (document.getElementById) ? document.getElementById('displayparams') : document.all['displayparams'];
              td.innerHTML = t;
              tr.style.display = '';
            }
            implodeParameters();
          }

          function setParameter(key, dt, ctrl)
          {
            var v;
            if (!ctrl) return null;
            switch (dt) {
              case 'int':
                ctrl.value = parseInt(ctrl.value);
                if (isNaN(ctrl.value)) ctrl.value = 0;
                v = ctrl.value;
                break;
              case 'float':
                ctrl.value = parseFloat(ctrl.value);
                if (isNaN(ctrl.value)) ctrl.value = 0;
                v = ctrl.value;
                break;
              case 'list':
                v = ctrl.options[ctrl.selectedIndex].value;
                break;
              case 'textarea':
                v = ctrl.value + '';
                break;
              default:
                v = ctrl.value + '';
                break;
            }
            currentParams[key] = v;
            implodeParameters();
          }

          function resetParameters()
          {
            document.mutate.params.value = '';
            lastmod[lastdf] = '';
            showParameters();
          }

          // implode parameters
          function implodeParameters()
          {
            var v, p, s = '';
            for (p in currentParams) {
              v = currentParams[p];
              if (v) s += '&' + p + '=' + encode(v);
            }
            document.forms['mutate'].params.value = s;
            if (lastdf) lastmod[lastdf] = s;
          }

          function encode(s)
          {
            s = s + '';
            s = s.replace(/\=/g, '%3D'); // =
            s = s.replace(/\&/g, '%26'); // &
            return s;
          }

          function decode(s)
          {
            s = s + '';
            s = s.replace(/\%3D/g, '='); // =
            s = s.replace(/\%26/g, '&'); // &
            return s;
          }

          document.addEventListener('DOMContentLoaded', function() {
            var h1help = document.querySelector('h1 > .help');
            h1help.onclick = function() {
              document.querySelector('.element-edit-message').classList.toggle('show');
            };
          });

        </script>
    @endpush

    <form name="mutate" method="post" action="index.php" enctype="multipart/form-data">
        {!! get_by_key($events, 'OnTVFormPrerender') !!}

        <input type="hidden" name="id" value="{{ $data->getKey() }}">
        <input type="hidden" name="a" value="302">
        <input type="hidden" name="or" value="{{ $origin }}">
        <input type="hidden" name="oid" value="{{ $originId }}">
        <input type="hidden" name="mode" value="{{ $action }}">
        <input type="hidden" name="params" value="{{ $data->display_params }}">

        <h1>
            <i class="{{ $_style['icon_tv'] }}"></i>
            @if($data->name)
                {{ $data->name }}
                <small>({{ $data->getKey() }})</small>
            @else
                {{ ManagerTheme::getLexicon('new_tmplvars') }}
            @endif
            <i class="{{ $_style['icon_question_circle'] }} help"></i>
        </h1>

        @include('manager::partials.actionButtons', $actionButtons)

        <div class="container element-edit-message">
            <div class="alert alert-info">{!! ManagerTheme::getLexicon('tmplvars_msg') !!}</div>
        </div>

        <div class="tab-pane" id="tmplvarsPane">
            <script>
              var tpTmplvars = new WebFXTabPane(document.getElementById('tmplvarsPane'), false);
            </script>

            <div class="tab-page" id="tabGeneral">
                <h2 class="tab">{{ ManagerTheme::getLexicon('settings_general') }}</h2>
                <script>tpTmplvars.addTabPage(document.getElementById('tabGeneral'));</script>

                <div class="container container-body">
                    @include('manager::form.row', [
                        'for' => 'name',
                        'label' => ManagerTheme::getLexicon('tmplvars_name'),
                        'element' => '<div class="form-control-name clearfix">' .
                            ManagerTheme::view('form.inputElement', [
                                'name' => 'name',
                                'value' => $data->name,
                                'class' => 'form-control-lg',
                                'attributes' => 'onchange="documentDirty=true;" maxlength="50"'
                            ]) .
                            ($modx->hasPermission('save_role')
                            ? '<label class="custom-control" data-tooltip="' . ManagerTheme::getLexicon('lock_tmplvars') . "\n" . ManagerTheme::getLexicon('lock_tmplvars_msg') .'">' .
                             ManagerTheme::view('form.inputElement', [
                                'type' => 'checkbox',
                                'name' => 'locked',
                                'checked' => ($data->locked == 1)
                             ]) .
                             '<i class="'. $_style['icon_lock'] .'"></i>
                             </label>
                             <small class="form-text text-danger hide" id="savingMessage"></small>
                             <script>if (!document.getElementsByName(\'name\')[0].value) document.getElementsByName(\'name\')[0].focus();</script>'
                            : '') .
                            '</div>'
                    ])

                    @include('manager::form.input', [
                        'name' => 'caption',
                        'id' => 'caption',
                        'label' => ManagerTheme::getLexicon('tmplvars_caption'),
                        'value' => $data->caption,
                        'attributes' => 'onchange="documentDirty=true;" maxlength="80"'
                    ])

                    @include('manager::form.input', [
                        'name' => 'description',
                        'id' => 'description',
                        'label' => ManagerTheme::getLexicon('tmplvars_description'),
                        'value' => $data->description,
                        'attributes' => 'onchange="documentDirty=true;" maxlength="255"'
                    ])

                    @include('manager::form.select', [
                        'name' => 'categoryid',
                        'id' => 'categoryid',
                        'label' => ManagerTheme::getLexicon('existing_category'),
                        'value' => $data->category,
                        'first' => [
                            'text' => ''
                        ],
                        'options' => $categories->pluck('category', 'id'),
                        'attributes' => 'onchange="documentDirty=true;"'
                    ])

                    @include('manager::form.input', [
                        'name' => 'newcategory',
                        'id' => 'newcategory',
                        'label' => ManagerTheme::getLexicon('new_category'),
                        'value' => (isset($data->newcategory) ? $data->newcategory : ''),
                        'attributes' => 'onchange="documentDirty=true;" maxlength="45"'
                    ])

                    @include('manager::form.select', [
                        'name' => 'type',
                        'id' => 'type',
                        'label' => ManagerTheme::getLexicon('tmplvars_type'),
                        'value' => $data->type,
                        'options' => $types,
                        'attributes' => 'onchange="documentDirty=true;"'
                    ])

                    @include('manager::form.textarea', [
                        'name' => 'elements',
                        'id' => 'elements',
                        'label' => ManagerTheme::getLexicon('tmplvars_elements'),
                        'small' => ManagerTheme::getLexicon('tmplvars_binding_msg'),
                        'value' => $data->elements,
                        'attributes' => 'onchange="documentDirty=true;"'
                    ])

                    @include('manager::form.textarea', [
                        'name' => 'default_text',
                        'id' => 'default_text',
                        'label' => ManagerTheme::getLexicon('tmplvars_default'),
                        'small' => ManagerTheme::getLexicon('tmplvars_binding_msg'),
                        'value' => $data->default_text,
                        'attributes' => 'onchange="documentDirty=true;"'
                    ])

                    @include('manager::form.select', [
                        'name' => 'display',
                        'id' => 'display',
                        'label' => ManagerTheme::getLexicon('tmplvars_widget'),
                        'value' => $data->display,
                        'first' => [
                            'text' => ''
                        ],
                        'options' => $display,
                        'attributes' => 'onchange="documentDirty=true;showParameters(this);"'
                    ])

                    <div class="row form-row" id="displayparamrow">
                        <label class="col-md-3 col-lg-2">{{ ManagerTheme::getLexicon('tmplvars_widget_prop') }}<br />
                            <a href="javascript:;" onclick="resetParameters(); return false">
                                <i class="<?= $_style['icon_refresh'] ?>"
                                    data-tooltip="{{ ManagerTheme::getLexicon('tmplvars_reset_params') }}"></i>
                            </a>
                        </label>
                        <div id="displayparams" class="col-md-9 col-lg-10"></div>
                    </div>

                @include('manager::form.input', [
                    'name' => 'rank',
                    'id' => 'rank',
                    'label' => ManagerTheme::getLexicon('tmplvars_rank'),
                    'value' => (isset($data->rank) ? $data->rank : 0),
                    'attributes' => 'onchange="documentDirty=true;" maxlength="4" size="1"'
                ])

                <!-- Access Permissions -->

                </div>
            </div>

            @if($data->properties)
                <!-- Config -->
                <div class="tab-page" id="tabConfig">
                    <textarea id="properties" name="properties" style="display:none;">{!! json_encode($data->properties) !!}</textarea>
                    <h2 class="tab">{{ ManagerTheme::getLexicon('settings_config') }}</h2>
                    <script>tpTmplvars.addTabPage(document.getElementById('tabConfig'));</script>

                    <div class="container container-body">
                        <div class="form-group">
                            <a href="javascript:;" class="btn btn-primary" onclick="props.setDefaults(this);return false;">{{ ManagerTheme::getLexicon('set_default_all') }}</a>
                        </div>
                        <div id="displaypropsrow">
                            <div id="displayprops"></div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="tab-page" id="tabTemplates">
                <h2 class="tab">{{ ManagerTheme::getLexicon('manage_templates') }}</h2>
                <script>tpTmplvars.addTabPage(document.getElementById('tabTemplates'));</script>

                <div class="container container-body">
                    <p>{{ ManagerTheme::getLexicon('tmplvar_tmpl_access_msg') }}</p>
                    <div class="form-group">
                        <a class="btn btn-secondary btn-sm" href="javascript:;"
                            onclick="check_all('template');return false;">{{ ManagerTheme::getLexicon('check_all') }}</a>
                        <a class="btn btn-secondary btn-sm" href="javascript:;"
                            onclick="check_none('template');return false;">{{ ManagerTheme::getLexicon('check_none') }}</a>
                        <a class="btn btn-secondary btn-sm" href="javascript:;"
                            onclick="check_toggle('template'); return false;">{{ ManagerTheme::getLexicon('check_toggle') }}</a>
                    </div>

                    @if(isset($tplOutCategory) && $tplOutCategory->count() > 0)
                        @component('manager::partials.panelCollapse', ['name' => 'tv_in_template', 'id' => 0, 'title' => ManagerTheme::getLexicon('no_category')])
                            <ul>
                                <?php /** @var EvolutionCMS\Models\SiteTemplate $item */ ?>
                                @foreach($tplOutCategory as $item)
                                    @include('manager::page.tmplvar.template', ['item' => $item, 'selected' => $controller->isSelectedTemplate($item)])
                                @endforeach
                            </ul>
                        @endcomponent
                    @endif

                    @if(isset($categoriesWithTpl))
                        @foreach($categoriesWithTpl as $cat)
                            @component('manager::partials.panelCollapse', ['name' => 'tv_in_template', 'id' => $cat->id, 'title' => $cat->name])
                                <ul>
                                    @foreach($cat->templates as $item)
                                        @include('manager::page.tmplvar.template', ['item' => $item, 'selected' => $controller->isSelectedTemplate($item)])
                                    @endforeach
                                </ul>
                            @endcomponent
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="tab-page" id="tabRoles">
                <h2 class="tab">{{ ManagerTheme::getLexicon('role_management_title') }}</h2>
                <script>tpTmplvars.addTabPage(document.getElementById('tabRoles'));</script>

                <div class="container container-body">
                    <p>{{ ManagerTheme::getLexicon('tmplvar_roles_access_msg') }}</p>
                    <div class="form-group">
                        <a class="btn btn-secondary btn-sm" href="javascript:;"
                            onclick="check_all('role');return false;">{{ ManagerTheme::getLexicon('check_all') }}</a>
                        <a class="btn btn-secondary btn-sm" href="javascript:;"
                            onclick="check_none('role');return false;">{{ ManagerTheme::getLexicon('check_none') }}</a>
                        <a class="btn btn-secondary btn-sm" href="javascript:;"
                            onclick="check_toggle('role'); return false;">{{ ManagerTheme::getLexicon('check_toggle') }}</a>
                    </div>

                    @if(isset($roles) && $roles->count() > 0)
                        @component('manager::partials.panelCollapse', ['name' => 'tv_in_roles', 'id' => 0, 'title' => ManagerTheme::getLexicon('role_management_title')])
                            <ul>
                                <?php /** @var EvolutionCMS\Models\SiteTemplate $item */ ?>
                                @foreach($roles as $item)
                                    @include('manager::page.tmplvar.role', ['item' => $item, 'selected' => $controller->isSelectedRole($item)])
                                @endforeach
                            </ul>
                        @endcomponent
                    @endif
                </div>
            </div>

            @if(get_by_key($modx->config, 'use_udperms') == 1 && $modx->hasPermission('access_permissions'))
                <div class="tab-page" id="tabAccess">
                    <h2 class="tab">{{ ManagerTheme::getLexicon('access_permissions') }}</h2>
                    <script>tpTmplvars.addTabPage(document.getElementById('tabAccess'));</script>

                    <div class="container container-body">

                        <script>
                          function makePublic(b)
                          {
                            var notPublic = false;
                            var f = document.forms['mutate'];
                            var chkpub = f['chkalldocs'];
                            var chks = f['docgroups[]'];
                            if (!chks && chkpub) {
                              chkpub.checked = true;
                              return false;
                            }
                            else if (!b && chkpub) {
                              if (!chks.length) {
                                notPublic = chks.checked;
                              } else {
                                for (var i = 0; i < chks.length; i++) {
                                  if (chks[i].checked) notPublic = true;
                                }
                              }
                              chkpub.checked = !notPublic;
                            }
                            else {
                              if (!chks.length) {
                                chks.checked = (b) ? false : chks.checked;
                              } else {
                                for (var i = 0; i < chks.length; i++) {
                                  if (b) chks[i].checked = false;
                                }
                              }
                              chkpub.checked = true;
                            }
                          }
                        </script>

                        <p>{{ ManagerTheme::getLexicon('tmplvar_access_msg') }}</p>

                        <?php

                        if (empty($groupsArray) && isset($_POST['docgroups']) && is_array($_POST['docgroups']) && empty($_POST['id'])) {
                            $groupsArray = $_POST['docgroups'];
                        }
                        $documentGroupNames = \EvolutionCMS\Models\DocumentgroupName::all()->toArray();
                        $chks = '';
                        foreach ($documentGroupNames as $row) {
                            $checked = in_array($row['id'], $groupsArray);
                            if ($modx->hasPermission('access_permissions')) {
                                if ($checked) {
                                    $notPublic = true;
                                }
                                $chks .= "<li><label><input type='checkbox' name='docgroups[]' value='" . $row['id'] . "' " . ($checked ? "checked='checked'" : '') . " onclick=\"makePublic(false)\" /> " . $row['name'] . "</label></li>";
                            } else {
                                if ($checked) {
                                    echo "<input type='hidden' name='docgroups[]'  value='" . $row['id'] . "' />";
                                }
                            }
                        }

                        if ($modx->hasPermission('access_permissions')) {
                            $chks = "<li><label><input type='checkbox' name='chkalldocs' " . (empty($notPublic) ? "checked='checked'" : '') . " onclick=\"makePublic(true)\" /> <span class='warning'>" . ManagerTheme::getLexicon('all_doc_groups') . "</span></label></li>" . $chks;
                        }

                        echo '<ul>' . $chks . '</ul>';

                        ?>
                    </div>
                </div>
            @endif
            <input type="submit" name="save" style="display:none">

            {!! get_by_key($events, 'OnTVFormRender') !!}
        </div>
    </form>
    <script>
        setTimeout(function () {
            showParameters();
            props.showParameters();
        }, 10);
    </script>
@endsection
