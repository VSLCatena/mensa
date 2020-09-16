<?php
/**
 * Created by PhpStorm.
 * User: SubSide
 * Date: 1-3-2018
 * Time: 15:26
 */

namespace App\Http\Controllers;

use App\Mail\MensaState;
use App\Mail\SigninCancelled;
use App\Models\Mensa;
use App\Models\MensaUser;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MensaAdminController extends MensaCookController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('isAdmin');
    }

    public function showLogs(Request $request, $mensaId){
        try {
            /* @var $mensa Mensa */
            $mensa = Mensa::findOrFail($mensaId);
        } catch(ModelNotFoundException $e){
            return redirect(route('home'))->with('error', 'Mensa niet gevonden.');
        }

        $logs = $mensa->logs()->orderBy('created_at', 'DESC')->get();
        return view('mensae.logs', compact('mensa', 'logs'));
    }

    public function togglePaid(Request $request, $mensaId){
        try {
            /* @var $mensaUser MensaUser */
            $mensaUser = MensaUser::findOrFail($request->get('id'));
        } catch(ModelNotFoundException $e){
            return response()->json([
                'error' => 'MensaUser niet gevonden!'
            ]);
        }

        if($mensaUser->mensa->closed){
            return response()->json(['error' => 'Deze mensa is al gesloten!']);
        }

        $mensaUser->paid = ($mensaUser->paid == $mensaUser->price()) ? 0 : $mensaUser->price();
        $mensaUser->save();

        // Log the payment change
        $this->log($mensaUser->mensa, ($mensaUser->is_intro?'Intro van ':'').$mensaUser->user->name.($mensaUser->paid?' heeft betaald.':' is op niet betaald gezet.'));

        return response()->json([
            'price' => '&euro;'.number_format($mensaUser->price(), 2),
            'paid' => $mensaUser->paid == $mensaUser->price()
        ]);
    }

    public function removeSignin(Request $request, $mensaId, $userId){
        try {
            $mUser = MensaUser::findOrFail($userId);
        } catch(ModelNotFoundException $e){
            return redirect(route('mensa.signins', ['id' => $mensaId]))->with('error', 'Gebruiker niet gevonden!');
        }

        if($mUser->mensa->closed){
            return redirect(route('mensa.overview', ['id' => $mensaId]))->with('error', 'Deze mensa is al gesloten!');
        }

        if($request->isMethod('get')){
            return view('mensae.confirmsignout', compact('mUser'));
        }

        $mUser->delete();

        // Log the deletion
        $this->log($mUser->mensa, $mUser->user->name.' is uitgeschreven.');

        // Send signout email
        Mail::to($mUser->user)->send(new SigninCancelled($mUser));

        return redirect(route('mensa.signins', ['id' => $mensaId]))->with('info', $mUser->user->name.' is uitgeschreven!');
    }

    // This is for if you're an admin
    public function newSignin(Request $request, $mensaId){
        try {
            $mensa = Mensa::findOrFail($mensaId);
        } catch(ModelNotFoundException $e){
            return redirect(route('home'))->with('error', 'Mensa niet gevonden!');
        }

        if($mensa->closed){
            return redirect(route('mensa.overview', ['id' => $mensa->id]))->with('error', 'Deze mensa is al gesloten!');
        }

        if($request->isMethod('get')){
            return view('mensae.newsignin', compact('mensa'));
        }

        $request->validate([
            'lidnummer' => 'required',
        ]);

        if($request->has('bulk')){
            return redirect(route('mensa.newsignin.bulk', ['id' => $mensa->id, 'lidnummer' => $request->get('lidnummer')]));
        }

        return redirect(route('signin', ['id' => $mensaId, 'lidnummer' => $request->get('lidnummer')]));
    }

    public function requestUserLookup(Request $request){
        $request->validate(['name' => 'regex:/^[a-zA-Z _]+$/']);
        return response()->json($this->searchLdapUsers($request->get('name')));
    }

    public function printState(Request $request, $mensaId){
        try {
            $mensa = Mensa::findOrFail($mensaId);
        } catch(ModelNotFoundException $e){
            return redirect(route('home'))->with('error', 'Mensa niet gevonden!');
        }

        if($request->isMethod("get")){
            return view('mensae.confirmprintstate', compact('mensa'));
        }
        Mail::to(config('mensa.contact.printer'))->send(new MensaState($mensa));

        $mensa->closed = true;
        $mensa->save();

        // Log the printing of the state
        $this->log($mensa, 'Het wijzigen van de mensa is geblokkeerd i.v.m. het printen van de mensastaat');
        $this->log($mensa, 'Mensastaat uitgeprint.');

        return redirect(route('mensa.overview', ['id' => $mensa->id]))->with('info', 'Mensastaat is uitgeprint!');
    }

    public function printStatePreview(Request $request, $mensaId){

        try {
            $mensa = Mensa::findOrFail($mensaId);
        } catch(ModelNotFoundException $e){
            return redirect(route('home'))->with('error', 'Mensa niet gevonden!');
        }

        // Delete all unconfirmed users
        $mensa->users()->where('confirmed', '0')->delete();

        return new MensaState($mensa);
    }

    public function openMensa(Request $request, $mensaId){
        try {
            $mensa = Mensa::findOrFail($mensaId);
        } catch(ModelNotFoundException $e){
            return redirect(route('home'))->with('error', 'Mensa niet gevonden!');
        }

        if($request->isMethod('get')){
            return view('mensae.confirmreopen', compact('mensa'));
        }

        if(!$mensa->closed) {
            return redirect(route('mensa.overview', ['id' => $mensa->id]))->with('info', 'Deze mensa is al open!');
        }

        $mensa->closed = false;
        if ($mensa->max_users <= 0)
            $mensa->max_users = config('mensa.default.max_users');
        $mensa->save();

        $this->log($mensa, 'Mensa opnieuw geopend voor wijzigingen');
        return redirect(route('mensa.overview', ['id' => $mensa->id]))->with('info', 'Mensa geopend voor aanpassingen!');
    }

    public function closeMensa(Request $request, $mensaId){
        try {
            $mensa = Mensa::findOrFail($mensaId);
        } catch(ModelNotFoundException $e){
            return redirect(route('home'))->with('error', 'Mensa niet gevonden!');
        }

        if($mensa->closed) {
            return redirect(route('mensa.overview', ['id' => $mensa->id]))->with('info', 'Deze mensa is al geopend!');
        }

        $mensa->closed = true;
        $mensa->save();

        $this->log($mensa, 'Mensa gesloten voor wijzigingen');
        return redirect(route('mensa.overview', ['id' => $mensa->id]))->with('info', 'Mensa gesloten voor aanpassingen!');
    }

    public function bulkSignin(Request $request, $mensaId, $lidnummer){
        try {
            $mensa = Mensa::findOrFail($mensaId);
        } catch(ModelNotFoundException $e){
            return redirect(route('home'))->with('error', 'Mensa niet gevonden!');
        }

        $user = $this->getLdapUserBy('description', $lidnummer);
        if($user == null){
            return redirect()->back()->with('error', 'Inschrijving niet gevonden! Als je denkt dat dit een fout is neem dan contact op met ' . config('mensa.contact.mail') . '.');
        }

        if($request->isMethod('get')){
            return view('mensae.bulkenlistintros', compact('mensa', 'user'));
        }

        $baseIntroUser = new MensaUser();
        $baseIntroUser->is_intro = true;
        $baseIntroUser->cooks = false;
        $baseIntroUser->dishwasher = false;
        $baseIntroUser->paid = false;
        $baseIntroUser->confirmed = true;
        $baseIntroUser->mensa()->associate($mensa);
        $baseIntroUser->lidnummer = $user->lidnummer;
        $baseIntroUser->confirmation_code = bin2hex(random_bytes(32));

        foreach($request->all("intro")['intro'] as $introData){
            $introUser = $baseIntroUser->replicate();
            $introUser->vegetarian = isset($introData['vegetarian']);
            $introUser->allergies = $introData['allergies'];
            $introUser->extra_info = $introData['info'];

            $introUser->save();
            if(isset($introData['extraOptions'])){
                foreach($introData['extraOptions'] as $id){
                    try {
                        $extraOption = $mensa->extraOptions()->findOrFail($id);
                        $introUser->extraOptions()->attach($extraOption);
                    } catch(ModelNotFoundException $e){}
                }
            }
        }

        $count = count($request->all('intro')['intro']);

        $this->log($mensa, $count.' intro'.($count == 1?'':'s').' ingeschreven voor '.$user->name.'.');

        return redirect(route('mensa.signins', ['id' => $mensa->id]))->with('info', $count.' intro'.($count == 1?'':'s').' succesvol ingeschreven voor '.$user->name.'!');
    }
}