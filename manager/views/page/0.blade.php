<?php
/********************************************************************/
/* default action: show not implemented message                     */
/********************************************************************/
// say that what was requested doesn't do anything yet
?>
@extends('manager::template.page')
@section('content')
    <div class='sectionHeader'>{{ ManagerTheme::getLexicon('functionnotimpl') }}</div>
    <div class='sectionBody'>
        <p>{{ ManagerTheme::getLexicon('functionnotimpl_message')  }}</p>
    </div>
@endsection
