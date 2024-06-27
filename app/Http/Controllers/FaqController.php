<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FaqController extends Controller
{
    public function view(Request $request){
        $faqs = Faq::all();
        return view('faq.view', compact('faqs'));
    }

    public function listFaqs(Request $request){
        if(!Auth::check() || !Auth::user()->mensa_admin){
            return redirect('home');
        }
        $faqs = Faq::all();
        return view('faq.list', compact('faqs'));
    }

    public function edit(Request $request, $id = null){
        if(!Auth::check() || !Auth::user()->mensa_admin){
            return redirect('home');
        }

        try {
            if($id == null){
                $faq = new Faq();
            } else {
                $faq = Faq::findOrFail($id);
            }
        } catch(ModelNotFoundException $e){
            return redirect('home');
        }

        if($request->isMethod('GET')){
            return view('faq.edit', compact('faq'));
        }


        $request->validate([
            'question' => 'required|max:191',
            'answer' => 'required',
        ]);

        $faq->question = $request->get('question');
        $faq->answer = $request->get('answer');
        $faq->lastEditedBy()->associate(Auth::user());
        $faq->save();

        return redirect(route('faq.list'))->with('info', 'FAQ toegevoegd/aangepast!');
    }

    public function delete(Request $request, $id){
        if(!Auth::check() || !Auth::user()->mensa_admin){
            return redirect('home');
        }

        try {
            $faq = Faq::findOrFail($id);
        } catch(ModelNotFoundException $e){
            return redirect('home');
        }

        if($request->isMethod('GET')){
            return view('faq.confirmdelete', compact('faq'));
        }

        $faq->delete();

        return redirect(route('faq.list'))->with('info', 'FAQ verwijderd!');
    }
}
