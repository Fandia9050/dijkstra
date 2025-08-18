@extends('master') @section("css")
<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
/>
<style>
    #map {
        height: 500px;
        width: 100%;
    }

    .select2-search__field {
        width: 100% !important;
    }

    .select2-container .select2-selection--single {
        height: 44px !important; /* samakan dengan tinggi input start */
        border: 1px solid #d1d5db; /* warna border seperti input */
        border-radius: 0.375rem; /* sama dengan rounded-md */
        padding: 6px 12px; /* agar teks di dalam rata */
        display: flex;
        align-items: center;
    }

    .select2-container--default
        .select2-selection--single
        .select2-selection__rendered {
        color: #111827; /* teks warna hitam */
        line-height: 28px; /* biar rata tengah */
        font-size: 1rem; /* sama dengan input */
    }

    .select2-container--default
        .select2-selection--single
        .select2-selection__arrow {
        height: 100%;
        right: 10px;
    }

    /* Supaya width penuh */
    .select2-container {
        width: 100% !important;
    }
</style>
<link rel="stylesheet" href="{{ asset('css/select2.min.css') }}" />
@endsection @section("content")
<div class="container mt-3">
    <div class="card">
        <div class="card-header">Multi-destination (Dijkstra)</div>
        <div class="card-body">
            <form id="routeForm" class="row g-3">
                @csrf
                <div class="col-md-4">
                    <label class="form-label">Start</label>
                    <select
                        id="start"
                        name="start"
                        class="form-control select2"
                    >
                        <option value="">Select starting point</option>
                        @foreach($locations as $loc)
                        <option value="{{ $loc->id }}">
                            {{ $loc->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-8">
                    <label class="form-label"
                        >Destination(s) (multiple allowed)</label
                    >
                    <select
                        id="locations"
                        class="form-control select2bs4"
                        multiple="multiple"
                        style="width: 100%"
                    ></select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-sm">
                        Find Route
                    </button>
                </div>
            </form>

            <div class="mt-3" id="info">
                Select Start & Destination, then click Calculate Route.
            </div>
            <div id="map" class="mt-3"></div>
        </div>
    </div>
</div>
@endsection @section("js")
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="{{ asset('js/select2.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $('#locations').select2({
            placeholder: 'search location...',
            ajax: {
                url: '/locations', // route yang mengarah ke method location()
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term, // kata kunci pencarian
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.map(function (item) {
                            return { id: item.id, text: item.name };
                        }),
                    };
                },
            },
        });
    });

    const map = L.map('map').setView([-2.5489, 118.0149], 5); // center Indonesia
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19,
    }).addTo(map);

    let layerRoutes = L.featureGroup().addTo(map); // Kumpulan semua polyline edge
    let layerMarkers = L.layerGroup().addTo(map);

    // Palet warna untuk edge
    const colors = [
        '#e6194b',
        '#f58231',
        '#ffe119',
        '#3cb44b',
        '#4363d8',
        '#911eb4',
        '#f032e6',
    ];

    $('#routeForm').on('submit', function (e) {
        e.preventDefault();

        const start = $('#start').val();
        const locations = $('#locations').val() || [];

        if (!start || locations.length === 0) {
            alert('Silakan pilih Start dan minimal satu Tujuan.');
            return;
        }

        $('#info').text('Calculate route...');

        fetch('{{ route("routes.shortestMulti") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            body: JSON.stringify({ start, locations }),
        })
            .then((r) => r.json())
            .then((res) => {
                layerRoutes.clearLayers();
                layerMarkers.clearLayers();

                if (res.error) {
                    $('#info').text(res.error);
                    return;
                }

                const bounds = [];

                // Pasang marker sesuai urutan
                res.path.forEach((p, idx) => {
                    const m = L.marker([p.lat, p.lng])
                        .addTo(layerMarkers)
                        .bindPopup(`<b>${idx + 1}. ${p.name}</b>`);
                    bounds.push([p.lat, p.lng]);
                });

                // Jika backend mengembalikan koordinat per-edge
                if (Array.isArray(res.edges) && res.edges.length > 0) {
                    res.edges.forEach((edge, idx) => {
                        if (
                            Array.isArray(edge.coords) &&
                            edge.coords.length > 0
                        ) {
                            const latlngs = edge.coords.map((c) => [
                                c[1],
                                c[0],
                            ]);
                            const color = colors[idx % colors.length];
                            const poly = L.polyline(latlngs, {
                                color: color,
                                weight: 5,
                                opacity: 0.9,
                            }).addTo(layerRoutes);
                            bounds.push(...latlngs);
                            poly.bindTooltip(
                                `<div>
                                <b>Edge ${idx + 1}</b><br>
                                ${edge.from} → ${edge.to}<br>
                                Jarak: ${edge.distance_km.toFixed(2)} km
                            </div>`,
                                { sticky: true }
                            );
                        }
                    });
                } else {
                    // fallback: semua rute jadi satu warna
                    if (
                        Array.isArray(res.route_coords) &&
                        res.route_coords.length > 0
                    ) {
                        const latlngs = res.route_coords.map((c) => [
                            c[1],
                            c[0],
                        ]);
                        L.polyline(latlngs, {
                            color: colors[0],
                            weight: 5,
                            opacity: 0.9,
                        }).addTo(layerRoutes);
                        bounds.push(...latlngs);
                    } else {
                        const latlngs = res.path.map((p) => [p.lat, p.lng]);
                        L.polyline(latlngs, {
                            color: colors[0],
                            weight: 5,
                            opacity: 0.9,
                        }).addTo(layerRoutes);
                        bounds.push(...latlngs);
                    }
                }

                if (bounds.length) map.fitBounds(bounds, { padding: [28, 28] });

                const km = res.distance.toFixed(2);
                $('#info').html(
                    `Total distance (based on graph weight): <b>${km} km</b>`
                );
            })
            .catch((err) => {
                console.error(err);
                $('#info').text(
                    'An error occurred while calculating the route.'
                );
            });
    });
</script>

@endsection
