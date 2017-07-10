(function($, window) {
    var defaults = {
        singleSelect: false,
        checkOnSelect:false,
        pagination: true,
        pageSize:50,
        pageList:[50,100,150,200],
        fitColumns: true,
        striped: true,
        scrollbarSize: 0,
        onBeforeLoad: function() {
            $(this).edatagrid('clearChecked');
            $('.btn-extra',$(this).datagrid('getPanel')).parent().parent().hide();
        },
        onLoadSuccess: function () {
            $(this).edatagrid('enableDnd');
        },
        onLoadError: function() {
            $.messager.alert(_euigLang['error'],_euigLang['server_error']+' unknown','error');
        },
        onDestroy: function () {
            $(this).edatagrid('reload');
        },
        onBeforeEdit: function (index, row) {
            row.editing = true;
            $(this).datagrid('refreshRow', index);
        },
        onAfterEdit: function (index, row) {
            row.editing = false;
            $(this).datagrid('refreshRow', index);
        },
        onCancelEdit: function (index, row) {
            row.editing = false;
            $(this).datagrid('refreshRow', index);
        },
        onClickRow: function (index, row) {
            row.editing = false;
            $(this).edatagrid('cancelEdit', index);
        },
        onSelect: function (index) {
            $(this).edatagrid('unselectRow', index);
        },
        onCheck: function(index) {
            $(this).edatagrid('unselectRow', index);
            $('.btn-extra',$(this).datagrid('getPanel')).parent().parent().show();
        },
        onUncheck: function() {
            var rows = $(this).edatagrid('getChecked');
            if (!rows.length) $('.btn-extra',$(this).datagrid('getPanel')).parent().parent().hide();
        },
        onCheckAll: function() {
            $(this).edatagrid('unselectAll');
            $('.btn-extra',$(this).datagrid('getPanel')).parent().parent().show();
        },
        onUncheckAll: function() {
            $('.btn-extra',$(this).datagrid('getPanel')).parent().parent().hide();
        }
    };
    function EUIGrid (options, tableId) {
        this._options = $.extend({},defaults,options);
        this._options.destroyMsg = {
            confirm: {
                title: _euigLang['delete'],
                    msg: _euigLang['are_you_sure_to_delete']
            }
        };
        this._options.loadMsg =_euigLang['please_wait'];
        this._tableId = tableId;
        this._orderBy = this._options.indexField;
        this._orderDir = 'desc';
        return this.init();
    }
    EUIGrid.prototype = {
        init: function() {
            this._options.onSortColumn = this.onSortColumn.bind(this);
            this._options.onBeforeDrag = this.onBeforeDrag.bind(this);
            this._options.onBeforeDrop = this.onBeforeDrop.bind(this);
            this._options.onDrop = this.onDrop.bind(this);
            return $(this._tableId).edatagrid(this._options);
        },
        onSortColumn: function (sort, order) {
            this._orderBy = sort;
            this._orderDir = order;
        },
        onBeforeDrag: function (row) {
            var grid = $(this._tableId);
            if (this._orderBy == this._options.indexField && !row.editing) {
                $('body').css('overflow-x', 'hidden');
                $('.datagrid-body',grid).css('overflow-y', 'hidden');
            } else {
                return false;
            }
        },
        onBeforeDrop: function (targetRow, sourceRow, point) {
            var grid = $(this._tableId);
            $('body').css('overflow-x', 'auto');
            $('.datagrid-body',grid).css('overflow-y', 'auto');
            this.targetRow = targetRow;
            this.targetRow.index = tgt = grid.edatagrid('getRowIndex', targetRow);
            this.sourceRow = sourceRow;
            this.sourceRow.index = src = grid.edatagrid('getRowIndex', sourceRow);
            this.point = point;
            dif = tgt - src;
            if ((point == 'bottom' && dif == -1) || (point == 'top' && dif == 1)) return false;
        },
        onDrop: function (targetRow, sourceRow, point) {
            var grid = $(this._tableId);
            var idField = this._options.idField;
            var indexField = this._options.indexField;
            var orderDir = this._orderDir;
            src = this.sourceRow.index;
            tgt = this.targetRow.index;
            var data = {
                'target':{},
                'source':{},
                'point': point,
                'sf_rid': this._options.rid,
                'orderDir': this._orderDir
            };
            data['target'][idField] = targetRow[idField];
            data['target'][indexField] = targetRow[indexField];
            data['source'][idField] = sourceRow[idField];
            data['source'][indexField] = sourceRow[indexField];

            $.ajax({
                url: this._options.url+'?mode=reorder',
                type: 'post',
                dataType: 'json',
                data: data
            }).done(function (response) {
                if (!response.success) {
                    $.messager.alert(_sfLang['error'], _sfLang['save_fail']);
                    grid.edatagrid('reload');
                } else {
                    rows = grid.edatagrid('getRows');
                    if (tgt < src) {
                        rows[tgt][indexName] = targetRow[indexName];
                        for (var i = tgt; i <= src; i++) {
                            rows[i][indexName] = rows[i - 1] != undefined ? rows[i - 1][indexName] - (orderDir == 'desc' ? 1 : -1) : rows[i][indexName];
                            grid.edatagrid('refreshRow', i);
                        }
                    } else {
                        rows[tgt][indexName] = targetRow[indexName];
                        for (var i = tgt; i >= src; i--) {
                            rows[i][indexName] = rows[i + 1] != undefined ? parseInt(rows[i + 1][indexName]) + (orderDir == 'desc' ? 1 : -1) : rows[i][indexName];
                            grid.edatagrid('refreshRow', i);
                        }
                    }
                }

            }).fail(function(xhr) {
                var message = xhr.status == 200 ? _euigLang['parse_error'] : _euigLang['server_error'] + xhr.status + ' ' + xhr.statusText;
                $.messager.alert(_euigLang['error'], message, 'error');
            });
        }
    };
    window.EUIGrid = EUIGrid;
})(jQuery, window);
