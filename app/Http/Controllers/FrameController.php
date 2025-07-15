<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Frame;

class FrameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.frames.index');
    }

    public function list(Request $request)
    {
        $query = Frame::query();

        if ($search = $request->input('search.value')) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        $total = $query->count();

        $start = is_numeric($request->input('start')) ? (int) $request->input('start') : 0;
        $length = is_numeric($request->input('length')) ? (int) $request->input('length') : 10;

        $data = $query
            ->offset($start)
            ->limit($length)
            ->get();

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data,
        ]);
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:single,double',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('Frames', 'public');
            $validated['image'] = $path;
        }

        Frame::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Frame created successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Frame::find($id);

        return view('admin.frames.detail', [
            'data' => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:single,double',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);


        $frame = Frame::findOrFail($id);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('Frames', 'public');
            $validated['image'] = $path;
        }

        $frame->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Frame updated successfully.',
        ]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $frame = Frame::find($id);

        if (!$frame) {
            return response()->json([
                'success' => false,
                'message' => 'Frame not found.'
            ], 404);
        }

        $frame->delete();

        return response()->json([
            'success' => true,
            'message' => 'Frame deleted successfully.'
        ]);
    }
}
