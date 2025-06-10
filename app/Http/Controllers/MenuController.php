<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parentMenus = Menu::with('children')->whereNull('parent_id')->orderBy('order')->get();
        return view('home', compact('parentMenus'));
    }

    public function manage()
    {
        $menus = Menu::with('children')->get();
        
        if (request()->ajax()) {
            return view('menus.manage', compact('menus'))->render();
        }
        
        return view('menus.manage', compact('menus'));
    }

    public function edit(Menu $menu)
    {
        $menus = Menu::whereNull('parent_id')->get();
        
        if (request()->ajax()) {
            return view('menus.edit', compact('menu', 'menus'))->render();
        }
        
        return view('menus.edit', compact('menu', 'menus'));
    }

    public function manageLinks()
    {
        $menus = Menu::with('children')->whereNull('parent_id')->get();
        return view('menus.manage-links', compact('menus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            Log::info('Iniciando cadastro de menu', $request->all());

            $validator = \Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'parent_id' => 'nullable|exists:menus,id',
                'link' => 'nullable|url|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($validator->fails()) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Erro de validação',
                        'errors' => $validator->errors()
                    ], 422);
                }
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $menu = new Menu();
            $menu->name = $request->name;
            $menu->parent_id = $request->parent_id ?: null;

            if ($request->hasFile('image')) {
                Log::info('Processando imagem do menu');
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('storage/menus'), $imageName);
                $menu->image = $imageName;
                $menu->link = url('storage/menus/' . $imageName);
            } else {
                $menu->link = $request->link;
            }

            $menu->save();
            Log::info('Menu salvo com sucesso', ['menu_id' => $menu->id]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Menu criado com sucesso!'
                ]);
            }

            return redirect()->route('menus.manage')
                ->with('success', 'Menu criado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao criar menu', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao criar menu: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('menus.manage')
                ->with('error', 'Erro ao criar menu: ' . $e->getMessage());
        }
    }

    public function showImage($id)
    {
        $menu = Menu::findOrFail($id);
        return view('menus.image', compact('menu'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        try {
            return response()->json($menu);
        } catch (\Exception $e) {
            Log::error('Erro ao mostrar menu', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Erro ao mostrar menu'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:menus,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->only(['name', 'parent_id']);

        if ($request->hasFile('image')) {
            // Deletar imagem antiga se existir
            if ($menu->image) {
                $oldImagePath = public_path('storage/menus/' . $menu->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('storage/menus'), $imageName);
            $data['image'] = $imageName;
            $data['link'] = url('storage/menus/' . $data['image']);
        }

        $menu->update($data);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Menu atualizado com sucesso!']);
        }

        return redirect()->route('menus.manage')
            ->with('success', 'Menu atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        try {
            Log::info('Iniciando exclusão do menu', ['menu_id' => $menu->id]);
            
            // Excluir a imagem se existir
            if ($menu->image) {
                Log::info('Excluindo imagem do menu', ['image_path' => $menu->image]);
                Storage::disk('public')->delete($menu->image);
            }
            
            // Excluir submenus primeiro
            if ($menu->children()->count() > 0) {
                Log::info('Excluindo submenus', ['parent_id' => $menu->id]);
                $menu->children()->delete();
            }
            
            // Excluir o menu
            $menu->delete();
            Log::info('Menu excluído com sucesso', ['menu_id' => $menu->id]);
            
            if (request()->ajax()) {
                return response()->json(['success' => true, 'message' => 'Menu excluído com sucesso']);
            }
            
            return redirect()->route('menus.manage')
                ->with('success', 'Menu excluído com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao excluir menu', [
                'menu_id' => $menu->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao excluir menu: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('menus.manage')
                ->with('error', 'Erro ao excluir menu: ' . $e->getMessage());
        }
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*.id' => 'required|exists:menus,id',
            'order.*.order' => 'required|integer|min:0'
        ]);

        try {
            foreach ($request->order as $item) {
                Menu::where('id', $item['id'])->update(['order' => $item['order']]);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar a ordem dos menus: ' . $e->getMessage()
            ], 500);
        }
    }
}
