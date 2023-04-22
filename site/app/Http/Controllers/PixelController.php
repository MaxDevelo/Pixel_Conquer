<?php

namespace App\Http\Controllers;

use App\Models\Pixel;
use Illuminate\Http\Request;
use Pusher\Pusher;
use UpdateCarte;



class PixelController extends Controller
{
    /**
     * Store a newly created pixel in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Only allow authenticated users to add a pixel
        $this->middleware('auth');

        // Validate the user input
        $validatedData = $request->validate([
            'x' => 'required|integer',
            'y' => 'required|integer',
            'color' => 'required|string',
            'user_id' => 'required|integer',
        ]);


        $x = $validatedData['x'];
        $y = $validatedData['y'];
        $color =  $validatedData['color'];
        $user = $validatedData['user_id'];

        // Rechercher l'enregistrement correspondant aux coordonnées spécifiées
        $pixel = Pixel::where('coordinate_x', $x)
            ->where('coordinate_y', $y)
            ->first();

        // Mettre à jour la couleur de la case si l'enregistrement existe
        if ($pixel) {
            $pixel->color = $color;
            $pixel->user_id = $user;
            $pixel->save();
        }

        // Save the new pixel to the database
        $pixel->save();
        event(new UpdateCarte($pixel));
        
        return redirect()->back();
    }
}
