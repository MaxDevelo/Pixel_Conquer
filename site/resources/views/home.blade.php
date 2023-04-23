@extends('layouts.app') @section('title', 'Pixel Conquer - Home')
@section('container') @parent

    <body>
        @php
            $isLoggedIn = session('User');
            if (session('User')) {
                $color = $isLoggedIn->color;
            } else {
                $color = '#000000';
            }
        @endphp
        <div class="container">
            <div class="row">
                <div class="col-md-9">
                    <h1>PIXEL CONQUER</h1>
                    @if ($isLoggedIn)
                        <div class="color-picker">
                            @if (session('User'))
                                <label for="color" style="font-size:20px; font-weight:bold">Your Team Color:</label>
                                <div class="color-picker__color"
                                    style="background-color: {{ $color }}; width:40px; height: 40px; margin: 0 auto; border:2px solid black;margin-bottom:10px">
                                </div>
                                <label for="color" style="font-size:20px; font-weight:bold; margin-bottom:40px">
                                    {{ $isLoggedIn->pixels }} pixels
                                    remaining</label>
                            @endif


                        </div>
                    @endif
                    <table id="pixel_canvas"></table>
                    @php
                        $pixels = App\Models\Pixel::all();
                    @endphp
                </div>
                <div class="col-md-3">
                    <div class="scoreboard">
                        <h2>SCOREBOARD</h2>
                        <ul class="team-scores">
                            <li class="team-score" style="background-color: red;"><span id="red-score">0 pixel(s)</span>
                            </li>
                            <li class="team-score" style="background-color: blue;"><span id="blue-score">0 pixel(s)</span>
                            </li>
                            <li class="team-score" style="background-color: green;"><span id="green-score">0 pixel(s)</span>
                            </li>
                            <li class="team-score" style="background-color: black;"><span id="black-score">0 pixel(s)</span>
                            </li>
                            <li class="team-score" style="background-color: purple;"><span id="purple-score">0
                                    pixel(s)</span></li>
                            <li class="team-score" style="background-color: orange;"><span id="orange-score">0
                                    pixel(s)</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @php
            $pixels = App\Models\Pixel::all();
        @endphp

        <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.4/echo.min.js"></script>
        <script>
            let pixels = "{{ $isLoggedIn->pixels ?? 0 }}";
            // On se connecte à notre compte Pusher
            const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
                cluster: 'eu',
                encrypted: true
            });



            const channel = pusher.subscribe('pixel');
            channel.bind('PixelUpdated', function(data) {
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
            (function(document) {
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
                const init = function() {
                    makeGrid();
                    // Set the grid's color listener.
                    elements.gridCanvas.addEventListener("mousedown", setGridColor);
                };

                function makeGrid() {
                    elements.gridCanvas.innerHTML = "";
                    let pixels = {!! json_encode($pixels) !!};

                    for (let y = 0; y < 50; y++) {
                        let tr = elements.gridCanvas.insertRow(y);

                        for (let x = 0; x < 50; x++) {
                            let td = tr.insertCell(x);

                            td.id = `pixel-${x}-${y}`;
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
                let isLoggedIn = {{ isset($isLoggedIn) ? 'true' : 'false' }};
                let colorUser = "{{ $color }}";

                function setGridColor(event) {
                    if (isLoggedIn && colorUser != "") {
                        let color = colorUser;
                        
                        if (pixels > 0) {
                            event.target.setAttribute(
                                "style",
                                "background-color: " + color
                            );

                            /// Save the grid color to the database.
                            let x = event.target.cellIndex;
                            let y = event.target.parentElement.rowIndex;
                            saveColorToDB(x, y, color);
                            pixels--;
                        } else {
                            alert("You have no more pixels to place.");
                        }

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
                    let user_id = "{{ $isLoggedIn->user_id ?? '' }}";
                    if (user_id) {
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
                            success: function() {
                                // console.log("Pixel added successfully.");
                            }
                        });
                    } else {
                        alert("You must be logged in to place pixels.");
                    }
                }


                init();
            })(document);
        </script>
    </body>

@endsection
