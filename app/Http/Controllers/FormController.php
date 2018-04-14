<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\User;
use App\Form;
use App\File;
use App\Field;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;

class FormController extends Controller {


	public function getSharedForms(Request $request) {
		//
		$totalForms = Form::where("share_all", '1')->count();

		$forms = Form::where("share_all", '1')->get();

		$totalFiltered = $totalForms; 

		$page_number = empty($request->page_number) ? 0: $request->page_number;
		$page_size = empty($request->page_size) ? 5: $request->page_size;
		$order = empty($request->order) ? 'created_at' : $request->order;
		$dir = empty($request->dir) ? 'desc': $request->dir;
		$offset = $page_number * $page_size;

        if(empty($request->filter)) {            
			$forms = Form::where("share_all", '1')
						->offset($offset)
                        ->limit($page_size)
                        ->orderBy($order,$dir)
                        ->get();
        } else {
            $filter = $request->filter; 

			$forms =  DB::table('forms AS f')
							->leftJoin('users AS u', 'f.user_id', '=', 'u.id')  
							->select('f.id', 'f.name', 'f.description', 'f.pages', 'f.user_id', 'f.created_at', 'f.updated_at')
							->where(function ($query){
								$query->where('f.share_all', '1');
								})
							->where(function($query) use($filter) {
								$query->where('f.id', 'like', "%{$filter}%")
								->orWhere('f.name', 'like', "%{$filter}%")
								->orWhere('f.description', 'LIKE',"%{$filter}%")
								->orWhere('u.first_name', 'LIKE',"%{$filter}%")
								->orWhere('u.last_name', 'LIKE',"%{$filter}%");
								})                                               
                            ->offset($offset)
                            ->limit($page_size)
                            ->orderBy($order,$dir)
                            ->get();

			$totalFiltered = DB::table('forms AS f')
									->leftJoin('users AS u', 'f.user_id', '=', 'u.id')  
									->select('f.id', 'f.name', 'f.description', 'f.pages', 'f.user_id', 'f.created_at', 'f.updated_at')
									->where(function ($query){
										$query->where('f.share_all', '1');
										})
									->where(function($query) use($filter) {
										$query->where('f.id', 'like', "%{$filter}%")
										->orWhere('f.name', 'like', "%{$filter}%")
										->orWhere('f.description', 'LIKE',"%{$filter}%")
										->orWhere('u.first_name', 'LIKE',"%{$filter}%")
										->orWhere('u.last_name', 'LIKE',"%{$filter}%");
										})  
                                    ->count();
        }		

		
		if(!empty($forms))
        {
            foreach ($forms as $form)
            {
				$user = User::findOrFail($form->user_id);
				$form->user_name = $user->fullname();
				$form->thumb = Form::findOrFail($form->id)->thumb();
				$form->files = Form::findOrFail($form->id)->files;

				$files = $form->files;
				// Fetch all files
				foreach($files as $file) {
					//$fields = $file->fields;
					$file->path = $file->path();
				}
            }
        }



        $response['data'] = $forms;
        $response['page_number'] = $page_number;
        $response['page_size'] = $page_size;
        $response['total_counts'] = $totalFiltered;

		return response()->json( $response, 200 );
	}

	public function getSharedForm($id) {
		$form = Form::findOrFail( $id );
		$form->thumb = $form->thumb();

		if ($form->share_all !="1") {
            throw new \App\Exceptions\InvalidAccessException();
		}

		return $this.show($id);
	}

	public function getAvailableFormsByCompany(Request $request, $company) {
		$companyForms =  DB::table('forms AS f')
						   ->leftJoin('users AS u', 'f.user_id', '=', 'u.id')
						   ->select('f.*')
						   ->where(function ($query) use($company) {
							   $query->where('u.provider_company',$company);

						   })->get();

		return response()->json( $companyForms, 200 );

	}
	public function getAvailableFormsbyUser(Request $request, $id) {

		$totalForms = Form::where( "user_id", $id )
					->orWhere("share_all", '1')->count();

		$forms = Form::where( "user_id", $id )
						->orWhere("share_all", '1')->get();

		$totalFiltered = $totalForms; 

		$page_number = empty($request->page_number) ? 0: $request->page_number;
		$page_size = empty($request->page_size) ? 5: $request->page_size;
		$order = empty($request->order) ? 'created_at' : $request->order;
		$dir = empty($request->dir) ? 'desc': $request->dir;
		$offset = $page_number * $page_size;

        if(empty($request->filter)) {            
			$forms = Form::where( "user_id", $id )
						->orWhere("share_all", '1')
						->offset($offset)
                        ->limit($page_size)
                        ->orderBy($order,$dir)
                        ->get();
        } else {
            $filter = $request->filter; 

			$forms = DB::table('forms AS f')
							->leftJoin('users AS u', 'f.user_id', '=', 'u.id')  
							->select('f.id', 'f.name', 'f.description', 'f.pages', 'f.user_id', 'f.created_at', 'f.updated_at')
							->where(function ($query) use($id) {
								$query->where('f.user_id',$id)
									->orWhere('f.share_all', '1');
								})
							->where(function($query) use($filter) {
								$query->where('f.id', 'like', "%{$filter}%")
								->orWhere('f.name', 'like', "%{$filter}%")
								->orWhere('f.description', 'LIKE',"%{$filter}%")
								->orWhere('u.first_name', 'LIKE',"%{$filter}%")
								->orWhere('u.last_name', 'LIKE',"%{$filter}%");
								})                                             
                            ->offset($offset)
                            ->limit($page_size)
                            ->orderBy($order,$dir)
                            ->get();

			$totalFiltered = DB::table('forms AS f')
									->leftJoin('users AS u', 'f.user_id', '=', 'u.id')  
									->select('f.id', 'f.name', 'f.description', 'f.pages', 'f.user_id', 'f.created_at', 'f.updated_at')
									->where(function ($query) use($id) {
										$query->where('f.user_id',$id)
											->orWhere('f.share_all', '1');
										})
									->where(function($query) use($filter) {
										$query->where('f.id', 'like', "%{$filter}%")
										->orWhere('f.name', 'like', "%{$filter}%")
										->orWhere('f.description', 'LIKE',"%{$filter}%")
										->orWhere('u.first_name', 'LIKE',"%{$filter}%")
										->orWhere('u.last_name', 'LIKE',"%{$filter}%");
										})    
                                    ->count();
        }		

		
		if(!empty($forms))
        {
            foreach ($forms as $form)
            {
				$user = User::findOrFail($form->user_id);
				$form->user_name = $user->fullname();
				$form->thumb = Form::findOrFail($form->id)->thumb();
				$form->files = Form::findOrFail($form->id)->files;

				$files = $form->files;
				// Fetch all files
				foreach($files as $file) {
					//$fields = $file->fields;
					$file->path = $file->path();
				}
            }
        }



        $response['data'] = $forms;
        $response['page_number'] = $page_number;
        $response['page_size'] = $page_size;
        $response['total_counts'] = $totalFiltered;

		return response()->json( $response, 200 );
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		//
		$forms = Form::all();
		foreach ($forms as $form) {
			$form->user_name = $form->user->fullname();
			$form->thumb = $form->thumb();
		} 

		return response()->json( $forms );
	}

	public function show($id) {
		$form = Form::findOrFail( $id );
		$form->user_name = $form->user->fullname();
		$form->thumb = $form->thumb();

		$files = $form->files;
		// Fetch all fields
		foreach($files as $file) {
			$fields = $file->fields;
			$file->path = $file->path();
			foreach($fields as $field) {
				if ($field->type == 'sign_draw' || $field->type == 'sign_initial') {
					$user = User::FindOrFail($field->value);
					if ($field->type == 'sign_draw') {
						$field->sign_path = $user->sign ? $user->sign->path() : null;
						$field->sign = $user->sign;
					} else {
						$field->sign_path = $user->initial ? $user->initial->path() : null;
						$field->initial = $user->initial;
					}
				}
			}
		}
		return response()->json( $form );
	}
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store( Request $request ) {

		$request->validate([
			'name' => 'required|min:1|max:60',
			'user_id' => 'required',
			'share_all' => 'required|boolean',
			'description' => 'sometimes|nullable|string|max:255',
			'file.*' => 'required|mimes:jpg,jpeg,png',
        ], [
            'name.required' => "Please enter a name for the form",
		]);

		$form = new Form();

		$form->name        = $request->name;
		$form->description = $request->description;
		$form->user_id	   = $request->user_id;
		$form->pages	   = $request->pages;
		$form->share_all   = $request->share_all;
		$form->save();


        foreach ($request->file as $i => $file) {
           // get extension
			$mimeType = $file->getClientMimeType();
            switch ($mimeType) {
                case 'image/jpeg':
                    $ext = 'jpg';
                    break;
                case 'image/png':
                    $ext = 'png';
                    break;
                default:
                    throw new \App\Exceptions\InvalidMimeException();
			}
			
			$fileSize = getimagesize($file);
			$width = $fileSize[0];
			$height = $fileSize[1];

			$new_file = new File();
			$new_file->name = $file->getClientOriginalName();
			$new_file->ext = $ext;
			$new_file->form_id = $form->id;
			$new_file->file_width = $width;
			$new_file->file_height = $height;
			$new_file->page_number = $i + 1;
			$new_file->save();
			// store on 	
			$targetFile = "{$new_file->id}.{$ext}";
			$path = Storage::putFileAs( FORM_IMAGE_PATH,  $request->file('file.' . $i), $targetFile);

			if ( $i == 0 ) {
				$file = $request->file('file.0');
				$targetThumb = "{$form->id}.jpg";
				$resize_thumb = Image::make($file)->fit(FOMR_IMAGE_THUMB_SIZEFOMR_IMAGE_THUMB_SIZE)->encode('jpg');
				$path = Storage::put( FORM_IMAGE_PATH . '/thumbs/' . $targetThumb,  $resize_thumb->__toString() );
			} 

			$new_file->path = $new_file->path();
		}

		return response()->json( $form );
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update( Request $request, $id ) {
		//
		$request->validate([
			'name' => 'required|min:1|max:60',
			'user_id' => 'required',
			'share_all' => 'required|boolean',
			'description' => 'sometimes|nullable|string|max:255',
			'file.*' => 'required|mimes:jpg,jpeg,png',
        ], [
            'name.required' => "Please enter a name for the form",
		]);
		

		$form = Form::findOrFail( $id );

		if ( $request->user_id != $form->user_id ){
            throw new \App\Exceptions\InvalidAccessException();
		}

		$request_files = $request['files'];

		foreach($request_files as $file ) {

			$fields = $file['fields'];

			$form_fields =  Field::where("file_id", $file['id'])->get();

			foreach( $form_fields as $form_field) {
				$is_delete = true;

				foreach( $fields as $field ) {
					if (isset($field['id']) && $field['id'] == $form_field['id']) {
						$is_delete = false;
						break;
					}
				}

				if ( $is_delete ){
					Field::destroy($form_field['id']);
				}
			}


			foreach( $fields as $field ) {
				$is_exist = isset($field['id']) && Field::where("id", $field['id'])->exists();

				if ($is_exist) {
					$update_field = Field::findOrFail( $field['id'] );
					$update_field->update($field);
				} else {
					$new_field = new Field();
					$new_field->fill( $field)->save();
				}
			}
		}

		$form->name        = $request->name;
		$form->description = $request->description;
		$form->user_id	   = $request->user_id;
		$form->pages	   = $request->pages;
		$form->share_all   = $request->share_all;
		$form->save();

		return $this->show($id);
	}

	public function getFields( $id ) {
		$files = File::where( "form_id", $id )->get();
		// Fetch all fields
		foreach($files as $file) {
			$file->fields;
			$file->path = $file->path();
			// Fetch all field meta
			foreach ( $file->fields as $field ) {
				$field->field_meta;
			}
		}

		return response()->json( $files );
	}

	public function getFiles( $id ) {
		$files = File::where( "form_id", $id )->get();
		// Fetch all fields
		foreach ($files as $file) {
			$file->path = $photo->path();
		}
		return response()->json( $files );
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy( $id ) {
		//
		$form = Form::findOrFail( $id );

		$delete = array(
			str_replace('public/', '', FORM_IMAGE_PATH) . "/thumbs/{$id}.jpg",
		);

		Storage::disk('public')->delete($delete);

		// Get all related files
		$files = $form->files;

		// Delete all fields associated with files
		foreach($files as $file) {
			$file->fields()->delete();
			$name = $file->id;
			$ext = $file->ext;
			$delete = array(
				str_replace('public/', '', FORM_IMAGE_PATH) . "/{$name}.{$ext}",
			);
	
			Storage::disk('public')->delete($delete);
		}

		// Delete related files
		$form->files()->delete();

		// Delete form itself
		$form->delete();

		return response()->json( null, 204 );
	}
}
