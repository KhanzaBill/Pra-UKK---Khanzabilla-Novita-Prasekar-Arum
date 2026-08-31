@extends('layouts.admin')

@section('title', 'Cetak QR Code - Yummy Chicken')

@section('styles')
<style>
    .qr-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .qr-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        border: 1.5px solid var(--border);
        box-shadow: var(--shadow-sm);
        display: flex;
        align-items: center;
        gap: 24px;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .qr-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .qr-image {
        width: 140px;
        height: 140px;
        flex-shrink: 0;
        display: block;
        border: 2px solid #212121;
        padding: 6px;
        border-radius: 12px;
        background: white;
    }

    .qr-info {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 10px;
        align-items: flex-start;
        min-width: 0;
    }

    .qr-card-header {
        background: linear-gradient(135deg, #B71C1C 0%, #D32F2F 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 10px;
        font-family: 'Playfair Display', serif;
        font-size: 1.05rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .qr-url {
        font-size: 0.8rem;
        color: var(--text-sub);
        word-break: break-all;
        background: #F4F6F9;
        padding: 8px 12px;
        border-radius: 8px;
        border: 1px solid var(--border);
        font-weight: 500;
        width: 100%;
    }

    @media (max-width: 576px) {
        .qr-card {
            flex-direction: column;
            text-align: center;
            align-items: center;
        }
        .qr-info {
            align-items: center;
            width: 100%;
        }
    }

    @media print {
        .sidebar, .topbar, .btn-print-hide, .ip-config-card { display: none !important; }
        .main-wrapper { margin: 0 !important; padding: 0 !important; }
        .container { padding: 0 !important; }
        .qr-list { gap: 15px; }
        .qr-card { border: 1px solid #CCC; page-break-inside: avoid; }
        .card { box-shadow: none; border: none; padding: 0; }
    }
</style>
@endsection

@section('content')
<div class="card btn-print-hide ip-config-card" style="margin-bottom: 24px;">
    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
        <input type="text" id="baseUrlInput" class="form-control" style="max-width: 340px; font-weight: 600;" value="{{ request()->getSchemeAndHttpHost() }}" placeholder="http://192.168.1.72:8000">
        <button type="button" class="btn btn-accent" onclick="updateQrUrls()">
            <i class="fa-solid fa-rotate"></i> Update QR Code
        </button>       
    </div>
</div>

<div class="card">
    <div class="card-header" style="margin-bottom: 24px;">
        <div>
            <h2 class="card-title">Generator QR Code</h2>
        </div>
    </div>

    <div class="qr-list">
        <!-- Take Away Card -->
        <div class="qr-card">
            <img class="qr-image qr-img" data-meja="" alt="QR Take Away">
            <div class="qr-info">
                <div class="qr-card-header" style="background: linear-gradient(135deg, #1565C0, #1E88E5);">
                   TAKE AWAY 
                </div>
                <div class="qr-url qr-url-text" data-meja="">-</div>
                <a href="#" target="_blank" class="btn btn-sm btn-secondary btn-print-hide qr-link" data-meja="">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i> Buka Link Take Away
                </a>
            </div>
        </div>

        <!-- Meja 01 s/d Meja 10 -->
        @foreach($mejas as $m)
            <div class="qr-card">
                <img class="qr-image qr-img" data-meja="{{ $m->id_meja }}" alt="QR {{ $m->nomor_meja }}">
                <div class="qr-info">
                    <div class="qr-card-header">
                     {{ $m->nomor_meja }}
                    </div>
                    <div class="qr-url qr-url-text" data-meja="{{ $m->id_meja }}">-</div>
                    <a href="#" target="_blank" class="btn btn-sm btn-secondary btn-print-hide qr-link" data-meja="{{ $m->id_meja }}">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> Buka Link {{ $m->nomor_meja }}
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

@section('scripts')
<script>
    function updateQrUrls() {
        var baseUrl = document.getElementById('baseUrlInput').value.trim();
        if (!baseUrl) baseUrl = window.location.origin;
        if (baseUrl.indexOf('http://') !== 0 && baseUrl.indexOf('https://') !== 0) {
            baseUrl = 'http://' + baseUrl;
        }
        // Remove trailing slash
        if (baseUrl.charAt(baseUrl.length - 1) === '/') {
            baseUrl = baseUrl.substring(0, baseUrl.length - 1);
        }

        // Update all QR images, URL texts, and links using data attributes
        var images = document.querySelectorAll('.qr-img');
        var urlTexts = document.querySelectorAll('.qr-url-text');
        var links = document.querySelectorAll('.qr-link');

        for (var i = 0; i < images.length; i++) {
            var meja = images[i].getAttribute('data-meja');
            var targetUrl;

            if (meja === '' || meja === null) {
                // Take Away - no meja param
                targetUrl = baseUrl;
            } else {
                targetUrl = baseUrl + '/?meja=' + meja;
            }

            // Set QR image
            images[i].src = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + encodeURIComponent(targetUrl);
        }

        for (var j = 0; j < urlTexts.length; j++) {
            var meja2 = urlTexts[j].getAttribute('data-meja');
            var targetUrl2;

            if (meja2 === '' || meja2 === null) {
                targetUrl2 = baseUrl;
            } else {
                targetUrl2 = baseUrl + '/?meja=' + meja2;
            }

            urlTexts[j].innerText = targetUrl2;
        }

        for (var k = 0; k < links.length; k++) {
            var meja3 = links[k].getAttribute('data-meja');
            var targetUrl3;

            if (meja3 === '' || meja3 === null) {
                targetUrl3 = baseUrl;
            } else {
                targetUrl3 = baseUrl + '/?meja=' + meja3;
            }

            links[k].href = targetUrl3;
        }
    }

    // Run on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateQrUrls();
    });
</script>
@endsection
