<?php

namespace App\Http\Controllers;

use App\WebHook;
use Illuminate\Http\Request;

class WebHookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $hooks = WebHook::query();

        if (!empty($request->query())) {
            foreach ($request->query() as $key => $value) {
                if ($key == 'page') {
                    continue;
                } elseif ($key == 'date') {
                    $hooks->whereDate('created_at', '>=', $value);
                } else {
                    $hooks->where($key, $value);
                }
            }
        }

        return $hooks->orderBy('created_at', 'desc')->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        try {
            $create = WebHook::create($data);
            $return = $create
                ? array(
                    'error' => false,
                    'message' => 'Successfully created hook ',
                    'data'    => $create,
                )
                : array(
                    'error' => true,
                    'message' => 'Failed to create hook ',
                );
        } catch (\Exception $e) {
            $return = array(
                'error' => true,
                'message' => $e->getMessage(),
            );
        }

        return $return;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\WebHook  $hook
     * @return \Illuminate\Http\Response
     */
    public function show($hook)
    {
        return is_null($hook) ? WebHook::all() : WebHook::find($hook);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\WebHook  $hook
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $hook)
    {
        $data = $request->all();
        try {
            $update = WebHook::find($hook)->update($data);
            $return = $update
                ? array(
                    'error' => false,
                    'message' => 'Successfully updated hook ',
                    'data'    => $hook,
                )
                : array(
                    'error' => true,
                    'message' => 'Failed to update hook ',
                );
        } catch (\Exception $e) {
            $return = array(
                'error' => true,
                'message' => $e->getMessage(),
            );
        }

        return $return;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\WebHook  $hook
     * @return \Illuminate\Http\Response
     */
    public function destroy($hook)
    {
        try {
            $delete = WebHook::find($hook)->delete();
            $return = $delete
                ? array(
                    'error' => false,
                    'message' => 'Successfully deleted hook ',
                )
                : array(
                    'error' => true,
                    'message' => 'Failed to delete hook ',
                );
        } catch (\Exception $e) {
            $return = array(
                'error' => true,
                'message' => $e->getMessage(),
            );
        }

        return $return;
    }
}
