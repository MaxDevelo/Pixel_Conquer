@extends('layouts.app') @section('title', 'Pixel Conquer - Home')
@section('container') @parent

<body>
    <h1>PIXEL CONQUER</h1>
    <table id="pixel_canvas"></table>
    @php $isLoggedIn = session('UserEloquent'); $color =
    $isLoggedIn->value('color'); @endphp
    @php
        $pixels = App\Models\Pixel::all();
    @endphp
        <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script >

        // On se connecte à notre compte Pusher
        const pusher = new Pusher('e4f3f6c60791baa3a256', {
            cluster: 'eu',
            encrypted: true
        });

        // On écoute l'événement "PixelUpdated"
        const channel = pusher.subscribe('pixel-channel');
        channel.bind('PixelUpdated', function(data) {
            // On récupère les données envoyées par Pusher
            const pixel = data.pixel;

            // On met à jour la couleur du pixel sur la page en temps réel
            const td = document.getElementById(`pixel-${pixel.coordinate_x}-${pixel.coordinate_y}`);
            td.style.backgroundColor = pixel.color;
        });

        /**
         * Reviewer Notes:
         *
         * `///` indicates a thought process/strategy taken to help the reviewer.
         */

        /// Wrapped it into an IIFE to protect namespacing.
        (function (document) {
            "use strict";

            /// Cache selectors here to avoid redundant DOM lookups.
            const elements = {
                colorPicker: document.getElementById("colorPicker"),
                gridCanvas: document.getElementById("pixel_canvas"),
            };

            /**
             * @description Initialize by binding the event handlers.
             *
             * @function
             */
            const init = function () {
                makeGrid();
                // Set the grid's color listener.
                elements.gridCanvas.addEventListener("mousedown", setGridColor);
            };

     function makeGrid() {
            elements.gridCanvas.innerHTML = "";
            let pixels = {!! json_encode($pixels) !!};

            for (let y = 0; y < 30; y++) {
                let tr = elements.gridCanvas.insertRow(y);

                for (let x = 0; x < 30; x++) {
                    let td = tr.insertCell(x);

                    // Find the pixel with the same coordinates and set its color as the background color.
                    let pixel = pixels.find(p => p.coordinate_x == x && p.coordinate_y == y);
                    if (pixel) {
                        td.style.backgroundColor = pixel.color;
                    }
                }
            }
        }



            /**
             * @description Set the selected grid's background color.
             *
             * @function
             */
            let isLoggedIn = {{ $isLoggedIn ?'true': 'false' }};
        let colorUser = "{{ $color }}";
        function setGridColor(event) {
            if (isLoggedIn) {
                let color = colorUser;
                event.target.setAttribute(
                    "style",
                    "background-color: " + color
                );

                /// Save the grid color to the database.
                let x = event.target.cellIndex;
                let y = event.target.parentElement.rowIndex;
                saveColorToDB(x, y, color);
            } else {
                alert("You must be logged in to place pixels.");
            }
        }

        /**
         * @description Save the selected grid's background color to the database.
         *
         * @function
         */
        function saveColorToDB(x, y, color) {
            let user_id = "{{ $isLoggedIn->value('user_id') }}";
            $.ajax({
                type: "POST",
                url: "/pixel",
                data: {
                    x: x,
                    y: y,
                    user_id: user_id,
                    color: color,
                    _token: "{{ csrf_token() }}"
                },
                success: function () {
                    // console.log("Pixel added successfully.");
                }
            });

        }


        init();
            }) (document);
    </script>
</body>

@endsection