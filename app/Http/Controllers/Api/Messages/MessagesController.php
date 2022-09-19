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
//        return response()->json($request->allFiles());
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
                $folderName = date('d-m-Y') . '/email-files/' . auth()->user()->name;
                $file->move(public_path() . '/' . $folderName, $name);
                $files_link[] = $folderName . '/' . $name;
            }
//            return $files_link;
        }
        $toArr[] = $request->to;
        $toArrs = [...$toArr[0]];
//        return response()->json($toArrs);
//        if (!empty($toArr) && isset($toArr) && is_array($toArr)) {
        foreach ($toArrs as $key => $item) {
            $sendTo[$key] = $item;
        }
//        }

        foreach ($sendTo as $key => $item) {
            $data[$key] = [
                'title' => $request->title,
                'subject' => $request->subject,
                'description' => $request->description,
                'files_link' => $files_link,
                'opened' => 0,
                'from' => auth()->id(),
                'to' => $item,
            ];
            $message = Messages::create($data[$key]);
        }

        return response()->json([
            'success' => true,
            'data' => "Sended"
        ], 201);
    }


    public function inbox()
    {
        $messages = Messages::where('from', auth()->user()->id)->paginate(40);

//        $messages['image'] = implode(' ', $messages['files_link']);
        return response()->json($messages);
    }
}
