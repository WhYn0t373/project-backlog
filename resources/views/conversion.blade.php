@extends('layouts.app')

@section('title', 'File Conversion')

@section('content')
<div id="drop-area" class="drop-area">
    <p>Drag & drop a file here or click to select</p>
    <input type="file" id="file-input" style="display:none;">
</div>

<div id="progress-bar" class="progress-bar hidden">
    <span id="progress-percent"></span>
</div>

<div id="error-msg" class="error-msg hidden"></div>

<a id="download-link" href="#" class="download-link hidden" download>Download converted file</a>
@endsection