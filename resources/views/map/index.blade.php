@extends('master')

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('select2/css/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
  #map { height: 520px; width: 100%; }
  .select2-container--bootstrap4 .select2-selection--multiple .select2-search__field { color:#495057 !important; }
</style>
@endsection

@section('content')
<div class="container mt-3">
  <div class="card">
    <div class="card-header">Multi-destination (Dijkstra) + Jalan Sebenarnya (OSRM)</div>
    <div class="card-body">
      <form id="routeForm" class="row g-3">
        @csrf
        <div class="col-md-4">
          <label class="form-label">Start</label>
          <select id="start" class="form-control select2bs4" style="width:100%"></select>
        </div>
        <div class="col-md-8">
          <label class="form-label">Tujuan (bisa lebih dari satu)</label>
          <select id="locations" class="form-control select2bs4" multiple="multiple" style="width:100%"></select>
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-primary">Hitung Rute</button>
        </div>
      </form>

      <div class="mt-3" id="info">Pilih Start & Tujuan lalu klik Hitung Rute.</div>
      <div id="map" class="mt-3"></div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('select2/js/select2.full.min.js') }}"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
$(function(){
  function buildSelect2($el){
    $el.select2({
      theme: 'bootstrap4',
      placeholder: 'Ketik untuk mencari lokasi…',
      ajax: {
        url: '{{ route("locations.search") }}',
        dataType: 'json',
        delay: 250,
        data: params => ({ q: params.term }),
        processResults: data => ({ results: data }),
        cache: true
      },
      minimumInputLength: 1
    });
  }
  buildSelect2($('#start'));
  buildSelect2($('#locations'));

  const map = L.map('map').setView([-2.5489, 118.0149], 5); // center Indonesia
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors', maxZoom: 19
  }).addTo(map);

  let layerRoute = L.polyline([], { weight: 5 }).addTo(map);
  let layerMarkers = L.layerGroup().addTo(map);

  $('#routeForm').on('submit', function(e){
    e.preventDefault();

    const start = $('#start').val();
    const locations = $('#locations').val() || [];

    if (!start || locations.length === 0) {
      alert('Silakan pilih Start dan minimal satu Tujuan.');
      return;
    }

    $('#info').text('Menghitung rute…');

    fetch('{{ route("routes.shortestMulti") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      body: JSON.stringify({ start, locations })
    })
    .then(r => r.json())
    .then(res => {
      layerRoute.setLatLngs([]);
      layerMarkers.clearLayers();

      if (res.error) {
        $('#info').text(res.error);
        return;
      }

      // Pasang marker & popup berurut
      const bounds = [];
      res.path.forEach((p, idx) => {
        const m = L.marker([p.lat, p.lng]).addTo(layerMarkers)
          .bindPopup(`<b>${idx+1}. ${p.name}</b>`);
        bounds.push([p.lat, p.lng]);
      });

      // Gambar polyline OSRM (kembalian [lng,lat] → swap ke [lat,lng])
      if (Array.isArray(res.route_coords) && res.route_coords.length > 0) {
        const latlngs = res.route_coords.map(c => [c[1], c[0]]);
        layerRoute.setLatLngs(latlngs);
        bounds.push(...latlngs);
      } else {
        // fallback: garis lurus antar path (jika OSRM gagal)
        const latlngs = res.path.map(p => [p.lat, p.lng]);
        layerRoute.setLatLngs(latlngs);
        bounds.push(...latlngs);
      }

      if (bounds.length) map.fitBounds(bounds, { padding: [28,28] });

      const km = (res.distance).toFixed(2);
      $('#info').html(`Total jarak (berdasarkan bobot graf): <b>${km} km</b>. Jalur digambar mengikuti jalan (OSRM).`);
    })
    .catch(err => {
      console.error(err);
      $('#info').text('Terjadi kesalahan saat menghitung rute.');
    });
  });
});
</script>
@endsection
