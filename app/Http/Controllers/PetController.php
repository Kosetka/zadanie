<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PetController extends Controller
{
    public static $url = 'https://petstore.swagger.io/v2/pet';

    public function create()
    {
        return view('pets.add');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'photoUrls' => 'required|array',
            'photoUrls.*' => 'url',
            'status' => 'required|in:available,sold,pending',
        ]);

        $petData = [
            'id' => 0,
            'name' => $validated['name'],
            'category' => [
                'id' => 0,
                'name' => 'string',
            ],
            'photoUrls' => $validated['photoUrls'],
            'tags' => [
                ['id' => 0, 'name' => 'string'],
            ],
            'status' => $validated['status'],
        ];

        $response = Http::post(self::$url, $petData);

        if ($response->successful()) {
            $responseData = $response->json();

            return redirect()->route('pets.index')
                ->with('success', 'Pet added successfully! ID: ' . json_encode($responseData["id"]));
        }

        if ($response->status() === 405) {
            return back()->with('error', 'Invalid input');
        }

        return back()->with('error', 'Something went wrong! Please try again.');
    }

    public function index(Request $request)
    {
        $status = $request->get('status', 'available');
        $page = $request->get('page', 1);
        $limit = 25;

        $response = Http::get(self::$url . "/findByStatus", [
            'status' => $status
        ]);

        if ($response->successful()) {
            $pets = $response->json();

            $filteredPets = array_filter($pets, function ($pet) use ($status) {
                return $pet['status'] === $status;
            });

            usort($filteredPets, function ($a, $b) {
                return $a['id'] <=> $b['id'];
            });

            // Paginacja na 25 zwierzaków na stronę

            $offset = ($page - 1) * $limit;
            $pagedPets = array_slice($filteredPets, $offset, $limit);

            return view('pets.index', [
                'pets' => $pagedPets,
                'status' => $status,
                'page' => $page,
                'total' => count($filteredPets),
                'limit' => $limit,
            ]);
        }

        // Przy błędnym statusie nadal zwraca 200, według dokumentacji powinno być 400
        if ($response->status() === 400) {
            return back()->with('error', 'Invalid status value');
        }

        return back()->with('error', 'Something went wrong! Please try again.');
    }

    public function destroy($id)
    {
        $response = Http::delete(self::$url . "/{$id}");

        if ($response->successful()) {
            return redirect()->route('pets.index')->with('success', 'Pet has been deleted.');
        }

        if ($response->status() === 400) {
            return back()->with('error', 'Invalid ID supplied.');
        }

        if ($response->status() === 404) {
            return back()->with('error', 'Pet not found.');
        }

        return back()->with('error', 'Something went wrong! Please try again.');
    }

    public function edit($id)
    {
        $response = Http::get(self::$url . "/{$id}");

        if ($response->status() === 404) {
            return redirect()->route('pets.index')->with('error', 'Pet not found.');
        }

        if ($response->status() === 400) {
            return redirect()->route('pets.index')->with('error', 'Invalid ID supplied.');
        }

        $pet = $response->json();

        return view('pets.edit', compact('pet'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|string|in:available,sold,pending',
            'photoUrls' => 'nullable|array',
            'photoUrls.*' => 'nullable|string',
        ]);

        $data = [
            'id' => $id,
            'name' => $validated['name'],
            'status' => $validated['status'],
            'photoUrls' => $validated['photoUrls'] ?? [],
        ];

        $response = Http::put(self::$url, $data);

        if ($response->status() === 400) {
            return redirect()->route('pets.index')->with('error', 'Invalid ID supplied.');
        }

        if ($response->status() === 404) {
            return redirect()->route('pets.index')->with('error', 'Pet not found.');
        }

        if ($response->status() === 405) {
            return redirect()->route('pets.index')->with('error', 'Validation exception.');
        }

        return redirect()->route('pets.index')->with('success', 'Pet has been updated.');
    }

}
