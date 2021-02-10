@extends('manager::template.page')
@section('content')
    <h1>
        <i class="{{ $_style['icon_info_circle'] }}"></i>{{ ManagerTheme::getLexicon('view_sysinfo') }}
    </h1>

    <!-- server -->
    <div class="tab-page">
        <div class="container container-body">
            <p><b>Server</b></p>
            <div class="row">
                <div class="table-responsive">
                    <table class="table data table-sm nowrap">
                        <tbody>
                        @foreach ($serverArr as $key => $value)
                            <tr>
                                <td width="1%">{{ empty($value['is_lexicon']) ? $key : ManagerTheme::getLexicon($key) }}</td>
                                <td>&nbsp;</td>
                                <td>
                                    @if (isset($value['render']))
                                        @include($value['render'], ['data' => $value['data']])
                                    @else
                                        <b>{{ $value['data'] }}</b>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <p>&nbsp;</p>

    <!-- database -->
    <?php
    $totaloverhead = 0;
    $total = 0;
    ?>
    <div class="tab-page">
        <div class="container container-body">
            <p><b>{{ ManagerTheme::getLexicon('database_tables') }}</b></p>
            <div class="row">
                <div class="table-responsive">
                    <table class="table data nowrap">
                        <thead>
                        <tr>
                            <td>{{ ManagerTheme::getLexicon('database_table_tablename') }}</td>
                            <td width="1%"></td>
                            <td class="text-xs-center">{{ ManagerTheme::getLexicon('database_table_records') }}</td>
                            <td class="text-xs-center">{{ ManagerTheme::getLexicon('database_table_datasize') }}</td>
                            <td class="text-xs-center">{{ ManagerTheme::getLexicon('database_table_overhead') }}</td>
                            <td class="text-xs-center">{{ ManagerTheme::getLexicon('database_table_effectivesize') }}</td>
                            <td class="text-xs-center">{{ ManagerTheme::getLexicon('database_table_indexsize') }}</td>
                            <td class="text-xs-center">{{ ManagerTheme::getLexicon('database_table_totalsize') }}</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 0; ?>

                        @foreach ($tables as $table)
                            <?php
                            if (isset($table['Name'])) {
                                $tableName = $table['Name'];
                            } else {
                                $tableName = $table['name'];
                            }
                            ?>
                            <tr>
                                @if(isset($tableName))
                                    <td class="text-primary"><b>{{ $tableName }}</b></td>
                                @else
                                    <td class="text-primary"><b>{{ $tableName }}</b></td>
                                @endif
                                <td class="text-xs-center">
                                    @if(isset($table['Comment']) && !empty($table['Comment']))
                                        <i class="{{ $_style['icon_question_circle'] }}"
                                           data-tooltip="{{ $table['Comment'] ?? 0}}"></i>
                                    @endif
                                </td>
                                <td class="text-xs-right">{{ $table['Rows'] ?? 0 }}</td>

                                @if (evolutionCMS()->hasPermission('settings') && in_array($tableName, $truncateable))
                                    <td class="text-xs-right">
                                        <a class="text-danger" href="index.php?a=54&mode=$action&u={{ $tableName }}"
                                           title="{{ ManagerTheme::getLexicon('truncate_table') }}">
                                            {{ nicesize(($table['Data_length']?? 0) + ($table['Data_free']?? 0)) }}
                                        </a>
                                    </td>
                                @else
                                    <td class="text-xs-right">
                                        {{ nicesize(($table['Data_length'] ?? 0) + ($table['Data_free']?? 0)) }}
                                    </td>
                                @endif

                                @if (evolutionCMS()->hasPermission('settings'))
                                    <td class="text-xs-right">
                                        @if(isset($table['Data_free']) && $table['Data_free'] > 0)
                                            <a class="text-danger"
                                               href="index.php?a=54&mode=$action&t={{ $tableName }}"
                                               title="{{ ManagerTheme::getLexicon('optimize_table') }}">
                                                <span> {{ nicesize($table['Data_free'] ?? 0) }}</span>
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                @else
                                    <td class="text-xs-right">
                                        @if(isset($table['Data_free']) && $table['Data_free'] > 0)
                                            {{ nicesize($table['Data_free']) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                @endif

                                <td class="text-xs-right">
                                    {{ nicesize(($table['Data_length'] ?? 0) - ($table['Data_free'] ?? 0)) }}
                                </td>
                                <td class="text-xs-right">
                                    {{ nicesize($table['Index_length'] ?? 0) }}
                                </td>
                                <td class="text-xs-right">
                                    {{ nicesize(($table['Index_length'] ?? 0) + ($table['Data_length'] ?? 0) + ($table['Data_free'] ?? 0)) }}
                                </td>
                            </tr>
                            <?php
                            $total = $total + ($table['Index_length'] ?? 0) + ($table['Data_length'] ?? 0);
                            $totaloverhead = $totaloverhead + ($table['Data_free'] ?? 0);
                            ?>
                        @endforeach
                        <tr class="unstyled">
                            <td class="text-xs-right">{{ ManagerTheme::getLexicon('database_table_totals') }}</td>
                            <td colspan="3">&nbsp;</td>
                            <td class="text-xs-right">
                                @if($totaloverhead > 0)
                                    <b class="text-danger">{{ nicesize($totaloverhead) }}</b><br/>
                                    ({{ number_format($totaloverhead) }} B)
                                @else
                                    -
                                @endif
                            </td>
                            <td colspan="2">&nbsp;</td>
                            <td class="text-xs-right"><b>{{ nicesize($total) }}</b><br/>({{ number_format($total) }} B)
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($totaloverhead > 0)
                <br>
                <p class="alert alert-danger">{{ ManagerTheme::getLexicon('database_overhead') }}</p>
            @endif
        </div>
    </div>
    @push('scripts.bot')
    @endpush
@endsection
