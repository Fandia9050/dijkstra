@extends('master') @section("css")
<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
/>
@endsection @section("content")
<div style="padding: 16px; max-width: 1100px; margin: 0 auto">
    <h2>Find Shortest Route (Dijkstra)</h2>

    <div class="controls">
        <div class="box">
            <label>Start</label><br />
            <select id="start" style="min-width: 220px">
                <option value="">-- pilih start --</option>
                @foreach($locations as $loc)
                <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="box">
            <label>End</label><br />
            <select id="end" style="min-width: 220px">
                <option value="">-- pilih end --</option>
                @foreach($locations as $loc)
                <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="box">
            <button id="findBtn">Find Shortest Route</button>
        </div>

        <div class="box" id="info">
            <strong>Total:</strong> <span id="totalDistance">-</span>
        </div>
    </div>

    <div id="map"></div>
</div>
@endsection @section("js")
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // inisialisasi peta
    const map = L.map('map').setView([0,0], 2); // nanti fitBounds
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    let currentPolyline = null;
    let currentMarkers = [];

    // helper: hapus markers & polyline lama
    function clearRoute() {
      if (currentPolyline) {
        map.removeLayer(currentPolyline);
        currentPolyline = null;
      }
      currentMarkers.forEach(m => map.removeLayer(m));
      currentMarkers = [];
    }

    // helper: create numbered marker
    function createNumberedMarker(lat, lng, number, popupHtml) {
      const icon = L.divIcon({
        className: 'custom-div-icon',
        html: `<div style="display:flex;align-items:center;gap:6px">
                 <div class="marker-label">${number}</div>
               </div>`,
        iconSize: [30, 42],
        iconAnchor: [15, 42],
      });

      const marker = L.marker([lat, lng], { icon }).addTo(map);
      if (popupHtml) marker.bindPopup(popupHtml);
      return marker;
    }

    async function findRoute() {
      const start = document.getElementById('start').value;
      const end = document.getElementById('end').value;

      if (!start || !end) {
        alert('Pilih start dan end terlebih dahulu');
        return;
      }
      if (start === end) {
        alert('Start dan End tidak boleh sama');
        return;
      }

      document.getElementById('findBtn').disabled = true;
      document.getElementById('findBtn').innerText = 'Loading...';

      try {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        const res = await fetch("{{ route('route.shortest') }}", {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({ start, end })
        });

        if (!res.ok) {
          const err = await res.json().catch(()=>({error: 'Unknown error'}));
          alert(err.error || 'Request failed');
          return;
        }

        const data = await res.json();

        // clear old
        clearRoute();

        // draw markers & polyline
        const coords = data.coords; // [{lat, lng, name, address}]
        const latlngs = coords.map(c => [c.lat, c.lng]);

        // markers
        coords.forEach((c, idx) => {
          const popupHtml = `<strong>${idx+1}. ${c.name}</strong><br/>${c.address ?? ''}`;
          const marker = createNumberedMarker(c.lat, c.lng, idx + 1, popupHtml);
          currentMarkers.push(marker);
        });

        // polyline
        currentPolyline = L.polyline(latlngs, { weight: 6, opacity: 0.85 }).addTo(map);

        // fit bounds
        map.fitBounds(currentPolyline.getBounds(), { padding: [40, 40] });

        // show distance (sesuaikan unit)
        document.getElementById('totalDistance').innerText = data.distance ?? '-';
      } catch (e) {
        console.error(e);
        alert('Terjadi error saat mengambil rute');
      } finally {
        document.getElementById('findBtn').disabled = false;
        document.getElementById('findBtn').innerText = 'Find Shortest Route';
      }
    }

    document.getElementById('findBtn').addEventListener('click', findRoute);

    // optional: center map to all locations initially
    (function initBounds(){
      const allOptionLatLng = [];
      @foreach($locations as $loc)
        @if($loc->latitude && $loc->longitude)
          allOptionLatLng.push([{{ (float)$loc->latitude }}, {{ (float)$loc->longitude }}]);
        @endif
      @endforeach

      if (allOptionLatLng.length) {
        const bounds = L.latLngBounds(allOptionLatLng);
        map.fitBounds(bounds, { padding: [40,40] });
      } else {
        // default view kalau tidak ada lokasi valid
        map.setView([0,0], 2);
      }
    })();
</script>

@endsection
