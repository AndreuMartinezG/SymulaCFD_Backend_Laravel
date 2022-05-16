<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    //
    ///////////////// STORE PROJECT ///////////////////////
    public function store(Request $request)
    {

        try{

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'user_id' => 'required|integer',
                'case' => 'required|string|max:255',
                'category' => 'required|string|max:255',
                'geometry_name' => 'required|string|max:255',
            ]);

            if($validator->fails()){
                return response()->json($validator->errors()->toJson(),400);
            }

            $project = Project::create([
                'title' => $request->get('title'),
                'description' => $request->get('description'),
                'user_id' => $request->get('user_id'),
                'case' => $request->get('case'),
                'category' => $request->get('category'),
                'geometry_name' => $request->get('geometry_name'),
            ]);

            return response()->json(compact('project'),201);

        } catch (\Exception $e) {

            Log::error($e->getMessage());
            return response()->json(['error' => 'En la creación del proyecto'], 500);

        }

    }

    ///////////////// INDEX PROJECT ///////////////////////
    public function index()
    {
        try {
                
            $projects = Project::all();
    
            return response()->json(compact('projects'),200);
    
        } catch (\Exception $e) {
    
            Log::error($e->getMessage());
            return response()->json(['error' => 'Error al obtener los proyectos'], 500);
    
        }
    }

    ///////////////// INDEX PROJECT BY USER ///////////////////////

    public function indexByUser($user_id)
    {

        try {
                    
            $projects = Project::where('user_id', $user_id)->get();
        
            return response()->json(compact('projects'),200);
        
        } catch (\Exception $e) {
        
            Log::error($e->getMessage());
            return response()->json(['error' => 'Error al obtener los proyectos'], 500);
        
        }
    }

    ///////////////// SHOW PROJECT ///////////////////////
    public function show($id)
    {
        try {
                        
            $project = Project::find($id);
            
            return response()->json(compact('project'),200);
            
        } catch (\Exception $e) {
            
            Log::error($e->getMessage());
            return response()->json(['error' => 'Error al obtener el proyecto'], 500);
            
        }
    }

    ///////////////// UPDATE PROJECT ///////////////////////

    public function update(Request $request, $id)
    {
        try{ 

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'user_id' => 'required|integer',
                'case' => 'required|string|max:255',
                'category' => 'required|string|max:255',
                'geometry_name' => 'required|string|max:255',
            ]);
        
            if($validator->fails()){
                return response()->json($validator->errors()->toJson(),400);
            }
        
            $project = Project::find($id);
            $project->title = $request->get('title');
            $project->description = $request->get('description');
            $project->user_id = $request->get('user_id');
            $project->case = $request->get('case');
            $project->category = $request->get('category');
            $project->geometry_name = $request->get('geometry_name');
            $project->save();
        
            return response()->json(compact('project'),200);

        } catch (\Exception $e) {
        
            Log::error($e->getMessage());
            return response()->json(['error' => 'Error al actualizar el proyecto'], 500);
        
        }
    }


    ///////////////// DELETE PROJECT ///////////////////////
    public function destroy($id)
    {
        try {
                
            $project = Project::find($id);
            $project->delete();
                
            return response()->json(['success' => 'Proyecto eliminado'],200);
                
        } catch (\Exception $e) {
                
            Log::error($e->getMessage());
            return response()->json(['error' => 'Error al eliminar el proyecto'], 500);
                
        }

    }

    ///////////////// UPDATE ROUTE_3D PROJECT ///////////////////////
    public function updateRoute3D(Request $request, $id)
    {
        try{
        
            $validator = Validator::make($request->all(), [
                'default_Route_3D' => 'required|string|max:255',
                'index_Route_3D' => 'required|string|max:255',
            ]);

            if($validator->fails()){
                return response()->json($validator->errors()->toJson(),400);
            }

            $project = Project::find($id);
            $project->default_Route_3D = $request->get('default_Route_3D');
            $project->index_Route_3D = $request->get('index_Route_3D');
            $project->save();

            return response()->json(compact('project'),200);
            
        } catch (\Exception $e) {

            Log::error($e->getMessage());
            return response()->json(['error' => 'Error al actualizar el proyecto'], 500);

        }
    }

}
