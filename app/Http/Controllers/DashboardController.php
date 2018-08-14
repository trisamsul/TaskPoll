<!-- 

	Controller: Main Controller for Dashboard
	To handle activity on the dashboard pages

 -->

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
	
	// Method to handle Home Page views
	public function home(){

		$polls = DB::table('polls')
	            ->join('users', 'users.id', '=', 'polls.user_id')
	            ->where('polls.status',1)
	            ->select('polls.*', 'users.username')
	            ->orderBy('polls.id', 'desc')
	            ->get();

		return view('home', ['polls' => $polls]);
		
	}

	// Method to handle SignIn Page views
	public function signin(){

		return view('signin');

	}

	// Method to handle Sign In process and authentication
	public function signincheck(Request $request){

		// validate if the username and password field are not empty
		$this->validate($request, [
			'username' => 'required',
			'password' => 'required'
		]);

		// Gather data for submitted username
		$validate = DB::table('users')
                    ->select('*')
                    ->where('username', $request->input('username'))
                    ->first();

        if ($validate) {
        	// If the data for the submitted username is exist on the database
			if(Hash::check($request->input('password'), $validate->password)){
				// If the submitted password is true
				// Save the current session with the following data
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

	// Method to handle Sign Out process
	public function signout(Request $request){

		// Clear the session
		$request->session()->flush();
		return redirect('/');

	}

	// Method to handle Register Page views
	public function register(){

		return view('register');

	}

	// Method to handle Register process
	public function insertuser(Request $request){

		// Validate if the username,email, and password field are not empty
		$this->validate($request, [
			'username' => 'required',
			'email' => 'required',
			'password' => 'required'
		]);

		// Insert the data into users table in the database
		DB::table('users')->insert([
		   	'username' => $request->input('username'), 
		   	'email' => $request->input('email'), 
		   	'password' => Hash::make($request->input('password')),
		   	'category' => $request->input('category')
		]);

		return redirect('/signin')->with('success','Register account success!');

	}	

	// Method to handle Dashboard Page views
	public function dashboard(Request $request){

		// Show this page if only the user already signed in
		if($request->session()->exists('signed_in')){
			// Load the data of number of Polls total, currently Active Polls, Contributors (all registered user), Owner (Administrator that can create poll)
			$count['polls_total'] = DB::table('polls')->count();
			$count['polls_active'] = DB::table('polls')->where('status',1)->count();
			$count['contributors'] = DB::table('users')->count();
			$count['owners'] = DB::table('users')->where('category',1)->count();

			// Load all the polls data
			$polls = DB::table('polls')
	            ->join('users', 'users.id', '=', 'polls.user_id')
	            ->where('polls.status',1)
	            ->select('polls.*', 'users.username')
	            ->orderBy('polls.id', 'desc')
	            ->get();

	        // Variable to save status for every polls that current user are already voted or not
	        $voted = [];
	        $i=0;
	        // Load the status
	        foreach($polls->all() as $poll){
	        	$check = DB::table('polls_votes')
	                    ->select('*')
	                    ->where([
	                    	['poll_id', '=', $poll->id],
	                    	['user_id', '=', $request->session()->get('id')]
	                    ])
	                    ->first();
				
		        if($check){
					// If the status of the current user for this poll is already voted then the status is 1
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

	// Method to handle My Poll (Own Polling List) Page views
	public function mypoll(Request $request){
		
		// Show this page if only the user already signed in
		if($request->session()->exists('signed_in')){
			// Load the data of the poll for current administrator
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

	// Method to handle Add Poll Page views
	public function addpoll(Request $request){
		
		// Show this page if only the user already signed in
		if($request->session()->exists('signed_in')){
			return view('addpoll');
		}else{
			return view('needsignin');
		}

	}

	// Method to handle process to add new poll
	public function insertpoll(Request $request){

		// Show this page if only the user already signed in
		if($request->session()->exists('signed_in')){
			$this->validate($request, [
				'title' => 'required',
				'option1' => 'required',
				'option2' => 'required'
			]);

			// Collect number of options
			$option_n = $request->input('number');

			// Insert new poll data into table 'polls' in database
			$PollLastId = DB::table('polls')->insertGetId([
				'user_id' => $request->session()->get('id'), 
				'title' => $request->input('title'),
				'status' => 1
			]);

			for($i=1;$i<=$option_n;$i++){
				// Insert every options into tbla 'polls_options' in the database
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

	// Method to handle close the selected poll
	public function closepoll(Request $request, $id){
		
		// Show this page if only the user already signed in
		if($request->session()->exists('signed_in')){
			// Update poll status as closed
			DB::table('polls')
	            ->where('id', $id)
	            ->update(['status' => 0]);

	        return redirect('/mypoll')->with('success','Poll have closed!');
		}else{
			return view('needsignin');
		}

	}

	// Method to handle open the selected poll
	public function openpoll(Request $request, $id){
		
		// Show this page if only the user already signed in
		if($request->session()->exists('signed_in')){
			// Update poll status as closed
			DB::table('polls')
	            ->where('id', $id)
	            ->update(['status' => 1]);

	        return redirect('/mypoll')->with('success','Polls have opened!');
		}else{
			return view('needsignin');
		}

	}

	// Method to handle Vote Page views
	public function vote(Request $request, $id){

		// Show this page if only the user already signed in
		if($request->session()->exists('signed_in')){
			// Check if the current user already voted for this poll or not
			$check = DB::table('polls_votes')
                    ->select('*')
                    ->where([
                    	['poll_id', '=', $id],
                    	['user_id', '=', $request->session()->get('id')]
                    ])
                    ->first();

            if(!$check){
            	// If the current user haven't vote for this poll, then the vote will be available
            	// Load poll data
            	$poll = DB::table('polls')
		            ->join('users', 'users.id', '=', 'polls.user_id')
		            ->select('polls.*', 'users.username')
		            ->where('polls.id',$id)
		            ->first();

		        // Load options data for the current poll
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

	// Method to handle process to add vote for the current poll
	public function insertvote(Request $request){

		// Show this page if only the user already signed in
		if($request->session()->exists('signed_in')){
			// Insert vote data into table 'polls_votes' in the database
			DB::table('polls_votes')->insert([
			   	'poll_id' => $request->input('poll_id'), 
			   	'option_id' => $request->input('option_id'),
			   	'user_id' => $request->input('user_id')
			]);

			// Get the current number of voted options
			$current = DB::table('polls_options')
	            ->select('voted')
	            ->where('id',$request->input('option_id'))
	            ->first();

	        // Update voted uptions number
			DB::table('polls_options')
	            ->where('id', $request->input('option_id'))
	            ->update(['voted' => $current->voted + 1]);

			return redirect('/dashboard')->with('success','You have voted!');
		}else{
			return view('needsignin');
		}
	}

	// Method to handle Detail Page views
	public function detail(Request $request, $id){

		// Show this page if only the user already signed in
		if($request->session()->exists('signed_in')){
			// Load poll data
			$poll = DB::table('polls')
	            ->join('users', 'users.id', '=', 'polls.user_id')
	            ->select('polls.*', 'users.username')
	            ->where('polls.id',$id)
	            ->first();

	        // Load options for the current polls
	        $options = DB::table('polls_options')
	            ->select('polls_options.*')
	            ->where('polls_options.poll_id',$id)
	            ->get();

			return view('detail', ['poll' => $poll, 'options' => $options]);
		}else{
			return view('needsignin');
		}

	}

	// Method to handle Change Password Page views
	public function changepass(Request $request){

		// Show this page if only the user already signed in
		if($request->session()->exists('signed_in')){
			return view('changepass');
		}else{
			return view('needsignin');
		}

	}

	// Method to handle process to update the password for the current user
	public function updatepass(Request $request){

		// Show this page if only the user already signed in
		if($request->session()->exists('signed_in')){
			// Get data of the current user
			$validate = DB::table('users')
                    ->select('*')
                    ->where('id', $request->session()->get('id'))
                    ->first();

			if(Hash::check($request->input('current'), $validate->password)){
				// If the submitted current password is correct
				if($request->input('new') == $request->input('new_confirm')){
					// If the submitted New Password same as the New Confirm Password
					// Update the password data in the database
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