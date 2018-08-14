<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;
use DB;

use App\Polls;
use App\PollsOptions;

class DashboardController extends Controller
{
	
	public function home(){

		$polls = DB::table('polls')
	            ->join('users', 'users.id', '=', 'polls.user_id')
	            ->where('polls.status',1)
	            ->select('polls.*', 'users.username')
	            ->orderBy('polls.id', 'desc')
	            ->get();

		return view('home', ['polls' => $polls]);
		
	}

	public function signin(){

		return view('signin');

	}

	public function signincheck(Request $request){

		$this->validate($request, [
			'username' => 'required',
			'password' => 'required'
		]);

		$validate = DB::table('users')
                    ->select('*')
                    ->where('username', $request->input('username'))
                    ->first();

        if ($validate) {
			if(Hash::check($request->input('password'), $validate->password)){
				session([
					'signed_in' => TRUE,
					'id' => $validate->id,
					'username' => $validate->username,
					'category' => $validate->category,
				]);

				return redirect('/dashboard');

			}else{
				return redirect('/signin')->with('fail','Incorrect password!');
			}
		}else{
			return redirect('/signin')->with('fail','Account not exist!');
		}
	}

	public function signout(Request $request){

		$request->session()->flush();
		return redirect('/');

	}

	public function register(){

		return view('register');

	}

	public function insertuser(Request $request){

		$this->validate($request, [
			'username' => 'required',
			'email' => 'required',
			'password' => 'required'
		]);

		DB::table('users')->insert([
		   	'username' => $request->input('username'), 
		   	'email' => $request->input('email'), 
		   	'password' => Hash::make($request->input('password')),
		   	'category' => $request->input('category')
		]);

		return redirect('/signin')->with('success','Register account success!');

	}	

	public function dashboard(Request $request){

		if($request->session()->exists('signed_in')){
			$count['polls_total'] = DB::table('polls')->count();
			$count['polls_active'] = DB::table('polls')->where('status',1)->count();
			$count['contributors'] = DB::table('users')->count();
			$count['owners'] = DB::table('users')->where('category',1)->count();

			$polls = DB::table('polls')
	            ->join('users', 'users.id', '=', 'polls.user_id')
	            ->where('polls.status',1)
	            ->select('polls.*', 'users.username')
	            ->orderBy('polls.id', 'desc')
	            ->get();

	        $voted = [];
	        $i=0;
	        foreach($polls->all() as $poll){
	        	$check = DB::table('polls_votes')
	                    ->select('*')
	                    ->where([
	                    	['poll_id', '=', $poll->id],
	                    	['user_id', '=', $request->session()->get('id')]
	                    ])
	                    ->first();
		        
		        if($check){
		        	$voted[$i] = 1;
		        }else{
		        	$voted[$i] = 0;
		        }

		        $i++;
	        }

			return view('dashboard', ['polls' => $polls, 'voted' => $voted, 'count' => $count]);	
		}else{
			return view('needsignin');
		}

	}

	public function mypoll(Request $request){
		
		if($request->session()->exists('signed_in')){
			$polls = DB::table('polls')
	            ->join('users', 'users.id', '=', 'polls.user_id')
	            ->select('polls.*', 'users.username')
	            ->where('polls.user_id',$request->session()->get('id'))
	            ->orderBy('polls.id', 'desc')
	            ->get();

			return view('mypoll', ['polls' => $polls]);
		}else{
			return view('needsignin');
		}

	}

	public function addpoll(Request $request){
		
		if($request->session()->exists('signed_in')){
			return view('addpoll');
		}else{
			return view('needsignin');
		}

	}

	public function insertpoll(Request $request){

		if($request->session()->exists('signed_in')){
			$this->validate($request, [
				'title' => 'required',
				'option1' => 'required',
				'option2' => 'required'
			]);

			$option_n = $request->input('number');

			$PollLastId = DB::table('polls')->insertGetId([
				'user_id' => $request->session()->get('id'), 
				'title' => $request->input('title'),
				'status' => 1
			]);

			for($i=1;$i<=$option_n;$i++){
				DB::table('polls_options')->insert([
				   	'poll_id' => $PollLastId, 
				   	'text' => $request->input('option'.$i),
				   	'voted' => 0
				]);
			}

			return redirect('/mypoll')->with('add','Poll has been added!')->with('poll_id',$PollLastId);
		}else{
			return view('needsignin');
		}
	}

	public function closepoll(Request $request, $id){
		
		if($request->session()->exists('signed_in')){
			DB::table('polls')
	            ->where('id', $id)
	            ->update(['status' => 0]);

	        return redirect('/mypoll')->with('success','Poll have closed!');
		}else{
			return view('needsignin');
		}

	}

	public function openpoll(Request $request, $id){
		
		if($request->session()->exists('signed_in')){
			DB::table('polls')
	            ->where('id', $id)
	            ->update(['status' => 1]);

	        return redirect('/mypoll')->with('success','Polls have opened!');
		}else{
			return view('needsignin');
		}

	}

	public function vote(Request $request, $id){

		if($request->session()->exists('signed_in')){
			$check = DB::table('polls_votes')
                    ->select('*')
                    ->where([
                    	['poll_id', '=', $id],
                    	['user_id', '=', $request->session()->get('id')]
                    ])
                    ->first();

            if(!$check){
            	$poll = DB::table('polls')
		            ->join('users', 'users.id', '=', 'polls.user_id')
		            ->select('polls.*', 'users.username')
		            ->where('polls.id',$id)
		            ->first();

		        $options = DB::table('polls_options')
		            ->select('polls_options.*')
		            ->where('polls_options.poll_id',$id)
		            ->get();

				return view('vote', ['poll' => $poll, 'options' => $options]);
            }else{
            	return redirect('/dashboard')->with('fail','You already voted that poll!');
            }
		}else{
			return view('needsignin');
		}

	}

	public function insertvote(Request $request){

		if($request->session()->exists('signed_in')){
			DB::table('polls_votes')->insert([
			   	'poll_id' => $request->input('poll_id'), 
			   	'option_id' => $request->input('option_id'),
			   	'user_id' => $request->input('user_id')
			]);

			$current = DB::table('polls_options')
	            ->select('voted')
	            ->where('id',$request->input('option_id'))
	            ->first();

			DB::table('polls_options')
	            ->where('id', $request->input('option_id'))
	            ->update(['voted' => $current->voted + 1]);

			return redirect('/dashboard')->with('success','You have voted!');
		}else{
			return view('needsignin');
		}
	}

	public function detail(Request $request, $id){

		if($request->session()->exists('signed_in')){
			$poll = DB::table('polls')
	            ->join('users', 'users.id', '=', 'polls.user_id')
	            ->select('polls.*', 'users.username')
	            ->where('polls.id',$id)
	            ->first();

	        $options = DB::table('polls_options')
	            ->select('polls_options.*')
	            ->where('polls_options.poll_id',$id)
	            ->get();

			return view('detail', ['poll' => $poll, 'options' => $options]);
		}else{
			return view('needsignin');
		}

	}

	public function changepass(Request $request){

		if($request->session()->exists('signed_in')){
			return view('changepass');
		}else{
			return view('needsignin');
		}

	}

	public function updatepass(Request $request){

		if($request->session()->exists('signed_in')){
			$validate = DB::table('users')
                    ->select('*')
                    ->where('id', $request->session()->get('id'))
                    ->first();

			if(Hash::check($request->input('current'), $validate->password)){
				if($request->input('new') == $request->input('new_confirm')){
					DB::table('users')
			            ->where('id', $request->session()->get('id'))
			            ->update(['password' => Hash::make($request->input('new'))]);

					return redirect('/changepass')->with('success','Password has been updated!');
				}else{
					return redirect('/changepass')->with('fail','New Password and Confirm New Password are different');
				}
			}else{
				return redirect('/changepass')->with('fail','Current password is wrong!');
			}
		}else{
			return view('needsignin');
		}

	}

}

?>