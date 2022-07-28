<?php

namespace App\Http\Controllers\Api\Messages;

use App\Http\Controllers\Controller;
use App\Models\Messages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessagesController extends Controller
{
    public function newMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:4',
            'subject' => 'nullable',
            'description' => 'nullable',
            'files_link' => 'nullable',
            'to' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->messages()
            ], 500);
        }

        if ($request->hasFile('files_link')) {
            foreach ($request->file('files_link') as $file) {
                $name = rand() . '-' . $file->getClientOriginalName();
                $folderName = date('d-m-Y') . '/email-files/'.auth()->user()->name;
                $file->move(public_path() . '/' . $folderName, $name);
                $files_links[] = $folderName . '/' . $name;
            }
        }
        $data = [
            'title' => $request->title,
            'subject' => $request->subject,
            'description' => $request->description,
            'files_link' => $files_links,
            'opened' => $request->opened,
            'from' => auth()->id(),
            'to' => $request->to,
        ];

        $message = Messages::create($data);
        return response()->json([
            'success' => true,
            'data' => $message
        ], 201);
    }


    public function inbox()
    {
        $messages = Messages::where('from', auth()->user()->id)->paginate(25);
        return response()->json($messages);
    }
}
