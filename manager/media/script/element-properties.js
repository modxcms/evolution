function ElementProperties(options) {
    this.name = options.name;
    this.currentParams = {};
    this.f = '';
    this.first = true;
    this.lang = options.lang;
    this.table = options.table;
    this.tr = options.tr;
    this.td = options.td;
    this.icon_refresh = options.icon_refresh;
}

ElementProperties.prototype = {
    showParameters: function (ctrl) {
        var c, p, df, cp, ar, label, value, key, dt, defaultVal, tr;

        this.currentParams = {}; // reset;

        if (ctrl && ctrl.form) {
            this.f = ctrl.form;
        } else {
            this.f = document.forms['mutate'];
            if (!this.f) {
                return;
            }
        }

        tr = document.getElementById(this.tr);

        // check if codemirror is used
        var props = typeof myCodeMirrors != 'undefined' && typeof myCodeMirrors['properties'] != 'undefined' ? myCodeMirrors['properties'].getValue() : this.f.properties.value,
            t, td, dp, desc;

        // convert old schemed setup parameters
        if (!this.IsJsonString(props)) {
            dp = props ? props.match(/([^&=]+)=(.*?)(?=&[^&=]+=|$)/g) : ''; // match &paramname=
            if (!dp) {
                tr.style.display = 'none';
            } else {
                for (p = 0; p < dp.length; p++) {
                    dp[p] = (dp[p] + '').replace(/^\s|\s$/, ''); // trim
                    ar = dp[p].match(/(?:[^\=]|==)+/g); // split by =, not by ==
                    key = ar[0];        // param
                    ar = (ar[1] + '').split(';');
                    label = ar[0];	// label
                    dt = ar[1];	    // data type
                    value = this.decode((ar[2]) ? ar[2] : '');

                    // convert values to new json-format
                    if (key && (dt === 'menu' || dt === 'list' || dt === 'list-multi' || dt === 'checkbox' || dt === 'radio')) {
                        defaultVal = this.decode((ar[4]) ? ar[4] : ar[3]);
                        desc = this.decode((ar[5]) ? ar[5] : '');
                        this.currentParams[key] = [];
                        this.currentParams[key][0] = {
                            'label': label,
                            'type': dt,
                            'value': ar[3],
                            'options': value,
                            'default': defaultVal,
                            'desc': desc
                        };
                    } else if (key) {
                        defaultVal = this.decode((ar[3]) ? ar[3] : ar[2]);
                        desc = this.decode((ar[4]) ? ar[4] : '');
                        this.currentParams[key] = [];
                        this.currentParams[key][0] = {
                            'label': label,
                            'type': dt,
                            'value': value,
                            'default': defaultVal,
                            'desc': desc
                        };
                    }
                }
            }
        } else {
            this.currentParams = JSON.parse(props);
        }

        t = '<table width="100%" class="' + this.table + ' grid"><thead><tr><td>' + this.lang.parameter + '</td><td>' + this.lang.value + '</td><td style="text-align:right;white-space:nowrap">' + this.lang.set_default + ' </td></tr></thead>';

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

                key = key.replace(/\"/g, '&quot;');
                value = value.replace(/\"/g, '&quot;');

                switch (type) {
                    case 'int':
                        c = '<input type="text" name="prop_' + key + '" value="' + value + '" onchange="' + this.name + '.setParameter(\'' + key + '\',\'' + type + '\',this)" />';
                        break;
                    case 'menu':
                        c = '<select name="prop_' + key + '" onchange="' + this.name + '.setParameter(\'' + key + '\',\'' + type + '\',this)">';
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
                        c = '<select name="prop_' + key + '" onchange="' + this.name + '.setParameter(\'' + key + '\',\'' + type + '\',this)">';
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
                        c = '<select name="prop_' + key + '" size="' + ls.length + '" multiple="multiple" onchange="' + this.name + '.setParameter(\'' + key + '\',\'' + type + '\',this)">';
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
                            c += '<label><input type="checkbox" name="prop_' + key + '[]" value="' + ls[i] + '"' + ((this.contains(lv, ls[i]) === true) ? ' checked="checked"' : '') + ' onchange="' + this.name + '.setParameter(\'' + key + '\',\'' + type + '\',this)" /> ' + ll[i] + '</label>&nbsp;';
                        }
                        break;
                    case 'radio':
                        c = '';
                        for (i = 0; i < ls.length; i++) {
                            c += '<label><input type="radio" name="prop_' + key + '" value="' + ls[i] + '"' + ((ls[i] === value) ? ' checked="checked"' : '') + ' onchange="' + this.name + '.setParameter(\'' + key + '\',\'' + type + '\',this)" /> ' + ll[i] + '</label>&nbsp;';
                        }
                        break;
                    case 'textarea':
                        c = '<textarea name="prop_' + key + '" rows="4" onchange="' + this.name + '.setParameter(\'' + key + '\',\'' + type + '\',this)">' + value + '</textarea>';
                        break;
                    default:  // string
                        c = '<input type="text" name="prop_' + key + '" value="' + value + '" onchange="' + this.name + '.setParameter(\'' + key + '\',\'' + type + '\',this)" />';
                        break;
                }
                info = '';
                info += desc ? '<br/><small>' + desc + '</small>' : '';
                sd = defaultVal != undefined ? '<a title="' + this.lang.set_default + '" href="javascript:;" class="btn btn-primary" onclick="' + this.name + '.setDefaultParam(\'' + key + '\',1);return false;"><i class="' + this.icon_refresh + '"></i></a>' : '';

                t += '<tr><td class="labelCell" width="20%"><span class="paramLabel">' + label + '</span><span class="paramDesc">' + info + '</span></td><td class="inputCell relative" width="74%">' + c + '</td><td style="text-align: center">' + sd + '</td></tr>';
            }

            t += '</table>';
        } catch (e) {
            t = e + '\n\n' + props;
        }

        td = document.getElementById(this.td);
        td.innerHTML = t;
        tr.style.display = '';
        if (JSON.stringify(this.currentParams) === '{}') return;

        this.implodeParameters();
    },
    contains: function (a, obj) {
        var i = a.length;
        while (i--) {
            if (a[i] === obj) {
                return true;
            }
        }
        return false;
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
    // implode parameters
    implodeParameters: function () {
        var stringified = JSON.stringify(this.currentParams, null, 2);
        if (typeof myCodeMirrors != 'undefined') {
            myCodeMirrors['properties'].setValue(stringified);
        } else {
            this.f.properties.value = stringified;
        }
        if (this.first) {
            documentDirty = false;
            this.first = false;
        }
    },
    encode: function (s) {
        s = s + '';
        s = s.replace(/\=/g, '%3D'); // =
        s = s.replace(/\&/g, '%26'); // &
        return s;
    },
    decode: function (s) {
        s = s + '';
        s = s.replace(/\%3D/g, '='); // =
        s = s.replace(/\%26/g, '&'); // &
        return s;
    },
    /**
     * @return {boolean}
     */
    IsJsonString: function (str) {
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }
        return true;
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
        var _this = this;
        Object.keys(this.currentParams).forEach(function (key) {
            show = key === last ? 1 : 0;
            _this.setDefaultParam(key, show);
        });
    }
}