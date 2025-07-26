<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\PetService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PetController extends Controller
{
    protected $petService;

    public function __construct(PetService $petService)
    {
        $this->petService = $petService;
    }

    /**
     * Mostrar mascotas de un usuario específico en cards
     */
    public function userPetsProfile($userId)
    {
        try {
            $user = User::findOrFail($userId);
            $pets = $this->petService->getUserPets($userId);
            $petsCount = $pets->count();
            $activePetsCount = $pets->where('status_pet', 'A')->count();

            // Estadísticas del usuario
            $userStats = [
                'total_pets' => $petsCount,
                'active_pets' => $activePetsCount,
                'inactive_pets' => $petsCount - $activePetsCount,
                'species_breakdown' => $pets->groupBy('species_pet')->map->count(),
                'newest_pet' => $pets->sortByDesc('created_at')->first(),
                'oldest_pet' => $pets->sortBy('created_at')->first(),
            ];

            return view('pets.user-profile', compact('user', 'pets', 'userStats'));

        } catch (Exception $e) {
            Log::error('Error al cargar perfil de usuario con mascotas: ' . $e->getMessage());
            return redirect()->route('pets.index')->withErrors(['error' => 'Usuario no encontrado.']);
        }
    }

    /**
     * Búsqueda de usuarios para mostrar sus mascotas
     */
    public function searchUsersForPets(Request $request)
    {
        try {
            $search = $request->get('search');

            if (empty($search)) {
                $users = User::where('us_status', 'A')
                    ->withCount(['pets' => function($query) {
                        $query->where('status_pet', 'A');
                    }])
                    ->orderBy('us_name')
                    ->take(20)
                    ->get();
            } else {
                $users = User::where('us_status', 'A')
                    ->where(function($query) use ($search) {
                        $query->where('us_name', 'like', "%{$search}%")
                            ->orWhere('us_lastName', 'like', "%{$search}%")
                            ->orWhere('us_email', 'like', "%{$search}%")
                            ->orWhere('us_dni', 'like', "%{$search}%");
                    })
                    ->withCount(['pets' => function($query) {
                        $query->where('status_pet', 'A');
                    }])
                    ->orderBy('us_name')
                    ->take(50)
                    ->get();
            }

            return view('pets.search-users', compact('users', 'search'));

        } catch (Exception $e) {
            Log::error('Error al buscar usuarios: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error al realizar la búsqueda.']);
        }
    }

    /**
     * Mostrar lista de mascotas
     */
    public function index(Request $request)
    {
        $users = User::where('us_status', 'A')->get();
        $pets = $this->petService->getAllPets(15);

        // Aplicar filtros si existen
        if ($request->has('name_pet') || $request->has('species_pet') || $request->has('id_usu')) {
            $filters = [];

            if ($request->has('name_pet') && $request->name_pet) {
                $filters['name'] = $request->name_pet;
            }

            if ($request->has('species_pet') && $request->species_pet) {
                $filters['species'] = $request->species_pet;
            }

            if ($request->has('id_usu') && $request->id_usu) {
                $filters['user_id'] = $request->id_usu;
            }

            $pets = $this->petService->getAllPets(15, $filters);
        }

        return view('pets.index', compact('users', 'pets'));
    }

    /**
     * Crear nueva mascota
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name_pet' => 'required|string|max:250',
                'species_pet' => 'required|string|max:250',
                'breed_pet' => 'required|string|max:250',
                'age_pet' => 'required|integer|min:0|max:30',
                'pet_image' => 'nullable|image|max:2048',
                'use_api_image' => 'nullable|in:1,0,true,false,on', // ✅ Más flexible
                'api_image_url' => 'nullable|url',
                'id_usu' => 'required|exists:tbl_user,us_id',
            ]);

            $validatedData['status_pet'] = 'A'; // Por defecto activo

            // Manejar imagen - Lógica mejorada
            if ($request->hasFile('pet_image')) {
                // Usuario subió su propia imagen (prioridad)
                $imagePath = $request->file('pet_image')->store('profile_images', 'public');
                $validatedData['image_pet'] = $imagePath;
                $validatedData['image_source'] = 'upload';

                Log::info('Imagen subida por usuario', [
                    'path' => $imagePath,
                    'source' => 'upload'
                ]);

            } elseif (
                ($request->filled('use_api_image') && in_array($request->use_api_image, ['1', 'true', 'on'])) &&
                $request->filled('api_image_url')
            ) {
                // Usuario eligió imagen de API
                $validatedData['image_pet'] = $request->api_image_url;
                $validatedData['image_source'] = 'api';

                Log::info('Imagen de API seleccionada', [
                    'url' => $request->api_image_url,
                    'source' => 'api',
                    'use_api_image' => $request->use_api_image
                ]);
            }

            Log::info('Datos finales antes de crear mascota', [
                'image_pet' => $validatedData['image_pet'] ?? 'none',
                'image_source' => $validatedData['image_source'] ?? 'none'
            ]);

            $pet = $this->petService->createPet($validatedData);

            if (!$pet) {
                return back()->withErrors(['name_pet' => 'No se pudo crear la mascota.'])->withInput();
            }

            return redirect()->route('pets.index')->with('success', 'Mascota creada con éxito.');

        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->toArray();
            $errorMessage = "Introduce bien estos datos:\n";
            foreach ($errors as $key => $value) {
                $errorMessage .= $key . ": " . $value[0] . "\n";
            }
            return back()->withErrors(['name_pet' => $errorMessage])->withInput();

        } catch (Exception $e) {
            Log::error('Error al crear la mascota: ' . $e->getMessage());
            return back()->withErrors(['name_pet' => 'No se pudo crear la mascota.'])->withInput();
        }
    }


    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $users = User::where('us_status', 'A')->get();
        $pet = $this->petService->getPetById($id);

        if (!$pet) {
            return redirect()->route('pets.index')->withErrors(['error' => 'Mascota no encontrada.']);
        }

        return view('pets.edit', compact('pet', 'users'));
    }

    /**
     * Actualizar mascota
     */
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'name_pet' => 'required|string|max:250',
                'species_pet' => 'required|string|max:250',
                'breed_pet' => 'required|string|max:250',
                'age_pet' => 'required|integer|min:0|max:30',
                'pet_image' => 'nullable|image|max:2048',
                'use_api_image' => 'nullable|in:1,0,true,false,on', // ✅ Más flexible
                'api_image_url' => 'nullable|url',
                'status_pet' => 'required|in:A,I',
                'id_usu' => 'required|exists:tbl_user,us_id',
            ]);

            // Manejar imagen solo si se proporciona una nueva
            if ($request->hasFile('pet_image')) {
                // Usuario subió nueva imagen (prioridad)
                $imagePath = $request->file('pet_image')->store('profile_images', 'public');
                $validatedData['image_pet'] = $imagePath;
                $validatedData['image_source'] = 'upload';

            } elseif (
                ($request->filled('use_api_image') && in_array($request->use_api_image, ['1', 'true', 'on'])) &&
                $request->filled('api_image_url')
            ) {
                // Usuario eligió nueva imagen de API
                $validatedData['image_pet'] = $request->api_image_url;
                $validatedData['image_source'] = 'api';
            }

            $pet = $this->petService->updatePet($id, $validatedData);

            if (!$pet) {
                return back()->withErrors(['name_pet' => 'No se pudo actualizar la mascota.'])->withInput();
            }

            return redirect()->route('pets.index')->with('success', 'Mascota actualizada con éxito.');

        } catch (Exception $e) {
            Log::error('Error al actualizar la mascota: ' . $e->getMessage());
            return back()->withErrors(['name_pet' => 'No se pudo actualizar la mascota.'])->withInput();
        }
    }

    /**
     * Eliminar mascota
     */
    public function destroy($id)
    {
        $result = $this->petService->deletePet($id);

        if (!$result) {
            return back()->withErrors(['error' => 'No se pudo eliminar la mascota.']);
        }

        return redirect()->route('pets.index')->with('success', 'Mascota eliminada con éxito.');
    }

    /**
     * Mostrar mascotas de un usuario específico
     */
    public function userPets($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('pets.index')->withErrors(['error' => 'Usuario no encontrado.']);
        }

        $pets = $this->petService->getUserPets($userId);

        return view('pets.user-pets', compact('user', 'pets'));
    }

    /**
     * Buscar mascotas
     */
    public function search(Request $request)
    {
        $criteria = $request->only(['name', 'species', 'breed']);

        if (empty(array_filter($criteria))) {
            return back()->withErrors(['search' => 'Debe proporcionar al menos un criterio de búsqueda.']);
        }

        $pets = $this->petService->searchPets($criteria);
        $users = User::where('us_status', 'A')->get();

        return view('pets.search-results', compact('pets', 'users', 'criteria'));
    }

    public function searchBreedImage(Request $request)
    {
        try {
            // Log para debugging
            Log::info('Buscando imagen de raza', [
                'species' => $request->get('species'),
                'breed' => $request->get('breed')
            ]);

            $species = $request->get('species');
            $breed = $request->get('breed');

            if (empty($species) || empty($breed)) {
                Log::warning('Especie o raza vacía', [
                    'species' => $species,
                    'breed' => $breed
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Especie y raza son requeridas'
                ], 400);
            }

            // Llamar al service
            $imageUrl = $this->petService->fetchBreedImage($species, $breed);

            Log::info('Resultado de búsqueda de imagen', [
                'species' => $species,
                'breed' => $breed,
                'image_url' => $imageUrl
            ]);

            return response()->json([
                'success' => true,
                'image_url' => $imageUrl,
                'message' => $imageUrl ? 'Imagen encontrada' : 'No se encontró imagen para esta raza'
            ]);

        } catch (Exception $e) {
            Log::error('Error al buscar imagen de raza', [
                'species' => $request->get('species'),
                'breed' => $request->get('breed'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al buscar imagen: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Estadísticas de mascotas
     */
    public function statistics()
    {
        try {
            $stats = $this->petService->getPetStatistics();
            return view('pets.statistics', compact('stats'));

        } catch (Exception $e) {
            Log::error('Error al obtener estadísticas: ' . $e->getMessage());
            return redirect()->route('pets.index')->withErrors(['error' => 'Error al cargar las estadísticas.']);
        }
    }

    /**
     * Activar mascota
     */
    public function activate($id)
    {
        try {
            $result = $this->petService->activatePet($id);

            if (!$result) {
                return back()->withErrors(['error' => 'No se pudo activar la mascota.']);
            }

            return redirect()->route('pets.index')->with('success', 'Mascota activada con éxito.');

        } catch (Exception $e) {
            Log::error('Error al activar mascota: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error al activar la mascota.']);
        }
    }

    /**
     * Desactivar mascota
     */
    public function deactivate($id)
    {
        try {
            $result = $this->petService->deactivatePet($id);

            if (!$result) {
                return back()->withErrors(['error' => 'No se pudo desactivar la mascota.']);
            }

            return redirect()->route('pets.index')->with('success', 'Mascota desactivada con éxito.');

        } catch (Exception $e) {
            Log::error('Error al desactivar mascota: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error al desactivar la mascota.']);
        }
    }
}
